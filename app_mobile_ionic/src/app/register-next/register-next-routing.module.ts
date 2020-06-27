import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { RegisterNextPage } from './register-next.page';

const routes: Routes = [
  {
    path: '',
    component: RegisterNextPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class RegisterNextPageRoutingModule {}
