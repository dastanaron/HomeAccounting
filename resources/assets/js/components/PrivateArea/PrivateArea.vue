<template>
    <v-app id="inspire">
        <v-toolbar dark color="primary">
            <!--<v-tooltip bottom>
                <v-toolbar-side-icon slot="activator" @click="goToPanel()"></v-toolbar-side-icon>
                <span>Выйти в панель</span>
            </v-tooltip>!-->
            <v-toolbar-title class="white--text">{{ menuTitle }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-tooltip bottom>
                <v-btn icon @click="openDynamicAccumulate()" slot="activator">
                    <v-icon>show_chart</v-icon>
                </v-btn>
                <span>Динамика накоплений</span>
            </v-tooltip>
            <v-tooltip bottom>
                <v-btn icon @click="applicationMenu=true" slot="activator">
                    <v-icon>apps</v-icon>
                </v-btn>
                <span>Меню программ</span>
            </v-tooltip>
            <v-tooltip bottom>
                <v-btn icon @click="reload()" slot="activator">
                    <v-icon>refresh</v-icon>
                </v-btn>
                <span>Обновить страницу</span>
            </v-tooltip>
        </v-toolbar>
        <v-alert :type="alertGet.type" dismissible v-model="alertGet.show">
            {{ alertGet.message }}
        </v-alert>
        <v-progress-linear indeterminate :active="ListenPreloader" height="3" color="primary"></v-progress-linear>
        <v-menu
                transition="slide-x-transition"
                bottom
                right
                :close-on-content-click="false"
                :nudge-width="200"
                v-model="applicationMenu"
        >
            <v-card>
                <v-list>
                    <v-list-tile avatar  @click="fundsControlApplication()">
                        <v-list-tile-avatar>
                            <v-icon>account_balance</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>Транзакции</v-list-tile-title>
                            <v-list-tile-sub-title>Управление транзакциями приходов и расходов</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
                <v-divider></v-divider>
                <v-list>
                    <v-list-tile avatar  @click="billsControlApplication()">
                        <v-list-tile-avatar>
                            <v-icon>account_balance_wallet</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>Счета</v-list-tile-title>
                            <v-list-tile-sub-title>Управление счетами</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
                <v-list>
                    <v-list-tile avatar  @click="categoryControlApplication()">
                        <v-list-tile-avatar>
                            <v-icon>folder</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>Категории</v-list-tile-title>
                            <v-list-tile-sub-title>Управление категориями транзакций</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
                <v-list>
                    <v-list-tile avatar  @click="moneyTransactionControlControlApplication()">
                        <v-list-tile-avatar>
                            <v-icon>compare_arrows</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>Перевод средств</v-list-tile-title>
                            <v-list-tile-sub-title>Перевод средств между счетами</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
                <v-list>
                    <v-list-tile avatar  @click="eventsControlApplication()">
                        <v-list-tile-avatar>
                            <v-icon>notifications</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>Управление событиями</v-list-tile-title>
                            <v-list-tile-sub-title>Управление напоминаниями бота</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
                <v-list>
                    <v-list-tile avatar  @click="analyticsApplication()">
                        <v-list-tile-avatar>
                            <v-icon>show_chart</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>Аналитика</v-list-tile-title>
                            <v-list-tile-sub-title>Графики</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
                <v-list>
                    <v-list-tile avatar  @click="goToPanel()">
                        <v-list-tile-avatar>
                            <v-icon>dashboard</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>Перейти в панель</v-list-tile-title>
                            <v-list-tile-sub-title>Перейти в панель управления</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
            </v-card>
        </v-menu>
        <!--<v-dialog v-model="dynamicAccumulateChart" scrollable max-width="80%">
            <v-card>
                <v-card-title>
                    График динамики накоплений
                    <v-spacer></v-spacer>
                    <v-btn icon color="primary" flat @click="dynamicAccumulateChart = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <dynamic-accumulate-chart-component ref="dynamicAccumulateChart"></dynamic-accumulate-chart-component>
                </v-card-text>
                <v-card-actions>

                </v-card-actions>
            </v-card>
        </v-dialog>!-->

        <swipe ref="swipeComponents" :options="swipeOptions">
            <swipe-item><bills-control ref="bills"></bills-control></swipe-item>
            <swipe-item><funds-control ref="funds"></funds-control></swipe-item>
            <swipe-item><category-control ref="categories"></category-control></swipe-item>
            <swipe-item><events-control ref="events"></events-control></swipe-item>
            <swipe-item><analytics ref="analytics"></analytics></swipe-item>
        </swipe>
        <money-transaction :showMoneyTransactionComponent="moneyTransactionControl"></money-transaction>
        <barcode-scanner :showBarcodeScanner="barcodeScannerControl"></barcode-scanner>

    </v-app>
</template>
<script>
    import BillsControl from "../BillsControl/BillsControl";
    import FundsControl from "../FundsControl/FundsControl";
    import CategoryControl from "../CategoryControl/CategoryControl";
    import MoneyTransaction from "../MoneyTransaction/MoneyTransaction";
    import BarcodeScanner from "../BarcodeScanner/BarcodeScanner";
    import EventsControl from "../EventsControl/EventsControl";
    import Analytics from "../analytics/analytics";
    import {Storage, Broker} from '../../classes/QueueBroker/index';
    import DynamicAccumulateChartComponent from "../analytics/dynamic-accumulate-chart-component";
    import Currency from "../../classes/DB/models/Currency";

    export default {
        name: "private-area",

        data: () => ({
            menuTitle: 'Личный кабинет',
            applicationMenu: false,

            sliderPosition: 0,

            swipeOptions: {
                startSlide: 1,
                speed: 300,
                auto: 0,
                slide: 0,
                continuous: true,
                disableScroll: false,
                stopPropagation: false,
                callback: function (index, slide) { this.slide = index },
                transitionEnd: function (index, slide) {  }
            },

            //Control Applications
            moneyTransactionControl: false, //money-transaction
            barcodeScannerControl: false, //barcode-scanner-control

            reloadBills: false,

            dynamicAccumulateChart: false,

        }),
        methods: {
            openDynamicAccumulate() {
                this.dynamicAccumulateChart = true;
            },
            reload() {
                window.location.reload()
            },
            swiperCallback(index) {

                switch (index) {
                    case 0:
                        this.menuTitle = 'Счета';
                        this.$refs.bills.getBills();
                        break;
                    case 1:
                        this.menuTitle = 'Транзакции';
                        this.$refs.funds.getFunds();
                        break;
                    case 2:
                        this.menuTitle = 'Категории';
                        this.$refs.categories.getCategories();
                        break;
                    case 3:
                        this.menuTitle = 'Напоминания';
                        this.$refs.events.getEvents();
                        break;
                    case 4:
                        this.menuTitle = "Аналитика";
                        break;

                }
            },
            billsControlApplication() {
                this.menuTitle = 'Счета';
                this.$refs.swipeComponents.slide(0);
                this.applicationMenu=false;
                this.$refs.bills.getBills();
            },
            fundsControlApplication() {
                this.menuTitle = 'Транзакции';
                this.$refs.swipeComponents.slide(1);
                this.applicationMenu=false;
                this.$refs.funds.getFunds();
            },
            categoryControlApplication() {
                this.menuTitle = 'Категории';
                this.$refs.swipeComponents.slide(2);
                this.applicationMenu=false;
                this.$refs.categories.getCategories();
            },
            barcodeControl() {
                this.menuTitle = 'Сканировать чек';
                this.billsControl = false;
                this.applicationMenu=false;
                this.fundsControl = false;
                this.moneyTransactionControl = false;
                this.barcodeScannerControl = false;
                this.categoryControl = false;
                this.eventsControl = false;
                this.barcodeScannerControl = true;
            },
            eventsControlApplication() {
                this.menuTitle = 'Напоминания';
                this.$refs.swipeComponents.slide(3);
                this.applicationMenu=false;
                this.$refs.events.getEvents();
            },
            analyticsApplication() {
                this.menuTitle = 'Аналитика';
                this.$refs.swipeComponents.slide(4);
                this.applicationMenu=false;
            },
            moneyTransactionControlControlApplication() {
                this.applicationMenu=false;
                this.moneyTransactionControl = true;
            },
            goToPanel() {
              window.location.href = '/home';
            },
        },
        computed: {
            ListenPreloader() {
                return this.$store.getters.getPreloader;
            },
            alertGet () {
                return this.$store.getters.getAlert;
            },
        },
        watch: {
            sliderPosition() {
                let index = this.$refs.swipeComponents.getPos();
                this.swiperCallback(this.sliderPosition);
            },
            swipeOptions: {
                handler: function (val, oldVal) {
                    this.swiperCallback(val.slide);
                },
                deep: true,
            },
        },
        mounted() {
            this.$on('closeMoneyTransaction', function () {
                this.moneyTransactionControl = false;
                this.billsControlApplication();
                this.$refs.bills.getBills();
            });

            const StorageObject = new Storage();

            const currentDateStamp = new Date().getTime();

            const tommorowDateStamp = currentDateStamp + (86400*1000);

            let currencyGetStamp = parseInt(StorageObject.get('currentCurrency'));

            if (StorageObject.get('currentCurrency') === null || currencyGetStamp >= tommorowDateStamp) {

                StorageObject.add('currentCurrency', currentDateStamp);

                const CurrencyModel = new Currency();

                CurrencyModel.fillTable();
            }


        },
        components: {
            DynamicAccumulateChartComponent,
            Analytics,
            EventsControl,
            BarcodeScanner,
            MoneyTransaction,
            CategoryControl,
            FundsControl,
            BillsControl,
        },

    }
</script>

<style scoped>

</style>