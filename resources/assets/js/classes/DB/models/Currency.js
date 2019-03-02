import WebSql from '../WebSql';
import axios from 'axios';

export default class Currency {

    constructor ()
    {

        this.dataProvider = {
            num_code: {type: Number},
            char_code: {type: String},
            nominal: {type: Number},
            name: {type: String},
            value: {type: Number}
        };

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

        this.db.createTable(this.tableName, columns)
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