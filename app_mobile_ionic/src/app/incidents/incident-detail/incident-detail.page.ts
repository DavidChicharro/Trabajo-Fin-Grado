import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { IncidentsService } from '../incidents.service';
import { IncidentList } from '../../models/incidentList';
import { SocialSharing } from '@ionic-native/social-sharing/ngx';
import { SERVER_URL } from './../../../environments/environment.prod';

@Component({
  selector: 'app-incident-detail',
  templateUrl: './incident-detail.page.html',
  styleUrls: ['./incident-detail.page.scss'],
})
export class IncidentDetailPage implements OnInit {

  incident: IncidentList;

  constructor(
    private activatedRoute: ActivatedRoute,
    private incidentsService: IncidentsService,
    private socialSharing: SocialSharing
  ) { }

  ngOnInit() {
    this.activatedRoute.paramMap.subscribe(paramMap => {
      const recipeId = paramMap.get('incidentId');
      this.incident = this.incidentsService.getIncidentDetail(recipeId);
    });
  }

  share() {
    let url = SERVER_URL+'/incidente?inc='+this.incident.id+'&del='+this.incident.delId;
    let message = "Mira este incidente:\n\n" + this.incident.incident + "\nOcurrido en " +
      this.incident.place + " el " + this.incident.date + " a las " + this.incident.hour +
      "\n\n" + this.incident.description + "\n\n";

    // console.log(message);
    this.socialSharing.share(message, '', '', url).then(() => {

    }).catch( e => {
      
    });
  }
}
