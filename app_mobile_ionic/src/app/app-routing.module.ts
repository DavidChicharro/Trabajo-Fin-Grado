import { NgModule } from '@angular/core';
import { PreloadAllModules, RouterModule, Routes } from '@angular/router';

const routes: Routes = [
  {
    path: '',
    loadChildren: () => import('./tabs/tabs.module').then(m => m.TabsPageModule)
  },
  {
    path: 'login',
    loadChildren: () => import('./auth/login/login.module').then( m => m.LoginPageModule)
  },
  {
    path: 'register',
    loadChildren: () => import('./register/register.module').then( m => m.RegisterPageModule)
  },
  {
    path: 'list',
    children: [
      {
        path: '',
        loadChildren: () => import('./incidents/list/list.module').then( m => m.ListPageModule)
      },
      {
        path: ':incidentId',
        loadChildren: () => import('./incidents/incident-detail/incident-detail.module').then( m => m.IncidentDetailPageModule)
      }
    ]
  },
  {
    path: 'uploaded',
    children: [
      {
        path: '',
        loadChildren: () => import('./incidents/uploaded/uploaded.module').then( m => m.UploadedPageModule)
      },
      {
        path: ':incidentId',
        loadChildren: () => import('./incidents/uploaded/detail/detail.module').then( m => m.DetailPageModule)
      }
    ]
  },
  {
    path: 'map',
    loadChildren: () => import('./incidents/map/map.module').then( m => m.MapPageModule)
  },
  {
    path: 'new',
    loadChildren: () => import('./incidents/new/new.module').then( m => m.NewPageModule)
  },
  {
    path: 'register-next',
    loadChildren: () => import('./register-next/register-next.module').then( m => m.RegisterNextPageModule)
  },
  {
    path: 'profile',
    loadChildren: () => import('./user/profile/profile.module').then( m => m.ProfilePageModule)
  },
  {
    path: 'panic-action',
    loadChildren: () => import('./user/panic-action/panic-action.module').then( m => m.PanicActionPageModule)
  },
  {
    path: 'secret-pin',
    loadChildren: () => import('./user/secret-pin/secret-pin.module').then( m => m.SecretPinPageModule)
  },
  {
    path: 'personal-data',
    loadChildren: () => import('./user/personal-data/personal-data.module').then( m => m.PersonalDataPageModule)
  },
  {
    path: 'pass-data',
    loadChildren: () => import('./user/pass-data/pass-data.module').then( m => m.PassDataPageModule)
  },
  {
    path: 'interest-areas',
    loadChildren: () => import('./interest/interest-areas/interest-areas.module').then( m => m.InterestAreasPageModule)
  },
  {
    path: 'interest/new/:minConfig/:maxConfig',
    loadChildren: () => import('./interest/new/new.module').then( m => m.NewPageModule)
  },
  {
    path: 'interest/delete',
    loadChildren: () => import('./interest/delete/delete.module').then( m => m.DeletePageModule)
  },
  {
    path: 'fav-contacts',
    children: [
      {
        path: '',
        loadChildren: () => import('./contacts/fav-contacts/fav-contacts.module').then( m => m.FavContactsPageModule)
      },
      {
        path: ':contactId',
        loadChildren: () => import('./contacts/contact-detail/contact-detail.module').then( m => m.ContactDetailPageModule)
      }
    ]
  },
  {
    path: 'new-contact',
    loadChildren: () => import('./contacts/new/new.module').then( m => m.NewPageModule)
  },
  {
    path: 'whose-im',
    children: [
      {
        path: '',
        loadChildren: () => import('./contacts/whose-im/whose-im.module').then( m => m.WhoseImPageModule)
      },
      {
        path: ':userId',
        loadChildren: () => import('./contacts/whose-im/contact/contact.module').then( m => m.ContactPageModule)
      }
    ]
  },
];
@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules })
  ],
  exports: [RouterModule]
})
export class AppRoutingModule {}
