import { TestBed } from '@angular/core/testing';

import { InterestAreasService } from './interest-areas.service';

describe('InterestAreasService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: InterestAreasService = TestBed.get(InterestAreasService);
    expect(service).toBeTruthy();
  });
});
