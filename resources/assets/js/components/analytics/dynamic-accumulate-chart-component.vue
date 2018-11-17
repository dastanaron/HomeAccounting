<template>
    <div>
        <div id="accumulateChart"></div>
        <v-btn icon color="primary" @click="getData()">
            <v-icon>refresh</v-icon>
        </v-btn>
    </div>

</template>

<script>
    import Chart from 'c3';

    export default {
        name: "dynamic-accumulate-chart-component",
        data: () => ({
            months: {},
            sum: {},
        }),
        methods: {
            getData() {
                this.$store.commit('setPreloader', true);

                axios.get('/analitycs/dynamic-accumulate')
                    .then(response=> {
                        this.months = response.data[0];
                        this.sum = response.data[1];

                        this.$store.commit('setPreloader', false);

                    })
                    .catch(error => {
                        this.$store.commit('AlertError', error.message);
                        this.$store.commit('setPreloader', false);
                    });
            },
            chartGenerate() {
                Chart.generate({
                    bindto: '#accumulateChart',
                    data: {
                        columns: [
                            this.sum
                        ]
                    },
                    axis: {
                        x: {
                            label: "Месяцы",
                            type: 'category',
                            categories: this.months,
                        },
                        y: {
                            label: 'Сумма',
                        }
                    }
                });
            }
        },
        mounted() {
            this.getData();
        },
        computed: {

        },
        watch: {
            months() {
                this.chartGenerate();
            }
        }
    }
</script>

<style scoped>

</style>