
import template from './template.html';

const Instascan = require('instascan');

export default {
    name: "BarcodeScanner",
    template: template,
    props: {
        showBarcodeScanner: {
            type: Boolean,
            default: false,
        }
    },
    data: () => ({
        scanner: null,
        activeCameraId: null,
        cameras: [],
        scans: []
    }),
    methods: {
        scan () {
            var self = this;
            self.scanner = new Instascan.Scanner({ video: document.getElementById('preview'), scanPeriod: 5 });
            self.scanner.addListener('scan', function (content, image) {
                self.scans.unshift({ date: +(Date.now()), content: content });
            });
            Instascan.Camera.getCameras().then(function (cameras) {
                self.cameras = cameras;
                if (cameras.length > 0) {
                    self.activeCameraId = cameras[0].id;
                    self.scanner.start(cameras[0]);
                } else {
                    console.error('No cameras found.');
                }
            }).catch(function (e) {
                console.error(e);
            });

        },
        formatName: function (name) {
            return name || '(unknown)';
        },
        selectCamera: function (camera) {
            this.activeCameraId = camera.id;
            this.scanner.start(camera);
        }

    },
    computed: {


    },
    watch: {

    },
    mounted() {

    }
}