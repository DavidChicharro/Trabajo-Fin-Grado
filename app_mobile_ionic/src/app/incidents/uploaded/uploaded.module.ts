import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { UploadedPageRoutingModule } from './uploaded-routing.module';

import { UploadedPage } from './uploaded.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    UploadedPageRoutingModule
  ],
  declarations: [UploadedPage]
})
export class UploadedPageModule {}
