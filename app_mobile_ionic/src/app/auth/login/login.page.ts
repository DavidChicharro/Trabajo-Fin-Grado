import { Router } from '@angular/router';
import { Component, OnInit } from '@angular/core';
import { Platform, ToastController, LoadingController } from '@ionic/angular';
import { Storage } from '@ionic/storage';
import { AuthService } from '../auth.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.page.html',
  styleUrls: ['./login.page.scss'],
})
export class LoginPage implements OnInit {

  response: {status: string, message: string};
  email: string;
  pswdTypeInput = 'password';
  eyeType = 'eye-off-outline';

  constructor(
    private storage: Storage,
    private loginService: AuthService,  // Cliente del servicio para peticiones
    private toastCtrl: ToastController,
    private router: Router,
    private platform: Platform,
    private loadingCtrl: LoadingController
  ) {
    this.checkLogin();

    this.platform.backButton.subscribe(()=> {
      navigator['app'].exitApp();
    });
}

  ngOnInit(
  ) { }

  checkLogin() {
    this.storage.get('api_token').then(user => {
      if(user !== null) {
        this.router.navigate(['/tabs/list']);
      }
    });
  }

  /**
   * Loguea al usuario y lo redirige a la página principal de la
   * aplicación (lista de incidentes)
   * @param email 
   * @param password 
   */
  login(email: HTMLInputElement, password: HTMLInputElement) {
    this.presentLoading();
    this.loginService.login(email.value, password.value).subscribe( response => {
      if(response.status === 'success') {
        this.loginService.store(email.value, response.api_token);
        this.router.navigate(['/tabs/list']);
      }else {
        this.loadingCtrl.dismiss();
        this.presentToast(response.message);
      }
    });
  }

  togglePswdMode() {
    this.pswdTypeInput = this.pswdTypeInput === 'text' ? 'password' : 'text';
    this.eyeType = this.eyeType === 'eye-off-outline' ? 'eye-outline' : 'eye-off-outline';
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
      message: 'Iniciando sesión...',
      duration: 3000
    });
    await loading.present();

    const { role, data } = await loading.onDidDismiss();
  }

}
