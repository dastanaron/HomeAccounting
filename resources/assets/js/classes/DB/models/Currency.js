import WebSql from '../WebSql';
import axios from 'axios';

export default class Currency {

    constructor ()
    {

        this.dataProvider = {};

        this.tableName = 'currency';

        this.db = new WebSql(this.tableName, '1.0', 'currency information table', 20000000);

        let columns = [
            {
                name: 'num_code',
                type: 'INTEGER',
                unique: true
            },
            {
                name: 'char_code',
                type: 'VARCHAR(25)',
                unique: true
            },
            {
                name: 'nominal',
                type: 'INTEGER'
            },
            {
                name: 'name',
                type: 'TEXT'
            },
            {
                name: 'value',
                type: 'DECIMAL(10,4)'
            }
        ];

        this.db.createTable(this.tableName, columns);
    }

    fillTable()
    {

        const columns = ['num_code', 'char_code', 'nominal', 'name', 'value'];

        this.dataProvider = '';

        this.getCurrenciesFromServer();

        let interval = setInterval(() => {

            if(this.dataProvider !== '') {

                clearInterval(interval);

                for (let key in this.dataProvider) {
                    let values = [
                        this.dataProvider[key].num_code,
                        this.dataProvider[key].char_code,
                        this.dataProvider[key].nominal,
                        this.dataProvider[key].name,
                        this.dataProvider[key].value
                    ];

                    this.db.insertRecordToTable(this.tableName, columns, values);
                }
            }

        }, 2000);
    }

    getCurrencyFromTable()
    {
        return new Promise((resolve, reject) => {
            this.db.db.transaction((tx) => {
                tx.executeSql("SELECT * FROM " + this.tableName, [], (trans, result) => {
                    resolve(result.rows);
                }, (error) => {
                    reject(error);
                })
            });
        });
    }

    getCurrenciesFromServer()
    {
        axios.get('/pa/get-currencies')
            .then(response=> {
                this.dataProvider = response.data.data;
            })
            .catch(error => {
                console.log(error);
            });
    }
}

/*
return new Promise((resolve, reject) => {

  this.db.transaction((tx) => {

    tx.executeSql(
      sql,
      params,
      (tx, res) => resolve({tx, res}),
      (tx, err) => reject({tx, err}),
    );

  });

});
 */