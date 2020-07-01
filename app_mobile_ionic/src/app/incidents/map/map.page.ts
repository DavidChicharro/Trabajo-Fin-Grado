import { Component, OnInit } from '@angular/core';
import { Router, NavigationExtras } from '@angular/router';
import { Map, tileLayer, marker, LayerGroup, Layer, circle, icon } from 'leaflet';
import { ModalController, LoadingController } from '@ionic/angular';
import { IncidentsService } from './../incidents.service';
import { FilterPage } from '../filter/filter.page'
// import {NativeGeocoder, NativeGeocoderOptions} from "@ionic-native/native-geocoder/ngx";

@Component({
  selector: 'app-map',
  templateUrl: './map.page.html',
  styleUrls: ['./map.page.scss'],
})
export class MapPage implements OnInit {

  map: Map;
  marker: any;
  layerGroup: LayerGroup;
  address: string[];
  mapIncidents = [];
  centersIncidents = [];
  dateFilter: string;
  incidentsFilter: string;
  iconsMap = [];

  constructor(
    // private geocoder: NativeGeocoder,
    private incidentsService: IncidentsService,
    private modalCtrl: ModalController,
    private loadingController: LoadingController,
    private router:Router
  ) {
    for (let i=1; i<=30; i++) {
      this.iconsMap.push(
        icon({
          iconUrl: "assets/markers/marker-" + i + ".png",
          iconAnchor: [10, 20],
        })
      );
    }
  }

  ngOnInit() {
    this.mapIncidents = this.incidentsService.getIncidentsList();
    this.centersIncidents = this.incidentsService.getIncidentsCenters();
  }

  ngOnDestroy() {
    if(this.map) {
      this.map.remove();
    }
  }

  confirmPickupLocation() {
    let navigationextras: NavigationExtras = {
      state: {
        pickupLocation: this.address
      }
    };
    this.router.navigate(["/tabs/list"], navigationextras);
  }

  
  ionViewDidEnter(){
    this.loadMap();
  }

  /**
   * Carga el mapa
   */
  loadMap() {
    if(this.map) {
      this.map.remove();
    }
    this.map = new Map("mapid").setView([37.18,-3.6], 14);
    this.getIncidents();
    this.layerGroup = new LayerGroup().addTo(this.map);

    tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> contributors, ' +
          '<a href="https://creativecommons.org/licenses/by-sa/2.0/" target="_blank">CC-BY-SA</a>, ' +
          'Imagery © <a href="https://www.mapbox.com/" target="_blank">Mapbox</a>',
      maxZoom: 22,
      id: 'mapbox/streets-v11',
      accessToken: 'pk.eyJ1IjoiZGF2aWRjaGljaGFycm8iLCJhIjoiY2s4dTRuenNqMDE5djNka2Q0amE3bHBnYyJ9.ebGkyWx_FQLj5oBW936UJg'
    }).addTo(this.map);
  }

  getIncidents (/* bounds,  */delitTypes=[], dateFrom="", dateTo="") {
    let arrIcons = this.iconsMap;

    let mymap = this.map;
    this.presentLoading();
    setTimeout(() => {
      let mapList = this.getMapIncidents();
      mapList.forEach(function (value) {
        console.log(value.delId);
        marker([value.lat, value.lng], {icon: arrIcons[value.delId - 1]})
        .bindPopup('<b>'+value.incident+'</b>  -  '+
            value.date+' '+value.hour+'.<br>'+value.place
            +'<br>'+value.description)
        .addTo(mymap);
      });

      // Se añaden los centros de zonas de incidentes al mapa
      let centersList = this.getCentersIncidentsAreas();
      centersList.forEach(function (center) {
        circle([center.lat, center.lng],
          250, {color: "#"+center.color})
          .addTo(mymap);
      });
    }, 2000);
  }

  public callGetIncidents() {
    this.getIncidents(//this.map.getBounds().toBBoxString(),
        // $('#tipos-incid').val(),
        // $('#date-from').val(),
        // $('#date-to').val()
    );
    this.map.remove
    this.layerGroup.clearLayers();
  }

  goBack(){
    this.router.navigate(["/tabs/list"]);
  }

  locatePosition(){
    this.map.locate({setView:true}).on("locationfound", (e: any)=> {
      this.marker = marker(
        [e.latitude,e.longitude],
        {draggable: true}
      ).addTo(this.map);
      this.marker.bindPopup("¡Estás aquí!").openPopup();
     
      this.marker.on("dragend", ()=> {
        const position = this.marker.getLatLng();
      });

      // console.log(this.newMarker);
    });
  }

  //The function below is added
  // getAddress(lat: number, long: number) {
  //   let options: NativeGeocoderOptions = {
  //     useLocale: true,
  //     maxResults: 5
  //   };
  //   this.geocoder.reverseGeocode(lat, long, options).then(results => {
  //     this.address = Object.values(results[0]).reverse();      
  //   });
  // }

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
      component: FilterPage
    });
    await modal.present();

    const { data } = await modal.onDidDismiss();
      console.log(data);

    if(typeof data !== 'undefined') {
      this.setAppliedFilters(data);
      let params = this.incidentsService.paramsToUrl(data);
      this.incidentsService.setIncidentsList(params);
      this.mapIncidents = this.incidentsService.getIncidentsList();
      this.loadMap();
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

  private getMapIncidents() {
    return [...this.mapIncidents];
  }

  private getCentersIncidentsAreas() {
    return [...this.centersIncidents]
  }

}
