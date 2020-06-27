import { Component, OnInit } from '@angular/core';
import { ModalController, LoadingController } from '@ionic/angular';
import { IncidentsService } from './../incidents.service';
import { FilterPage } from '../filter/filter.page';


@Component({
  selector: 'app-list',
  templateUrl: './list.page.html',
  styleUrls: ['./list.page.scss'],
})
export class ListPage implements OnInit {

  listIncidents = [];
  dateFilter: string;
  incidentsFilter: string;
  sub: any;

  constructor(
    private incidentsService: IncidentsService,
    private modalCtrl: ModalController,
    public loadingController: LoadingController
  ) { }

  ngOnInit() {
    this.listIncidents = this.incidentsService.getIncidentsList();
  }

  /**
   * Actualiza la información de la pantalla al deslizarla hacia abajo
   * 
   * @param event
   */
  doRefresh(event) {
    this.incidentsService.setIncidentsList();

    setTimeout(() => {
      this.listIncidents = [];
      this.listIncidents = this.incidentsService.getIncidentsList();
      event.target.complete();
    }, 3000);
  }

  // Cargar más datos al final
  loadData(event) {
    setTimeout(() => {
      // console.log('Done');
      // this.addMoreItems();
      event.target.complete();

      // App logic to determine if all data is loaded
      // and disable the infinite scroll
      if (this.listIncidents.length == 1000) {
        event.target.disabled = true;
      }
    }, 2000);
  }

  /**
   * Almacena los filtros aplicados para mostrarlos en
   * la vista de los incidentes filtrados
   * @param data 
   */
  private setAppliedFilters(data) {
    if(data.dateFrom !== "" && data.dateTo !== "") {
      let dateFrom = this.incidentsService.dateFormat(data.dateFrom);
      let dateTo = this.incidentsService.dateFormat(data.dateTo);

      this.dateFilter = dateFrom + ' - ' + dateTo;
    } else {
      this.dateFilter = undefined;
    }

    if(typeof data.selectedDelitos !== 'undefined' && data.selectedDelitos.length > 0) {
      let incNames = [];
      let incidents = this.incidentsService.getDelitos();
      
      data.selectedDelitos.forEach(function(id) {
        incNames.push( incidents.find(el =>
          el.id == id).delito
        );
      }); 

      this.incidentsFilter = incNames.join(', ');
    } else {
      this.incidentsFilter = undefined;
    }
  }


  /**
   * Abre el modal del filtro de incidentes
   */
  async openFilterModal() {
    const modal = await this.modalCtrl.create({
      component: FilterPage/* ,
      // Enviar información al modal
      componentProps: {
        nombre: 'Fernando',
        pais: 'Costa Rica'
      } */
    });
    await modal.present();

    const { data } = await modal.onDidDismiss();
      // console.log(data);

    if(typeof data !== 'undefined') {
      this.setAppliedFilters(data);
      let params = this.incidentsService.paramsToUrl(data);
      this.incidentsService.setIncidentsList(params);
      this.listIncidents = this.incidentsService.getIncidentsList();
    }
  }

  /**
   * Muestra un spinner de carga de datos
   */
  async presentLoading() {
    const loading = await this.loadingController.create({
      message: 'Cargando incidentes...',
      duration: 2000
    });
    await loading.present();

    const { role, data } = await loading.onDidDismiss();
  }
}
