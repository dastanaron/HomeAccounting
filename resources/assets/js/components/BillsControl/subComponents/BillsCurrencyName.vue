<template>
    <div>
        <span>{{ currencyName }}</span>
    </div>
</template>

<script>
    import Currency from '../../../classes/DB/models/Currency';

    export default {
        name: "bills-currency-name",

        props: {
            currencyCode: {
                type: Number,
                default: 0,
            },
        },

        data: () => ({
            currencyName: '',
        }),
        methods: {
            getCurrencyName() {
                let currency = new Currency();
                let currencyInfo = currency.getCurrency(this.currencyCode);

                currencyInfo.then((result) => {
                    this.currencyName = result.char_code;
                })

            },
        },
        mounted() {
            this.getCurrencyName();
        },
        watch: {
            currencyCode() {
                this.getCurrencyName();
            },
        }
    }
</script>

<style scoped>

</style>