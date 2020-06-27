import { Component, OnInit } from '@angular/core';
import { ModalController } from '@ionic/angular';
import { IncidentsService } from '../incidents.service';

@Component({
  selector: 'app-filter',
  templateUrl: './filter.page.html',
  styleUrls: ['./filter.page.scss'],
})
export class FilterPage implements OnInit {

  listDelitos = [];
  dateTo = "";
  dateFrom = "";
  selectedDelitos = [];

  constructor(
    private incidentsService: IncidentsService,
    private modalCtrl: ModalController
  ) { }

  ngOnInit() {
    this.listDelitos = this.incidentsService.getDelitos();
  }

  /**
   * Cierra el modal de filtro
   */
  dismissModal() {
    this.modalCtrl.dismiss();
  }

  /**
   * Datos devueltos del filtro (modal) a lista incidentes
   */
  getArguments() {
    this.modalCtrl.dismiss({
      dateFrom: this.dateFrom,
      dateTo: this.dateTo,
      selectedDelitos: this.selectedDelitos
    });
  }

  /**
   * Devuelve la lista de delitos al filtro
   */
  getDelitos() {
    return this.listDelitos;
  }

}
