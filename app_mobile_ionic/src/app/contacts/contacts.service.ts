import { Injectable } from '@angular/core';
import { ToastController } from '@ionic/angular';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Storage } from '@ionic/storage';
import { ContactList } from '../models/contactList';
import { SERVER_DEBUG_URL } from './../../environments/environment';
import { SERVER_URL } from './../../environments/environment.prod';


@Injectable({
  providedIn: 'root'
})
export class ContactsService {

  contactsList: ContactList[];
  whoseImContactList: ContactList[];
  contact: any;

  constructor(
    private _http: HttpClient,
    private storage: Storage,
    private toastCtrl: ToastController,
  ) {
    this.setContactsList();
    this.setWhoseContactImList();
  }

  /**
   * Devuelve la lista de contactos favoritos
   */
  getContactsList() {
    return this.contactsList;
  }

  /**
   * Llama a la API para obtener los contactos favoritos del usuario
   * 
   * @param email 
   */
  getContactsApi(user): Observable<any>  {
    let params = new HttpParams();
    params = params.append('api_token', user);
    
    return this._http.get<any>(SERVER_DEBUG_URL+'/api/get_fav_contacts', {params: params});
  }

  /**
   * Establece la lista de usuarios que son contactos favoritos
   */
  setContactsList() {
    const list = [];

    this.storage.get('api_token').then(user => {
      if(user !== null) {
        this.getContactsApi(user).subscribe(response => {
          if(response.status === 'success') {
            for(let contact in response.favContacts) {
              list.push({
                id: response.favContacts[contact].fav_contact_id,
                name: response.favContacts[contact].nombre,
                email: response.favContacts[contact].email,
                tel: response.favContacts[contact].telefono,
                real_order: response.favContacts[contact].orden_real,
                order: response.favContacts[contact].orden_vista
              });
            }
          }
        });
      }
    });

    this.contactsList = list;
  }

  /**
   * Devuelve los detalles de un usuario consultado que es contacto favorito
   * 
   * @param contactId 
   */
  getContactDetail(contactId: string) {
    return {
      ...this.contactsList.find(contact => {
        return contact.id == contactId;
      })
    };
  }

  /**
   * Devuelve los detalles del usuario consultado y del cual se es contacto favorito
   * 
   * @param userId 
   */
  getUserDetailWhoseImContact(userId: string) {
    return {
      ...this.whoseImContactList.find(contact => {
        return contact.id == userId;
      })
    };
  }

  /**
   * Devuelve la lista de usuarios de quienes se es contacto favorito
   */
  getWhoseContactImList() {
    return this.whoseImContactList;
  }

  /**
   * Llamada a la API para obtener los usuarios de quienes se es contacto favorito
   * 
   * @param user 
   */
  getWhoseContactImApi(user): Observable<any>  {
    let params = new HttpParams();
    params = params.append('api_token', user);
    
    return this._http.get<any>(SERVER_DEBUG_URL+'/api/get_whose_contact_im', {params: params});
  }

  /**
   * Establece la lista de usuarios de quienes se es contacto favorito
   */
  setWhoseContactImList() {
    const list = [];

    this.storage.get('api_token').then(user => {
      if(user !== null) {
        this.getWhoseContactImApi(user).subscribe(response => {
          if(response.status === 'success') {
            for(let contact in response.contacts) {
              list.push({
                id: response.contacts[contact].fav_contact_id,
                name: response.contacts[contact].nombre,
                email: response.contacts[contact].email,
                tel: response.contacts[contact].telefono
              });
            }
          }
        });
      }
    });

    this.whoseImContactList = list;
  }

  /**
   * Elimina un contacto y recalcula el orden de la lista
   * 
   * @param userId 
   */
  deleteContact(userId) {
    this.storage.get('api_token').then(user => {
      if(user !== null) {
        this.deleteContactApi(user, userId).subscribe(response => {
          console.log(response);
          if (response.status === 'success') {
            let contact = this.contactsList.find(element => element.id === userId);
            let index = this.contactsList.indexOf(contact);

            if (index > -1) {
              this.contactsList.splice(index, 1);

              this.contactsList.forEach((val, idx) => {
                if (idx >= index) {
                  val.order = val.order - 1;
                }
              });
            }
          }
        });
      }
    });
  }

  /**
   * Se elimina al usuario como contacto favorito de otro usuario
   * 
   * @param userId 
   */
  deleteMeAsContact(userId) {
    this.storage.get('api_token').then(user => {
      if(user !== null) {
        this.deleteContactApi(user, userId, "true").subscribe(response => {
          if (response.status === 'success') {
            let contact = this.whoseImContactList.find(element => element.id === userId);
            let index = this.whoseImContactList.indexOf(contact);

            if (index > -1) {
              this.whoseImContactList.splice(index, 1);

              this.whoseImContactList.forEach((val, idx) => {
                if (idx >= index) {
                  val.order = val.order - 1;
                }
              });
            }
          }
        });
      }
    });
  }

  /**
   * Llamada a la API para eliminar un contacto 
   * favorito o para eliminarse como tal
   * 
   * @param user 
   * @param userId 
   * @param swap 
   */
  deleteContactApi(user, userId, swap="false"): Observable<any> {
    let params = new HttpParams();
    params = params.append('api_token', user);
    params = params.append('userId', userId);
    params = params.append('swap', swap);

    return this._http.get<any>(SERVER_DEBUG_URL+'/api/remove_reject_contact', {params: params});
  }

  /**
   * Actualiza el orden de los contactos favoritos
   * 
   * @param contactsOrder 
   */
  updateContactsOrder(contactsOrder) {
    this.storage.get('api_token').then(email => {
      if(email !== null) {
        this.updateContactsOrderApi(email, contactsOrder).subscribe(response => {
          console.log(response);
          if (response.status === 'success')
            this.presentToast('Orden actualizado correctamente');
          else
            this.presentToast('Error al actualizar el orden');
        });
      }
    });
  }

  /**
   * Llamada a la API para actualizar el orden de los contactos favoritos
   * 
   * @param user 
   * @param contactsOrder 
   */
  updateContactsOrderApi(user, contactsOrder): Observable<any> {
    let params = new HttpParams();
    params = params.append('api_token', user);
    params = params.append('order', contactsOrder);

    return this._http.get<any>(SERVER_DEBUG_URL+'/api/update_contacts_order', {params: params});
  }

  /**
   * Llamada a la API para buscar un usuario
   * 
   * @param user 
   * @param contact 
   */
  searchContactApi(user, contact): Observable<any> {
    let params = new HttpParams();
    params = params.append('api_token', user);
    params = params.append('contact', contact);
    
    return this._http.get<any>(SERVER_DEBUG_URL+'/api/search_contact', {params: params});
  }

  /**
   * Devuelve un contacto
   */
  getContact() {
    return this.contact;
  }

  /**
   * Añade un nuevo contacto favorito
   * 
   * @param contact 
   * @param petition 
   */
  addContact(contact, petition) {
    this.storage.get('api_token').then(user => {
      if(user !== null) {
        this.addContactApi(contact, petition, user).subscribe(response => {
          console.log(response);

          if (response.status === 'success')
            this.presentToast('Petición para ser contacto enviada correctamente');
          else
            this.presentToast('Error al enviar la petición');
        });
      }
    });
  }

  /**
   * Llamada a la API para añadir un contacto favorito
   * 
   * @param contact 
   * @param petition 
   * @param user
   */
  addContactApi(contact, petition, user): Observable<any> {
    let params = new HttpParams();
    params = params.append('userId', contact);
    params = params.append('petition', petition);
    params = params.append('api_token', user);
    
    return this._http.get<any>(SERVER_DEBUG_URL+'/api/add_contact', {params: params});
  }

  /**
   * Presenta un mensaje informativo volátil por pantalla
   * 
   * @param message 
   */
  async presentToast(message) {
    const toast = await this.toastCtrl.create({
      message: message,
      duration: 3000
    });
    toast.present();
  }
}
