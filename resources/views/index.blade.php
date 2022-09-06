<html>
<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div id="app" class="container">
        <br>
        <h3>medbuy</h3>
        <hr>
        <h5>
            薬の外箱を撮影してください。
        </h5>
        <div v-show="isModeVideo">
            <div class="float-right">
                <button type="button" class="btn btn-warning" @click="capture">撮影</button>
            </div>
            <video ref="video" width="640" height="480"></video>
            <br>
            <br>
        </div>
        <div v-show="isModeImage">
            <div class="float-right">
                この画像でよろしいですか？
                <br>
                <div class="text-right">
                    <button type="button" class="btn btn-light" @click="cancel">やり直す</button>
                    <button type="button" class="btn btn-success" @click="extract">OK</button>
                </div>
                <div style="white-space:pre;" v-if="extractedText">
                    <hr>
                    <span class="badge badge-primary">撮影できたお薬</span>
                    <div v-text="extractedText"></div>
                    <a :href="itemURL">商品を探す</a>
                    <!-- <button type="button" class="btn btn-success" onclick="location.href=`https://search.rakuten.co.jp/search/mall/${this.extractedText}/`">商品を探す</button> -->
                </div>
            </div>
            <canvas ref="canvas" width="640" height="480"></canvas>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.10/dist/vue.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js"></script>
    <script>

        new Vue({
            el: '#app',
            data: {
                imageData: null,
                mode: 'video',
                timeCount: 0,
                extractedText: '',
                // selectedText: ''
            },
            computed: {
                video() {
                    return this.$refs['video'];
                },
                canvas() {
                    return this.$refs['canvas'];
                },
                context() {
                    return this.canvas.getContext('2d');
                },
                isModeVideo() {
                    return (this.mode === 'video');
                },
                isModeImage() {
                    return (this.mode === 'image');
                },
                itemURL: function(){
                  return 'https://search.rakuten.co.jp/search/mall/' + this.extractedText + '/'
                }
            },
            methods: {
                capture() {
                    this.timeCount = 1;
                    const timer = setInterval(() => {
                        if(this.timeCount === 1) {
                            clearInterval(timer);
                            const video = this.video;
                            this.context.drawImage(video, 0, 0, video.videoWidth, video.videoHeight);
                            this.imageData = this.canvas.toDataURL('image/jpeg', 1.0);
                            this.mode = 'image';
                        }
                        this.timeCount -= 1;
                    }, 1000);
                },
                cancel() {
                    this.mode = 'video';
                },
                extract() {
                    const url = '/medicine/extract';
                    const formData = new FormData();
                    formData.append('image', this.imageData);
                    axios.post(url, formData)
                        .then((response) => {
                            const result = response.data.result;
                            if(result) {
                                this.extractedText = response.data.text;
                            }
                        });
                },
                // search(){
                //   const url ='/search';

                // }
            },
            mounted() {
                // ウェブカメラへアクセス
                navigator.mediaDevices.getUserMedia({ video: true })
                    .then((stream) => {
                        this.video.srcObject = stream;
                        this.video.play();
                    });
            }
        });

    </script>
</body>
</html>