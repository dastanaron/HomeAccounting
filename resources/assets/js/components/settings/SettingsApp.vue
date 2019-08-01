<template>
    <v-app id="inspire">
        <v-toolbar dark color="primary">
            <v-toolbar-title class="white--text">Настройки</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-tooltip bottom>
                <v-btn icon @click="reload()" slot="activator">
                    <v-icon>refresh</v-icon>
                </v-btn>
                <span>Обновить страницу</span>
            </v-tooltip>
            <v-tooltip bottom>
                <v-btn icon @click="goToPanel()" slot="activator">
                    <v-icon>input</v-icon>
                </v-btn>
                <span>Перйети в панель</span>
            </v-tooltip>
            <v-tooltip bottom>
                <v-btn icon @click="goToPrivateArea()" slot="activator">
                    <v-icon>exit_to_app</v-icon>
                </v-btn>
                <span>Вернуться в личный кабинет</span>
            </v-tooltip>
        </v-toolbar>
        <v-tabs
                v-model="active"
                color="primary"
                dark
                slider-color="green"
        >
            <v-tab
                v-for="(item, index) in settingTypes"
                :key="index"
            >
                <router-link class="router-link in-tabs" :to="{ name: item.routeName }" replace>{{ item.name }}</router-link>
            </v-tab>
            <v-tab-item
                    v-for="(item, index) in settingTypes"
                    :key="index"
            >
                <v-alert :type="alertGet.type" dismissible v-model="alertGet.show">
                    {{ alertGet.message }}
                </v-alert>
                <v-progress-linear indeterminate :active="ListenPreloader" height="3" color="primary"></v-progress-linear>
                <router-view></router-view>
            </v-tab-item>
        </v-tabs>
    </v-app>

    <!--
    example route
    <router-link :to="{ name: 'home' }">Home</router-link> |
    <router-link :to="{ name: 'hello' }">Hello World</router-link>
    !-->
</template>

<script>
    export default {
        name: "SettingsApp.vue",
        data: () => ({
            active: 0,
            settingTypes: [
                {
                    name: 'Начало',
                    routeName: 'Index'
                },
                {
                    name: 'Интеграция с Nalog.ru',
                    routeName: 'NalogIntegration'
                }
            ],
        }),
        methods: {
            goToPrivateArea() {
                window.location.href = '/pa';
            },
            reload() {
                window.location.reload()
            },
            goToPanel() {
                window.location.href = '/home';
            },
            testMethod() {
                return 'test';
            }
        },
        computed: {
            ListenPreloader() {
                return this.$store.getters.getPreloader;
            },
            alertGet () {
                return this.$store.getters.getAlert;
            },
        },
        mounted() {
        },
        watch: {
            '$route' (to, from) {
                this.$store.commit('setPreloader', true);
                this.$store.commit('setPreloader', false);
            },
            /*$route: () => {
                console.log('hello');
                this.$store.commit('setPreloader', true);
                const currentActiveTab = parseInt(this.active);
                this.active = (currentActiveTab < 2 ? currentActiveTab + 1 : 0)
            }*/
        }
    }
</script>

<style scoped>
    a.router-link {
        color: #fff;
        text-decoration: none;
    }
    .in-tabs {

    }
</style>