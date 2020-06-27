import { Injectable } from '@angular/core';
import { Storage } from '@ionic/storage';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { SERVER_DEBUG_URL } from './../../environments/environment';
import { SERVER_URL } from './../../environments/environment.prod';


import { Response } from '../models/response';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  status: any;
  email: string;

  constructor(
    private _http: HttpClient,
    private storage: Storage
  ) { }

  /**
   * Guarda en el almacenamiento interno el 
   * email el usuario y su token de la API
   * 
   * @param email 
   * @param token 
   */
  store(email: string, token: string) {
    this.storage.set('email', email);
    this.storage.set('api_token', token);
  }

  /**
   * Limpia el almacenamiento interno
   */
  clear() {
    this.storage.clear();
  }

  setEmailStorage() {
    this.storage.get('email').then(val => {
      this.email = val;
    });
  }

  getStoredEmail() {
    return this.email;
  }

  // Observable se pone siempre que sea una solicitud de un servicio
  // Entre <> pongo el tipo de respuesta -> Modelo creado
  login(email, password): Observable<Response> {
    let params = new HttpParams();
    params = params.append('email', email);
    params = params.append('password', password);

    return this._http.get<Response>(SERVER_DEBUG_URL+'/api/login', {params: params});
  }

  checkUserExists(email): Observable<any> {
    return this._http.get<any>(SERVER_DEBUG_URL+'/api/check_user?email='+email);
  }

  registUser(email, pass, nom, ap, date, tlf, dni): Observable<any>{
    let params = new HttpParams();
    params = params.append('email', email);
    params = params.append('password', pass);
    params = params.append('nombre', nom);
    params = params.append('apellidos', ap);
    params = params.append('fecha_nacimiento', date);
    params = params.append('telefono', tlf);
    params = params.append('dni', dni);
    
    return this._http.get<any>(SERVER_DEBUG_URL+'/api/regist_user', {params: params});
  }

  updateUser(user, email, nom, ap, date, tlf, tlf_fijo, dni): Observable<any>{
    let params = new HttpParams();
    params = params.append('api_token', user);
    params = params.append('email', email);
    params = params.append('nombre', nom);
    params = params.append('apellidos', ap);
    params = params.append('fecha_nacimiento', date);
    params = params.append('telefono', tlf);
    params = params.append('telefono_fijo', tlf_fijo);
    params = params.append('dni', dni);
    
    return this._http.get<any>(SERVER_DEBUG_URL+'/api/update_user', {params: params});
  }

  updatePass(user, pass, newPass): Observable<any>{
    let params = new HttpParams();
    params = params.append('api_token', user);
    params = params.append('password', pass);
    params = params.append('newPass', newPass);

    return this._http.get<any>(SERVER_DEBUG_URL+'/api/update_pass', {params: params});
  }

  public calcDniLetter(number){
    const ctrlCharArr = "TRWAGMYFPDXBNJZSQVHLCKET";
    let position = number%23;
    return ctrlCharArr.charAt(position);
  }

  public capitalizeFirst(text){
    return text.charAt(0).toUpperCase() + text.substring(1);
  }

  public formatDate(date: string) {
    return date.split('T')[0];
  }
}
