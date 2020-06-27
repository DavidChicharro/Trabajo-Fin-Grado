import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { IonicModule } from '@ionic/angular';

import { FavContactsPage } from './fav-contacts.page';

describe('FavContactsPage', () => {
  let component: FavContactsPage;
  let fixture: ComponentFixture<FavContactsPage>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FavContactsPage ],
      imports: [IonicModule.forRoot()]
    }).compileComponents();

    fixture = TestBed.createComponent(FavContactsPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
