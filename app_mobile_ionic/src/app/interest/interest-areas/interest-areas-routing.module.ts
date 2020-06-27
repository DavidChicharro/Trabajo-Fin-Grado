import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { InterestAreasPage } from './interest-areas.page';

const routes: Routes = [
  {
    path: '',
    component: InterestAreasPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class InterestAreasPageRoutingModule {}
