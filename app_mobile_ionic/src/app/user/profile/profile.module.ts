import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { ProfilePageRoutingModule } from './profile-routing.module';
import { ProfilePage } from './profile.page';

import { PanicActionPage } from '../panic-action/panic-action.page';
import { PanicActionPageModule } from '../panic-action/panic-action.module';

import { SecretPinPage } from '../secret-pin/secret-pin.page';
import { SecretPinPageModule } from '../secret-pin/secret-pin.module';

import { PersonalDataPage } from '../personal-data/personal-data.page';
import { PersonalDataPageModule } from '../personal-data/personal-data.module';

import { PassDataPage } from '../pass-data/pass-data.page';
import { PassDataPageModule } from '../pass-data/pass-data.module';

PassDataPage

@NgModule({
  entryComponents: [
    PanicActionPage,
    SecretPinPage,
    PersonalDataPage,
    PassDataPage
  ],
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    ProfilePageRoutingModule,
    PanicActionPageModule,
    SecretPinPageModule,
    PersonalDataPageModule,
    PassDataPageModule
  ],
  declarations: [ProfilePage]
})
export class ProfilePageModule {}
