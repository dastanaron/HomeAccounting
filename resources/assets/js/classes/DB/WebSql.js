/**
 * Class for webSQL working
 */
class WebSql {

    /**
     * @param dbName
     * @param version
     * @param displayName
     * @param estimateSize
     */
    constructor(dbName, version='1.0', displayName = '', estimateSize=20000)
    {
        this.db = openDatabase(dbName, version, displayName, estimateSize)
    }

    /**
     * @param tableName
     * @param columns
     */
    createTable(tableName, columns) {

        let columnsString = '';

        let countColumns = columns.length;

        let countIteration = 1;

        for (let key in columns) {
            if(columns[key].name != '' && columns[key].type != '') {

                columnsString += columns[key].name + ' ' + columns[key].type;

                if(columns[key].unique) {
                    columnsString += ' UNIQUE,';
                }
                else if(countIteration !== countColumns) {
                    columnsString += ', ';
                }

                countIteration++;
            }
            else {
                console.error('no required parameters');
            }
        };

        let columnsToRequest = '(' + columnsString + ')';

        this.db.transaction((tx) => {
            tx.executeSql("CREATE TABLE " + tableName + columnsToRequest, [], null, null)
        })

        this.db.transaction((tx) => {
            tx.executeSql("SELECT COUNT(*) FROM " + tableName, [], function(result){
                console.log(result);
            }, (tx, error) => {
                console.log(tx);
                console.log(error);
            });
        });
    }

    /**
     * @param tableName
     * @param columns
     * @param values
     */
    insertRecordToTable(tableName, columns, values) {

        let valuesCount = values.length;

        if(columns.length !== valuesCount || columns.length < 1) {
            throw 'Error, invalid parameters columns or values';
        }

        let valuesString = '';

        for (let i=1; i<=valuesCount; i++) {
            valuesString += '?';

            if(i != valuesCount) {
                valuesString += ', ';
            }
        }

        let query = 'INSERT INTO '+tableName+' ('+columns.join(', ')+') values('+valuesString+')';

        this.db.transaction((tx) => {
            tx.executeSql(query, values, null, null);
        });
    }
}

/*
column = [
{
name: 'id',
type: 'INTEGER'
unique: true
},
{
name: 'test',
type: 'TEXT'
}
];
 */

/*
NULL. The value is a NULL value.

INTEGER. The value is a signed integer, stored in 1, 2, 3, 4, 6, or 8 bytes depending on the magnitude of the value.

REAL. The value is a floating point value, stored as an 8-byte IEEE floating point number.

TEXT. The value is a text string, stored using the database encoding (UTF-8, UTF-16BE or UTF-16LE).

BLOB. The value is a blob of data, stored exactly as it was input.
 */

module.exports = WebSql;