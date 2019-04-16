
import axios from 'axios';

export default class Currency {

    constructor ()
    {

    }

    getCurrencies()
    {
        return new Promise((resolve, reject) => {
            axios.get('/pa/get-currencies')
                .then(response => {
                    resolve(response.data.data);
                })
                .catch(error => {
                    reject(error);
                });
        });
    }

    getCurrency(currencyCode)
    {
        return new Promise((resolve, reject) => {
            axios.get('/pa/get-currency', {
                params: {
                    currencyCode: currencyCode
                }
            })
            .then(response => {;
                resolve(response.data.data);
            })
            .catch(error => {
                reject(error);
            });
        });
    }
}