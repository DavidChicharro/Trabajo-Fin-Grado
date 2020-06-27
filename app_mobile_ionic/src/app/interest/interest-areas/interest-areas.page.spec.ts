import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { IonicModule } from '@ionic/angular';

import { InterestAreasPage } from './interest-areas.page';

describe('InterestAreasPage', () => {
  let component: InterestAreasPage;
  let fixture: ComponentFixture<InterestAreasPage>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ InterestAreasPage ],
      imports: [IonicModule.forRoot()]
    }).compileComponents();

    fixture = TestBed.createComponent(InterestAreasPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
