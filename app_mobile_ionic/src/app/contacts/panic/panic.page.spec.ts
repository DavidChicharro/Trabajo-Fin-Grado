import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { IonicModule } from '@ionic/angular';

import { PanicPage } from './panic.page';

describe('PanicPage', () => {
  let component: PanicPage;
  let fixture: ComponentFixture<PanicPage>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PanicPage ],
      imports: [IonicModule.forRoot()]
    }).compileComponents();

    fixture = TestBed.createComponent(PanicPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
