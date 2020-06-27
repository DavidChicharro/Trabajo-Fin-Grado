import { Component, OnInit } from '@angular/core';
import { IncidentsService } from './../incidents.service';

@Component({
  selector: 'app-uploaded',
  templateUrl: './uploaded.page.html',
  styleUrls: ['./uploaded.page.scss'],
})
export class UploadedPage implements OnInit {

  listUploaded = [];

  constructor(
    private incidentsService: IncidentsService
  ) { }

  ngOnInit() {
    this.listUploaded = this.incidentsService.getUploadedIncidents();
  }

  /**
   * Actualiza la información de la pantalla al deslizarla hacia abajo
   * 
   * @param event
   */
  doRefresh(event) {
    this.incidentsService.setUploadedIncidents();

    setTimeout(() => {
      this.listUploaded = [];
      this.listUploaded = this.incidentsService.getUploadedIncidents();
      event.target.complete();
    }, 2000);
  }
}
