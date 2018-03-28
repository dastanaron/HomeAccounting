<template>
    <v-app id="fundsControl" v-if="showFundsComponent">
        <div class="my-4">
            <v-tooltip bottom>
                <v-btn class="new-button" slot="activator" flat large color="success" @click="">
                    <i class="fas fa-plus-square"></i>
                </v-btn>
                <span>Создать новый</span>
            </v-tooltip>

            <v-data-table
                    :loading="loadingDataTable"
                    :headers="headers"
                    :items="dataTables"
                    v-bind:pagination.sync="pagination"
                    hide-actions
                    class="elevation-1"
                    :search="search"
                    item-key="uuid"
            >
                <v-progress-linear slot="progress" color="success" indeterminate></v-progress-linear>
                <template slot="items" slot-scope="props">
                    <tr>
                        <td>{{ props.item.id }}</td>
                        <td class="text-xs-right">{{ props.item.bills_name}}</td>
                        <td class="text-xs-right">{{ props.item.rev }}</td>
                        <td class="text-xs-right">{{ props.item.category_name }}</td>
                        <td class="text-xs-right">{{ props.item.sum }}</td>
                        <td class="text-xs-right">{{ props.item.date }}</td>
                        <td class="text-xs-right control">
                            <v-tooltip top>
                                <v-btn flat small color="primary" slot="activator" @click="test(props.item)"><i class="fas fa-pencil-alt"></i></v-btn>
                                <span>Редактировать</span>
                            </v-tooltip>
                            <v-tooltip top>
                                <v-btn flat small color="error" slot="activator" @click=""><i class="fas fa-trash-alt"></i></v-btn>
                                <span>Удалить</span>
                            </v-tooltip>

                        </td>
                    </tr>
                </template>
            </v-data-table>
        </div>
    </v-app>
</template>

<script>
    export default {
        name: "funds-control",
        props: {
            showFundsComponent: {
                type: Boolean,
                default: false,
            },
        },
        data: () => ({
            search: '',
            loadingDataTable: false,
            pagination: {'sortBy': 'sum', 'descending': true, 'rowsPerPage': -1},
            headers: [
                {
                    text: 'ID',
                    align: 'left',
                    sortable: false,
                    value: 'id'
                },
                { text: 'Счет', value: 'bills_name', align: 'right' },
                { text: 'Доход или расход', value: 'rev', align: 'right' },
                { text: 'Категория', value: 'category_name', align: 'right' },
                { text: 'Сумма', value: 'sum', align: 'right' },
                { text: 'Дата', value: 'date', align: 'right'},
            ],
            dataTables: [],

        }),
        methods: {
            getFunds(page=1) {
                axios.get('/pa/funds-list?page='+page)
                    .then(response=> {
                        console.log(response.data)
                        this.dataTables = response.data.data;
                    })
                    .catch(function (error) {
                        console.log(error)
                    });

            },
            test(obj) {
                console.log(obj);
            }
        },
        mounted() {
          this.getFunds();
        },
    }
</script>

<style scoped>

</style>