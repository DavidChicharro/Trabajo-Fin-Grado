import { Component, OnInit, Input } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { AlertController, ModalController, LoadingController, ToastController } from '@ionic/angular';
import { Storage } from '@ionic/storage';
import { Observable } from 'rxjs';
import { Map, tileLayer, marker, LayerGroup, Layer } from 'leaflet';
import { SERVER_DEBUG_URL } from './../../../environments/environment';
import { SERVER_URL } from './../../../environments/environment.prod';

@Component({
  selector: 'app-location',
  templateUrl: './location.page.html',
  styleUrls: ['./location.page.scss'],
})
export class LocationPage implements OnInit {

  @Input() contactId;
  @Input() contactName;
  isLoading = false;
  interval: any;
  zoomSetted = false;

  map: Map;
  marker: any;
  layerGroup: LayerGroup;
  lat: any;
  lng: any;

  constructor(
    private storage: Storage,
    private _http: HttpClient,
    private alertCtrl: AlertController,
    private modalCtrl: ModalController,
    private loadingCtrl: LoadingController,
    private toastCtrl: ToastController
  ) {
    this.getContactLocation();
    this.interval = setInterval(() => {
      this.getContactLocation();
    }, 7500);
  }

  ngOnInit() {
    this.presentLoading();
  }

  ionViewDidEnter(){
    this.loadMap();
  }

  ngOnDestroy() {
    if(this.map) {
      this.map.remove();
    }
  }

  /**
   * Carga el mapa
   */
  loadMap() {
    if(this.map) {
      this.map.remove();
    }
    this.map = new Map("mapContactLocation").setView([40, -4], 5);
    this.layerGroup = new LayerGroup().addTo(this.map);

    tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> contributors, ' +
          '<a href="https://creativecommons.org/licenses/by-sa/2.0/" target="_blank">CC-BY-SA</a>, ' +
          'Imagery © <a href="https://www.mapbox.com/" target="_blank">Mapbox</a>',
      maxZoom: 22,
      id: 'mapbox/streets-v11',
      accessToken: 'pk.eyJ1IjoiZGF2aWRjaGljaGFycm8iLCJhIjoiY2s4dTRuenNqMDE5djNka2Q0amE3bHBnYyJ9.ebGkyWx_FQLj5oBW936UJg'
    }).addTo(this.map);
  }

  updateCoordinates() {
    let layerGroup = this.layerGroup;
    layerGroup.clearLayers();
    marker([this.lat, this.lng]).addTo(layerGroup);
    this.map.panTo([this.lat, this.lng]);

    if (!this.zoomSetted) {
      setTimeout(() => {
        this.map.setView([this.lat, this.lng], 15);;
        this.zoomSetted = true;
        this.dismissLoading();
      }, 1000);      
    }
  }

  /**
   * Obtiene la ubicación del contacto que está en posible situacioń de peligro
   */
  getContactLocation() {
    this.storage.get('api_token').then(user => {
      if(user !== null) {
        this.getContactLocationApi(user).subscribe(res => {
          if (res.status === 'success') {
            this.lat = res.location.lat;
            this.lng = res.location.lng;
            this.updateCoordinates();
          } else if (res.status === 'error') {
            if (res.location === -1) {
              this.dismissLoading();
              this.presentToast(this.contactName + ' ha dejado de compartir ubicación');
              this.dismissModal();
            }
          }
        });
      }
    });
  }

  /**
   * Llamada a la API para obtener las ubicación del contacto
   */
  getContactLocationApi(user): Observable<any> {
    let params = new HttpParams();
    params = params.append('contactId', this.contactId);
    params = params.append('api_token', user);

    return this._http.get<any>(SERVER_DEBUG_URL+'/api/get_user_location', {params: params});
  }

  /**
   * Alerta para confirmar que se quiere salir del modal
   */
  async alertExit() {
    const alert = await this.alertCtrl.create({
      header: '¿Deseas salir?',
      message: 'Si sales no podrás seguir viendo la ubicación',
      buttons: [
        {
          text: 'Cancelar',
          role: 'cancel'
        }, {
          text: 'Confirmar',
          cssClass: 'warning-button',
          handler: () => {
            this.dismissModal();
          }
        }
      ]
    });

    await alert.present();
  }


  /**
   * Cierra el modal de filtro
   */
  dismissModal() {
    // this.doGetContactLocation = false;
    clearInterval(this.interval);
    this.modalCtrl.dismiss();
  }

  async presentToast(message) {
    const toast = await this.toastCtrl.create({
      message: message,
      duration: 3000
    });
    toast.present();
  }

  /**
   * Muestra un spinner de carga de datos
   */
  async presentLoading() {
    this.isLoading = true;
    return await this.loadingCtrl.create({
      message: 'Cargando...',
      duration: 10000
    }).then(a => {
      a.present().then(() => {
        if (!this.isLoading) {
          a.dismiss();
        }
      });
    });
    // await loading.present();

    // const { role, data } = await loading.onDidDismiss();
  }

  async dismissLoading() {
    this.isLoading = false;
    return await this.loadingCtrl.dismiss();
  }
}