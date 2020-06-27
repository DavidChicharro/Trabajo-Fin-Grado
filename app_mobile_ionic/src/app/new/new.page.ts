import { Component, OnInit } from '@angular/core';
import {NewsService} from './news.service';

@Component({
  selector: 'app-new',
  templateUrl: './new.page.html',
  styleUrls: ['./new.page.scss'],
})
export class NewPage implements OnInit {

  noticias = [];

  constructor(private newService: NewsService) {}

  ngOnInit() {
    this.noticias = this.newService.getNews();
  }

}
