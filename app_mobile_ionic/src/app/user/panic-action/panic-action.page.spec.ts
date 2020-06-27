import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { IonicModule } from '@ionic/angular';

import { PanicActionPage } from './panic-action.page';

describe('PanicActionPage', () => {
  let component: PanicActionPage;
  let fixture: ComponentFixture<PanicActionPage>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PanicActionPage ],
      imports: [IonicModule.forRoot()]
    }).compileComponents();

    fixture = TestBed.createComponent(PanicActionPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
