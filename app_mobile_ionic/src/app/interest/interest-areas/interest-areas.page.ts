import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { LoadingController, AlertController } from '@ionic/angular';
import { Map, tileLayer, marker, circle, LayerGroup, Layer } from 'leaflet';
import { InterestAreasService } from '../interest-areas.service';
import { Bounds } from '../bounds.model';

@Component({
  selector: 'app-interest-areas',
  templateUrl: './interest-areas.page.html',
  styleUrls: ['./interest-areas.page.scss'],
})
export class InterestAreasPage implements OnInit {

  map: Map;
  layerGroup: LayerGroup;
  interestAreas = [];
  bounds: Bounds;
  cont: number;

  minConfig: string;
  maxConfig: string;

  constructor(
    private intArService: InterestAreasService,
    private loadingCtrl: LoadingController,
    private router: Router
  ) {
    this.cont = 0;
  }

  doRefresh(event) {
    this.ionViewDidEnter();
  }

  ngOnInit() {
  }

  ngOnDestroy() {
    if(this.map) {
      this.map.remove();
    }
  }

  ionViewDidEnter(){
    if (this.cont === 0)
      this.loadMap();
    else {
      this.intArService.setInterestAreas();
      this.layerGroup.clearLayers();
      setTimeout(() => {
        this.getInterestAreas();
      }, 3000);
    }
  }

  doStuff() {
    // console.log('doStuff()');
    if (typeof this.interestAreas !== 'undefined') {
      if (this.interestAreas.length !== 0) {
        setTimeout(this.doStuff, 1000);
        return;
      }
      this.getInterestAreas();
      this.getMinMaxConfig();
    }
  }

  /**
   * Carga el mapa
   */
  loadMap() {
    this.cont++;
    
    if(this.map) {
      this.map.remove();
    }
    this.map = new Map("mapfav").setView([37.18,-3.6], 14);
    this.layerGroup = new LayerGroup().addTo(this.map);

    this.doStuff();

    tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> contributors, ' +
          '<a href="https://creativecommons.org/licenses/by-sa/2.0/" target="_blank">CC-BY-SA</a>, ' +
          'Imagery © <a href="https://www.mapbox.com/" target="_blank">Mapbox</a>',
      maxZoom: 22,
      id: 'mapbox/streets-v11',
      accessToken: 'pk.eyJ1IjoiZGF2aWRjaGljaGFycm8iLCJhIjoiY2s4dTRuenNqMDE5djNka2Q0amE3bHBnYyJ9.ebGkyWx_FQLj5oBW936UJg'
    }).addTo(this.map);
        
  }
  

  getInterestAreas () {
    let mymap = this.map;
    let layerGroup = this.layerGroup;
    this.presentLoading();
    setTimeout(() => {
      this.interestAreas = this.intArService.getInterestAreas();
      this.bounds = this.intArService.getBounds();
      // console.log(this.interestAreas);

      let interestAreas = [...this.interestAreas];
      interestAreas.forEach(function (value) {
        marker([value.lat, value.lng])
        .bindPopup('<b>'+value.name+'</b><br>Radio: '+
            value.radio+' m')
        .addTo(layerGroup);
        /* .on('click', function(e) {
          console.log(e.target._latlng);
        }); */
        circle([value.lat, value.lng], value.radio, {color: "red"}).addTo(layerGroup);
      });

      mymap.fitBounds([
        [+this.bounds.south, +this.bounds.west],
        [+this.bounds.north, +this.bounds.east]
      ], {maxZoom: 15});
    }, 1000);
  }

  getMinMaxConfig() {
    setTimeout(() => {
      let minMaxConfig = this.intArService.getInterestAreasConfig();
      this.minConfig = minMaxConfig.radio_min;
      this.maxConfig = minMaxConfig.radio_max;
    }, 3000);
  }

  /**
   * Muestra un spinner de carga de datos
   */
  async presentLoading() {
    const loading = await this.loadingCtrl.create({
      message: 'Cargando zonas de interés...',
      duration: 500
    });
    await loading.present();

    const { role, data } = await loading.onDidDismiss();
  }

}
