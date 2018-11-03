const axios = require('axios');

function Broker(storage, prefix='storageKey') {
    this.storage = storage;
    this.prefix = prefix;

    if(this.storage.get('broker') === null) {
        this.broker = {};
        this.storage.add('broker', this.broker)
    }
    else {
        this.broker = this.storage.getObject('broker');
    }
};

Broker.prototype.queueCount = function () {
    return Object.keys(this.broker).length;
};

Broker.prototype.saveToStorage = function (method, url, data) {
    let key = this.prefix + '_' + (Object.keys(this.broker).length + 1);

    this.broker[key] = {method, url, data};

    this.storage.add('broker', this.broker);
};

Broker.prototype.run = function (VueObject) {
    for (let key in this.broker) {
        this.sendToServer(this.broker[key], key)
    }
}

Broker.prototype.sendToServer = function (object, brokerKey) {

    axios({
        method: object.method,
        url: object.url,
        data: object.data,
    })
    .then(response => {
        if(response.data.status == 200) {
            delete this.broker[brokerKey];
            this.storage.add('broker', this.broker);
        }
        else {
            console.log(response.data)
        }

    })
    .catch(error => {

    });

};

module.exports = Broker;