<template>
    <v-app id="inspire">
        <v-toolbar dark color="primary">
            <v-tooltip bottom>
                <v-btn icon @click="goToLK()" slot="activator">
                    <v-icon>input</v-icon>
                </v-btn>
                <span>Вернуться в ЛК</span>
            </v-tooltip>
            <v-toolbar-title class="white--text">QRCodeScanner</v-toolbar-title>
            <v-spacer></v-spacer>

        </v-toolbar>
        <v-menu
                transition="slide-x-transition"
                bottom
                right
                :close-on-content-click="false"
                :nudge-width="200"
        >
            <v-card>

            </v-card>
        </v-menu>
        <v-alert
                dismissible
                :value="isShowAlert"
                :type="alertType"
        >
            {{alertMessage}}
        </v-alert>
        <template>
            <v-card>
                <v-card-title class="headline">Выбор камеры</v-card-title>
                <v-card-text>
                        <v-alert
                                :value="cameraIsNotSupported"
                                type="warning"
                        >
                            Камера не найдена или функционал не поддерживается
                        </v-alert>
                    <template v-for="camera in cameras">
                        <v-btn flat small color="primary" @click="selectCamera(camera)">{{camera.name}}</v-btn>
                    </template>
                </v-card-text>
                <v-card-actions>
                    <div>
                        <video id="preview" width="100%"></video>
                    </div>
                    <div>
                        <template v-for="string in scanned">
                            <div>{{string}}</div>
                        </template>
                    </div>
                </v-card-actions>
                <v-card-actions v-if="!cameraIsNotSupported">
                    <v-btn color="error" @click="stopCamera()">Камера стоп</v-btn>
                </v-card-actions>
            </v-card>

        </template>
    </v-app>
</template>

<script>
    import Instascan from 'instascan';
    export default {
        name: "qrcode-scanner",
        data: () => ({
            cameras: [],
            scanner: {},
            cameraIsNotSupported: true,
            scanned: [],

            isShowAlert: false,
            alertType: 'success',
            alertMessage: '',
        }),
        methods: {
            selectCamera(camera) {
               this.scanner.start(camera);
            },
            stopCamera() {
                this.scanner.stop();
            },
            goToLK() {
                window.location.href="/pa";
            },
            showAlert(type, message) {
                this.alertType = type;
                this.alertMessage = message;
                this.isShowAlert = true;
            },
            sendToServer(method, url, data) {
                axios({
                    method: method,
                    url: url,
                    data: data
                })
                .then(response=> {
                    console.log(response);
                    if(response.data.status === 'saved') {
                        this.showAlert('success', 'Чек сохранен');
                    }
                    else {
                        this.showAlert('error', 'сервер вернул неправильный ответ');
                    }
                })
                .catch(error => {
                    this.showAlert('error', error.message);
                    console.error(error);
                });
            },
            sendCheck(qrCodeString) {
                let data = {
                    qrcode: qrCodeString,

                };
                this.sendToServer('post', '/qr-code-scanner/send-check', data);
            }
        },
        mounted() {
            this.scanner = new Instascan.Scanner({ video: document.getElementById('preview'), mirror: false });
            this.scanner.addListener('scan', (content, image) => {
                this.scanned.push(content);
                this.sendCheck(content);
                this.stopCamera();
            });

            Instascan.Camera.getCameras().then((cameras) => {
                if (cameras.length > 0) {
                    this.cameras = cameras;
                    this.cameraIsNotSupported = false;
                } else {
                    this.cameraIsNotSupported = true;
                }
            }).catch(function (e) {
                this.showAlert('error', error.message);
                console.error(e);
            });
        },
    }
</script>

<style scoped>

</style>