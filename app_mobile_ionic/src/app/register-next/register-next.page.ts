import { Router } from '@angular/router';
import { Component, OnInit } from '@angular/core';
import { ToastController } from '@ionic/angular';
import { AuthService } from './../auth/auth.service';

@Component({
  selector: 'app-register-next',
  templateUrl: './register-next.page.html',
  styleUrls: ['./register-next.page.scss'],
})
export class RegisterNextPage implements OnInit {

  email: string;
  password: string;
  nombre: string;
  apellidos: string;
  dni: string;
  fecha_nacimiento: string;
  telefono:string;

  validNombre: boolean;
  validApellidos: boolean;
  validDNI: boolean;
  validFNac: boolean;
  validTlf: boolean;
  validDate: boolean;
  validForm: boolean;

  max_date: string;

  constructor(
    private router: Router,
    private auth: AuthService,
    private toastCtrl: ToastController
  ) {
    if(this.router.getCurrentNavigation().extras.state) {
      let state = this.router.getCurrentNavigation().extras.state;
      this.email = state.email;
      this.password = state.password;
    }

    this.validForm = false;
    this.setMaxDate();
  }

  ngOnInit() { }

  registerForm() {
    this.formatFields();

    this.auth.registUser(
      this.email,
      this.password,
      this.nombre,
      this.apellidos,
      this.auth.formatDate(this.fecha_nacimiento),
      this.telefono,
      this.dni,
    ).subscribe(response => {
      if(response.status === 'success') {
        this.presentToast(response.message + ' Inicia sesión');
        this.router.navigate(['/login']);
      }else {
        this.presentToast('Algo salió mal. Por favor, revisa los campos');
      }
    }); 
  }

  validateName() {
    this.validNombre = this.nombre.length>1 && this.nombre.length<255;
    this.validateForm();
  }
  
  validateApellidos() {
    this.validApellidos = this.apellidos.length>1 && this.apellidos.length<255;
    this.validateForm();
  }

  validateDNI() {
    let dniCtrl = this.auth.calcDniLetter(this.dni.substr(0,8));
    let dniRegExp = new RegExp('^[0-9]{8}(-)?['+dniCtrl+','+dniCtrl.toLowerCase()+']$');
    this.validDNI = dniRegExp.test(this.dni);

    this.validateForm();
  }

  validateTelefono() {
    let telRegExp = new RegExp('^[6,7][0-9]{8}$');
    this.validTlf = telRegExp.test(this.telefono);

    this.validateForm();
  }

  validateDate() {
    this.validDate = this.fecha_nacimiento != "";
  }

  validateForm() {
    this.validForm = 
      (this.validNombre && this.validApellidos && this.validDNI
        && this.validTlf && this.validDate) ?
      true : false; 
  }

  private setMaxDate() {
    let maxDate = new Date(new Date().setFullYear(new Date().getFullYear()-12));
    let month: number = maxDate.getMonth()+1;
    let maxMonth = (month<10) ? '0'+month : month;
    let day: number = maxDate.getDate();
    let maxDay = (day<10) ? '0'+day : day;

    this.max_date = maxDate.getFullYear()+'-'+maxMonth+'-'+maxDay;
  }

  private formatFields() {
    this.nombre = this.auth.capitalizeFirst(this.nombre);
    this.apellidos = this.auth.capitalizeFirst(this.apellidos);
    let dniNum = this.dni.substr(0,8);
    let dniCtrlChar = this.dni.substr(-1).toUpperCase();
    this.dni = (dniNum+'-'+dniCtrlChar);
  }

  async presentToast(response) {
    const toast = await this.toastCtrl.create({
      message: response,
      duration: 3000
    });
    toast.present();
  }

}
