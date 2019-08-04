<template>
    <v-card>
        <v-card-title><h3>Интеграция с Nalog.ru</h3></v-card-title>
        <v-responsive>
            <v-container grid-list-md>
                <p>
                    Интеграция для проверки чеков с <a href="https://www.nalog.ru/rn53/news/activities_fts/6954236/" target="_blank">nalog.ru</a>
                    в рамках нашего сервиса может осуществляться только для граждан Росиийской Федерации. Если вы правильно настроете интеграцию, вам откроется функционал
                    сканирования чеков, прямо с помощью камеры мобильного телефона.
                </p>
                <div>
                    <h4>Как это работает?</h4>
                    <p>
                        Очень просто. Когда у вас будет правильно настроена интеграция, для вас откроется раздел "Сканирование чеков"
                        в личном кабинете. Вам можно будет сканировать чек с помощью камеры вашего мобильного телефона, или web-камерой вашего ПК.
                        После сканирования, метка чека сохраняется в системе и ожидает получения самого чека из Nalog.ru. Когда чек будет получен, вы
                        сможете использовать его в своих аналитических рассчетах и прикреплять к вашим транзакциям, а так же создавать транзакции на основе
                        полученного чека.
                    </p>
                </div>
                <v-form>
                    <v-layout wrap>
                            <v-flex xs12 sm6 md4>
                                <v-text-field v-model="registerFormData.name" :rules="rules.nameRules" label="Имя" required></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <v-text-field v-model="registerFormData.email" :rules="rules.emailRules" label="E-mail" required></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <v-text-field v-model="registerFormData.phone" :rules="rules.phoneRules" label="Телефон" required></v-text-field>
                            </v-flex>
                        <template v-if="(showSMSCodeGroup && showRegisterGroup) || showUpdateGroup">
                            <v-flex xs12 sm6 md4>
                                <v-switch v-model="smsCodeTypeFieldSwitcher" label="Показать пароль"></v-switch>
                                <v-text-field :type="smsCodeTypeField" v-model="registerFormData.smsCode" :rules="rules.smsCodeRules" label="Код из СМС" required></v-text-field>
                            </v-flex>
                        </template>
                        <v-flex xs12 sm6 md4>
                            <v-switch v-model="registerFormData.isActive" label="Интеграция активна"></v-switch>
                        </v-flex>
                    </v-layout>
                    <v-layout v-show="showRegisterGroup">
                        <v-flex xs12 sm6 md4 v-show="!wantRestorePassword">
                            <v-btn color="success" :disabled="!registerFormData.isComplete" @click="register()">
                                Получить СМС код
                            </v-btn>
                        </v-flex>
                        <v-flex xs12 sm6 md4 v-show="wantRestorePassword">
                            <v-btn color="warning" :disabled="!registerFormData.isComplete" @click="restorePassword()">
                                Восстановить пароль
                            </v-btn>
                        </v-flex>
                        <v-flex xs12 sm6 md4 v-show="showSMSCodeGroup && showRegisterGroup">
                            <v-btn color="success" :disabled="!registerFormData.isComplete" @click="createIntegration()">
                                Сохранить
                            </v-btn>
                        </v-flex>
                    </v-layout>
                    <v-layout v-show="showUpdateGroup">
                        <v-flex xs12 sm6 md4>
                            <v-btn color="primary" :disabled="!registerFormData.isComplete" @click="updateIntegration()">
                                Обновить
                            </v-btn>
                        </v-flex>
                        <v-flex xs12 sm6 md4>
                            <v-btn color="warning" :disabled="!registerFormData.isComplete" @click="restorePassword()">
                                Восстановить пароль
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </v-form>
            </v-container>
        </v-responsive>
        <v-card-actions>

        </v-card-actions>
    </v-card>
</template>

<script>
    import APIRoutes from './ApiRoutes';
    import axios from 'axios';
    export default {
        name: "NalogIntegration",
        data: () => ({
            showRegisterGroup: true,
            showSMSCodeGroup: false,
            showUpdateGroup: false,
            smsCodeTypeFieldSwitcher: false,
            registerFormData: {
                name: '',
                email: '',
                phone: '+7',
                smsCode: null,
                isActive: true,
                isComplete: false,
            },
            wantRestorePassword: false,
            rules: {
                nameRules: [
                    v => !!v || 'Имя обязательно для заполнения',
                    v => /^[A-ZА-Я]{1}[a-zA-Zа-яА-Я]+$/.test(v) || 'Имя должно быть с заглавной буквы, без цифр и не менее 2-ух символов'
                ],
                emailRules: [
                    v => !!v || 'E-mail обязателен для заполнения',
                    v => /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(v) || 'E-mail не валиден '
                ],
                phoneRules: [
                    v => !!v || 'Телефон обязателен для заполнения',
                    v => /^\+7[0-9]{10}$/.test(v) || 'Телефон должен начинаться с +7 далее 10 значный номер пример +79134567890'
                ],
                smsCodeRules: [
                    v => !!v || 'СМС код не может быть пустым',
                    v => /^\d{4,6}$/.test(v) || 'СМС-код состоит только из цифр длинной от 4 до 6'
                ]
            }
        }),
        methods: {
            getSettings() {
                this.$store.commit('setPreloader', true);

                axios.get(APIRoutes.nalogRu.getSettings.url)
                    .then(response => {
                        if (response.data.code === 1) {
                            this.registerFormData.email    = response.data.meta.email;
                            this.registerFormData.name     = response.data.meta.name;
                            this.registerFormData.phone    = response.data.meta.phone;
                            this.registerFormData.smsCode  = response.data.meta.smsCode;
                            this.registerFormData.isActive = Boolean(response.data.integration.is_active);
                            this.showRegisterGroup         = false;
                            this.showUpdateGroup           = true;
                        }
                        this.$store.commit('setPreloader', false);
                    })
                    .catch(error => {
                        console.log(error);
                        this.$store.commit('AlertError', 'Ошибка сервера, попробуйте позже');
                        this.$store.commit('setPreloader', false);
                    });
            },
            updateIntegration() {
                if (this.registerFormData.smsCode === null) {
                    this.$store.commit('AlertError', 'Заполните поле код из СМС');
                    return;
                }
                this.$store.commit('setPreloader', true);

                axios.put(APIRoutes.nalogRu.update.url, {
                    name: this.registerFormData.name,
                    email: this.registerFormData.email,
                    phone: this.registerFormData.phone,
                    smsCode: this.registerFormData.smsCode,
                    isActive: this.registerFormData.isActive
                })
                    .then(response => {
                        if (response.data.code !== 1) {
                            this.$store.commit('AlertError', 'Обновление интеграции не удалось: ' + this.codeFromAPItoMessage(response.data.code));
                        }
                        else {
                            this.$store.commit('setAlert', {type: 'success', status: true, message: 'Интеграция успешно обновлена'});
                            window.location.reload();
                        }
                        this.$store.commit('setPreloader', false);

                    })
                    .catch(error => {
                        console.log(error);
                        this.$store.commit('AlertError', 'Ошибка сервера, попробуйте позже');
                        this.$store.commit('setPreloader', false);
                    });
            },
            register() {
                this.$store.commit('setPreloader', true);

                axios.post(APIRoutes.nalogRu.register.url, {
                    name: this.registerFormData.name,
                    email: this.registerFormData.email,
                    phone: this.registerFormData.phone,
                    smsCode: null,
                })
                .then(response => {
                    if (response.data.code !== 1) {
                        this.$store.commit('AlertError', 'Регистрация не удалась: ' + this.codeFromAPItoMessage(response.data.code));

                        if (response.data.code === 2) {
                            this.wantRestorePassword = true;
                            this.showSMSCodeGroup = true;
                        }
                    }
                    else {
                        this.showSMSCodeGroup = true;
                    }
                    this.$store.commit('setPreloader', false);

                })
                .catch(error => {
                    console.log(error);
                    this.$store.commit('AlertError', 'Ошибка сервера, попробуйте позже');
                    this.$store.commit('setPreloader', false);
                });
            },
            restorePassword() {
                this.$store.commit('setPreloader', true);
                this.$store.commit('closeAlert');

                axios.post(APIRoutes.nalogRu.restorePassword.url, {
                    name: this.registerFormData.name,
                    email: this.registerFormData.email,
                    phone: this.registerFormData.phone,
                    smsCode: null,
                })
                    .then(response => {
                        if (response.data.code !== 1) {
                            this.$store.commit('AlertError', 'Восстановление пароля не удалось: ' + this.codeFromAPItoMessage(response.data.code));
                        }
                        else {
                            this.showSMSCodeGroup = true;
                        }
                        this.$store.commit('setPreloader', false);

                    })
                    .catch(error => {
                        console.log(error);
                        this.$store.commit('AlertError', 'Ошибка сервера, попробуйте позже');
                        this.$store.commit('setPreloader', false);
                    });
            },
            createIntegration() {
                if (this.registerFormData.smsCode === null) {
                    this.$store.commit('AlertError', 'Заполните поле код из СМС');
                    return;
                }

                this.$store.commit('setPreloader', true);
                this.$store.commit('closeAlert');

                axios.post(APIRoutes.nalogRu.create.url, {
                    name: this.registerFormData.name,
                    email: this.registerFormData.email,
                    phone: this.registerFormData.phone,
                    smsCode: this.registerFormData.smsCode,
                })
                    .then(response => {
                        if (response.data.code !== 1) {
                            this.$store.commit('AlertError', 'Не удалось сохранить интеграцию: ' + this.codeFromAPItoMessage(response.data.code));
                        }
                        else {
                            this.showSMSCodeGroup = false;
                            this.$store.commit('setAlert', {type: 'success', status: true, message: 'Интеграция успешно созадана'});
                            window.location.reload();
                        }
                        this.$store.commit('setPreloader', false);

                    })
                    .catch(error => {
                        console.log(error);
                        this.$store.commit('AlertError', 'Ошибка сервера, попробуйте позже');
                        this.$store.commit('setPreloader', false);
                    });
            },
            codeFromAPItoMessage(code) {
                switch (code) {
                    case 2:
                        return 'Клиент уже существует';
                    case 3:
                        return 'Номер телефона не валиден';
                    case 4:
                        return 'Пользователь не найден';
                    case 5:
                        return 'Интеграция не найдена';
                    case 6:
                        return 'Интеграция уже существует';
                    case 7:
                        return 'Невозможно авторизоваться с этими данными, проверьте телефон и смс-код';
                }
            },
            validateRegistrationForm() {
                let formData = this.registerFormData;
                if (formData.name !== ''
                    && (formData.phone !== '' || formData.phone !== '+7')
                    && formData.email !== '') {
                    this.registerFormData.isComplete = true;
                }
            }
        },
        mounted() {
            this.getSettings();
        },
        computed: {
            smsCodeTypeField() {
                if (this.smsCodeTypeFieldSwitcher) {
                    return 'text';
                }
                else {
                    return 'password';
                }
            }
        },
        watch: {
            registerFormData: {
                handler: function (val, oldVal) {
                    this.validateRegistrationForm();
                },
                deep: true,
            },
        },
    }
</script>

<style scoped>

</style>