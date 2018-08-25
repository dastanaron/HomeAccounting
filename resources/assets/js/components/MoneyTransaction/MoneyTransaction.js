
import template from './template.html';

export default {
    name: "MoneyTransaction",
    template: template,
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