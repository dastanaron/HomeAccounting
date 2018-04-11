import template from './template.html';

export default {
    name: "events-control",
    template: template,
    props: {
        showEventControl: {
            type: Boolean,
            default: false,
        },
    },
    data: () => ({
        headers: [
            {
                text: 'ID',
                align: 'left',
                sortable: false,
                value: 'id'
            },
            { text: 'Заголовок', value: 'head', align: 'right' },
            { text: 'Сообщение', value: 'message', align: 'right' },
            { text: 'Выполнено', value: 'completed', align: 'right' },
            { text: 'Дата', value: 'date_notif', align: 'right' },
            { text: 'Управление', value: '', align: 'right'},
        ],
        search: '',
        loadingDataTable: false,
        pagination: {'sortBy': 'date_notif', 'descending': true, 'rowsPerPage': -1},
        dataTables: [],
    }),
    methods: {
        getEvents() {

            this.$store.commit('setPreloader', true);

            axios.get('/pa/event-list')
                .then(response=> {
                    this.dataTables = response.data;

                    console.log(response.data);

                    this.$store.commit('setPreloader', false);

                })
                .catch(error => {
                    this.$store.commit('AlertError', error.message);
                });

        },
        isMobile() {
            let mobile_getter = this.$store.getters.mobile;

            let mobile = false;

            if(mobile_getter !== null) {
                mobile = true;
            }
            return mobile;
        },
    },
    mounted() {
      this.getEvents();
    },
}