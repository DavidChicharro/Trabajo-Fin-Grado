import { Component, OnInit, Input } from '@angular/core';
import { ModalController } from '@ionic/angular';
import { Storage } from '@ionic/storage';
import { UserService } from '../user.service';


@Component({
  selector: 'app-secret-pin',
  templateUrl: './secret-pin.page.html',
  styleUrls: ['./secret-pin.page.scss'],
})
export class SecretPinPage implements OnInit {

  secretPin: string;
  prevSecretPin: string;

  constructor(
    private modalCtrl: ModalController,
    private userService: UserService,
    private storage: Storage
  ) { }

  ngOnInit() {
    this.obtainSecretPin();
  }

  changePin(pin: HTMLIonInputElement) {
    this.secretPin = ""+pin.value;
  }

  /**
   * Cierra la página
   */
  dismissModal() {
    this.modalCtrl.dismiss();
  }

  /**
   * Si se modifica el pin secreto, se almacena el nuevo valor en la B.D.
   */
  savePin() {
    if(this.secretPin !== this.prevSecretPin) {
      this.storage.get('api_token').then(user => {
        if(user !== null) {
          this.userService.setApiConfig(user, 'secretpin', this.secretPin).subscribe(res => {
          });
        }
      });
    }

    this.dismissModal();
  }

  /**
   * Obtiene el pin secreto del usuario
   */
  private obtainSecretPin() {
    if(this.secretPin !== this.prevSecretPin) {
      this.storage.get('api_token').then(user => {
        this.userService.getApiSecretPin(user).subscribe(response => {
          if (response.status === 'success') {
            this.secretPin = this.prevSecretPin = response.configParams;
          } else {
            this.secretPin = "";
            // Si no recibe nada, se cierra el modal
            this.dismissModal();
          }
        });
      });
    }
  }

}
