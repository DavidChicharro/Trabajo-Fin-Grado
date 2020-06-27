import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { NewPage } from './new.page';

const routes: Routes = [
  {
    path: '',
    component: NewPage
  },
  {
    path: 'new-detail',
    loadChildren: () => import('./new-detail/new-detail.module').then( m => m.NewDetailPageModule)
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class NewPageRoutingModule {}
