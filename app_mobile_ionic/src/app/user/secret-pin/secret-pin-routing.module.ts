import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { SecretPinPage } from './secret-pin.page';

const routes: Routes = [
  {
    path: '',
    component: SecretPinPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class SecretPinPageRoutingModule {}
