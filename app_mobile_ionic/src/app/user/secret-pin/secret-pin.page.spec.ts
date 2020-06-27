import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { IonicModule } from '@ionic/angular';

import { SecretPinPage } from './secret-pin.page';

describe('SecretPinPage', () => {
  let component: SecretPinPage;
  let fixture: ComponentFixture<SecretPinPage>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SecretPinPage ],
      imports: [IonicModule.forRoot()]
    }).compileComponents();

    fixture = TestBed.createComponent(SecretPinPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
