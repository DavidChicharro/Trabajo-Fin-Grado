import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { SecretPinPageRoutingModule } from './secret-pin-routing.module';

import { SecretPinPage } from './secret-pin.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    SecretPinPageRoutingModule
  ],
  declarations: [SecretPinPage]
})
export class SecretPinPageModule {}
