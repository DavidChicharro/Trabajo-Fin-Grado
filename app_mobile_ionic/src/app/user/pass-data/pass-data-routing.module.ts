import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { PassDataPage } from './pass-data.page';

const routes: Routes = [
  {
    path: '',
    component: PassDataPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class PassDataPageRoutingModule {}
