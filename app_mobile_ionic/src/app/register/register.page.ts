import { Observable } from 'rxjs';
import { Component, OnInit } from '@angular/core';
import { Router, NavigationExtras } from '@angular/router';
import { AuthService } from './../auth/auth.service';
import { ToastController, LoadingController } from '@ionic/angular';

@Component({
  selector: 'app-register',
  templateUrl: './register.page.html',
  styleUrls: ['./register.page.scss'],
})
export class RegisterPage implements OnInit {

  validForm: boolean;
  validEmail: boolean;
  validPass: boolean;
  matchPass: boolean;

  email: string;
  password: string;
  repeatPassword: string;

  allowCheckMatch = false;
  displayNotMatchPass = false;
  displayNotValidPassFormat = false;

  pswdTypeInput = 'password';
  eyeType = 'eye-off-outline';
  pswdRptTypeInput = 'password';
  eyeRptType = 'eye-off-outline';

  constructor(
    private router: Router,
    private auth: AuthService,
    private toastCtrl: ToastController,
    private loadingCtrl: LoadingController,  
  ) {
    this.validForm = false;
  }

  ngOnInit() {
  }

  public getParams() {
    return {
      email: this.email,
      password: this.password
    }
  }

  registerForm() {
    this.auth.checkUserExists(this.email).subscribe(response => {
      this.presentLoading();
      if(response.status === 'success') {
        this.router.navigate(['/register-next'], {
          state: {
            email: this.email,
            password: this.password
          }
        });
      }else {
        this.loadingCtrl.dismiss();
        this.presentToast(response.message);
      }
    });    
  }

  registerFirst(email: HTMLInputElement, password: HTMLInputElement, repeatPassword: HTMLInputElement) {
    console.log(email.value, password.value, repeatPassword.value);
  }

  validateEmail() {
    let emailRegExp = new RegExp('^(([^<>()\\[\\]\\\\.,;:\\s@"]+(\\.[^<>()\\[\\]\\\\.,;:\\s@"]+)*)|(".+"))@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}])|(([a-zA-Z\\-0-9]+\\.)+[a-zA-Z]{2,}))$');
    this.validEmail = emailRegExp.test(this.email);

    this.validateForm();
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
    this.matchPass = this.password === this.repeatPassword;

    this.displayNotMatchPass = this.matchPass ? false : true;

    this.validateForm();

    if(!this.allowCheckMatch)
      this.allowCheckMatch = true;
  }

  validateForm() {
    this.validForm = 
      (this.validEmail && this.validPass && this.matchPass) ?
      true : false;
  }

  togglePswdMode() {
    this.pswdTypeInput = this.pswdTypeInput === 'text' ? 'password' : 'text';
    this.eyeType = this.eyeType === 'eye-off-outline' ? 'eye-outline' : 'eye-off-outline';
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

  async presentLoading() {
    const loading = await this.loadingCtrl.create({
      spinner: 'crescent',
      message: 'Verificando usuario...',
      duration: 3000
    });
    await loading.present();

    const { role, data } = await loading.onDidDismiss();
  }

}
