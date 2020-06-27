import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { IonicModule } from '@ionic/angular';

import { RegisterNextPage } from './register-next.page';

describe('RegisterNextPage', () => {
  let component: RegisterNextPage;
  let fixture: ComponentFixture<RegisterNextPage>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RegisterNextPage ],
      imports: [IonicModule.forRoot()]
    }).compileComponents();

    fixture = TestBed.createComponent(RegisterNextPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
