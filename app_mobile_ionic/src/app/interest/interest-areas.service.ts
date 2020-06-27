import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Storage } from '@ionic/storage'
import { Bounds } from './bounds.model';
import { SERVER_DEBUG_URL } from './../../environments/environment';
import { SERVER_URL } from './../../environments/environment.prod';

@Injectable({
  providedIn: 'root'
})
export class InterestAreasService {

  apiToken: string;
  interestAreas = [];
  bounds: Bounds;

  intAreaConfig: any;

  constructor(
    private _http: HttpClient,
    private storage: Storage,
  ) {
    this.setInterestAreas();
    this.setInterestAreaConfig();
    this.storage.get('api_token').then(token => {
      if(token !== null) {
        this.apiToken = token;
      }
    });
  }

  setInterestAreas() {
    const list = [];
    let bounds: Bounds;

    this.storage.get('api_token').then(user => {
      if(user !== null) {
        this.getApiInterestAreas(user).subscribe(res => {
          if (res.status === 'success') {
            let result = JSON.parse(res.interestAreas);
            result.forEach(function(intAr) {              
              list.push({
                id: intAr.id,
                lat: intAr.latitud_zona_interes,
                lng: intAr.longitud_zona_interes,
                name: intAr.nombre_zona_interes,
                radio: intAr.radio_zona_interes
              });
            });

            bounds = res.bounds;
          }

          this.interestAreas = list;
          this.bounds = bounds;
        });
      }
    });
  }

  setInterestAreaConfig() {
    this.storage.get('api_token').then(user => {
      if(user !== null) {
        this.getApiInterestAreaConfig(user).subscribe(res => {
          if (res.status === 'success') {
            this.intAreaConfig = res.config;
          }
        });
      };
    });
  }

  getInterestAreas() {
    return this.interestAreas;
  }

  getBounds() {
    return this.bounds;
  }

  getApiInterestAreas(user): Observable<any> {
    return this._http.get<any>(SERVER_DEBUG_URL+'/api/get_interest_areas?api_token='+user);
  }

  getApiInterestAreaConfig(user): Observable<any> {
    return this._http.get<any>(SERVER_DEBUG_URL+'/api/new_area?api_token='+user);
  }

  getInterestAreasConfig() {
    return this.intAreaConfig;
  }

  uploadInterestArea(latLng, place, radio): Observable<any> {
    let spltLatLng = latLng.split(',');
    let params = new HttpParams();
    params = params.append('api_token', this.apiToken);
    params = params.append('lat_zona_int', spltLatLng[0]);
    params = params.append('long_zona_int', spltLatLng[1]);
    params = params.append('nombre_lugar', place);
    params = params.append('radio_zona_int', radio);
    
    return this._http.get<any>(SERVER_DEBUG_URL+'/api/store_interest_area', {params: params});
  }
}
