import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { PanicPageRoutingModule } from './panic-routing.module';

import { PanicPage } from './panic.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    PanicPageRoutingModule
  ],
  declarations: [PanicPage]
})
export class PanicPageModule {}
