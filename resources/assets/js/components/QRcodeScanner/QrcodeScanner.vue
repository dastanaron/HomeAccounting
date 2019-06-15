<template>
    <v-app id="inspire">
        <v-toolbar dark color="primary">
            <!--<v-tooltip bottom>
                <v-toolbar-side-icon slot="activator" @click="goToPanel()"></v-toolbar-side-icon>
                <span>Выйти в панель</span>
            </v-tooltip>!-->
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
        <template>
            <v-card>
                <v-card-title class="headline">Выбор камеры</v-card-title>
                <v-card-text>
                        <v-alertgit
                                :value="cameraIsNotSupported"
                                type="warning"
                        >
                            Камера не найдена или функционал не поддерживается
                        </v-alertgit>
                    <template v-for="camera in cameras">
                        <v-btn flat small color="primary" @click="selectCamera(camera)">{{camera.name}}</v-btn>
                    </template>
                </v-card-text>
                <v-card-actions>
                    <div>
                        <video id="preview"></video>
                    </div>
                    <div>
                        <template v-for="string in scanned">
                            <div>{{string}}</div>
                        </template>
                    </div>
                </v-card-actions>
                <v-card-actions>
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
        }),
        methods: {
            selectCamera(camera) {
               this.scanner.start(camera);
            },
            stopCamera() {
                this.scanner.stop();
            }
        },
        mounted() {
            this.scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
            this.scanner.addListener('scan', (content, image) => {
                this.scanned.push(content);
                console.log(content);
                console.log(image);
            });

            Instascan.Camera.getCameras().then((cameras) => {
                if (cameras.length > 0) {
                    this.cameras = cameras;
                    this.cameraIsNotSupported = false;
                } else {
                    this.cameraIsNotSupported = true;
                }
            }).catch(function (e) {
                console.error(e);
            });
        },
    }
</script>

<style scoped>

</style>