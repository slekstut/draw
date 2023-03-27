<template>
    <div>
      <div ref="container"></div>
    </div>
  </template>
  
  <script>
  import Konva from 'konva';
  
  export default {
    mounted() {
      // Create a new Konva layer for the brush strokes
      this.brushLayer = new Konva.Layer();
      // Create a new Konva line for the brush strokes
      this.brush = new Konva.Line({
        stroke: 'black',
        strokeWidth: 5,
        lineCap: 'round',
        lineJoin: 'round'
      });
      // Set up the mouse event listeners
      this.isDrawing = false;
      this.$refs.container.addEventListener('mousedown', this.handleMouseDown);
      this.$refs.container.addEventListener('touchstart', this.handleMouseDown);
      this.$refs.container.addEventListener('mousemove', this.handleMouseMove);
      this.$refs.container.addEventListener('touchmove', this.handleMouseMove);
      this.$refs.container.addEventListener('mouseup', this.handleMouseUp);
      this.$refs.container.addEventListener('touchend', this.handleMouseUp);
      // Create a new Konva stage and add the brush layer to it
      this.stage = new Konva.Stage({
        container: this.$refs.container,
        width: 500,
        height: 500
      });
      this.stage.add(this.brushLayer);
    },
    beforeDestroy() {
      // Remove the mouse event listeners
      this.$refs.container.removeEventListener('mousedown', this.handleMouseDown);
      this.$refs.container.removeEventListener('touchstart', this.handleMouseDown);
      this.$refs.container.removeEventListener('mousemove', this.handleMouseMove);
      this.$refs.container.removeEventListener('touchmove', this.handleMouseMove);
      this.$refs.container.removeEventListener('mouseup', this.handleMouseUp);
      this.$refs.container.removeEventListener('touchend', this.handleMouseUp);
    },
    methods: {
      handleMouseDown(e) {
        this.isDrawing = true;
        this.brush.points([e.offsetX, e.offsetY]);
        this.brushLayer.add(this.brush);
      },
      handleMouseMove(e) {
        if (!this.isDrawing) {
          return;
        }
        var newPoints = this.brush.points().concat([e.offsetX, e.offsetY]);
        this.brush.points(newPoints);
        this.brushLayer.batchDraw();
      },
      handleMouseUp(e) {
        this.isDrawing = false;
      }
    }
  };
  </script>
  