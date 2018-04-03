
import template from './template.html';

export default {
    name: "category-control",
    template: template,
    props: {
        showCategoryComponent: {
            type: Boolean,
            default: false,
        },
    },

    data: () => ({
        search: '',
        loadingDataTable: false,
        pagination: {'sortBy': 'name', 'ascending': true, 'rowsPerPage': -1},
        headers: [
            {
                text: 'ID',
                align: 'left',
                sortable: false,
                value: 'id'
            },
            { text: 'Название', value: 'name', align: 'right' },
            { text: 'Управление', value: '', align: 'right' },

        ],

        max50chars: (v) => v.length <= 50 || 'Слишком длинное наименование!',

        dataTables: [],

        createForm: false,

        categoryFormType: 'create',

        categoryFormData: {
            category_id: 0,
            name: '',
        },

    }),
    methods: {
        getCategories() {
            this.$store.commit('setPreloader', true);
            axios.get('/pa/categories-list')
                .then(response=> {
                    this.dataTables = response.data;
                    this.$store.commit('setPreloader', false);
                })
                .catch(error => {
                    this.$store.commit('AlertError', error.message);
                });
        },
        editForm(object) {
            this.categoryFormData.category_id = object.id;
            this.categoryFormData.name = object.name;
            this.categoryFormType="update";
        },
        newForm() {
            this.createForm = true;
            this.categoryFormType = 'create';
            this.categoryFormData.category_id = 0;
            this.categoryFormData.name = '';
        },
        deleteCategory(object) {

            let confirm = window.confirm('Вы действительно хотите удалить элемент?');

            if(confirm === true) {
                this.categoryFormType="delete";
                this.categoryFormData.category_id = object.id;
                this.categorySave();
            }

        },
        categorySave() {
            let url = '/pa/categories';
            let method = '';

            if(this.categoryFormType === 'create') {
                method = 'post';
            }
            else if(this.categoryFormType === 'update') {
                method = 'put';
            }
            else if(this.categoryFormType=== 'delete') {
                method = 'delete';
            }

            this.$store.commit('setPreloader', true);

            axios({
                method: method,
                url: url,
                data: this.categoryFormData,
            })
                .then(response=> {
                    if(response.data.status == 200) {
                        this.createForm = false;
                        this.getCategories();
                        this.$store.commit('setPreloader', false);
                    }
                    else {
                        console.log(response);
                    }

                })
                .catch(error => {
                    this.$store.commit('AlertError', error.message);
                });
        },
    },
    computed: {

    },
    watch: {

    },
    mounted() {
        this.getCategories();
    },
}