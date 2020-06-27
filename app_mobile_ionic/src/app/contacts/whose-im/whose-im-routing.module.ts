import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { WhoseImPage } from './whose-im.page';

const routes: Routes = [
  {
    path: '',
    component: WhoseImPage
  },
  {
    path: 'contact',
    loadChildren: () => import('./contact/contact.module').then( m => m.ContactPageModule)
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class WhoseImPageRoutingModule {}
