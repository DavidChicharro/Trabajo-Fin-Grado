import { Component, OnInit, Input } from '@angular/core';
import { ModalController } from '@ionic/angular';
import { Storage } from '@ionic/storage';
import { UserService } from '../user.service';


@Component({
  selector: 'app-panic-action',
  templateUrl: './panic-action.page.html',
  styleUrls: ['./panic-action.page.scss'],
})
export class PanicActionPage implements OnInit {

  panicAction: string;
  prevPanicAct: string;

  constructor(
    private modalCtrl: ModalController,
    private userService: UserService,
    private storage: Storage
  ) { }

  ngOnInit() {
    this.obtainPanicAction();
  }

  /**
   * Cierra la página
   */
  dismissModal() {
    this.modalCtrl.dismiss();
  }

  /**
   * Si se modifica la acción de pánico, se almacena el nuevo valor en la B.D.
   */
  setConfig() {
    if (this.panicAction !== this.prevPanicAct) {
      this.storage.get('api_token').then(user => {
        if(user !== null) {
          this.userService.setApiConfig(user, 'panicact', this.panicAction).subscribe(res => {
          });
        }
      });
    }
    
    this.dismissModal();
  }

  /**
   * Obtiene la acción de pánico del usuario
   */
  private obtainPanicAction() {
    this.storage.get('api_token').then(user => {
      if(user !== null) {
        this.userService.getApiPanicAction(user).subscribe(response => {
          if (response.status === 'success') {
            this.panicAction = this.prevPanicAct = 
              (response.configParams===null) ? 'null' : response.configParams;
          } else {
            this.panicAction = "";
            // Si no recibe nada, se cierra el modal
            this.dismissModal();
          }
        });
      }
    });
  }

}
