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

        let query = 'REPLACE INTO '+tableName+' ('+columns.join(', ')+') values('+valuesString+')';

        this.db.transaction((tx) => {
            tx.executeSql(query, values, null, null);
        }, (error) => {
            console.error(error);
        });
    }
}

module.exports = WebSql;