
import BillsControl from "../BillsControl/BillsControl";
import FundsControl from "../FundsControl/FundsControl";
import CategoryControl from "../CategoryControl/CategoryControl";
import MoneyTransaction from "../MoneyTransaction/MoneyTransaction";
import BarcodeScanner from "../BarcodeScanner/BarcodeScanner";

import template from './template.html';

export default {
    name: "private-area",
    template: template,
    data: () => ({
        menuTitle: 'Личный кабинет',
        applicationMenu: false,

        //Control Applications
        billsControl: false, //bills-control
        fundsControl: false, //funds-control
        categoryControl: false, //category-control
        moneyTransactionControl: false, //money-transaction
        barcodeScannerControl: false, //barcode-scaner-control

        reloadBills: false,

    }),
    methods: {
        reload() {
            window.location.reload()
        },
        billsControlApplication() {
            this.menuTitle = 'Управление счетами';
            this.applicationMenu=false;
            this.fundsControl = false;
            this.categoryControl = false;
            this.moneyTransactionControl = false;
            this.barcodeScannerControl = false;
            this.billsControl = true;
        },
        fundsControlApplication() {
            this.menuTitle = 'Управление расходами';
            this.billsControl = false;
            this.applicationMenu=false;
            this.moneyTransactionControl = false;
            this.categoryControl = false;
            this.barcodeScannerControl = false;
            this.fundsControl = true;
        },
        categoryControlApplication() {
            this.menuTitle = 'Управление категориями';
            this.billsControl = false;
            this.applicationMenu=false;
            this.fundsControl = false;
            this.moneyTransactionControl = false;
            this.barcodeScannerControl = false;
            this.categoryControl = true;
        },
        barcodeControl() {
            this.menuTitle = 'Сканировать чек';
            this.billsControl = false;
            this.applicationMenu=false;
            this.fundsControl = false;
            this.moneyTransactionControl = false;
            this.barcodeScannerControl = false;
            this.categoryControl = false;
            this.barcodeScannerControl = true;
        },
        moneyTransactionControlControlApplication() {
            this.applicationMenu=false;
            this.moneyTransactionControl = true;
        }


    },
    computed: {
        ListenPreloader() {
            return this.$store.getters.getPreloader;
        },
        alertGet () {
            return this.$store.getters.getAlert;
        }
    },
    mounted() {
        this.fundsControlApplication();
        this.$on('closeMoneyTransaction', function () {
            this.billsControlApplication();
            this.$children[0].$children.find(child => { return child.$options.name === "bills-control"; }).$emit('reloadTable');
        });
    },
    components: {
        BarcodeScanner,
        MoneyTransaction,
        CategoryControl,
        FundsControl,
        BillsControl,
    },

}