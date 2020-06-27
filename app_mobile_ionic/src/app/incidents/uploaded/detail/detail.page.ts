import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { SocialSharing } from '@ionic-native/social-sharing/ngx';
import { IncidentsService } from '../../incidents.service';
import { IncidentList } from '../../../models/incidentList';
import { SERVER_URL } from './../../../../environments/environment.prod';

@Component({
  selector: 'app-detail',
  templateUrl: './detail.page.html',
  styleUrls: ['./detail.page.scss'],
})
export class DetailPage implements OnInit {

  incident: IncidentList;

  constructor(
    private activatedRoute: ActivatedRoute,
    private incidentsService: IncidentsService,
    private socialSharing: SocialSharing
  ) { }

  ngOnInit() {
    this.activatedRoute.paramMap.subscribe(paramMap => {
      const recipeId = paramMap.get('incidentId');
      this.incident = this.incidentsService.getUploadedIncidentDetail(recipeId);
    });
  }

  share() {
    let url = SERVER_URL+'/incidente?inc='+this.incident.id+'&del='+this.incident.delId;
    let message = "Mira este incidente:\n\n" + this.incident.incident + "\nOcurrido en " +
      this.incident.place + " el " + this.incident.date + " a las " + this.incident.hour +
      "\n\n" + this.incident.description + "\n\n";

    this.socialSharing.share(message, '', '', url).then(() => {

    }).catch( e => {
      
    });
  }
}
