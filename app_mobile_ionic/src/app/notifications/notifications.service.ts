import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Storage } from '@ionic/storage';
import { Observable, Subject } from 'rxjs';
import { Notification } from '../models/notification';
import { SERVER_DEBUG_URL } from '../../environments/environment';
import { SERVER_URL } from '../../environments/environment.prod';

@Injectable({
  providedIn: 'root'
})
export class NotificationsService {

  numNotifications = 0;
  notifications: Notification[];
  private subject = new Subject<any>();

  constructor(
    private _http: HttpClient,
    private storage: Storage,
  ) {
    this.setNotifications();
  }

  setNotifications() {
    this.storage.get('api_token').then(user => {
      if(user !== null) {
        this.setNotificationsApi(user).subscribe(res => {
          if (res.status === 'success') {
            this.numNotifications = res.notifications.length;

            const notifList = [];

            res.notifications.forEach((notif) => {
              notifList.push({
                id: notif.id,
                senderId: notif.data.sender_id,
                senderName: notif.data.sender_name,
                senderEmail: notif.data.sender_email,
                recipientId: notif.data.recipient_id,
                recipientName: notif.data.recipient_name,
                notificationType: notif.data.notification_type,
                message: notif.data.message,
              });
            });

            this.notifications = notifList;
          }
        });
      }
    });
  }

  setNotificationsApi(user): Observable<any> {
    let params = new HttpParams();
    params = params.append('api_token', user);
    
    return this._http.get<any>(SERVER_DEBUG_URL+'/api/get_notifications', {params: params});
  }

  getNumNotifications(): number {
    return this.numNotifications;
  }

  getNotifications(): Notification[] {
    return [...this.notifications];
  }

  /**
   * Acepta la petición de un contacto
   * @param contactId 
   * @param notifId 
   */
  acceptContact(contactId, notifId) {
    this.storage.get('api_token').then(user => {
      if(user !== null) {
        this.acceptContactApi(user, contactId).subscribe(res => {
          if (res.status === 'success') {
            this.markNotificationAsReadApi(user, notifId).subscribe(notifRes => {
              if (notifRes.status === 'success') {
                this.numNotifications = notifRes.unreadNotificatons;
                this.deleteNotification(notifId);
              }
            });
          }
        });
      }
    });
  }

  /**
   * Rechaza la petición de un contacto
   * 
   * @param contactId 
   * @param notifId 
   */
  rejectContact(contactId, notifId) {
    this.storage.get('api_token').then(user => {
      if(user !== null) {
        this.rejectContactApi(user, contactId).subscribe(res => {
          if (res.status === 'success') {
            this.markNotificationAsReadApi(user, notifId).subscribe(notifRes => {
              if (notifRes.status === 'success') {
                this.numNotifications = notifRes.unreadNotificatons;
                this.deleteNotification(notifId);
              }
            });
          }
        });
      }
    });
  }

  /**
   * Llamada a la API para aceptar la petición de un contacto
   * 
   * @param user 
   * @param contactId 
   */
  acceptContactApi(user, contactId): Observable<any> {
    let params = new HttpParams();
    params = params.append('api_token', user);
    params = params.append('favContactId', contactId);
    
    return this._http.get<any>(SERVER_DEBUG_URL+'/api/accept_favourite_contact', {params: params});
  }

  /**
   * Llamada a la API para rechazar la petición de un contacto
   * 
   * @param user 
   * @param contactId 
   */
  rejectContactApi(user, contactId): Observable<any> {
    let params = new HttpParams();
    params = params.append('api_token', user);
    params = params.append('contactId', contactId);
    
    return this._http.get<any>(SERVER_DEBUG_URL+'/api/remove_reject_contact', {params: params});
  }

  /**
   * Marca una notificación como leída
   * 
   * @param notifId 
   */
  markNotificationAsRead(notifId) {
    this.storage.get('api_token').then(user => {
      if(user !== null) {
        this.markNotificationAsReadApi(user, notifId).subscribe(notifRes => {
          if (notifRes.status === 'success') {
            this.numNotifications = notifRes.unreadNotificatons;
            this.deleteNotification(notifId);
          }
        });
      }
    });
  }

  /**
   * Llama a la API para marcar una notificación como leída
   * 
   * @param user 
   * @param notifId 
   */
  markNotificationAsReadApi(user, notifId): Observable<any> {
    let params = new HttpParams();
    params = params.append('api_token', user);
    params = params.append('notificationId', notifId);
    
    return this._http.get<any>(SERVER_DEBUG_URL+'/api/mark_notification_as_read', {params: params});
  }

  /**
   * Elimina una notificación
   * 
   * @param notifId 
   */
  deleteNotification(notifId) {
    let notification = this.notifications.find(element => element.id === notifId);
    let index = this.notifications.indexOf(notification);

    if (index > -1)
      this.notifications.splice(index, 1);
  }

  decrementNumNotifications(data: any) {
    this.subject.next(data);
  }

  getObservable(): Subject<any> {
    return this.subject;
  }
}
