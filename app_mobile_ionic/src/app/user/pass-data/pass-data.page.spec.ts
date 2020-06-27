import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { IonicModule } from '@ionic/angular';

import { PassDataPage } from './pass-data.page';

describe('PassDataPage', () => {
  let component: PassDataPage;
  let fixture: ComponentFixture<PassDataPage>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PassDataPage ],
      imports: [IonicModule.forRoot()]
    }).compileComponents();

    fixture = TestBed.createComponent(PassDataPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
