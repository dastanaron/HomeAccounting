function Storage(debug) {

    if(debug === true)
    {
        this.debugMode = true;
    }

    this.storage = window.localStorage;
};

Storage.prototype.addObjectToStorage = function (key, object) {
    this.storage.setItem(key, JSON.stringify(object));
};

Storage.prototype.addStringToStorage = function (key, value) {
    this.storage.setItem(key, value);
};

Storage.prototype.get = function (key) {
    return this.storage.getItem(key);
};

Storage.prototype.getObject = function (key) {
    try
    {
        return JSON.parse(this.storage.getItem(key));
    }
    catch (e)
    {
        this._debug(e);
        this._debug(key + ' = ' + this.storage.getItem(key));
        return false;
    }
};

Storage.prototype.add = function (key, value) {
    try
    {
        if(typeof value === 'object') {
            this.addObjectToStorage(key, value);
        }
        else if (typeof value === 'string' || typeof value === 'number') {
            this.addStringToStorage(key, value);
        }
        else {
            this._debug('2 parameter does not belong to a known type')
        }

        return this.storage;

    }
    catch (e)
    {
        if (e === QUOTA_EXCEEDED_ERR) {
            this._debug('LocalStorage is exceeded the free space limit')
        }
        else
        {
            this._debug(e)
        }
    }
};

Storage.prototype.clear = function () {
    try
    {
        this.storage.clear();
        return true;
    }
    catch (e)
    {
        this._debug(e)
        return false;
    }
};

Storage.prototype.delete = function(key) {
    try
    {
        this.storage.removeItem(key);
        return true;
    }
    catch (e)
    {
        this._debug(e)
        return false;
    }
};

Storage.prototype.view = function () {
    return this.storage;
};


Storage.prototype._debug = function(error) {
    if(this.debugMode)
    {
        console.error(error);
    }
    return null;
};

module.exports = Storage;