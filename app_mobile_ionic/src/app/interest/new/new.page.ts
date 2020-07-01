import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Map, tileLayer, marker, circle, icon, LayerGroup, Layer } from 'leaflet';
import { InterestAreasService } from '../interest-areas.service';

@Component({
  selector: 'app-new',
  templateUrl: './new.page.html',
  styleUrls: ['./new.page.scss'],
})
export class NewPage implements OnInit {

  map: Map;
  iconArea: any;
  layerGroup: LayerGroup;
  interestArea: any;
  radius: any;

  minArea: string;
  maxArea: string;
  minMaxArea: any;

  lugar: string;
  nombre_lugar: string;
  radio: number;

  showSliderSubmit = false;
  height: string;

  constructor(
    private intArService: InterestAreasService,
    private _http: HttpClient,
    private activatedRoute: ActivatedRoute,
    private router: Router
  ) {
    this.height = "100%";
    this.radio = 600;
    this.iconArea = icon({
      iconUrl: "assets/markers/marker-fav.png",
      iconAnchor: [15, 40],
    });
  }

  ngOnInit() {
    this.minArea = this.activatedRoute.snapshot.paramMap.get('minConfig');
    this.maxArea = this.activatedRoute.snapshot.paramMap.get('maxConfig');
  }

  ngOnDestroy() {
    if(this.map) {
      this.map.remove();
    }
  }

  ionViewDidEnter() {
    this.loadMap();
  }

  addInterestArea() {
    this.intArService.uploadInterestArea(
      this.lugar, this.nombre_lugar, this.radio
    ).subscribe(res => {
      if (res.status === 'success') {
        this.router.navigate(['tabs/interest-areas']);
      }
    });
  }

  /**
   * Carga el mapa
   */
  loadMap() {
    // this.cont++;
    // Si se borra todo, quitar esta condición y su contenido
    if(this.map) {
      this.map.remove();
    }
    this.map = new Map("mapFavAdd").setView([37.18,-3.6], 14);
    this.layerGroup = new LayerGroup().addTo(this.map);

    tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> contributors, ' +
          '<a href="https://creativecommons.org/licenses/by-sa/2.0/" target="_blank">CC-BY-SA</a>, ' +
          'Imagery © <a href="https://www.mapbox.com/" target="_blank">Mapbox</a>',
      maxZoom: 22,
      id: 'mapbox/streets-v11',
      accessToken: 'pk.eyJ1IjoiZGF2aWRjaGljaGFycm8iLCJhIjoiY2s4dTRuenNqMDE5djNka2Q0amE3bHBnYyJ9.ebGkyWx_FQLj5oBW936UJg'
    }).addTo(this.map);

    this.map.on('click', (e)=> {
      this.onMapClick(e);
    });         
  }

  onMapClick(e) {
    this.layerGroup.clearLayers();
    let lat = e.latlng.lat;
    let lng = e.latlng.lng;
    marker([lat, lng], {icon: this.iconArea}).addTo(this.layerGroup);
    
    this.radius = circle([lat, lng], this.radio, {color: "red"}).addTo(this.layerGroup);

    let placeName = "";
    this.getPlaceName(lat, lng).subscribe(data => {
      placeName = ((typeof(data.address.locality)!== 'undefined')?data.address.locality +", ":"")+
        ((typeof(data.address.city_district)!== 'undefined')?data.address.city_district +", ":"")+
        ((typeof(data.address.village)!== 'undefined')?data.address.village:"")+
        ((typeof(data.address.town)!== 'undefined')?data.address.town:"")+
        ((typeof(data.address.city)!== 'undefined')?data.address.city:"")+
        ((typeof(data.address.county)!== 'undefined')?" ("+data.address.county +")":"");

      this.nombre_lugar = (placeName !== "") ? placeName : data.address.country;
    });
    
    this.lugar = parseFloat(lat).toFixed(4) + ',' + parseFloat(lng).toFixed(4);
    this.showSliderSubmit = true;
    this.height = "90%";
  }

  /**
   * Modifica el tamaño del radio en el mapa
   */
  changeRadius() {
    this.radius.setRadius(this.radio);
  }

  getPlaceName(lat, lng): Observable<any> {
    return this._http.get<any>('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' +
    lat + '&lon=' + lng);
  }

}
