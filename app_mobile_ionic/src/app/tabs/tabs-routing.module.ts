import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { TabsPage } from './tabs.page';

const routes: Routes = [
  {
    path: 'tabs',
    component: TabsPage,
    children: [
      {
        path: 'register',
        children: [
          {
            path: '',
            loadChildren: () =>
                import('../register/register.module').then(m => m.RegisterPageModule)
          }
        ]
      },
      {
        path: 'list',
        children: [
          {
            path: '',
            loadChildren: () =>
                import('../incidents/list/list.module').then( m => m.ListPageModule)
          }
        ]
      },
      {
        path: 'interest-areas',
        children: [
          {
            path: '',
            loadChildren: () =>
                import('../interest/interest-areas/interest-areas.module').then( m => m.InterestAreasPageModule)
          }
        ]
      },
      {
        path: 'profile',
        children: [
          {
            path: '',
            loadChildren: () =>
                import('../user/profile/profile.module').then( m => m.ProfilePageModule)
          }
        ]
      },
      {
        path: 'fav-contacts',
        children: [
          {
            path: '',
            loadChildren: () =>
                import('../contacts/fav-contacts/fav-contacts.module').then( m => m.FavContactsPageModule)
          }
        ]
      },
      {
        path: 'notifications',
        children: [
          {
            path: '',
            loadChildren: () => 
                import('../notifications/notifications.module').then( m => m.NotificationsPageModule)
          }
        ]
      },
      // {
      //   path: '',
      //   redirectTo: '/tabs/list',
      //   pathMatch: 'full'
      // },
      {
        path: '',
        redirectTo: '/login',
        pathMatch: 'full'
      }
    ]
  },
  // {
  //   path: '',
  //   redirectTo: '/tabs/list',
  //   pathMatch: 'full'
  // },
  {
    path: '',
    redirectTo: '/login',
    pathMatch: 'full'
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class TabsPageRoutingModule {}
