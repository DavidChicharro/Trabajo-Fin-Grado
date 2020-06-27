import { Component, OnInit } from '@angular/core';
import { ContactsService } from '../contacts.service';

@Component({
  selector: 'app-whose-im',
  templateUrl: './whose-im.page.html',
  styleUrls: ['./whose-im.page.scss'],
})
export class WhoseImPage implements OnInit {

  listContacts = [];

  constructor(
    private contactsService: ContactsService,
  ) { }

  ngOnInit() {
    this.listContacts = this.contactsService.getWhoseContactImList();
  }

  /**
   * Actualiza la información de la pantalla al deslizarla hacia abajo
   * 
   * @param event 
   */
  doRefresh(event) {
    this.contactsService.setWhoseContactImList();

    setTimeout(() => {
      this.listContacts = [];
      this.listContacts = this.contactsService.getWhoseContactImList();
      event.target.complete();
    }, 3000);
  }

}
