import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { WhoseImPageRoutingModule } from './whose-im-routing.module';

import { WhoseImPage } from './whose-im.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    WhoseImPageRoutingModule
  ],
  declarations: [WhoseImPage]
})
export class WhoseImPageModule {}
