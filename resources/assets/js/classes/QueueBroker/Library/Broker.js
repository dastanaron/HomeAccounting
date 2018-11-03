const axios = require('axios');

function Broker(storage) {
    this.storage = storage;
};

Broker.prototype.test = function (obj) {
    obj.$store.commit('AlertError', 'testError');
};

Broker.prototype.sendToServer = function (method, url, data, VueObject) {

    axios({
        method: method,
        url: url,
        data: data,
    })
    .then(response => {
        if(response.data.status == 200) {
            console.log('sent to server');
        }
        else {
            console.log(response.data)
        }

    })
    .catch(error => {
        VueObject.$store.commit('AlertError', error.message);
    });

};

module.exports = Broker;