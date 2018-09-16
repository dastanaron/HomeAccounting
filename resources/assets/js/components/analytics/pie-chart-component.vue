<template>
    <div class="pie-chart" v-show="visibility"></div>
</template>

<script>
    import Chart from 'c3';

    /**
     * todo: придумать генерацию уникальных классов для вывода графика
     * или на крайний случай передавать какой-то уникальный идентификатор с родителя
     * Как сейчас вроде все работает нормально
     */
    export default {
        name: "pie-chart-component",
        props: {
            data: {
                default: {},
            },
            visibility: {
                type: Boolean,
                default: true,
            },
        },
        data: () => ({

        }),
        methods: {
            chartGenerate() {
                Chart.generate({
                    bindto: '.pie-chart',
                    data: {
                        // iris data from R
                        columns: this.data,
                        type : 'pie',
                        /*onclick: function (d, i) { console.log("onclick", d, i); },
                        onmouseover: function (d, i) { console.log("onmouseover", d, i); },
                        onmouseout: function (d, i) { console.log("onmouseout", d, i); }*/
                    }
                });
            }
        },
        mounted() {

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
