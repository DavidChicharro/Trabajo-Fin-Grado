import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Storage } from '@ionic/storage';
import { ModalController, ToastController } from '@ionic/angular';
import { AuthService } from './../../auth/auth.service';

@Component({
  selector: 'app-pass-data',
  templateUrl: './pass-data.page.html',
  styleUrls: ['./pass-data.page.scss'],
})
export class PassDataPage implements OnInit {

  validForm: boolean;
  validPass: boolean;
  matchPass: boolean;
  diffPass: boolean;

  // email: string;
  password: string;
  newPassword: string;
  repeatPassword: string;

  allowCheckMatch = false;
  displayNotMatchPass = false;
  displayNotValidPassFormat = false;
  displayDiffPass = false;

  pswdTypeInput = 'password';
  eyeType = 'eye-off-outline';
  newPswdTypeInput = 'password';
  eyeNewType = 'eye-off-outline';
  pswdRptTypeInput = 'password';
  eyeRptType = 'eye-off-outline';

  constructor(
    private modalCtrl: ModalController,
    private toastCtrl: ToastController,
    private router: Router,
    private auth: AuthService,
    private storage: Storage
  ) { }

  ngOnInit() {
  }

  changePasswd() {
    this.storage.get('api_token').then(user => {
      if(user !== null) {
        this.auth.updatePass (
          user,
          this.password,
          this.newPassword
        ).subscribe(response => {
          if(response.status === 'success') {
            this.auth.clear();
            this.presentToast(response.message + 
              '\nHas modificado la contraseña. Por favor, inicia sesión de nuevo');
            this.router.navigate(['/login']);
            this.dismissModal();
          }else {
            this.presentToast('Algo salió mal. Por favor, revisa los campos');
          }
        });
      }
    });
  }

  validatePass() {
    let passRegExp = new RegExp('^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[\\W|_])[A-Za-z\\d\\W|_]{8,}$');
    this.validPass = passRegExp.test(this.password);

    this.displayNotValidPassFormat = this.validPass ? false : true;
    
    this.validateForm();

    if(this.allowCheckMatch)
      this.checkMatchPass();
  }

  checkMatchPass() {    
    this.matchPass = this.newPassword === this.repeatPassword;

    this.displayNotMatchPass = this.matchPass ? false : true;

    this.validateForm();

    if(!this.allowCheckMatch)
      this.allowCheckMatch = true;
  }

  checkDiffPass() {
    this.diffPass = this.password !== this.newPassword;

    this.displayDiffPass = this.diffPass ? false : true;
  }

  validateForm() {
    this.validForm = 
      (this.validPass && this.matchPass && this.diffPass) ?
      true : false;
  }

  togglePswdMode() {
    this.pswdTypeInput = this.pswdTypeInput === 'text' ? 'password' : 'text';
    this.eyeType = this.eyeType === 'eye-off-outline' ? 'eye-outline' : 'eye-off-outline';
  }

  toggleNewPswdMode() {
    this.newPswdTypeInput = this.newPswdTypeInput === 'text' ? 'password' : 'text';
    this.eyeNewType = this.eyeNewType === 'eye-off-outline' ? 'eye-outline' : 'eye-off-outline';
  }
  
  togglePswdRptMode() {
    this.pswdRptTypeInput = this.pswdRptTypeInput === 'text' ? 'password' : 'text';
    this.eyeRptType = this.eyeRptType === 'eye-off-outline' ? 'eye-outline' : 'eye-off-outline';
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
