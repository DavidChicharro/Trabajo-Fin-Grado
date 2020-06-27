import { Component, OnInit } from '@angular/core';
import { Storage } from '@ionic/storage';
import { ContactsService } from '../contacts.service';

@Component({
  selector: 'app-new',
  templateUrl: './new.page.html',
  styleUrls: ['./new.page.scss'],
})
export class NewPage implements OnInit {

  inputSearch: string;
  contact: any;

  contactNotFound = false;
  contactFound = false;
  contactAlredyAdded = false;
  contactSubmitedRequest = false;
  contactFoundAlredyAdded = false;

  constructor(
    private contactsService: ContactsService,
    private storage: Storage
  ) {
    this.inputSearch = "";
  }

  ngOnInit() {
  }

  searchKeyBoard(event) {
    /* console.log(event);
    console.log(event.target.value); */
    this.search(event.target.value);
  }

  searchButton() {
    this.search(this.inputSearch);
  }

  /**
   * Busca a un usuario por su email o teléfono y muestra 
   * el resultado correspondiente a la búsqueda
   * 
   * @param contact 
   */
  search(contact) {
    this.disableAll();
        
    this.storage.get('api_token').then(user => {
      if(user !== null) {
        this.contactsService.searchContactApi(user, contact).subscribe(response => {
          if (response.status === 'success') {
            this.contact = response.contact;

            if (response.contact.is_fav === false) {
              this.contactFound = true;
            } else {
              if(response.contact.is_fav === 0) {
                this.contactSubmitedRequest = true;
              } else if (response.contact.is_fav === 1) {
                this.contactAlredyAdded = true;
              } else {
                this.contactFoundAlredyAdded = true;
              }
            }            
          }
          else {
            this.contactNotFound = true;
          }
        });        
      }
    });
  }

  /**
   * Envía una petición para ser contacto favorito a un usuario
   */
  addContact() {
    this.contactsService.addContact(this.contact.id, this.contactFoundAlredyAdded);
    this.disableAll();
    this.contactSubmitedRequest = true;
  }

  /**
   * Establece a falso todas las variables que indican si 
   * se muestran las distintas secciones en pantalla
   */
  private disableAll() {
    this.contactNotFound = this.contactFound = 
    this.contactAlredyAdded = this.contactSubmitedRequest =
    this.contactFoundAlredyAdded = false;
  }
}
