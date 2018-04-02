<template>
    <div id="moneyTransactionControl" v-if="showMoneyTransactionComponent">
        <v-dialog v-model="showMoneyTransactionComponent" persistent max-width="700px">
            <v-card>
                <v-card-title>
                    <span class="headline">Перевод между счетами</span>
                </v-card-title>
                <v-card-text>
                    <v-form v-model="valid" ref="form" lazy-validation>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm6>
                                    <v-select
                                            :items="billsList"
                                            v-model="billSource"
                                            item-text="name"
                                            item-value="id"
                                            label="Выберите счёт"
                                            hint="Счет с которого переводим"
                                            clearable
                                            autocomplete
                                    ></v-select>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <v-select
                                            :items="billsList"
                                            v-model="billDestination"
                                            item-text="name"
                                            item-value="id"
                                            label="Выберите счёт"
                                            hint="Счет на который переводим"
                                            clearable
                                            autocomplete
                                    ></v-select>
                                </v-flex>
                                <v-flex xs12>
                                    <v-text-field
                                            v-model="billSumTransaction"
                                            clearable
                                            label="Сумма"
                                            hint="Сумма перевода"
                                    ></v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="info darken-1" flat @click="close()">Закрыть</v-btn>
                    <v-btn color="success darken-1" flat @click="transactionSend()">Сохранить</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    export default {
        name: "MoneyTransaction",
        props: {
            showMoneyTransactionComponent: {
                type: Boolean,
                default: false,
            }
        },
        data: () => ({
            valid: true,
            billSource: {},
            billDestination: {},
            billSumTransaction: '',
            billsList: [
                {
                    id: 0,
                    name: 'test',
                }
            ],
        }),
        methods: {
            getBills() {
                axios.get('/pa/bills-list')
                    .then(response=> {

                        this.billsList = new Array();

                        for (let key in response.data) {
                            this.billsList.push({
                                id: response.data[key]['id'],
                                name: response.data[key]['name'],
                            })
                        }
                    })
                    .catch(error => {
                        this.$store.commit('AlertError', error.message);
                    });

            },
            transactionSend() {
                this.$store.commit('setPreloader', true);

                axios.post('/pa/bills/transfer', {
                    bill_source: this.billSource,
                    bill_destination: this.billDestination,
                    sum: this.billSumTransaction,
                })
                .then(response=> {
                    this.$store.commit('setAlert', {
                        type: 'success',
                        status: true,
                        message: 'Транзакция перевода успешна'
                    });
                    this.close();
                    this.$store.commit('setPreloader', false);
                })
                .catch(error => {
                    this.$store.commit('setAlert', {
                        type: 'error',
                        status: true,
                        message: 'Ошибка передачи транзакции, попробуйте еще раз. Ответ сервера: '+error.message,
                    });
                });

            },
            close() {
                this.$parent.$parent.$emit('closeMoneyTransaction');
            },
        },
        watch: {
        },
        mounted() {
          this.getBills();
        },
    }
</script>

<style scoped>

</style>