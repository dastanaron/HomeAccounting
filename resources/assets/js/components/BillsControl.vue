<template>
    <v-app id="billsControl" v-if="showBillsTable">
        <div class="my-4">
            <v-tooltip bottom>
                <v-btn class="new-button" slot="activator" flat large icon color="success" @click="createBillsForm()">
                    <v-icon>add_box</v-icon>
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
                        <td class="text-xs-right">{{ props.item.name}}</td>
                        <td class="text-xs-right">{{ sumFormat(props.item.sum) }}</td>
                        <td class="text-xs-right">{{ props.item.deadline }}</td>
                        <td class="text-xs-right">{{ props.item.comment }}</td>
                        <td class="text-xs-right control">
                            <v-tooltip top>
                                <v-btn flat icon small color="primary" slot="activator" @click="billEditForm(props.item)"><v-icon>mode_edit</v-icon></v-btn>
                                <span>Редактировать</span>
                            </v-tooltip>
                            <v-tooltip top>
                                <v-btn flat icon small color="error" slot="activator" @click="billDelete(props.item)"><v-icon>delete</v-icon></v-btn>
                                <span>Удалить</span>
                            </v-tooltip>

                        </td>
                    </tr>
                </template>
                <template slot="footer">
                    <tr>
                        <td colspan="2">
                            <strong>Итого:</strong>
                        </td>
                        <td class="text-xs-right">
                            <strong>{{ totalValue }}</strong>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                </template>
            </v-data-table>
        </div>
        <v-layout row justify-center>
            <v-dialog v-model="billsFormShow" persistent max-width="700px">
                <v-card>
                    <v-card-title>
                        <span class="headline">{{ billFormTitle }}</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm6 md4>
                                    <v-text-field v-model="billFormData.name" label="Введите название" required></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-text-field v-model="billFormData.sum" :rules="billFormData.sumRules" label="Введите сумму" required></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-dialog
                                            ref="dialog"
                                            persistent
                                            v-model="DeadLinePicker"
                                            lazy
                                            full-width
                                            width="290px"
                                    >
                                        <v-text-field
                                                slot="activator"
                                                label="Срок окончания"
                                                hint="Может быть нужно, для счета сберегательной программы со сроком действия"
                                                v-model="billFormData.deadline"
                                                prepend-icon="event"
                                                readonly
                                        ></v-text-field>
                                        <v-date-picker type="date" locale="ru" v-model="billFormData.deadline" scrollable>
                                            <v-spacer></v-spacer>
                                            <v-btn flat color="primary" @click="DeadLinePicker=false">OK</v-btn>
                                        </v-date-picker>
                                    </v-dialog>
                                </v-flex>
                                <v-flex xs12>
                                    <v-text-field v-model="billFormData.comment" label="Коммментарий"></v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-container>
                        <small>*-поля обязательные для заполнения</small>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="info darken-1" flat @click.native="billsFormShow = false">Закрыть</v-btn>
                        <v-btn color="success darken-1" flat @click="billSave()">Сохранить</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>
    </v-app>
</template>

<script>
    export default {
        name: "bills-control",
        props: {
            showBillsTable: {
                type: Boolean,
                default: false,
            },
        },
        data: () => ({

            billFormType: 'create',
            billsFormShow: false,
            billFormTitle: 'Управление счетом',
            DeadLinePicker: false,
            billFormData: {
                id: '',
                name: '',
                sum: '',
                deadline: '',
                comment: '',
                sumRules: [
                    v => !!v || 'Сумма обязательна к заполнению',
                    v => /^\d+$/.test(v) || 'Сумма должна быть числом'
                ]
            },
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
                { text: 'Название', value: 'name', align: 'right' },
                { text: 'Сумма', value: 'sum', align: 'right' },
                { text: 'Окончание программы', value: 'deadline', align: 'right' },
                { text: 'Комментарий', value: 'comment', align: 'right' },
                { text: 'Управление', value: '', align: 'right'},
            ],
            dataTables: [

            ]
        }),
        methods: {
            getBills() {

                this.$store.commit('setPreloader', true);

                axios.get('/pa/bills-list')
                    .then(response=> {
                        this.dataTables = response.data;

                        this.$store.commit('setPreloader', false);

                    })
                    .catch(error => {
                        this.$store.commit('AlertError', error.message);
                    });

            },
            createBillsForm() {
                this.billsFormShow = true;

                //clear form
                this.billFormData.name = this.billFormData.sum =
                    this.billFormData.deadline = this.billFormData.comment =
                        this.billFormData.id = '';

                this.billFormTitle = 'Создание нового счета';
                this.billFormType = 'create';
            },
            billSave() {

                let url = '/pa/bills';
                let method = '';

                if(this.billFormType == 'create') {
                    method = 'post';
                }
                else if(this.billFormType == 'update') {
                    method = 'put';
                }
                else if(this.billFormType == 'delete') {
                    method = 'delete';
                }

                axios({
                    method: method,
                    url: url,
                    data:
                        {
                            bill_id: this.billFormData.id,
                            name: this.billFormData.name,
                            sum: this.billFormData.sum,
                            deadline: this.billFormData.deadline,
                            comment: this.billFormData.comment,
                        }
                })
                    .then(response=> {
                        if(response.data.status == 200) {
                            this.billsFormShow = false;
                            this.getBills();
                        }
                        else {
                            console.log(response);
                        }

                    })
                    .catch(error => {
                        this.$store.commit('AlertError', error.message);
                    });
            },
            billEditForm(object) {
                //construct form
                this.billFormData.id = object.id;
                this.billFormData.name = object.name;
                this.billFormData.sum = object.sum;
                this.billFormData.deadline = object.deadline;
                this.billFormData.comment = object.comment;

                this.billFormType = 'update';

                this.billsFormShow = true;

            },
            billDelete(object) {

                let confirm = window.confirm('Вы действительно хотите удалить элемент?');

                if(confirm === true) {
                    this.billFormType = 'delete';
                    this.billFormData.id = object.id;
                    this.billSave();
                    this.getBills();
                }

            },
            sumFormat(sum) {
                return sum.toString().replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ');
            },
        },
        computed: {
            totalValue() {
                let total = 0;

                for(let key in this.dataTables) {
                    total += this.dataTables[key]['sum'];
                }

                return this.sumFormat(total);
            },
        },
        watch: {
            showBillsTable: function (val) {
                this.getBills();
            },
        },
        mounted() {
            this.$on('reloadTable', function () {
                this.getBills();
            })
            this.getBills();
        }

        }
</script>

<style scoped>

</style>