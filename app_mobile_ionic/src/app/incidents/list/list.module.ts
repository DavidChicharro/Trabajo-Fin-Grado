import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { ListPageRoutingModule } from './list-routing.module';
import { ListPage } from './list.page';

import { FilterPage } from '../filter/filter.page';
import { FilterPageModule } from '../filter/filter.module';

@NgModule({
  entryComponents: [
    FilterPage
  ],
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    ListPageRoutingModule,
    FilterPageModule
  ],
  declarations: [ListPage]
})
export class ListPageModule {}
