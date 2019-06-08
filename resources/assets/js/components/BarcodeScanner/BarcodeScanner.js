
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

        }

    },
    computed: {


    },
    watch: {

    },
    mounted() {
        this.$nextTick(function () {
            console.log(this.$refs.barcode)

            let scanner = new Instascan.Scanner({ video: document.getElementById('barcode') });
            scanner.addListener('scan', function (content) {
                console.log(content);
            });
            Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length > 0) {
                    scanner.start(cameras[0]);
                } else {
                    console.error('No cameras found.');
                }
            }).catch(function (e) {
                console.error(e);
            });
        });

    }
}