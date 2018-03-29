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
            <!--<v-btn icon>
                <v-icon>more_vert</v-icon>
            </v-btn>!-->
        </v-toolbar>
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
            </v-card>
        </v-menu>
        <bills-control :showBillsTable="billsControl"></bills-control>
        <funds-control :showFundsComponent="fundsControl"></funds-control>
        <category-control :showCategoryComponent="categoryControl"></category-control>
    </v-app>
</template>

<script>
    import BillsControl from "./BillsControl";
    import FundsControl from "./FundsControl";
    import CategoryControl from "./CategoryControl";

    export default {
        name: "private-area",

        data: () => ({
            menuTitle: 'Личный кабинет',
            applicationMenu: false,

            //Control Applications
            billsControl: false, //bills-control
            fundsControl: false, //funds-control
            categoryControl: false, //category-control

        }),
        methods: {
            reload() {
                window.location.reload()
            },
            billsControlApplication() {
                this.menuTitle = 'Управление счетами';
                this.billsControl = true;
                this.applicationMenu=false;
                this.fundsControl = false;
                this.categoryControl = false;
            },
            fundsControlApplication() {
                this.menuTitle = 'Управление расходами';
                this.billsControl = false;
                this.applicationMenu=false;
                this.fundsControl = true;
                this.categoryControl = false;
            },
            categoryControlApplication() {
                this.menuTitle = 'Управление категориями';
                this.billsControl = false;
                this.applicationMenu=false;
                this.fundsControl = false;
                this.categoryControl = true;
            },


        },
        computed: {
          ListenPreloader() {
            return this.$store.getters.getPreloader;
          },
        },
        mounted() {
            this.fundsControlApplication();
        },
        components: {
            CategoryControl,
            FundsControl,
            BillsControl,
        },

    }
</script>

<style scoped>

</style>