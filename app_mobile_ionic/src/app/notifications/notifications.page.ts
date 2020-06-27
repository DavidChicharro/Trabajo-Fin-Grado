import { Component, OnInit } from '@angular/core';
// import { Platform, AlertController } from '@ionic/angular';
import { ModalController } from '@ionic/angular';
import { LocalNotifications } from '@ionic-native/local-notifications/ngx';
import { NotificationsService } from '../notifications/notifications.service';
// import { Notification } from '../models/notification';
import { LocationPage } from './location/location.page';


@Component({
  selector: 'app-notifications',
  templateUrl: './notifications.page.html',
  styleUrls: ['./notifications.page.scss'],
})
export class NotificationsPage implements OnInit {

  notifications = [];
  readNotifications = [];

  constructor(
    // private plt: Platform,
    private modalCtrl: ModalController,
    private localNotifications: LocalNotifications,
    private notifService: NotificationsService
  ) { }

  ngOnInit() {
    this.notifications = this.notifService.getNotifications();
  }

  ionViewDidEnter(){
    this.notifService.setNotifications();

    setTimeout(() => {
      this.notifications = [];
      this.notifications = this.notifService.getNotifications();
    }, 1500);
  }

  /**
   * Refresca las notificaciones al hacer un "drop-down"
   * 
   * @param event 
   */
  doRefresh(event) {
    this.notifService.setNotifications();

    setTimeout(() => {
      this.notifications = [];
      this.notifications = this.notifService.getNotifications();

      event.target.complete();
    }, 3000);
  }

  /**
   * Aceptar ser contacto favorito de quien envía la petición
   * 
   * @param notif 
   */
  acceptContact(notif) {
    this.notifService.acceptContact(notif.senderId, notif.id);
    this.deleteNotification(notif.id);
  }

  /**
   * Recharzar ser contacto favorito de quien envía la petición
   */
  rejectContact(notif) {
    this.notifService.rejectContact(notif.senderId, notif.id);
    this.deleteNotification(notif.id);
  }

  /**
   * Marca una notificación como leída
   * 
   * @param notif 
   */
  markAsRead(notif) {
    this.notifService.markNotificationAsRead(notif.id);
    this.deleteNotification(notif.id);
  }

  /**
   * Abre un modal para ver la ubicación del contacto favorito
   * 
   * @param notif 
   */
  viewContactLocation(notif) {
    this.openPanicLocationModal(notif.senderId, notif.senderName);

    //Cambiar 'Ver ubicación' por 'Marcar como leída'
    this.readNotifications.push(notif.id);
  }

  // Configurar TO-DO !!!!!!!!!!!!!!!!!
  send(title, text) {
    this.localNotifications.schedule(
      {
        title: title,
        text: text,
        trigger: { at: new Date(new Date().getTime() + 100) },
        led: 'FF0000',
        foreground: true
      }
    );
  }

  /**
   * Eliminar una notificación
   * 
   * @param notifId 
   */
  deleteNotification(notifId) {
    let notification = this.notifications.find(element => element.id === notifId);
    let index = this.notifications.indexOf(notification);

    if (index > -1)
      this.notifications.splice(index, 1);

    // Llama a 'tabs' para decrementar el número de notificaciones
    this.notifService.decrementNumNotifications({
      numNotifications: -1
    });
  }

  /**
   * Abre el modal de acción de pánico
   */
  async openPanicLocationModal(contactId, contactName) {
    const modal = await this.modalCtrl.create({
      component: LocationPage,
      componentProps: {
        contactId: contactId,
        contactName: contactName
      }
    });
    await modal.present();

    const { data } = await modal.onDidDismiss();
      // console.log(data);

    /* if(typeof data !== 'undefined') {
      this.setAppliedFilters(data);
      let params = this.incidentsService.paramsToUrl(data);
      this.incidentsService.setIncidentsList(params);
      this.listIncidents = this.incidentsService.getIncidentsList();
    } */
  }

  /* notify() {
    this.plt.ready().then(()  => {
      this.localNotifications.on('click').subscribe(res => {
        console.log(res);
        let msg = res.data ? res.data.mydata : '';
        this.showAlert(res.title, res.text, msg);
      });

      this.localNotifications.on('trigger').subscribe(res => {

      });
    });
  } */

  /* async showAlert(header, sub, msg) {
    const alert = await this.alertCtrl.create({
      header: header,
      subHeader: sub,
      message: msg,
      buttons: [
        {
          text: 'Cancelar',
          role: 'cancel'
        }, {
          text: 'Confirmar',
        }
      ]
    });

    await alert.present();
  } */
}
