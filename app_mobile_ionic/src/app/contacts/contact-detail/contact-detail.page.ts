import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { AlertController } from '@ionic/angular';
import { ContactList } from '../../models/contactList';
import { ContactsService } from '../contacts.service';

@Component({
  selector: 'app-contact-detail',
  templateUrl: './contact-detail.page.html',
  styleUrls: ['./contact-detail.page.scss'],
})
export class ContactDetailPage implements OnInit {

  contact: ContactList;

  constructor(
    private activatedRoute: ActivatedRoute,
    private router: Router,
    private contactsService: ContactsService,
    private alertCtrl: AlertController
  ) { }

  ngOnInit() {
    this.activatedRoute.paramMap.subscribe(paramMap => {
      const recipeId = paramMap.get('contactId');
      this.contact = this.contactsService.getContactDetail(recipeId);
    });
  }

  sendLocation() {
    console.log('Send Location');
  }

  /**
   * Elimina al usuario como contacto favorito
   * 
   * @param contactId 
   */
  deleteContact(contactId) {
    this.contactsService.deleteContact(contactId);
    this.router.navigate(['/tabs/fav-contacts']);
  }

  /**
   * Presenta una alerta sobre si se desea confirmar 
   * la eliminación de un contacto favorito
   * 
   * @param contactId 
   * @param contactName 
   */
  async alertDeleteContact(contactId, contactName) {
    const alert = await this.alertCtrl.create({
      header: '¿Eliminar contacto favorito?',
      message: '¿Estás seguro que deseas eliminar a ' + contactName 
      + ' como contacto favorito?',
      buttons: [
        {
          text: 'Cancelar',
          role: 'cancel'
        }, {
          text: 'Confirmar',
          cssClass: 'warning-button',
          handler: () => {
            this.deleteContact(contactId);
          }
        }
      ]
    });

    await alert.present();
  }
}
