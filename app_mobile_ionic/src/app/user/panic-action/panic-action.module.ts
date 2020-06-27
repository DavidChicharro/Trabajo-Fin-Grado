import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { PanicActionPageRoutingModule } from './panic-action-routing.module';

import { PanicActionPage } from './panic-action.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    PanicActionPageRoutingModule
  ],
  declarations: [PanicActionPage]
})
export class PanicActionPageModule {}
