<template>
    <div id="fundsControl" v-if="showCategoryComponent">
        <div class="my-4">
            <v-tooltip bottom>
                <v-btn class="new-button" slot="activator" flat icon large color="success" @click="newForm()">
                    <div class="creating-control">
                        <v-icon>add_box</v-icon>
                    </div>
                </v-btn>
                <span>Создать новый</span>
            </v-tooltip>
            <div class="edit-dialog" v-if="createForm">
                <v-text-field
                        label="Введите название"
                        v-model="categoryFormData.name"
                        single-line
                        :rules="[max50chars]"
                ></v-text-field>
                <v-tooltip bottom>
                    <v-btn class="new-button" slot="activator" flat icon color="success" @click="categorySave()">
                        <v-icon>done</v-icon>
                    </v-btn>
                    <span>Сохранить</span>
                </v-tooltip>
                <v-tooltip bottom>
                    <v-btn class="new-button" slot="activator" flat icon color="error" @click="createForm=false">
                        <v-icon>clear</v-icon>
                    </v-btn>
                    <span>Закрыть</span>
                </v-tooltip>
            </div>

            <v-data-table
                    :loading="loadingDataTable"
                    :headers="headers"
                    :items="dataTables"
                    v-bind:pagination.sync="pagination"
                    hide-actions
                    class="elevation-1"
                    :search="search"
                    item-key="uuid"
            >
                <v-progress-linear slot="progress" color="success" indeterminate></v-progress-linear>
                <template slot="items" slot-scope="props">
                    <tr>
                        <td>{{ props.item.id }}</td>
                        <td class="text-xs-right" @click="editForm(props.item)">
                            <v-edit-dialog
                                    :return-value.sync="props.item.name"
                            > {{ props.item.name }}
                                <v-text-field
                                        slot="input"
                                        label="Изменить"
                                        v-model="categoryFormData.name"
                                        single-line
                                        counter="50"
                                        @change="categorySave()"
                                        :rules="[max50chars]"
                                ></v-text-field>
                            </v-edit-dialog>
                        </td>
                        <td class="text-xs-right control">
                            <v-tooltip top>
                                <v-btn flat icon small color="error" slot="activator" @click="deleteCategory(props.item)"><v-icon>delete</v-icon></v-btn>
                                <span>Удалить</span>
                            </v-tooltip>

                        </td>
                    </tr>
                </template>
            </v-data-table>
        </div>
    </div>
</template>

<script>
    export default {
        name: "category-control",
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
                    .catch(function (error) {
                        console.log(error)
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
                    .catch(function (error) {
                        console.log(error)
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
</script>

<style scoped>

</style>