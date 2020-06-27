import { Component, OnInit } from '@angular/core';
import { NotificationsService } from '../notifications/notifications.service';

@Component({
  selector: 'app-tabs',
  templateUrl: 'tabs.page.html',
  styleUrls: ['tabs.page.scss']
})
export class TabsPage implements OnInit {

  numNotifications = 0;

  constructor(
    private notifService: NotificationsService
  ) {
    setInterval(() => {
      this.notifService.setNotifications();
      setTimeout(() => {
        this.numNotifications = this.notifService.getNumNotifications();
      }, 5000);
    }, 20000);  // Comprueba el número de notificaciones cada 20s

    this.notifService.getObservable().subscribe((data) => {
      // console.log('Dato recibido: ', data);
      if (typeof data.numNotifications !== 'undefined') {
        this.numNotifications += data.numNotifications;
      }
    });
  }

  ngOnInit() {
    setTimeout(() => {
      this.numNotifications = this.notifService.getNumNotifications();
    }, 5000);
  }
}
