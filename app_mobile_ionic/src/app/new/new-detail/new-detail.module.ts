import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { NewDetailPageRoutingModule } from './new-detail-routing.module';

import { NewDetailPage } from './new-detail.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    NewDetailPageRoutingModule
  ],
  declarations: [NewDetailPage]
})
export class NewDetailPageModule {}
