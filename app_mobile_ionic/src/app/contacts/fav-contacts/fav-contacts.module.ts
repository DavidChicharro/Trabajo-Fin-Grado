import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { FavContactsPageRoutingModule } from './fav-contacts-routing.module';

import { FavContactsPage } from './fav-contacts.page';
import { PanicPage } from '../panic/panic.page';
import { PanicPageModule } from '../panic/panic.module';

@NgModule({
  entryComponents: [
    PanicPage
  ],
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    FavContactsPageRoutingModule,
    PanicPageModule
  ],
  declarations: [FavContactsPage]
})
export class FavContactsPageModule {}
