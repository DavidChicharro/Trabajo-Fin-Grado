import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Storage } from '@ionic/storage'
import { Observable } from 'rxjs';
import { IncidentsService } from '../incidents.service';
import { Map, tileLayer, marker, LayerGroup } from 'leaflet';
import { SERVER_DEBUG_URL } from '../../../environments/environment';
import { SERVER_URL } from '../../../environments/environment.prod';


@Component({
  selector: 'app-new',
  templateUrl: './new.page.html',
  styleUrls: ['./new.page.scss'],
})
export class NewPage implements OnInit {

  userEmail: string;
  apiToken: string;
  listDelitos = [];
  delito: string;
  fecha: string;
  hora: string;
  lugar: string;
  nombre_lugar: string;
  descripcion: string;
  longDesc: number;
  agravantes: any = [
    { val: 1, name: "Disfraz", checked: false },
    { val: 2, name: "Abuso de superioridad", checked: false },
    { val: 3, name: "Sufrimiento inhumano", checked: false },
    { val: 4, name: "Racismo, discriminación, homofobia, machismo, ...", checked: false }
  ];
  afectado_testigo: string;

  map: Map;
  marker: any;
  layerGroup: LayerGroup;

  constructor(
    private incidentsService: IncidentsService,
    private _http: HttpClient,
    private storage: Storage,
    private router: Router,
  ) {
    this.listDelitos = this.incidentsService.getDelitos();
    this.longDesc = 0;
  }

  ngOnInit() {
    // this.setUserEmail();
    this.setApiToken();
  }

  ngOnDestroy() {
    if(this.map) {
      this.map.remove();
    }
  }

  ionViewDidEnter(){
    this.loadMap();
  }

  loadMap() {
    // Si se borra todo, quitar esta condición y su contenido
    // if(this.map) {
    //   this.map.remove();
    // }
    this.map = new Map("mapNewInc").setView([37.18,-3.6], 14);
    this.layerGroup = new LayerGroup().addTo(this.map);
    // var searchControl = new L.esri.Controls.Geosearch().addTo(mymap);

    tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> contributors, ' +
          '<a href="https://creativecommons.org/licenses/by-sa/2.0/" target="_blank">CC-BY-SA</a>, ' +
          'Imagery © <a href="https://www.mapbox.com/" target="_blank">Mapbox</a>',
      maxZoom: 22,
      id: 'mapbox/streets-v11',
      accessToken: 'pk.eyJ1IjoiZGF2aWRjaGljaGFycm8iLCJhIjoiY2s4dTRuenNqMDE5djNka2Q0amE3bHBnYyJ9.ebGkyWx_FQLj5oBW936UJg'
    }).addTo(this.map);
    
    this.map.on('click', (e)=> {
      this.onMapClick(e);
    });    
  }

  onMapClick(e) {
    this.layerGroup.clearLayers();
    let lat = e.latlng.lat;
    let lng = e.latlng.lng;
    marker([lat, lng]).addTo(this.layerGroup);

    let placeName = "";
    this.getPlaceName(lat, lng).subscribe(data => {
      placeName = ((typeof(data.address.locality)!== 'undefined')?data.address.locality +", ":"")+
        ((typeof(data.address.city_district)!== 'undefined')?data.address.city_district +", ":"")+
        ((typeof(data.address.village)!== 'undefined')?data.address.village:"")+
        ((typeof(data.address.town)!== 'undefined')?data.address.town:"")+
        ((typeof(data.address.city)!== 'undefined')?data.address.city:"")+
        ((typeof(data.address.county)!== 'undefined')?" ("+data.address.county +")":"");

      this.nombre_lugar = (placeName !== "") ? placeName : data.address.country;
    });
    
    
    this.lugar = parseFloat(lat).toFixed(4) + ',' + parseFloat(lng).toFixed(4);
  }

  getPlaceName(lat, lng): Observable<any> {
    return this._http.get<any>('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' +
    lat + '&lon=' + lng);
  }

  logForm() {
    let fecha = this.fecha.split('T')[0];
    let hora = this.hora.split('T')[1].substr(0,5);
    let fecha_hora = fecha + ' ' + hora;
    let nombre_lugar = (typeof this.nombre_lugar === 'undefined') ? this.lugar : this.nombre_lugar;
    let descripcion = (typeof this.descripcion === 'undefined') ? "" : this.descripcion;
    let afectado_testigo = this.afectado_testigo ? '0' : '1';
    let agrList = [];
    let agravantes;
    this.agravantes.forEach(function(agravante) {
      if(agravante.checked)
        agrList.push(agravante.val);
    });
    agravantes = (agrList.length > 0) ? agrList.join(',') : null;

    let params = this.paramsToUrl(this.delito, fecha_hora, this.lugar, 
      nombre_lugar, descripcion, agravantes, afectado_testigo);

    this.uploadIncident(params).subscribe(response => {
      if(response.status === 'success') {
        this.router.navigate(['tabs/list']);
      }
    });
  }

  uploadIncident(params): Observable<any> {
    return this._http.get<any>(SERVER_DEBUG_URL+'/api/store_incident', {params: params});
  }

  paramsToUrl(delito, fecha_hora, lugar, 
    nombre_lugar, descripcion, agravantes, afectado_testigo) {

    let params = new HttpParams();
    params = params.append('api_token', this.apiToken);
    params = params.append('delito', delito);
    params = params.append('lugar', lugar);
    params = params.append('nombre_lugar', nombre_lugar);
    params = params.append('fecha_hora_incidente', fecha_hora);
    params = params.append('descripcion_incidente', descripcion);
    params = params.append('agravantes', agravantes);
    params = params.append('afectado_testigo', afectado_testigo);

    return params;
  }

  setApiToken() {
    this.storage.get('api_token').then(token => {
      if(token !== null) {
        this.apiToken = token;
      }
    });
  }
}