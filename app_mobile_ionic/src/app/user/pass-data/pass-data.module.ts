import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { PassDataPageRoutingModule } from './pass-data-routing.module';

import { PassDataPage } from './pass-data.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    PassDataPageRoutingModule
  ],
  declarations: [PassDataPage]
})
export class PassDataPageModule {}
