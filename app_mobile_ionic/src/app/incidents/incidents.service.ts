import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Storage } from '@ionic/storage'
import { IncidentList } from '../models/incidentList';
import { CentersAreasList } from '../models/centersAreasList';
import { SERVER_DEBUG_URL } from './../../environments/environment';
import { SERVER_URL } from './../../environments/environment.prod';

@Injectable({
  providedIn: 'root'
})
export class IncidentsService {

  public listIncidents: IncidentList[];
  public mapIncidents: IncidentList[];
  public uploadedIncidents: IncidentList[];
  public centersIncidentsAreas: CentersAreasList[];
  public listDelitos: any;

  constructor(
    private _http: HttpClient,
    private storage: Storage,
  ) {
    this.setIncidentsList();
    this.setUploadedIncidents();
    this.setDelitos();
    this.setCentersIncidentsAreas();
  }


  /**
   * Establece la lista de incidentes al cargar la página y
   * cuando se filtran
   * 
   * @param parametros 
   */
  setIncidentsList(parametros="") {
    const list = [];

    this.getIncidentsListAPI(parametros).subscribe(response => {

      response.incidents.forEach(function(inc) {
        let dateHour = inc.fecha_hora.split(" ");
        let spltDate = dateHour[0].split('-');
        let spltTime = dateHour[1].split(':');
        const incidentName = inc.incidente.charAt(0).toUpperCase() +
          inc.incidente.substring(1);
        
        list.push({
          id: inc.id,
          delId: inc.delito_id,
          incident: incidentName,
          place: inc.nombre_lugar,
          lat: inc.latitud,
          lng: inc.longitud,
          date: spltDate[2]+'/'+spltDate[1]+'/'+spltDate[0],
          hour: spltTime[0]+':'+spltTime[1],
          description: inc.descripcion
        });
      });
    });

    this.listIncidents = list;
  }

  /**
   * Establece la lista de incidentes subidos al cargar la página
   */
  setUploadedIncidents() {
    const list = [];

    this.storage.get('api_token').then(user => {
      if(user !== null) {
        this.getUploadedIncidentsAPI(user).subscribe(response => {
          response.incidents.forEach(function(inc) {
            let dateHour = inc.fecha_hora.split(" ");
            let spltDate = dateHour[0].split('-');
            let spltTime = dateHour[1].split(':');
            let spltUpDate = inc.fecha_hora_subida.split(" ")[0].split('-');
            const incidentName = inc.incidente.charAt(0).toUpperCase() +
              inc.incidente.substring(1);
            
            list.push({
              id: inc.id,
              incident: incidentName,
              place: inc.nombre_lugar,
              date: spltDate[2]+'/'+spltDate[1]+'/'+spltDate[0],
              hour: spltTime[0]+':'+spltTime[1],
              description: inc.descripcion,
              uploaded: spltUpDate[2]+'/'+spltUpDate[1]+'/'+spltUpDate[0],
            });
          });

          this.uploadedIncidents = list;
        });
      }
    });
  }

  /**
   * Establece la lista de centros de zonas de concentración de incidentes
   */
  setCentersIncidentsAreas() {
    const list = [];

    this.storage.get('api_token').then(user => {
      if(user !== null) {
        this.getCentersIncidentsAreasAPI(user).subscribe(response => {
          if (response.status === 'success') {
            response.centers.forEach(function(center) {            
              list.push({
                id: center.id,
                lat: center.lat_center,
                lng: center.lng_center,
                sevLvl: center.severity_level,
                color: center.color
              });
            });

            this.centersIncidentsAreas = list;
          }
        });
      }      
    });
  }

  private setDelitos() {
    const list = [];

    this.getDelitosAPI().subscribe(response => {
      for (let key of Object.keys(response.delitos)) {
        const delitoName = response.delitos[key].charAt(0).toUpperCase() +
          response.delitos[key].substring(1);

          list.push({
            id: key,
            delito: delitoName
          });
      }
    });

    this.listDelitos = list;
  }

  getIncidentsListAPI(params=""): Observable<any> {
    return this._http.get<any>(SERVER_DEBUG_URL+'/api/get_list_incidents'+params);
  }

  getCentersIncidentsAreasAPI(user): Observable<any> {
    return this._http.get<any>(SERVER_DEBUG_URL+'/api/get_list_centers_incidents_areas?api_token='+user);
  }

  getUploadedIncidentsAPI(user): Observable<any> {
    return this._http.get<any>(SERVER_DEBUG_URL+'/api/get_uploaded_incidents?api_token='+user);
  }

  getIncidentsList() {
    return this.listIncidents;
  }

  getIncidentsCenters() {
    return this.centersIncidentsAreas;
  }

  getUploadedIncidents() {
    return this.uploadedIncidents;
  }

  /**
   * Consulta los delitos de la BD
   */
  getDelitosAPI(): Observable<any> {
    return this._http.get<any>(SERVER_DEBUG_URL+'/api/get_delitos');
  }

  /**
   * Devuelve la lista de delitos
   */
  getDelitos() {
    return this.listDelitos;
  }

  /**
   * Devuelve los incidentes detallados
   * @param incidentId
   */
  getIncidentDetail(incidentId: string) {
    return {
      ...this.listIncidents.find(incident => {
        return incident.id == incidentId;
      })
    };
  }

  /**
   * Devuelve los incidentes subidos detallados
   * @param incidentId 
   */
  getUploadedIncidentDetail(incidentId: string) {
    return {
      ...this.uploadedIncidents.find(incident => {
        return incident.id == incidentId;
      })
    };
  }

  /**
   * Transforma los recursos del filtro (modal) en el formato
   * correcto de URL para realizar la petición a la API
   * @param params 
   */
  paramsToUrl(params) {
    let url = "";
    let isFirstParam = true;

    if(params.dateFrom !== '' && params.dateTo !== '') {
      let dateFrom = params.dateFrom.split('T')[0];
      let dateTo = params.dateTo.split('T')[0];

      url += '?desde=' + dateFrom;
      url += '&hasta=' + dateTo;
      isFirstParam = false;
    }
    if(typeof params.selectedDelitos !== 'undefined' && params.selectedDelitos.length > 0) {
      url += isFirstParam ? '?':'&';

      params.selectedDelitos.forEach(function (delId, index) {
        if(index !== 0)
          url += '&';
        
        url += 'tipos_incidentes[]=' + delId;
      });
    }

    return url;
  }

  dateFormat(date) {
    let onlyDate = date.split('T')[0];
    let spltDate = onlyDate.split('-');

    return spltDate[2]+'/'+spltDate[1]+'/'+spltDate[0];
  }

}
