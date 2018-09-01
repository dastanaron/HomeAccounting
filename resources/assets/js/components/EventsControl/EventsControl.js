import template from './template.html';

export default {
    name: "events-control",
    template: template,
    props: {
        showEventControl: {
            type: Boolean,
            default: true,
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

        eventsFormShow: false,
        eventsFormTitle: 'Новое напоминание',
        eventFormType: 'create',
        eventFormData: {
            id: null,
            type_event: 1,
            head: '',
            message: '',
            completed: 0,
            date_notif: null,
            time_notif: null,
            dateTimeNotif: null,
        },

        dateNotificationPicker: false,
        timeNotificationPicker: false,
    }),
    methods: {
        getEvents() {

            this.$store.commit('setPreloader', true);

            axios.get('/pa/event-list')
                .then(response=> {
                    this.dataTables = response.data;

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
        createEventForm() {

            this.eventFormData = {
                id: null,
                type_event: 1,
                head: '',
                message: '',
                completed: 0,
                date_notif: null,
                time_notif: null,
                dateTimeNotif: null,
            };

            this.eventsFormTitle = 'Новое напоминание';
            this.eventFormType = 'create';
            this.eventsFormShow = true;
        },
        editEventForm(object) {

            this.eventFormData = {
                id: object.id,
                type_event: object.type_event,
                head: object.head,
                message: object.message,
                completed: object.completed,
                date_notif: this.separateDateTime(object.date_notif)[0],
                time_notif: this.separateDateTime(object.date_notif)[1],
                dateTimeNotif: object.date_notif,
            };

            this.eventsFormTitle = 'Редактировать напоминание';
            this.eventFormType = 'update';
            this.eventsFormShow = true;
        },
        separateDateTime(dateTime) {
            return dateTime.split(' ');
        },
        deleteEvent(object) {
            let confirm = window.confirm('Вы действительно хотите удалить элемент?');

            if(confirm === true) {
                this.eventFormType = 'delete';
                this.eventFormData.id = object.id
                this.eventSave();
            }

        },
        eventSave() {
            let url = '/pa/events';
            let method = '';

            if(this.eventFormType === 'create') {
                method = 'post';
            }
            else if(this.eventFormType === 'update') {
                method = 'put';
            }
            else if(this.eventFormType === 'delete') {
                method = 'delete';
            }

            axios({
                method: method,
                url: url,
                data:
                    {
                        event_id: this.eventFormData.id,
                        type_event: this.eventFormData.type_event,
                        head: this.eventFormData.head,
                        message: this.eventFormData.message,
                        completed: this.eventFormData.completed,
                        date: this.dateTimeNotif,
                    }
            })
                .then(response=> {
                    if(response.data.status == 200) {
                        this.eventsFormShow = false;
                        this.getEvents();
                    }
                    else {
                        this.$store.commit('AlertError', 'Ошибка получения данных с сервера');
                    }

                })
                .catch(error => {
                    this.$store.commit('AlertError', error.message);
                });
        },
    },
    computed: {
        dateTimeNotif() {

            let dateTime = this.eventFormData.date_notif + ' ' + this.eventFormData.time_notif;
            this.eventFormData.dateTimeNotif = dateTime;
            return dateTime;

        },
    },
    mounted() {
      this.getEvents();
    },
}