import { Injectable } from '@angular/core';
import { Noticia } from './new.model';

@Injectable({
  providedIn: 'root'
})
export class NewsService {

  public myList: Noticia[];

  constructor() {
    this.myList = this.setNoticias();
  }

  // Método privado para rellenar el array de Noticias por defecto
  private setNoticias() {
    const list = [];

    list.push({
      id: 1,
      title: 'Hola bb',
      subtitle: 'Ábreme',
      abstract: 'Quiero decirte que te quiero muchísimo.'
    });
    list.push({
      id: 2,
      title: 'Eres un biberón',
      subtitle: 'Ábreme en segundo lugar',
      abstract: 'Te echo muchísimo de menos.'
    });
    list.push({
      id: 3,
      title: 'Y eres preciosa',
      subtitle: 'Ábreme en tercer lugar',
      abstract: 'Ojalá poder darte ahora mismo un besito 💙💙💙 \n Buenas noches, te quiero un montón un montón'
    });

    /*for (let i = 1; i <= 3; i++) {
      list.push({
        id: i,
        title: 'Titulo ' + i,
        subtitle: 'Esta noticia... ',
        abstract: 'Este es el contenido de la noticia ' + i + '. Como era de esperar, blablabla.'
      });
    }*/
    return list;
  }

  // Devuelve las noticias
  // Los 3 puntos (...) sirven para devolver una copia del objeto
  getNews() {
    return [...this.myList];
  }

  // Acepto un string porque el parámetro de la url no se interpreta como number
  getNew(newId: string) {
    return {
      ...this.myList.find(noticia => {
        // @ts-ignore
        // tslint:disable-next-line:triple-equals
        return noticia.id == newId;
      })
    };
  }

  addNew(title: string, subtitle: string, abstract: string) {
    this.myList.push({
      id: this.myList.length + 1,
      title,
      subtitle,
      abstract
    });
  }

}
