import { Component, OnInit } from '@angular/core';
import { ModalController } from '@ionic/angular';
import { Storage } from '@ionic/storage';
import { ContactsService } from '../contacts.service';
import { UserService } from './../../user/user.service';
import { PanicPage } from '../panic/panic.page';

@Component({
  selector: 'app-fav-contacts',
  templateUrl: './fav-contacts.page.html',
  styleUrls: ['./fav-contacts.page.scss'],
})
export class FavContactsPage implements OnInit {

  email: string;
  disabledPanicButton = true; // habilita el botón del pánico si hay acción de pánico establecida
  panicAction: string;

  listContacts = [];
  disabledReorder = true; // permite ver/ocultar la opción de reodenación
  orderChanged = false;   // controla si se ha modificado el orden de contactos

  orderContactsIcon = 'swap-vertical';
  orderColor = 'k-blue';

  constructor(
    private contactsService: ContactsService,
    private userService: UserService,
    private modalCtrl: ModalController,
    private storage: Storage
  ) {
  }

  ngOnInit() {    
    this.listContacts = this.contactsService.getContactsList();
  }

  ionViewDidEnter(){
    this.obtainPanicAction();
  }

  /**
   * Actualiza la información de la pantalla al deslizarla hacia abajo
   * 
   * @param event
   */
  doRefresh(event) {
    this.contactsService.setContactsList();

    setTimeout(() => {
      this.listContacts = [];
      this.listContacts = this.contactsService.getContactsList();
      event.target.complete();
    }, 3000);
  }

  /**
   * Actualiza el orden de los contactos y la vista del botón que activa dicha funcionalidad
   */
  toggleReorder() {
    this.disabledReorder = !this.disabledReorder;
    this.orderContactsIcon = (this.orderContactsIcon === 'save-outline') ? 'swap-vertical' : 'save-outline';
    this.orderColor = (this.orderColor === 'k-red') ? 'k-blue' : 'k-red';

    if (this.disabledReorder && this.orderChanged) {
      let contactsOrder = []; // Array con IDs de contactos según el orden en que aparecen
      this.listContacts.forEach((contact) => {
        contactsOrder.push(contact.id);
      });
      console.log(contactsOrder);
      this.contactsService.updateContactsOrder(contactsOrder);
      this.orderChanged = false;
    }
  }

  /**
   * Actualiza el orden de los contactos por pantalla
   * 
   * @param event 
   */
  reorderItems(event) {
    this.orderChanged = true;
    // console.log(`Moving item from ${event.detail.from} to ${event.detail.to}`);
    const itemMove = this.listContacts.splice(event.detail.from, 1)[0];
    this.listContacts.splice(event.detail.to, 0, itemMove);
    event.detail.complete();

    // Reasignación de orden
    for(let i=0; i<this.listContacts.length; i++)
      this.listContacts[i].order = i+1;
  }

  /**
   * Obtiene la acción de pánico del usuario
   */
  private obtainPanicAction() {
    this.storage.get('api_token').then(email => {
      if(email !== null) {
        this.userService.getApiPanicAction(email).subscribe(response => {
          if (response.status === 'success') {
            this.panicAction = (response.configParams===null) ? 'null' : response.configParams;
            this.disabledPanicButton = 
              (this.panicAction=='ubicacion' || this.panicAction=='llamada') ? false : true;
          }
        });
      }
    });
  }

  /**
   * Abre el modal de acción de pánico
   */
  async openPanicModal() {
    const modal = await this.modalCtrl.create({
      component: PanicPage,
      componentProps: {
        panicAction: this.panicAction,
        favouriteContacts: this.listContacts
      }
    });
    await modal.present();

    const { data } = await modal.onDidDismiss();
  }
}
