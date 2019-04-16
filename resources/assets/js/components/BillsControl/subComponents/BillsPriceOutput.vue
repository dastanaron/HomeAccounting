<template>
    <span class="sum">
        <span>{{ inputValue }}</span>
        &nbsp;
        <span class="converted" v-if="convertedValue">
            ({{ convertedValue }} <span>RUB</span>)
        </span>
    </span>
</template>

<script>
    import Currency from '../../../classes/DB/models/Currency';

    export default {
        name: "bills-price-output",
        props: {
            sum: {
                type: Number,
                default: 0,
            },
            currencyCode: {
                type: Number,
                default: 0,
            },
        },

        data: () => ({
            currencyInfo: {},
        }),
        methods: {
            getCurrencyInfo() {
                let currency = new Currency();
                let currencyInfo = currency.getCurrency(this.currencyCode);

                currencyInfo.then((result) => {
                    this.currencyInfo = result;
                });

            },
            sumFormat(sum) {
                let number =  sum.toString().replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ');
                return number.replace(/\./, ',');
            },
        },
        computed: {
            convertedValue() {

                const defaultCurrency = this.$store.getters.getDefaultCurrency;

                if(this.currencyInfo !== '' && this.currencyCode !== defaultCurrency) {
                    let sum = this.currencyInfo.value / this.currencyInfo.nominal * this.sum;
                    let rounded = Math.ceil((sum)*100)/100;
                    return this.sumFormat(rounded)
                }
                else {
                    return 0;
                }
            },
            inputValue() {
                return this.sumFormat(this.sum);
            },
        },
        watch: {
            currencyCode() {
                this.getCurrencyInfo();
            }
        },
        mounted() {
            this.getCurrencyInfo();
        }
    }
</script>

<style scoped>
    span.converted {
        font-size: 0.9em;
        color: rgba(125, 116, 116, 0.87);
    }

    span.converted > span {
        text-transform: uppercase;
        font-weight: bold;
        color: rgba(106, 226, 66, 0.87);
    }
</style>