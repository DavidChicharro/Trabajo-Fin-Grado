import { Component, OnInit, Input } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { ModalController, IonBackdrop, Platform } from '@ionic/angular';
import { Storage } from '@ionic/storage';
import { Geolocation, Geoposition } from '@ionic-native/geolocation/ngx';
import { CallNumber } from '@ionic-native/call-number/ngx';
import { CallLog, CallLogObject } from '@ionic-native/call-log/ngx';
import { Observable } from 'rxjs';
import { SERVER_DEBUG_URL } from './../../../environments/environment';
import { SERVER_URL } from './../../../environments/environment.prod';

@Component({
  selector: 'app-panic',
  templateUrl: './panic.page.html',
  styleUrls: ['./panic.page.scss'],
})
export class PanicPage implements OnInit {

  @Input() panicAction;
  @Input() favouriteContacts;

  showCountdown = true;
  showLocation = false;
  showCall = false;
  countdown = 5;
  radius = 80;
  circumference: string;
  circle: any;
  doPanicAction = true;

  watch: any;
  latitude: any;
  longitude: any;
  locationChanged = false;

  contactsIds = [];
  contactsTlfNumbers = [];
  callMsg: any; // !!!!!!!!!!
  // continueCalling = true;
  filters: CallLogObject[];
  recordsFoundText: string;
  recordsFound: any;
  callInterval: any;

  callHistory: any;
  updatedCallHistory: any;

  /* allowCall = true;    // Permitir llamada
  callNext = true;   // Primera llamada
  allCallHistory = [{
    "date":1590948296905,
    "number":"608130680",
    "type":2,
    "duration":14,
    "new":1,
    "cachedName":"ana",
    "name":"ana"
  },
  {
    "date":1590943319726,
    "number":"616612067",
    "type":2,
    "duration":0,
    "new":1,
    "cachedName":"Mamá",
    "name":"Mamá"
  },
  {
    "date":1590943277451,
    "number":"616612067",
    "type":2,
    "duration":17,
    "new":1,
    "cachedName":"Mamá",
    "name":"Mamá"
  }
  ];

  newCallHistory = [{
    "date":1590950000000,
    "number":"694815326",
    "type":2,
    "duration":14,
    "new":1,
    "cachedName":"Nuevo Mail",
    "name":"Nuevo Mail"
  }]; */

  constructor(
    private platform: Platform,
    private modalCtrl: ModalController,
    private geolocation: Geolocation,
    private callNumber: CallNumber,
    private callLog: CallLog,
    private storage: Storage,
    private _http: HttpClient
  ) {
    this.circumference = (2 * this.radius * Math.PI+'');
    this.callMsg = "----";  // !!!!!!!!!!!!
  }

  ngOnInit() {
    // Almacena los identificadores de los contactos favoritos
    this.favouriteContacts.forEach((contact) => {
      this.contactsIds.push(contact.id);
      this.contactsTlfNumbers.push(contact.tel);
    });
  }

  ngAfterViewInit() {
    this.circle = document.getElementById('circle');
    this.circle.style.transition = `all ${this.countdown}s linear`;

    setTimeout(() => this.startTimer(), 0);
  }

  /**
   * Comienza la cuenta atrás para llevar a cabo la acción de pánico
   */
  startTimer() {
    const interval = setInterval(() => {
      this.countdown--;
      
      if (this.countdown === 0) {
        clearInterval(interval);
        this.showCountdown = false; // Se oculta la pantalla que se estaba viendo con el contador
        if (this.doPanicAction) {
          if (this.panicAction === 'ubicacion') {
            this.shareLocation();
          } else if (this.panicAction === 'llamada') {
            /* this.callHistory = this.getCallHistory();
            this.updateCallHistory(); */
            this.callContacts();
          } else {
            this.dismissModal();
          }
        }
      }
    }, 1000);

    this.circle.setAttribute('stroke-dasharray', this.circumference);
    this.circle.setAttribute('stroke-dashoffset', this.circumference);
  }

  shareLocation() {
    this.showLocation = true;

    // Se llama a actualizar ubicación cada vez que se produce un cambio en las coordenadas
    this.watch = this.geolocation.watchPosition({ enableHighAccuracy: true })
    .subscribe((data) => {
      this.updateLocation(data.coords);
    });

    this.shareLocationContacts(); 
  }

  /**
   * Deja de compartir la ubicación
   */
  stopShareLocation() {
    this.watch.unsubscribe();
    this.setLocationNull();
    this.locationChanged = false;

    this.dismissModal();
  }

  callContact(number) {
    // console.log('Calling... '+number);
    // this.callMsg = 'Llamando a ' + number;
    this.callNumber.callNumber(number, true)
      .then(res => {this.callMsg = res;})
      .catch(err => {this.callMsg = err});
  }

  callContacts() {
    this.showCall = true;

    // Llama al primer contacto
    this.callContact(this.contactsTlfNumbers.shift());

    /* this.callMsg += '\nQuedan ' + this.contactsTlfNumbers.length + ' más\n';
    if (this.contactsTlfNumbers.length > 0) {
      // Se comprueba cada 10 segundos el último registro del historial de llamadas
      this.callInterval = setInterval(() => {
        if (this.contactsTlfNumbers.length > 0) {
          // console.log('Entro en setInterval');
          let lastCall = JSON.stringify(this.callHistory[0]);    // última llamada del historial -> elemento fijo -> será global
          let newLastCall = JSON.stringify(this.updatedCallHistory[0]); // última llamada del historial -> llamada cada vez
          let newLastCallDuration = this.updatedCallHistory[0]['duration'];
          this.callMsg += '\nÚltima llamada' + newLastCall + '\n';

          this.updateCallHistory();
          // console.log('Duracion: ' + newLastCallDuration);

          if (typeof lastCall === 'undefined' || lastCall === newLastCall) {
            console.log('Sigue llamando o está en la llamada');
            this.callMsg += '\nSigue llamando o está en la llamada';
          } else if (newLastCallDuration > 0){
            console.log('Ha cogido la llamada');
            this.callMsg += '\nHa cogido la llamada';
            clearInterval(this.callInterval);
          } else {
            console.log('Llamo al siguiente porque no ha cogido');
            this.callMsg += '\nLlamo al siguiente porque no ha cogido';
            this.updateCallHistory(true); //???!!
            //lastCall = newLastCall; // actualizo el elemento fijo. Ahora la última del historial es otra
            this.callContact(this.contactsTlfNumbers.shift());            
          }
        } else {
          clearInterval(this.callInterval);
          console.log('NO HAY MÁS números coño');
          this.callMsg += '\nNO HAY MÁS números coño';
          //Cerrar modal !!!!!!!! this.stopCallContacts()
        }
      }, 5000);
    } else {
      clearInterval(this.callInterval);
      console.log('NO HAY MÁS NÚMEROS');
      this.callMsg += '\nNO HAY MÁS NÚMEROS';
      //Cerrar modal !!!!!!!! this.stopCallContacts()
    } */

    this.stopCallContacts();
  }

  /**
   * Se obtiene el historial de llamadas de los últimos 30 minutos
   */
  /* getCallHistory() {
    let today = new Date();
    let last30 = new Date(today);
    last30.setTime(today.getTime() - 30*60000);
    let fromTime = last30.getTime();

    this.filters = [{
      name: "date",
      value: fromTime.toString(),
      operator: ">=",
    }];

    this.platform.ready().then(() => {
      this.callLog.hasReadPermission().then(hasPermission => {
        if (!hasPermission) {
          this.callLog.requestReadPermission().then(results => {
            this.callLog.getCallLog(this.filters).then(results => {
              this.recordsFoundText = JSON.stringify(results);
              this.recordsFound = results;
              return results;              
            });
          }).catch(e => alert(" requestReadPermission " + JSON.stringify(e)));
        } else {
          this.callLog.getCallLog(this.filters).then(results => {
            this.recordsFoundText = JSON.stringify(results);
            this.recordsFound = results;
            return results;
          });
        }
      }).catch(e => alert(" hasReadPermission " + JSON.stringify(e)));
    });
  } */

  /**
   * Actualiza el historial de llamadas
   */
  /* updateCallHistory(updateOriginal = false) {
    this.updatedCallHistory = this.getCallHistory();
    if (updateOriginal)
      this.callHistory = this.updateCallHistory;
  } */

  /**
   * Cesa el modal de llamada de pánico y lo cierra
   */
  stopCallContacts() {
    clearInterval(this.callInterval);
    this.dismissModal();
  }

  /**
   * Cancela el botón del pánico
   */
  cancel() {
    this.doPanicAction = false;
    this.dismissModal();
  }

  /**
   * Actualiza la ubicación actual del usuario si la posición cambia ~30m
   * 
   * @param coords 
   */
  updateLocation(coords: Coordinates) {
    if (this.locationChanged) {  // Si NO es la primera vez que se accede a la ubicación
      let difLat = Math.abs(this.latitude - coords.latitude);
      let difLng = Math.abs(this.longitude - coords.longitude);

      if(difLat > 0.0002 || difLng > 0.002) { //Si hay cambio sustancial en la posición
        this.storage.get('api_token').then(user => {
          if(user !== null) {
            this.updateLocationApi(user, coords).subscribe(response => {
              if(response.status === 'success') {
                this.latitude = coords.latitude;
                this.longitude = coords.longitude;
              }
            });
          }
        });
      }
    } else {
      this.storage.get('api_token').then(user => {
        if(user !== null) {
          this.updateLocationApi(user, coords).subscribe(response => {
            if(response.status === 'success') {
              this.latitude = coords.latitude;
              this.longitude = coords.longitude;

              this.locationChanged = true;
            }
          });
        }
      });
    }
  }

  /**
   * Actualiza la posición del usuario en la base de datos
   * 
   * @param user 
   * @param coords 
   */
  updateLocationApi(user: string, coords: Coordinates): Observable<any> {
    let lat = coords.latitude.toFixed(4).toString();
    let lng = coords.longitude.toFixed(4).toString();
    let params = new HttpParams();
    params = params.append('api_token', user);
    params = params.append('lat', lat);
    params = params.append('lng', lng);
    
    return this._http.get<any>(SERVER_DEBUG_URL+'/api/update_location', {params: params});
  }

  /**
   *Establece a NULL la posición actual del usuario
   */
  setLocationNull() {
    this.storage.get('api_token').then(user => {
      if(user !== null) {
        this.setLocationNullApi(user).subscribe(response => {
          if(response.status === 'success') {
            console.log('success set null');
          }
        });
      }
    });
  }

  /**
   * Llamada a la API para establecer a NULL la posición actual del usuario
   */
  setLocationNullApi(user: string): Observable<any> {
    let params = new HttpParams();
    params = params.append('api_token', user);
    params = params.append('lat', 'null');
    params = params.append('lng', 'null');
    
    return this._http.get<any>(SERVER_DEBUG_URL+'/api/update_location', {params: params});
  }

  /**
   * Notifica a los contactos favoritos que está en posible situación 
   * de peligro y les notifica para poder ver su ubicación
   */
  shareLocationContacts() {
    this.storage.get('api_token').then(user => {
      if(user !== null) {
        this.shareLocationContactsApi(user, this.contactsIds).subscribe(response => {
          if(response.status === 'success') {
            console.log('success NOTIFICATION CONTACTS');
          }
        });
      }
    });
  }

  /**
   * Llamada a la API para notificar a los contactos 
   * favoritos que pueden ver la ubicación del usuario
   * 
   * @param user 
   * @param contacts 
   */
  shareLocationContactsApi(user: string, contacts) {
    let params = new HttpParams();
    params = params.append('api_token', user);
    params = params.append('contacts', contacts);
    
    return this._http.get<any>(SERVER_DEBUG_URL+'/api/share_location', {params: params});
  }

  /**
   * Cierra el modal de filtro
   */
  dismissModal() {
    this.modalCtrl.dismiss();
  }
}
