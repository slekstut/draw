<template>
    <div ref="container"></div>
  </template>
  <script>
  import Konva from 'konva';
  
  export default {
    mounted() {
      // Create a new Konva stage and layer
      this.stage = new Konva.Stage({
        container: this.$refs.container,
        width: 500,
        height: 500
      });
      this.layer = new Konva.Layer();
      // Create a new Konva rectangle
      this.rect = new Konva.Rect({
        x: 50,
        y: 50,
        width: 100,
        height: 100,
        fill: 'red',
        stroke: 'black',
        strokeWidth: 2
      });
      // Add the rectangle to the layer and the layer to the stage
      this.layer.add(this.rect);
      this.stage.add(this.layer);
    }
  };
  </script>
  