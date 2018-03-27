<template>
    <v-app id="fundsControl" v-if="showFundsComponent">
            asdasd
    </v-app>
</template>

<script>
    export default {
        name: "funds-control",
        props: {
            showFundsComponent: {
                type: Boolean,
                default: false,
            },
        },
    }
</script>

<style scoped>

</style>