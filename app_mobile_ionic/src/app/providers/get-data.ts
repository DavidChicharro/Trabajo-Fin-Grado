import { Injectable } from '@angular/core';
import { HTTP } from '@ionic-native/http/ngx';

@Injectable()
export class GetData {

    constructor(public http: HTTP) {}

    getRemoteData() {
        this.http.get('https://jsonplaceholder.typicode.com/posts', {}, {})
            .then(data => {
                console.log(data.status);
                console.log(data.data); // data received by server
                console.log(data.headers);
            })
            .catch(error => {
                console.log(error.status);
                console.log(error.error); // error message as string
                console.log(error.headers);

            });
    }
}
