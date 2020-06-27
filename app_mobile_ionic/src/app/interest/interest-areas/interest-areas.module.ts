import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { InterestAreasPageRoutingModule } from './interest-areas-routing.module';

import { InterestAreasPage } from './interest-areas.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    InterestAreasPageRoutingModule
  ],
  declarations: [InterestAreasPage]
})
export class InterestAreasPageModule {}
