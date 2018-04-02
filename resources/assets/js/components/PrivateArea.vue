<template>
    <v-app id="inspire">
        <v-toolbar dark color="primary">
            <!--<v-toolbar-side-icon></v-toolbar-side-icon>!-->
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
            </v-card>
        </v-menu>
        <bills-control :showBillsTable="billsControl"></bills-control>
        <funds-control :showFundsComponent="fundsControl"></funds-control>
        <category-control :showCategoryComponent="categoryControl"></category-control>
        <money-transaction :showMoneyTransactionComponent="moneyTransactionControl"></money-transaction>
    </v-app>
</template>

<script>
    import BillsControl from "./BillsControl";
    import FundsControl from "./FundsControl";
    import CategoryControl from "./CategoryControl";
    import MoneyTransaction from "./MoneyTransaction";

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
                this.billsControl = true;
            },
            fundsControlApplication() {
                this.menuTitle = 'Управление расходами';
                this.billsControl = false;
                this.applicationMenu=false;
                this.moneyTransactionControl = false;
                this.categoryControl = false;
                this.fundsControl = true;
            },
            categoryControlApplication() {
                this.menuTitle = 'Управление категориями';
                this.billsControl = false;
                this.applicationMenu=false;
                this.fundsControl = false;
                this.moneyTransactionControl = false;
                this.categoryControl = true;
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
            MoneyTransaction,
            CategoryControl,
            FundsControl,
            BillsControl,
        },

    }
</script>

<style scoped>

</style>