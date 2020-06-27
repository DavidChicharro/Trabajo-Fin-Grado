import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { AlertController } from '@ionic/angular';
import { ContactList } from '../../../models/contactList';
import { ContactsService } from '../../contacts.service';

@Component({
  selector: 'app-contact',
  templateUrl: './contact.page.html',
  styleUrls: ['./contact.page.scss'],
})
export class ContactPage implements OnInit {

  contact: ContactList;

  constructor(
    private activatedRoute: ActivatedRoute,
    private router: Router,
    private contactsService: ContactsService,
    private alertCtrl: AlertController
  ) { }

  ngOnInit() {
    this.activatedRoute.paramMap.subscribe(paramMap => {
      const recipeId = paramMap.get('userId');
      this.contact = this.contactsService.getUserDetailWhoseImContact(recipeId);
    });
  }

  /**
   * Se elimina al usuario como contacto favorito
   * 
   * @param contactId
   */
  deleteMeAsContact(contactId) {
    this.contactsService.deleteMeAsContact(contactId);
    this.router.navigate(['/whose-im']);
  }

  /**
   * Presenta una alerta sobre si se desea confirmar la
   * eliminación del usuario como contacto favorito
   * 
   * @param contactId 
   * @param contactName 
   */
  async alertDeleteMeAsContact(contactId, contactName) {
    const alert = await this.alertCtrl.create({
      header: '¿Eliminarte como contacto favorito?',
      message: '¿Estás seguro que deseas eliminarte como ' +
      'contacto favorito de ' + contactName + '?',
      buttons: [
        {
          text: 'Cancelar',
          role: 'cancel'
        }, {
          text: 'Confirmar',
          cssClass: 'warning-button',
          handler: () => {
            this.deleteMeAsContact(contactId);
          }
        }
      ]
    });

    await alert.present();
  }

}
