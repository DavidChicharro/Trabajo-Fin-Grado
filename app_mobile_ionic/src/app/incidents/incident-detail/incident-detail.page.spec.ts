import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { IonicModule } from '@ionic/angular';

import { IncidentDetailPage } from './incident-detail.page';

describe('IncidentDetailPage', () => {
  let component: IncidentDetailPage;
  let fixture: ComponentFixture<IncidentDetailPage>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ IncidentDetailPage ],
      imports: [IonicModule.forRoot()]
    }).compileComponents();

    fixture = TestBed.createComponent(IncidentDetailPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
