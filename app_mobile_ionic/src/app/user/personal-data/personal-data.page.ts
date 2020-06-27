import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ModalController, ToastController } from '@ionic/angular';
import { Storage } from '@ionic/storage';
import { UserService } from '../user.service';
import { AuthService } from './../../auth/auth.service';

@Component({
  selector: 'app-personal-data',
  templateUrl: './personal-data.page.html',
  styleUrls: ['./personal-data.page.scss'],
})
export class PersonalDataPage implements OnInit {

  // email: string;
  formEmail: string;
  nombre: string;
  apellidos: string;
  dni: string;
  fecha_nacimiento: string;
  telefono:string;
  telefono_fijo:string;

  validNombre = true;
  validApellidos = true;
  validEmail = true;
  validFNac = true;
  validTlf = true;
  validTlfFijo = true;
  validForm = false;

  max_date: string;

  constructor(
    private modalCtrl: ModalController,
    private toastCtrl: ToastController,
    private router: Router,
    private auth: AuthService,
    private userService: UserService,
    private storage: Storage
  ) {
    this.setMaxDate();
  }

  ngOnInit() {
    this.storage.get('api_token').then(user => {
      if(user !== null) {
        this.userService.getApiUserData(user).subscribe(res => {
          if (res.status === "success") {
            this.formEmail = res.userData.email;
            this.nombre = res.userData.nombre;
            this.apellidos = res.userData.apellidos;
            this.dni = res.userData.dni;
            this.fecha_nacimiento = res.userData.fecha_nacimiento;
            this.telefono = res.userData.telefono;
            this.telefono_fijo = res.userData.telefono_fijo;
          } else {
            this.dismissModal();
          }
        });
      }
    });
  }

  registerForm() {
    this.formatFields();

    this.storage.get('api_token').then(user => {
      if(user !== null) {
        this.auth.updateUser(
          user,
          this.formEmail,
          this.nombre,
          this.apellidos,
          this.auth.formatDate(this.fecha_nacimiento),
          this.telefono,
          this.telefono_fijo,
          this.dni,
        ).subscribe(response => {
          if(response.status === 'success') {
            if (response.newEmail !== null) {
              this.auth.clear();
              this.presentToast(response.message + 
                '\nHas modificado el email. Por favor, inicia sesión de nuevo');
              this.router.navigate(['/login']);
            }

            this.presentToast(response.message);
            this.dismissModal();
          }else {
            this.presentToast('Algo salió mal. Por favor, revisa los campos');
          }
        });
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

  validateEmail() {
    let emailRegExp = new RegExp('^(([^<>()\\[\\]\\\\.,;:\\s@"]+(\\.[^<>()\\[\\]\\\\.,;:\\s@"]+)*)|(".+"))@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}])|(([a-zA-Z\\-0-9]+\\.)+[a-zA-Z]{2,}))$');
    this.validEmail = emailRegExp.test(this.formEmail);

    this.validateForm();
  }

  private tlfRegex(type) {
    if (type==='movil')
      return new RegExp('^[6,7][0-9]{8}$');

    return new RegExp('^[8,9][0-9]{8}$');
  }

  validateTelefono() {
    let telRegExp = this.tlfRegex('movil');
    this.validTlf = telRegExp.test(this.telefono);

    this.validateForm();
  }

  validateTlfFijo() {
    let telRegExp = this.tlfRegex('fijo');
    this.validTlfFijo = (telRegExp.test(this.telefono_fijo)) || (this.telefono_fijo === '');
    
    this.validateForm();
  }

  validateDate() {
    this.validFNac = this.fecha_nacimiento != "";
  }

  validateForm() {
    this.validForm = 
      (this.validNombre && this.validApellidos
        && this.validTlf && this.validTlfFijo
        && this.validFNac) ? true : false;
  }

  private formatFields() {
    this.nombre = this.auth.capitalizeFirst(this.nombre);
    this.apellidos = this.auth.capitalizeFirst(this.apellidos);
  }

  private setMaxDate() {
    let maxDate = new Date(new Date().setFullYear(new Date().getFullYear()-12));
    let month: number = maxDate.getMonth()+1;
    let maxMonth = (month<10) ? '0'+month : month;

    this.max_date = maxDate.getFullYear()+'-'+maxMonth+'-'+maxDate.getDate();
  }

  async presentToast(response) {
    const toast = await this.toastCtrl.create({
      message: response,
      duration: 3000
    });
    toast.present();
  }

  /**
   * Cierra la página
   */
  dismissModal() {
    this.modalCtrl.dismiss();
  }

}
