import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ModalController } from '@ionic/angular';
import { AndroidPermissions } from '@ionic-native/android-permissions/ngx';
import { LocationAccuracy } from '@ionic-native/location-accuracy/ngx';
import { AuthService } from './../../auth/auth.service';
import { PanicActionPage } from '../panic-action/panic-action.page';
import { SecretPinPage } from '../secret-pin/secret-pin.page';
import { PersonalDataPage } from '../personal-data/personal-data.page';
import { PassDataPage } from '../pass-data/pass-data.page';

@Component({
  selector: 'app-profile',
  templateUrl: './profile.page.html',
  styleUrls: ['./profile.page.scss'],
})
export class ProfilePage implements OnInit {

  constructor(
    private authService: AuthService,
    private router: Router,
    private modalCtrl: ModalController,
    private androidPermissions: AndroidPermissions,
    private locationAccuracy: LocationAccuracy
  ) {
    this.authService.setEmailStorage();
  }

  ngOnInit() {
  } 
 
  /**
   * Abre el modal del filtro de incidentes
   */
  async openPanicActionModal() {
    const modal = await this.modalCtrl.create({
      component: PanicActionPage,
      /* componentProps: {
        email: this.authService.getStoredEmail()
      } */
    });
    await modal.present();

    const { data } = await modal.onDidDismiss();
  }

  /**
   * Abre el modal del filtro de incidentes
   */
  async openSecretPinModal() {
    const modal = await this.modalCtrl.create({
      component: SecretPinPage,
      /* componentProps: {
        email: this.authService.getStoredEmail()
      } */
    });
    await modal.present();

    const { data } = await modal.onDidDismiss();
  }

  enableLocation() {
    this.androidPermissions.checkPermission(
    this.androidPermissions.PERMISSION.ACCESS_COARSE_LOCATION).then(result => {
        if (result.hasPermission) {
          //If having permission show 'Turn On GPS' dialogue
          this.askToTurnOnGPS();
        } else {
          //If not having permission ask for permission
          this.requestGPSPermission();
        }
    }, err => {
      alert(err);
    });
  }

  requestGPSPermission() {
    this.locationAccuracy.canRequest().then((canRequest: boolean) => {
      if (canRequest) {
        console.log("4");
      } else {
        //Show 'GPS Permission Request' dialogue
        this.androidPermissions.requestPermission(
        this.androidPermissions.PERMISSION.ACCESS_COARSE_LOCATION).then(() => {
            // call method to turn on GPS
            this.askToTurnOnGPS();
          },
          error => {
            //Show alert if user click on 'No Thanks'
            alert('Error al solicitar permisos de geolocalización. No podrás enviar tu ubicación si no aceptas los permisos')
          }
        );
      }
    });
  }

  askToTurnOnGPS() {
    this.locationAccuracy.request(
    this.locationAccuracy.REQUEST_PRIORITY_HIGH_ACCURACY).then(() => {
        // When GPS Turned ON call method to get Accurate location coordinates
        // this.getLocationCoordinates();
      },
      error => alert('Error al solicitar permisos de geolocalización. No podrás enviar tu ubicación si no aceptas los permisos.')
    );
  }

  /**
   * Abre el modal de datos personales
   */
  async openPersonalDataModal() {
    const modal = await this.modalCtrl.create({
      component: PersonalDataPage,
      componentProps: {
        email: this.authService.getStoredEmail()
      }
    });
    await modal.present();

    const { data } = await modal.onDidDismiss();
  }

  /**
   * Abre el modal de datos personales
   */
  async openPassDataModal() {
    const modal = await this.modalCtrl.create({
      component: PassDataPage,
      componentProps: {
        email: this.authService.getStoredEmail()
      }
    });
    await modal.present();

    const { data } = await modal.onDidDismiss();
  }

  /**
   * Limpia el email del almacenamiento interno
   * y devuelve la vista a la pantalla de login
   */
  logout() {
    this.authService.clear();
    this.router.navigate(['/login']);
  }
}
