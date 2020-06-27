import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { IonicModule } from '@ionic/angular';

import { UploadedPage } from './uploaded.page';

describe('UploadedPage', () => {
  let component: UploadedPage;
  let fixture: ComponentFixture<UploadedPage>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UploadedPage ],
      imports: [IonicModule.forRoot()]
    }).compileComponents();

    fixture = TestBed.createComponent(UploadedPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
