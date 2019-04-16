
import template from './template.html';
import Currency from '../../classes/DB/models/Currency';
import BillsCurrencyName from './subComponents/BillsCurrencyName';
import BillsPriceOutput from "./subComponents/BillsPriceOutput";
import { find } from 'lodash';


export default {
    name: "bills-control",
    template: template,
    props: {
        showBillsTable: {
            type: Boolean,
            default: true,
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
            currency: '',
            comment: '',
            sumRules: [
                v => !!v || 'Сумма обязательна к заполнению',
                v => /^\d+[\,]?\d*$/.test(v) || 'Сумма должна быть числом вида (100 или 100,25)'
            ]
        },

        currenciesList: [
            {
                name: "Российский рубль",
                num_code: 643,
            }
        ],

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
            { text: 'Валюта', value: 'currency', align: 'right'},
            { text: 'Окончание программы', value: 'deadline', align: 'right' },
            { text: 'Комментарий', value: 'comment', align: 'right' },
            { text: 'Управление', value: '', align: 'right'},
        ],
        dataTables: [

        ],
        calcTotalSum: 0,
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
        getCurrency() {
            let currency = new Currency();

            currency.getCurrencies().then( (result) => {
                this.currenciesList =[];

                for(let key in result) {
                    this.currenciesList[key] = result[key];
                }
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
                        currency: this.billFormData.currency,
                        comment: this.billFormData.comment,
                    }
            })
                .then(response=> {
                    if(response.data.status == 200) {
                        this.billsFormShow = false;
                        this.getBills();
                    }
                    else {
                        this.$store.commit('AlertError', 'произошла ошибка');
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
            this.billFormData.sum = object.sum.toString().replace(/\./gi, ',');
            this.billFormData.deadline = object.deadline;
            this.billFormData.currency = object.currency;
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
            let number =  sum.toString().replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ');
            return number.replace(/\./, ',');
        },
        isMobile() {
            let mobile_getter = this.$store.getters.mobile;

            let mobile = false;

            if(mobile_getter !== null) {
                mobile = true;
            }
            return mobile;
        },
        calculateTotalSum() {
            this.calcTotalSum = 0;

            let otherCurrency = [];

            const defaultCurrency = this.$store.getters.getDefaultCurrency;

            for(let key in this.dataTables) {
                if(this.dataTables[key]['currency'] === defaultCurrency) {
                    this.calcTotalSum += this.dataTables[key]['sum'];
                }
                else {
                    otherCurrency.push(this.dataTables[key]);
                }
            }

            if(otherCurrency.length > 0) {
                let currency = new Currency();

                for (let key in otherCurrency) {
                    let currencyInfo = currency.getCurrency(otherCurrency[key]['currency']);
                    currencyInfo.then((result) => {
                        let sum = result.value / result.nominal * otherCurrency[key]['sum'];
                        let rounded = Math.ceil((sum)*100)/100;
                        this.calcTotalSum += rounded;
                    });
                }
            }
        },
    },
    computed: {
        totalValue() {
            return this.sumFormat(this.calcTotalSum);
        },
    },
    watch: {
        showBillsTable: function (val) {
            this.getBills();
        },
        dataTables: function(val) {
            this.calculateTotalSum();
        }
    },
    created() {
        this.getBills();
        this.getCurrency();
    },
    components: {
        BillsCurrencyName,
        BillsPriceOutput
    },

}