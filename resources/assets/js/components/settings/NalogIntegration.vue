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
                        <template v-if="showRegisterGroup">
                            <v-flex xs12 sm6 md4>
                                <v-text-field v-model="registerFormData.name" :rules="rules.nameRules" label="Имя" required></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <v-text-field v-model="registerFormData.email" :rules="rules.emailRules" label="E-mail" required></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <v-text-field v-model="registerFormData.phone" :rules="rules.phoneRules" label="Телефон" required></v-text-field>
                            </v-flex>
                        </template>
                        <template v-if="showSMSCodeGroup">
                            <v-flex xs12 sm6 md4>
                                <v-text-field v-model="registerFormData.smsCode" :rules="rules.smsCodeRules" label="Код из СМС" required></v-text-field>
                            </v-flex>
                        </template>
                    </v-layout>
                    <v-layout>
                        <v-flex xs12 sm6 md4>
                            <v-btn color="success">
                                Получить СМС код
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
    export default {
        name: "NalogIntegration",
        data: () => ({
            showRegisterGroup: true,
            showSMSCodeGroup: false,
            registerFormData: {
                name: '',
                email: '',
                phone: '+7',
                smsCode: null,
            },
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
        mounted() {

        }
    }
</script>

<style scoped>

</style>