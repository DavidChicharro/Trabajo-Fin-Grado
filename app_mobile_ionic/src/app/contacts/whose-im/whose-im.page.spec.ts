import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { IonicModule } from '@ionic/angular';

import { WhoseImPage } from './whose-im.page';

describe('WhoseImPage', () => {
  let component: WhoseImPage;
  let fixture: ComponentFixture<WhoseImPage>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WhoseImPage ],
      imports: [IonicModule.forRoot()]
    }).compileComponents();

    fixture = TestBed.createComponent(WhoseImPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
