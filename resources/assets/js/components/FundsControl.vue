<template>
    <div id="fundsControl" v-if="showFundsComponent">
        <div class="my-4">
            <v-tooltip bottom>
                <v-btn class="new-button" slot="activator" flat icon large color="success" @click="newFund()">
                    <v-icon>add_box</v-icon>
                </v-btn>
                <span>Создать новый</span>
            </v-tooltip>
            <v-card>
                <v-card-title>
                    <h2>Фильтры</h2>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12 sm4 md2>
                                <v-select
                                        :items="revList"
                                        v-model="fundsFilterForm.rev"
                                        item-text="name"
                                        item-value="value"
                                        label="Доход или расход"
                                        clearable
                                        autocomplete
                                ></v-select>
                            </v-flex>
                            <v-flex xs12 sm4 md2>
                                <v-select
                                        :items="billsList"
                                        v-model="fundsFilterForm.bills_id"
                                        item-text="name"
                                        item-value="id"
                                        label="Выберите счёт"
                                        clearable
                                        autocomplete
                                ></v-select>
                            </v-flex>
                            <v-flex xs12 sm4 md2>
                                <v-select
                                        :items="categoryList"
                                        v-model="fundsFilterForm.category_id"
                                        item-text="name"
                                        item-value="id"
                                        label="Категория"
                                        clearable
                                        autocomplete
                                ></v-select>
                            </v-flex>
                            <v-flex xs12 sm4 md2>
                                <v-text-field v-model="fundsFilterForm.sum" clearable label="Сумма"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm4 md2>
                                <v-dialog
                                        ref="dialog"
                                        persistent
                                        v-model="DatePickerFilterStart"
                                        lazy
                                        full-width
                                        width="290px"
                                >
                                    <v-text-field
                                            slot="activator"
                                            label="Дата от"
                                            v-model="fundsFilterForm.date_start"
                                            prepend-icon="event"
                                            readonly
                                            clearable
                                    ></v-text-field>
                                    <v-date-picker type="date" locale="ru" v-model="fundsFilterForm.date_start" scrollable>
                                        <v-spacer></v-spacer>
                                        <v-btn flat color="primary" @click="DatePickerFilterStart=false">OK</v-btn>
                                    </v-date-picker>
                                </v-dialog>
                            </v-flex>
                            <v-flex xs12 sm4 md2>
                                <v-dialog
                                        ref="dialog"
                                        persistent
                                        v-model="DatePickerFilterEnd"
                                        lazy
                                        full-width
                                        width="290px"
                                >
                                    <v-text-field
                                            slot="activator"
                                            label="Дата до"
                                            v-model="fundsFilterForm.date_end"
                                            prepend-icon="event"
                                            readonly
                                            clearable
                                    ></v-text-field>
                                    <v-date-picker type="date" locale="ru" v-model="fundsFilterForm.date_end" scrollable>
                                        <v-spacer></v-spacer>
                                        <v-btn flat color="primary" @click="DatePickerFilterEnd=false">OK</v-btn>
                                    </v-date-picker>
                                </v-dialog>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-title>
            </v-card>
            <v-card>
                <v-card-title>
                    <v-content>
                        <div><b>Страница:</b> {{ fundsAllData.current_page }}</div>
                        <div><b>Число страниц:</b> {{ fundsAllData.last_page }}</div>
                        <div><b>На странице:</b> {{ dataTables.length }}</div>
                        <div><b>Всего элементов:</b> {{ fundsAllData.total }}</div>
                    </v-content>
                    <v-text-field
                            append-icon="search"
                            label="Поиск"
                            single-line
                            hide-details
                            v-model="search"
                    ></v-text-field>
                </v-card-title>
                <v-card-text>
                    Для корректных рассчетов, необходимо настроить фильтры, например по расходу и доходу, чтобы
                    система их не суммировала
                </v-card-text>
            </v-card>
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
                            <td class="text-xs-right">{{ convertRev(props.item.rev) }}</td>
                            <td class="text-xs-right">{{ props.item.category_name }}</td>
                            <td class="text-xs-right">{{ sumFormat(props.item.sum) }}</td>
                            <td class="text-xs-right">{{ props.item.cause }}</td>
                            <td class="text-xs-right">{{ props.item.date }}</td>
                            <td class="text-xs-right control">
                                <v-tooltip top>
                                    <v-btn flat icon large color="primary" slot="activator" @click="editFund(props.item)"><v-icon>mode_edit</v-icon></v-btn>
                                    <span>Редактировать</span>
                                </v-tooltip>
                                <v-tooltip top>
                                    <v-btn flat icon large color="error" slot="activator" @click="deleteFund(props.item)"><v-icon>delete</v-icon></v-btn>
                                    <span>Удалить</span>
                                </v-tooltip>

                            </td>
                        </tr>
                    </template>
                    <template slot="footer">
                        <tr>
                            <td colspan="4">
                                <strong>Итого:</strong>
                            </td>
                            <td class="text-xs-right">
                                <strong>{{ totalValue }}</strong>
                            </td>
                            <td></td><td></td><td></td>
                        </tr>
                    </template>
                </v-data-table>
                <div class="text-xs-center">
                    <v-pagination :length="fundsAllData.last_page" v-model="fundsFilterForm.page" :total-visible="4"></v-pagination>
                </div>
        </div>
        <v-layout row justify-center>
            <v-dialog v-model="fundsFormShow" persistent max-width="700px">
                <v-card>
                    <v-card-title>
                        <span class="headline">{{ fundsFormTitle }}</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm6 md4>
                                    <v-select
                                            :items="billsList"
                                            v-model="fundsFormData.bills_id"
                                            item-text="name"
                                            item-value="id"
                                            label="Выберите счёт"
                                            :rules="fundsFormData.billsRules"
                                            :disabled="billDisabled"
                                            autocomplete
                                            required
                                    ></v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-select
                                            :items="revList"
                                            v-model="fundsFormData.rev"
                                            item-text="name"
                                            item-value="value"
                                            label="Доход или расход"
                                            :rules="fundsFormData.revRules"
                                            autocomplete
                                            required
                                    ></v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-select
                                            :items="categoryList"
                                            v-model="fundsFormData.category_id"
                                            item-text="name"
                                            item-value="id"
                                            label="Категория"
                                            :rules="fundsFormData.categoryRules"
                                            autocomplete
                                            required
                                    ></v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-text-field v-model="fundsFormData.sum" :rules="fundsFormData.sumRules" label="Сумма" required></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-dialog
                                            ref="dialog"
                                            persistent
                                            v-model="DatePicker"
                                            lazy
                                            full-width
                                            width="290px"
                                    >
                                        <v-text-field
                                                slot="activator"
                                                label="Дата"
                                                hint="Дата, когда совершена транзакция"
                                                v-model="fundsFormData.date"
                                                prepend-icon="event"
                                                :rules="fundsFormData.dateRules"
                                                readonly
                                        ></v-text-field>
                                        <v-date-picker type="date" locale="ru" v-model="fundsFormData.date" scrollable>
                                            <v-spacer></v-spacer>
                                            <v-btn flat color="primary" @click="DatePicker=false">OK</v-btn>
                                        </v-date-picker>
                                    </v-dialog>
                                </v-flex>
                                <v-flex xs12>
                                    <v-text-field v-model="fundsFormData.cause" :rules="fundsFormData.causeRules" label="Причина" required></v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-container>
                        <small>*-поля обязательные для заполнения</small>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="info darken-1" flat @click.native="fundsFormShow = false">Закрыть</v-btn>
                        <v-btn color="success darken-1" flat @click="fundsSave()">Сохранить</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>
    </div>
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
            pagination: {'sortBy': 'date', 'descending': true, 'rowsPerPage': -1},
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
                { text: 'Причина', value: 'cause', align: 'right' },
                { text: 'Дата', value: 'date', align: 'right'},
                { text: 'Управление', value: '', align: 'right'},
            ],
            dataTables: [],

            fundsFormShow: false,
            fundsFormTitle: 'Форма редактирования',
            DatePicker: false,

            DatePickerFilterStart: false,
            DatePickerFilterEnd: false,

            fundsFormType: 'create',

            fundsAllData: {
                current_page: 0,
                total: 0,
                last_page: 0,

            },
            fundsFormData: {
                funds_id: 0,
                bills_id: 0,
                rev: 0,
                category_id: 0,
                sum: 0,
                cause: '',
                date: '',
                sumRules: [
                    v => !!v || 'Сумма обязательна к заполнению',
                    v => /^\d+$/.test(v) || 'Сумма должна быть целым числом'
                ],
                billsRules: [
                    v => !!v || 'Счет обязателен к заполнению',
                    v => /^\d+$/.test(v) || 'неверный идентификатор'
                ],
                revRules: [
                    v => !!v || 'Доход или Расход должен быть выбран',
                    v => /^\d+$/.test(v) || 'неверный идентификатор'
                ],
                categoryRules: [
                    v => !!v || 'Категория должена быть выбрана',
                    v => /^\d+$/.test(v) || 'неверный идентификатор'
                ],
                dateRules: [
                    v => !!v || 'Поле даты должно быть заполнено',
                ],
                causeRules: [
                    v => !!v || 'Поле причины должно быть заполнено',
                ],
            },
            billDisabled: false,


            fundsFilterForm: {
                rev: 0,
                bills_id: 0,
                category_id: 0,
                date_start: '',
                date_end: '',
                sum: 0,
                paginate: 20,
                page: 1,
            },

            billsList: [
                {
                    id: 0,
                    name: 'test',
                }
            ],

            revList: [
                {
                    value: 1,
                    name: 'Доход',
                },
                {
                    value: 2,
                    name: 'Расход',
                },
            ],

            categoryList: [
                {
                  id: 1,
                  name: 'Тестовая',
                },
            ],

        }),
        methods: {
            getFunds() {
                this.$store.commit('setPreloader', true);

                let data = this.fundsFilterForm;

                if(data.page == '') {
                    data.page = 1;
                }

                axios.get('/pa/funds-list', {params: data})
                    .then(response=> {
                        this.fundsAllData = response.data;
                        this.dataTables = response.data.data;

                        for(let key in this.dataTables) {
                            this.dataTables[key].date = this.dateFormat(this.dataTables[key].date)
                        }
                        this.$store.commit('setPreloader', false);
                    })
                    .catch(error => {
                        this.$store.commit('AlertError', error.message);
                    });

            },
            getBills() {
                axios.get('/pa/bills-list')
                    .then(response=> {

                        this.billsList = new Array();

                        for (let key in response.data) {
                            this.billsList.push({
                                id: response.data[key]['id'],
                                name: response.data[key]['name'],
                            })
                        }
                    })
                    .catch(error => {
                        this.$store.commit('AlertError', error.message);
                    });

            },
            getCategories() {
                this.$store.commit('setPreloader', true);
                axios.get('/pa/categories-list')
                    .then(response=> {
                        this.categoryList = response.data;
                        this.$store.commit('setPreloader', false);
                    })
                    .catch(error => {
                        this.$store.commit('AlertError', error.message);
                    });
            },
            convertRev(rev) {
                if(rev === 1) {
                    return 'Приход';
                }
                else if(rev === 2) {
                    return 'Расход';
                }
                console.error('convertRev input: ' + rev);
                return 'Ошибка';
            },
            fundsSave() {

                let url = '/pa/funds';
                let method = '';

                if(this.fundsFormType === 'create') {
                    method = 'post';
                }
                else if(this.fundsFormType === 'update') {
                    method = 'put';
                }
                else if(this.fundsFormType === 'delete') {
                    method = 'delete';
                }

                this.$store.commit('setPreloader', true);

                axios({
                    method: method,
                    url: url,
                    data: this.fundsFormData,
                })
                    .then(response=> {
                        if(response.data.status == 200) {
                            this.fundsFormShow = false;
                            this.getFunds();
                            this.$store.commit('setPreloader', false);
                        }
                        else {
                            console.log(response);
                        }

                    })
                    .catch(error => {
                        this.$store.commit('AlertError', error.message);
                    });
            },
            newFund() {

                this.getCategories();
                this.billDisabled = false;

                //clear form
                this.fundsFormData.bills_id = this.fundsFormData.rev = this.fundsFormData.category_id = this.fundsFormData.sum = 0;
                this.fundsFormData.cause;

                this.fundsFormData.date = this.getCurrentDate();

                this.fundsFormTitle = 'Новая транзакция';
                this.fundsFormShow = true;
                this.fundsFormType = 'create';

            },
            editFund(object) {

                this.getCategories();
                this.billDisabled = true;

                this.$store.commit('setPreloader', true);
                //form render data
                this.fundsFormData.category_id = object.category_id;
                this.fundsFormData.funds_id = object.id;
                this.fundsFormData.bills_id = object.bills_id;
                this.fundsFormData.cause = object.cause;
                this.fundsFormData.date = object.date;
                this.fundsFormData.rev = object.rev;
                this.fundsFormData.sum = object.sum;

                this.fundsFormType = 'update';
                this.fundsFormShow = true;
                this.$store.commit('setPreloader', false);

            },
            deleteFund(object) {

                let confirm = window.confirm('Вы действительно хотите удалить элемент?');

                if(confirm === true) {
                    this.fundsFormType = 'delete';
                    this.fundsFormData.funds_id = object.id;
                    this.fundsSave();
                    this.getFunds();
                }

            },
            sumFormat(sum) {
                return sum.toString().replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ');
            },
            fundFilter() {
                console.log(this.fundsFilterForm);
            },
            getCurrentDate() {

                let date = new Date();

                return this.dateFormat(date.toString());

            },
            dateFormat(date) {

                let dateInput = Date.parse(date);

                let dateObject = new Date(dateInput);

                let dateString = '';

                let dd = dateObject.getDate();
                if (dd < 10) dd = '0' + dd;

                let mm = dateObject.getMonth() + 1;
                if (mm < 10) mm = '0' + mm;

                dateString = dateObject.getFullYear()+'-'+mm+'-'+dd;

                return dateString;
            }
        },
        computed: {
            totalValue() {
                let total = 0;

                for(let key in this.dataTables) {
                    total += this.dataTables[key]['sum'];
                }

                return this.sumFormat(total);
            },
            slugData() {

            }

        },
        watch: {
            showFundsComponent: function (val) {
                this.getBills();
            },
            fundsFilterForm: {
                handler: function (val, oldVal) {
                    this.getFunds();
                    },
                deep: true,
            },
        },
        mounted() {
          this.getFunds();
          this.getBills();
          this.getCategories();
        },
    }
</script>

<style scoped>

</style>