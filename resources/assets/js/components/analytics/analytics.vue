<template>
    <div class="my-4">
        <div id="chart"></div>
    </div>
</template>
<script>
    import Chart from 'c3';

    export default {
        name: "analytics",

        data: () => ({

        }),
        methods: {
            getData() {
                //Получаем данные с сервера
            },
        },
        computed: {

        },
        watch: {

        },
        mounted() {
            Chart.generate({
                data: {
                    x: 'x',
                    columns: [
                        ['x', '2013-01-01', '2013-01-02', '2013-01-03', '2013-01-04', '2013-01-05', '2013-01-06'],
                        ['data1', 30, 200, 100, 400, 150, 250],
                        ['data2', 130, 340, 200, 500, 250, 350],
                        ['data3', 130, 340, 200, 500, 250, 350],
                    ]
                },
                axis: {
                    x: {
                        type: 'timeseries',
                        tick: {
                            format: '%d-%m-%Y'
                        }
                    }
                }
            });
        },
        components: {

        },

    }
</script>

<style scoped>

</style>