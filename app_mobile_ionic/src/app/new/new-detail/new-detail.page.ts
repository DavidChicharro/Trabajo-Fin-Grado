import { Component, OnInit } from '@angular/core';
// import {Router} from '@angular/router';  // Enrutador -> mejor lo que hay a continuación
import {ActivatedRoute} from '@angular/router';
import {NewsService} from '../news.service';
import {Noticia} from '../new.model';

@Component({
  selector: 'app-new-detail',
  templateUrl: './new-detail.page.html',
  styleUrls: ['./new-detail.page.scss'],
})
export class NewDetailPage implements OnInit {

  new: Noticia;

  // En el constructor instancia el enrutador y el servicio
  constructor(private activatedRoute: ActivatedRoute, private newsService: NewsService) { }

  ngOnInit() {
    // para cada uno de los parámetros, los recorro
    this.activatedRoute.paramMap.subscribe(paramMap => {
      // mejor una redirección
      // if (!paramMap) {
      //   console.log('error');
      // }
      const recipeId = paramMap.get('newId'); // Mismo nombre que en el enrutador
      this.new = this.newsService.getNew(recipeId);
      console.log(this.new);
    });
  }

}
