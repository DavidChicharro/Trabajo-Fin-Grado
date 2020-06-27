import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { HttpClient, HttpParams } from '@angular/common/http';
import { SERVER_DEBUG_URL } from './../../environments/environment';
import { SERVER_URL } from './../../environments/environment.prod';


@Injectable({
  providedIn: 'root'
})
export class UserService {

  panicAction: string;
  secretPin: string;
  // email: string;

  constructor(
    private _http: HttpClient,
  ) { }

  getApiConfig(user, param): Observable<any> {
    let params = new HttpParams();
    params = params.append('api_token', user);
    params = params.append('params', param);

    return this._http.get<any>(SERVER_DEBUG_URL+'/api/get_config', {params: params});
  }

  getApiPanicAction(user): Observable<any> {
    return this.getApiConfig(user, 'panicact');
  }

  getApiSecretPin(user): Observable<any> {
    return this.getApiConfig(user, 'secretpin');
  }

  setApiConfig(user, param, value): Observable<any> {
    let params = new HttpParams();
    params = params.append('api_token', user);
    params = params.append('configId', param);
    params = params.append('value', value);

    return this._http.get<any>(SERVER_DEBUG_URL+'/api/set_config', {params: params});
  }

  getApiUserData(user): Observable<any> {
    let params = new HttpParams();
    params = params.append('api_token', user);

    return this._http.get<any>(SERVER_DEBUG_URL+'/api/get_user_data', {params: params});
  }

}
