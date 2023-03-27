<template>
    <div>
        <div>brush icon</div>
        <div>rectangle icon</div>
        <KonvaBrush />
        <KonvaRectangle />
        <canvas ref="canvas" width="400" height="300"></canvas>
    </div>
</template>
  
<script>
import { ref } from 'vue';
import Konva from 'konva';

export default {
    mounted() {
        const canvas = this.$refs.canvas;
        const width = canvas.width;
        const height = canvas.height;

        const stage = new Konva.Stage({
            container: canvas,
            width,
            height,
        });

        const layer = new Konva.Layer();
        stage.add(layer);

        let isDrawing = false;
        let lastLine;

        canvas.addEventListener('mousedown', (event) => {
            isDrawing = true;
            stage.setPointersPositions(event); // set the pointer position manually
            const pos = stage.getPointerPosition();
            if (pos) {
                lastLine = new Konva.Line({
                    stroke: 'black',
                    strokeWidth: 5,
                    globalCompositeOperation: 'source-over',
                    points: [pos.x, pos.y],
                });
                layer.add(lastLine);
            }
        });

        canvas.addEventListener('touchstart', (event) => {
            isDrawing = true;
            stage.setPointersPositions(event); // set the pointer position manually
            const pos = stage.getPointerPosition();
            if (pos) {
                lastLine = new Konva.Line({
                    stroke: 'black',
                    strokeWidth: 5,
                    globalCompositeOperation: 'source-over',
                    points: [pos.x, pos.y],
                });
                layer.add(lastLine);
            }
        });

        canvas.addEventListener('mouseup', () => {
            isDrawing = false;
        });

        canvas.addEventListener('touchend', () => {
            isDrawing = false;
        });

        canvas.addEventListener('mousemove', (event) => {
            if (!isDrawing) {
                return;
            }
            stage.setPointersPositions(event); // set the pointer position manually
            const pos = stage.getPointerPosition();
            if (pos && lastLine) {
                const newPoints = lastLine.points().concat([pos.x, pos.y]);
                lastLine.points(newPoints);
                layer.batchDraw();
            }
        });

        canvas.addEventListener('touchmove', (event) => {
            if (!isDrawing) {
                return;
            }
            stage.setPointersPositions(event); // set the pointer position manually
            const pos = stage.getPointerPosition();
            console.log('touchmove pos', pos);

            if (pos && lastLine) {
                const newPoints = lastLine.points().concat([pos.x, pos.y]);
                lastLine.points(newPoints);
                layer.batchDraw();
            }
        });
    },
};
</script>
<style>
canvas {
    border: 1px solid red;
}
</style>