<template>
    <div class="draw">
        <div class="draw__actions">
            <div class="draw__actions--tools">
                <button @click="drawMode = 'line'">
                    <svg xmlns="http://www.w3.org/2000/svg" height="48" viewBox="0 96 960 960" width="48">
                        <path
                            d="M215 939q-33.835 0-66.917-11.5Q115 916 90 890q35-12 50-35t15-62q0-43.75 30.676-74.375Q216.353 688 260.176 688 304 688 334.5 718.625T365 793q0 64-43.5 105T215 939Zm0-60q35 0 62.5-25t27.5-61q0-20-12.5-32.5T260 748q-20 0-32.5 12.5T215 793q0 39-8.5 57.5T175 873q6 1 20 3.5t20 2.5Zm230-177-90-95 376-376q14-14 31-14.5t32 14.5l29 29q15 15 14.5 32.5T823 324L445 702Zm-185 91Z" />
                    </svg>
                </button>
                <button @click="drawMode = 'rect'">
                    <svg xmlns="http://www.w3.org/2000/svg" height="48" viewBox="0 96 960 960" width="48">
                        <path d="M80 896V256h800v640H80Zm60-60h680V316H140v520Zm0 0V316v520Z" />
                    </svg>
                </button>
                <button @click="drawMode = 'circle'">
                    <svg xmlns="http://www.w3.org/2000/svg" height="48" viewBox="0 96 960 960" width="48">
                        <path
                            d="M480 976q-82 0-155-31.5t-127.5-86Q143 804 111.5 731T80 576q0-83 31.5-156t86-127Q252 239 325 207.5T480 176q83 0 156 31.5T763 293q54 54 85.5 127T880 576q0 82-31.5 155T763 858.5q-54 54.5-127 86T480 976Zm0-60q142 0 241-99.5T820 576q0-142-99-241t-241-99q-141 0-240.5 99T140 576q0 141 99.5 240.5T480 916Zm0-340Z" />
                    </svg>
                </button>
            </div>
            <div class="draw__actions--control">
                <button @click="openModal">
                    <svg xmlns="http://www.w3.org/2000/svg" height="48" viewBox="0 96 960 960" width="48">
                        <path
                            d="M840 373v503q0 24-18 42t-42 18H180q-24 0-42-18t-18-42V276q0-24 18-42t42-18h503l157 157Zm-60 27L656 276H180v600h600V400ZM479.765 811Q523 811 553.5 780.735q30.5-30.264 30.5-73.5Q584 664 553.735 633.5q-30.264-30.5-73.5-30.5Q437 603 406.5 633.265q-30.5 30.264-30.5 73.5Q376 750 406.265 780.5q30.264 30.5 73.5 30.5ZM233 472h358V329H233v143Zm-53-72v476-600 124Z" />
                    </svg>
                </button>
                <div>
                    <label for="fileInput" class="btn">
                        <svg xmlns="http://www.w3.org/2000/svg" height="48" viewBox="0 96 960 960" width="48">
                            <path
                                d="M452 854h60V653l82 82 42-42-156-152-154 154 42 42 84-84v201ZM220 976q-24 0-42-18t-18-42V236q0-24 18-42t42-18h361l219 219v521q0 24-18 42t-42 18H220Zm331-554V236H220v680h520V422H551ZM220 236v186-186 680-680Z" />
                        </svg>
                    </label>
                    <input type="file" id="fileInput" ref="fileInput" class="hidden" @change="handleFileUpload">
                </div>
            </div>
            <b-modal v-if="showModal" v-model="showModal" title="Filename required" ok-only @ok="saveDrawing">
                <b-form-input name="filename" ref="filename" v-model="filename"></b-form-input>
            </b-modal>
        </div>
        <div ref="container" class="draw-area"></div>
    </div>
</template>
  
  
<script>
import Konva from 'konva'
import axios from 'axios'
import { BButton, BModal } from 'bootstrap-vue'

export default {
    name: 'CanvasDrawTool',
    components: {
        BButton,
        BModal,
    },
    data() {
        return {
            stage: null,
            layer: null,
            isDrawing: false,
            lastLine: null,
            brushSize: 10,
            brushColor: 'black',
            showModal: false,
            filename: null,
            uploadedImage: null,
            imageUrl: '',
        }
    },
    mounted() {
        this.initStage()
        this.initListeners()
    },
    methods: {
        initStage() {
            this.stage = new Konva.Stage({
                container: this.$refs.container,
                width: window.innerWidth,
                height: window.innerHeight,
            })

            this.layer = new Konva.Layer()
            this.stage.add(this.layer)
        },

        initListeners() {
            window.addEventListener('resize', () => {
                this.stage.width(window.innerWidth)
                this.stage.height(window.innerHeight)
            })

            this.stage.on('mousedown touchstart', (e) => {
                this.isDrawing = true
                const pos = this.stage.getPointerPosition()
                if (this.drawMode === 'line') {
                    this.lastLine = new Konva.Line({
                        stroke: this.brushColor,
                        strokeWidth: this.brushSize,
                        globalCompositeOperation:
                            e.evt.buttons === 2 ? 'destination-out' : 'source-over',
                        points: [pos.x, pos.y],
                    })
                    this.layer.add(this.lastLine)
                } else if (this.drawMode === 'rect') {
                    this.lastRect = new Konva.Rect({
                        stroke: this.brushColor,
                        strokeWidth: this.brushSize,
                        globalCompositeOperation:
                            e.evt.buttons === 2 ? 'destination-out' : 'source-over',
                        x: pos.x,
                        y: pos.y,
                        width: 0,
                        height: 0,
                    })
                    this.layer.add(this.lastRect)
                } else if (this.drawMode === 'circle') {
                    this.lastCircle = new Konva.Circle({
                        stroke: this.brushColor,
                        strokeWidth: this.brushSize,
                        globalCompositeOperation:
                            e.evt.buttons === 2 ? 'destination-out' : 'source-over',
                        x: pos.x,
                        y: pos.y,
                        radius: 0,
                    })
                    this.layer.add(this.lastCircle)
                }
            })

            this.stage.on('mousemove touchmove', (e) => {
                if (!this.isDrawing) return
                const pos = this.stage.getPointerPosition()
                if (this.drawMode === 'line') {
                    let newPoints = this.lastLine.points().concat([pos.x, pos.y])
                    this.lastLine.points(newPoints)
                } else if (this.drawMode === 'rect') {
                    let width = pos.x - this.lastRect.x()
                    let height = pos.y - this.lastRect.y()
                    this.lastRect.width(width)
                    this.lastRect.height(height)
                } else if (this.drawMode === 'circle') {
                    let dx = pos.x - this.lastCircle.x()
                    let dy = pos.y - this.lastCircle.y()
                    let radius = Math.sqrt(dx * dx + dy * dy)
                    this.lastCircle.radius(radius)
                }
                this.layer.batchDraw()
            })

            this.stage.on('mouseup touchend', () => {
                this.isDrawing = false
                if (this.drawMode === 'line') {
                    this.lastLine = null
                } else if (this.drawMode === 'rect') {
                    this.lastRect = null
                } else if (this.drawMode === 'circle') {
                    this.lastCircle = null
                }
            })
        },
        openModal() {
            this.showModal = true
        },
        async saveDrawing() {
            let dataUrl;
            if (this.imageUrl) {
                dataUrl = this.imageUrl;
            } else {
                dataUrl = this.stage.toDataURL();
            }
            const fileData = this.filename;
            try {
                const response = await this.$axios.post('/filevalidation', { dataUrl, fileData });
                console.log('res', response.data);
                console.log('sent request!');

                const link = document.createElement('a');
                link.href = dataUrl;
                link.download = this.filename || 'drawing.png';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                this.$toasted.show('Drawing was successfully saved!', { duration: 3000, type: 'success' });


                if (this.$refs.modal) {
                    this.$refs.modal.hide();
                }
                this.filename = '';
            } catch (error) {
                if (error.response) {
                    const err = error.response.data.errors.fileData[0];
                    this.showModal = true;
                    this.$toasted.show(err, { duration: 3000, type: 'error' });
                } else {
                    console.log('Error:', error.message);
                }
            }
        },

        async handleFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                this.$toasted.show('Unsupported file type. Please upload a JPEG, PNG, or GIF image.', { duration: 3000, type: 'error' });
                return;
            }

            const formData = new FormData();
            formData.append('file', file);
            try {
                const response = await this.$axios.post('/upload', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                const imageUrl = response.data.imageUrl;
                this.imageUrl = imageUrl
                const existingImage = this.layer.findOne('Image');
                if (existingImage) {
                    existingImage.remove();
                }

                const image = new Image();
                image.onload = () => {
                    const konvaImage = new Konva.Image({
                        image: image,
                        width: this.stage.width(),
                        height: this.stage.height()
                    });
                    this.layer.add(konvaImage);
                    this.layer.batchDraw();
                };
                image.src = imageUrl;
            } catch (error) {
                console.log('Error:', error.message);
            }
        }

    },
}
</script>

<style>
body {
    overflow-x: hidden;
}

.draw {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 24px;
}

.draw__actions {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 24px;
}

.draw__actions--control {
    display: flex;
    position: relative;
}

.draw__actions--control::before {
    content: '';
    position: absolute;
    top: 50%;
    left: -14px;
    transform: translateY(-50%);
    width: 1px;
    height: 32px;
    background-color: black;
}

.draw__actions--control .btn {
    margin: 0;
}

button {
    background-color: transparent;
    border: 1px solid transparent;
}

button:hover {
    border: 1px solid black;
}

.draw-area {
    width: 500px;
    height: 500px;
    border: 1px solid black;
}

.hidden {
    display: none;
}
</style>
  