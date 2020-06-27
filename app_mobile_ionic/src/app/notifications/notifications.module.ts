import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { NotificationsPageRoutingModule } from './notifications-routing.module';

import { NotificationsPage } from './notifications.page';

import { LocationPageModule } from './location/location.module';
import { LocationPage } from './location/location.page';

@NgModule({
  entryComponents: [
    LocationPage
  ],
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    NotificationsPageRoutingModule,
    LocationPageModule
  ],
  declarations: [NotificationsPage]
})
export class NotificationsPageModule {}
