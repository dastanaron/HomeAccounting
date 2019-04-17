<template>
    <v-app id="inspire">
        <v-toolbar dark color="primary">
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
                    <v-list-tile avatar  @click="">
                        <v-list-tile-avatar>
                            <v-icon>account_balance</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>Первый пункт</v-list-tile-title>
                            <v-list-tile-sub-title>Описание</v-list-tile-sub-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
                <v-divider></v-divider>
            </v-card>
        </v-menu>
        <template>
            Здесь будет область для блокнотиков
        </template>
    </v-app>
</template>

<script>
    export default {
        name: "Organizer",
        data: () => ({
            menuTitle: 'Органайзер',
            applicationMenu: false,
        }),
        computed: {
            ListenPreloader() {
                return this.$store.getters.getPreloader;
            },
            alertGet () {
                return this.$store.getters.getAlert;
            },
        },
    }
</script>

<style scoped>

</style>