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
            </v-card>
        </v-menu>
        <div v-touch:swipe="swipe">
            <bills-control :showBillsTable="billsControl"></bills-control>
            <funds-control :showFundsComponent="fundsControl"></funds-control>
            <category-control :showCategoryComponent="categoryControl"></category-control>
            <money-transaction :showMoneyTransactionComponent="moneyTransactionControl"></money-transaction>
            <barcode-scanner :showBarcodeScanner="barcodeScannerControl"></barcode-scanner>
            <events-control :showEventControl="eventsControl"></events-control>
        </div>
    </v-app>
</template>
<script>
    import BillsControl from "../BillsControl/BillsControl";
    import FundsControl from "../FundsControl/FundsControl";
    import CategoryControl from "../CategoryControl/CategoryControl";
    import MoneyTransaction from "../MoneyTransaction/MoneyTransaction";
    import BarcodeScanner from "../BarcodeScanner/BarcodeScanner";
    import EventsControl from "../EventsControl/EventsControl";

    export default {
        name: "private-area",

        data: () => ({
            menuTitle: 'Личный кабинет',
            applicationMenu: false,

            //Control Applications
            billsControl: false, //bills-control
            fundsControl: false, //funds-control
            categoryControl: false, //category-control
            moneyTransactionControl: false, //money-transaction
            barcodeScannerControl: false, //barcode-scanner-control
            eventsControl: false, //events-control

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
                this.eventsControl = false;
                this.billsControl = true;
            },
            fundsControlApplication() {
                this.menuTitle = 'Управление расходами';
                this.billsControl = false;
                this.applicationMenu=false;
                this.moneyTransactionControl = false;
                this.categoryControl = false;
                this.barcodeScannerControl = false;
                this.eventsControl = false;
                this.fundsControl = true;
            },
            categoryControlApplication() {
                this.menuTitle = 'Управление категориями';
                this.billsControl = false;
                this.applicationMenu=false;
                this.fundsControl = false;
                this.moneyTransactionControl = false;
                this.barcodeScannerControl = false;
                this.eventsControl = false;
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
                this.eventsControl = false;
                this.barcodeScannerControl = true;
            },
            eventsControlApplication() {
                this.menuTitle = 'Управление напоминаниями';
                this.billsControl = false;
                this.applicationMenu=false;
                this.fundsControl = false;
                this.moneyTransactionControl = false;
                this.barcodeScannerControl = false;
                this.categoryControl = false;
                this.barcodeScannerControl = false;
                this.eventsControl = true;
            },
            moneyTransactionControlControlApplication() {
                this.applicationMenu=false;
                this.moneyTransactionControl = true;
            },
            goToPanel() {
              window.location.href = '/home';
            },
            swipe (direction) {

                if(this.billsControl === true) {
                    if(direction === 'left') {
                        this.fundsControlApplication();
                    }
                    else if(direction === 'right') {
                        this.eventsControlApplication();
                    }
                }

                else if(this.fundsControl === true) {
                    if(direction === 'left') {
                        this.categoryControlApplication();
                    }
                    else if(direction === 'right') {
                        this.billsControlApplication();
                    }
                }

                else if(this.categoryControl === true) {
                    if(direction === 'left') {
                        this.eventsControlApplication();
                    }
                    else if(direction === 'right') {
                        this.fundsControlApplication();
                    }
                }

                else if(this.eventsControl === true) {
                    if(direction === 'left') {
                        this.billsControlApplication();
                    }
                    else if(direction === 'right') {
                        this.categoryControlApplication();
                    }
                }
                
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