<template>
    <div class="my-4">

        <v-card>
            <v-card-title>
                <h2>Фильтры</h2>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-flex xs12 sm4 md2>
                            <v-autocomplete
                                    :items="revList"
                                    v-model="filterForm.rev"
                                    item-text="name"
                                    item-value="value"
                                    label="Доход или расход"
                                    clearable
                            ></v-autocomplete>
                        </v-flex>
                        <v-flex xs12 sm4 md2>
                            <v-dialog
                                    ref="dialog"
                                    persistent
                                    v-model="DatePickerFilterStart"
                                    lazy
                                    full-width
                                    width="290px"
                            >
                                <v-text-field
                                        slot="activator"
                                        label="Дата от"
                                        v-model="filterForm.dateStart"
                                        prepend-icon="event"
                                        readonly
                                        clearable
                                ></v-text-field>
                                <v-date-picker type="date" locale="ru" v-model="filterForm.dateStart" scrollable>
                                    <v-spacer></v-spacer>
                                    <v-btn flat color="primary" @click="DatePickerFilterStart=false">OK</v-btn>
                                </v-date-picker>
                            </v-dialog>
                        </v-flex>
                        <v-flex xs12 sm4 md2>
                            <v-dialog
                                    ref="dialog"
                                    persistent
                                    v-model="DatePickerFilterEnd"
                                    lazy
                                    full-width
                                    width="290px"
                            >
                                <v-text-field
                                        slot="activator"
                                        label="Дата до"
                                        v-model="filterForm.dateEnd"
                                        prepend-icon="event"
                                        readonly
                                        clearable
                                ></v-text-field>
                                <v-date-picker type="date" locale="ru" v-model="filterForm.dateEnd" scrollable>
                                    <v-spacer></v-spacer>
                                    <v-btn flat color="primary" @click="DatePickerFilterEnd=false">OK</v-btn>
                                </v-date-picker>
                            </v-dialog>
                        </v-flex>
                        <v-flex xs12 sm4 md2>
                            <v-btn color="primary" @click="createValidator()" :disabled="requestButtonDisabled">Запросить</v-btn>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card-title>
        </v-card>
        <v-card>
            <chart-component
                    ref="chartLineCategories"
                    :data="chartLineCategoriesData"
                    :visibility="visibilityCharts.chartLineCategories">
            </chart-component>
        </v-card>
        <v-card>
            <v-card-text>
                Выберите даты и тип, после нажмите кнопку запросить. Система будет подготавливать данные. Как только они будут готовы,
                система автоматически нарисует график. Для удобства можно щелкать по категориям, чтобы отфильтровать данные на графике.
            </v-card-text>
        </v-card>

    </div>
</template>
<script>
    import axios from "axios";
    import ChartComponent from "./chart-component";

    export default {
        name: "analytics",

        data: () => ({
            controlSum: '',

            visibilityCharts: {
                chartLineCategories: true,
            },

            chartLineCategoriesData: [],

            DatePickerFilterStart: false,
            DatePickerFilterEnd: false,

            filterForm: {
                rev: 2,
                dateStart: '',
                dateEnd: '',
            },

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

            interval: null,
            intervalCounter: 10,
            incrementIntervalCounter: 0,

            requestButtonDisabled: false,

        }),
        methods: {
            //Получает готовый json для графика, выключает интервал, прелоадер и разблокирует кнопку
            getData() {

                axios.post('/analytics/get-chart-data', {
                    control_sum: this.controlSum,
                })
                    .then(response=> {

                        this.incrementIntervalCounter++;

                        if(this.incrementIntervalCounter > this.intervalCounter)
                        {
                            clearInterval(this.interval);

                            this.$store.commit('setPreloader', false);
                            this.requestButtonDisabled = false;
                            this.$store.commit('setAlert', {type: 'warning', status: true, message: 'сервер не отвечает'})
                        }

                        if(response.data.status !== 400) {
                            this.chartLineCategoriesData = response.data;
                        }
                        else {
                            this.$store.commit('setAlert', {type: 'warning', status: true, message: 'Не найдены данные по выбранным параметрам'})
                        }

                        if(response.data.status == 'try_again')
                        {
                            return ;
                        }

                        clearInterval(this.interval);

                        this.$store.commit('setPreloader', false);
                        this.requestButtonDisabled = false;

                    })
                    .catch(error => {
                        clearInterval(this.interval);
                    });
            },
            //Если проходит валидацию, дисейблим кнопу и запускаем предзагрузчик
            createValidator() {
                if (this.filterForm.dateStart !== '' && this.filterForm.dateEnd !== '' && this.filterForm !== '') {
                    this.requestButtonDisabled = true;
                    this.$store.commit('setPreloader');
                    this.createGraph();
                }
                else {
                    this.$store.commit('setAlert', {type: 'error', status: true, message: 'Не заполнены обязательные поля'})
                }
            },
            //Кидает запрос, там работает через очередь, вклчает интервал ожидания ответа
            createGraph() {
                axios.post('/analytics/create', {
                    date_start: this.filterForm.dateStart + ' 00:00:00',
                    date_end: this.filterForm.dateEnd + ' 23:59:59',
                    rev: this.filterForm.rev,
                })
                    .then(response=> {

                        if(response.data.status === 200) {
                            this.controlSum = response.data.controlSum;
                            this.interval = setInterval(() => {
                                this.getData();
                            }, 2000);
                        }


                    })
                    .catch(error => {
                        console.error(error)
                    });
            },

        },
        computed: {

        },
        watch: {

        },
        mounted() {

        },
        components: {
            ChartComponent
        },

    }
</script>

<style scoped>

</style>