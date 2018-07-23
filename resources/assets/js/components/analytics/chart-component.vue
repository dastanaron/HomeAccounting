<template>
    <div class="chart" v-show="visibility"></div>
</template>

<script>
    import Chart from 'c3';

    /**
     * todo: придумать генерацию уникальных классов для вывода графика
     * или на крайний случай передавать какой-то уникальный идентификатор с родителя
     * Как сейчас вроде все работает нормально
     */
    export default {
        name: "chart-component",
        props: {
            data: {
                type: Array,
                default: {},
            },
            visibility: {
                type: Boolean,
                default: true,
            },
            chartType: {
                type: String,
                default: 'timeseries',
            }
        },
        data: () => ({

        }),
        methods: {
            chartGenerate() {
                Chart.generate({
                    bindto: '.chart',
                    data: {
                        x: 'x',
                        columns: this.data,
                    },
                    axis: {
                        x: {
                            type: this.chartType,
                            tick: {
                                format: '%d-%m-%Y'
                            }
                        }
                    }
                });
            }
        },
        mounted() {
            console.log(this.id);
        },
        computed: {
            id() {
                return 'test';
            },
        },
        watch: {
            data() {
                this.chartGenerate();
            }
        }
    }
</script>

<style scoped>

</style>