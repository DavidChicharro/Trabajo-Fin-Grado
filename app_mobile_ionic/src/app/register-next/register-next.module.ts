import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { RegisterNextPageRoutingModule } from './register-next-routing.module';

import { RegisterNextPage } from './register-next.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    RegisterNextPageRoutingModule
  ],
  declarations: [RegisterNextPage]
})
export class RegisterNextPageModule {}
