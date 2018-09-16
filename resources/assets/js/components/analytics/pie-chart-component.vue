<template>
    <div class="chart-block">
        <div class="bar-chart" v-show="visibility"></div>
        <div class="pie-chart" v-show="visibility"></div>
    </div>
</template>

<script>
    import Chart from 'c3';
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

                Chart.generate({
                    bindto: '.bar-chart',
                    data: {
                        columns: this.data,
                        type: 'bar'
                    },
                    bar: {
                        width: {
                            ratio: 1 // this makes bar width 50% of length between ticks
                        }
                        // or
                        //width: 100 // this makes bar width 100px
                    }
                });

            }
        },
        mounted() {

        },
        computed: {

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
