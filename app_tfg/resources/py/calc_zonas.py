#!/usr/bin/python3
from math import sin, cos, sqrt, atan2, radians
from datetime import date
import pymysql
from sklearn.cluster import MeanShift, estimate_bandwidth
from sklearn import preprocessing
import numpy as np

'''
Junta dos listas en una sola como tuplas
'''
def merge(list1, list2):      
    merged_list = [(list1[i], list2[i]) for i in range(0, len(list1))] 
    return merged_list

 
'''
Devuelve el centro de una lista de puntos
'''
def get_center(X):
	sum_lat = 0.0
	sum_lng = 0.0

	for x in X:
		 sum_lat += x[0]
		 sum_lng += x[1]
		 
	avg_lat = sum_lat / (1.0 * len(X))
	avg_lng = sum_lng / (1.0 * len(X))

	return [avg_lat, avg_lng]


'''
Normaliza en un intervalo [0, 10]
'''
def normalize(x, min_x, max_x):
    return (((x-min_x)/(max_x-min_x)))*10.0


'''
Denormaliza en un intervalo [0, 10]
'''
def denormalize(x_norm, min_x, max_x):
    return ((x_norm/10.0)*(max_x-min_x))+min_x


'''
Agrupa los incidentes en función de la zona a la que pertenencen
'''
def group_by_areas(incidents):
	used_areas = []
	incs_by_area = {}
	
	for inc in incidents:
		if (inc[3] not in used_areas):
			used_areas.append(inc[3])
			incs_by_area[inc[3]] = []
			
		#Añade lat, lng, y nivel_gravedad
		incs_by_area[inc[3]].append([inc[1], inc[2], inc[4]])
		
	return incs_by_area

'''
Calcula la distancia entre dos puntos
'''
def distance(inc, c):
	R = 6371000.0
	lat1 = radians(inc[0])
	lon1 = radians(inc[1])
	lat2 = radians(c[0])
	lon2 = radians(c[1])

	dlon = lon2 - lon1
	dlat = lat2 - lat1

	a = sin(dlat / 2)**2 + cos(lat1) * cos(lat2) * sin(dlon / 2)**2
	c = 2 * atan2(sqrt(a), sqrt(1 - a))

	return R * c


'''
Calcula el nivel de gravedad de los centros de zonas
'''
def get_centers_sev_lvl(incidents, center):
	centers_with_levels = []
	center_level = 0.0
	num_incidents_in_center = 0

	for inc in incidents:
		dist = distance(inc, center)

		if (dist < 250):
			center_level += float(inc[2])
			num_incidents_in_center += 1

	# Si hay más de 4 incidentes en el centro o el nivel de gravedad es >2
	# entonces se incluye dicha zona de incidentes
	if (num_incidents_in_center > 2 or center_level > 2.0):
		return [center[0], center[1], center_level]
	return []


''' ---------------INICIO---------------- '''

db = pymysql.connect("localhost", "david", "", "")
cursor = db.cursor()

try:
	with cursor as cur:
		sql = "SELECT id, latitud_incidente, longitud_incidente, nombre_lugar, nivel_gravedad FROM incidentes WHERE nivel_gravedad IS NOT NULL AND oculto = 0 and caducado = 0 ORDER BY nombre_lugar"
		cur.execute(sql)
		incidents = cur.fetchall()
finally:
	db.close()


incidents_by_area = group_by_areas(incidents)
centers = []
# Se itera cada zona
for key in incidents_by_area:
	# Si la zona tiene más de dos incidentes
	if (len(incidents_by_area[key]) > 2):
		latitude_list = []	# Lista con las latitudes de los incidentes de la zona
		longitude_list = []	# Lista con las longitudes de los incidentes de la zona
		norm_lat = []		# Lista con las latitues normalizadas en un interavalo [0, 10]
		norm_lng = []		# Lista con las longitudes normalizadas en un interavalo [0, 10]

		# Se itera cada incidente de cada zona
		for inc in incidents_by_area[key]:
			latitude_list.append(float( inc[0]) )
			longitude_list.append(float( inc[1]) )

		
		# Normalización las latitudes en un intervalo [0, 10]		
		min_lat = min(latitude_list)
		max_lat = max(latitude_list)
		for latitude in latitude_list:
			norm_lat.append( normalize(latitude, min_lat, max_lat) )
		
		# Normalización las longitudes en un intervalo [0, 10]
		min_lng = min(longitude_list)
		max_lng = max(longitude_list)
		for longitude in longitude_list:
			 norm_lng.append( normalize(longitude, min_lng, max_lng) )
		
		norm_dataset = merge(norm_lat, norm_lng)
		X = np.array(norm_dataset)
		
		bandwidth = estimate_bandwidth(X, random_state=82202551, n_jobs=-1)
		area_centers = []

		if (bandwidth != 0.0):
			ms = MeanShift(bandwidth=bandwidth, bin_seeding=True).fit(X)
			area_centers = ms.cluster_centers_

		for c in area_centers:
			de_n_center = [denormalize(c[0], min_lat, max_lat), denormalize(c[1], min_lng, max_lng)]
			center_sev_lvl = get_centers_sev_lvl(incidents_by_area[key], de_n_center)
			if (len(center_sev_lvl) > 0):
				centers.append( center_sev_lvl )


# Se almacenan los centros y su nivel de gravedad en un fichero para
# su posterior asignación de un color y almacenamiento en la BD
base_path = '/var/www/Trabajo-Fin-Grado/files/'
f = open(base_path+'centers_'+str(date.today())+'.csv', 'w')
for c in centers:
	f.write('{:f},{:f},{:f}\n'.format(c[0], c[1], c[2]))

f.close()
