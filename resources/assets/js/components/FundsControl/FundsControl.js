
import template from './template.html';

export default {
    name: "funds-control",
    template: template,
    props: {
        showFundsComponent: {
            type: Boolean,
            default: true,
        },
    },
    data: () => ({
        search: '',
        loadingDataTable: false,
        filters: true,
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
        totalValue: 0,
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
            rev: 2,
            bills_id: 0,
            category_id: 0,
            date_start: '',
            date_end: '',
            sum: null,
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
                    this.fundsAllData = response.data.paginate;
                    this.dataTables = response.data.paginate.data;
                    this.totalValue = response.data.totalSum;

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
        colorCategory(rev) {
            if(rev === 1) {
                return 'success';
            }
            else if(rev === 2) {
                return 'error';
            }
            console.error('colorCategory input: ' + rev);
            return 'warning';
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
            this.fundsFormData.cause = '';

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
        },
        filtersControl() {
            if(this.filters === true) {
                this.filters = false;
            }
            else {
                this.filters = true;
            }
        },
        isMobile() {
            let mobile_getter = this.$store.getters.mobile;

            let mobile = false;

            if(mobile_getter !== null) {
                mobile = true;
            }
            return mobile;
        },
        mobileFilterDefaultDisable() {
            this.filters = !this.isMobile();
        },
    },
    computed: {
        paginateValue() {
            let total = 0;

            for(let key in this.dataTables) {
                total += this.dataTables[key]['sum'];
            }

            return this.sumFormat(total);
        },
        slugData() {

        },

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
        this.mobileFilterDefaultDisable()
    },
}