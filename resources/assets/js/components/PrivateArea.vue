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
        <v-dialog v-model="applicationMenu" max-width="50%">
            <v-card>
                <v-card-title>
                    <span class="application-menu-title">Меню программ</span>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" flat @click.stop="applicationMenu=false"><i class="fas fa-times"></i></v-btn>
                </v-card-title>
                <v-container>
                    <v-layout row fill-height>
                        <v-flex xs12 md4>
                            <div class="application-menu-element" @click="billsControlApplication()">
                                <div class="application-menu-element-logo">
                                    <i class="fas fa-money-bill-alt"></i>
                                </div>
                                <div class="application-menu-element-text">
                                    Счета
                                </div>
                            </div>
                        </v-flex>
                        <v-flex xs12 md4>
                            <div class="application-menu-element">
                                <div class="application-menu-element-logo">
                                    <i class="fab fa-gg-circle"></i>
                                </div>
                                <div class="application-menu-element-text">
                                    Приход/Расход
                                </div>
                            </div>
                        </v-flex>
                        <v-flex xs12 md4>
                            <div class="application-menu-element">
                                <div class="application-menu-element-logo">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="application-menu-element-text">
                                    Перемещение средств
                                </div>
                            </div>
                        </v-flex >
                        <v-flex xs12 md4>
                            <div class="application-menu-element">
                                <div class="application-menu-element-logo">
                                    <i class="fas fa-th-list"></i>
                                </div>
                                <div class="application-menu-element-text">
                                    Редактор статей движения средств
                                </div>
                            </div>
                        </v-flex>
                        <v-flex xs12 md4>
                            <div class="application-menu-element">
                                <div class="application-menu-element-logo">
                                    <i class="fas fa-bell"></i>
                                </div>
                                <div class="application-menu-element-text">
                                    Напоминания
                                </div>
                            </div>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card>
        </v-dialog>
        <bills-control :showBillsTable="billsControl"></bills-control>
    </v-app>
</template>

<script>
    import BillsControl from "./BillsControl";

    export default {
        name: "private-area",

        data: () => ({
            menuTitle: 'Личный кабинет',
            applicationMenu: false,

            //Control Applications
            billsControl: true, //bills-control

        }),
        methods: {
            reload() {
                window.location.reload()
            },
            billsControlApplication() {
                this.billsControl = true;
                this.applicationMenu=false
            }


        },
        mounted() {

        },
        components: {
            BillsControl,
        },

    }
</script>

<style scoped>

</style>