<template>
    <div>
      <div ref="container"></div>
      <button @click="setTool('rectangle')">Rectangle</button>
      <button @click="setTool('circle')">Circle</button>
      <button @click="setTool('brush')">Brush</button>
    </div>
  </template>
  
  <script>
  import { ref } from "vue";
  import Konva from "konva";
  
  export default {
    data() {
      return {
        tool: "rectangle", // Set default tool to rectangle
        isDrawing: false,
        shape: null,
        stage: null,
        layer: null,
      };
    },
    mounted() {
      // Create a new Konva stage and add the layer to it
      this.stage = new Konva.Stage({
        container: this.$refs.container,
        width: 500,
        height: 500,
      });
      this.layer = new Konva.Layer();
      this.stage.add(this.layer);
  
      // Add mouse event listeners to the layer
      this.layer.on("mousedown touchstart", this.handleMouseDown);
      this.layer.on("mousemove touchmove", this.handleMouseMove);
      this.layer.on("mouseup touchend", this.handleMouseUp);
    },
    methods: {
      setTool(tool) {
        this.tool = tool;
      },
      handleMouseDown(e) {
        this.isDrawing = true;
        const pos = this.stage.getPointerPosition();
        switch (this.tool) {
          case "rectangle":
            this.shape = new Konva.Rect({
              x: pos.x,
              y: pos.y,
              width: 0,
              height: 0,
              stroke: "black",
              strokeWidth: 2,
            });
            break;
          case "circle":
            this.shape = new Konva.Circle({
              x: pos.x,
              y: pos.y,
              radius: 0,
              stroke: "black",
              strokeWidth: 2,
            });
            break;
          case "brush":
            this.shape = new Konva.Line({
              stroke: "black",
              strokeWidth: 5,
              lineCap: "round",
              lineJoin: "round",
            });
            this.layer.add(this.shape);
            this.shape.points([pos.x, pos.y]);
            break;
        }
        this.layer.add(this.shape);
        this.layer.batchDraw();
      },
      handleMouseMove(e) {
        if (!this.isDrawing) return;
        const pos = this.stage.getPointerPosition();
        switch (this.tool) {
          case "rectangle":
            const width = pos.x - this.shape.x();
            const height = pos.y - this.shape.y();
            this.shape.width(width);
            this.shape.height(height);
            break;
          case "circle":
            const dx = pos.x - this.shape.x();
            const dy = pos.y - this.shape.y();
            const radius = Math.sqrt(dx * dx + dy * dy);
            this.shape.radius(radius);
            break;
          case "brush":
            var newPoints = this.shape.points().concat([pos.x, pos.y]);
            this.shape.points(newPoints);
            break;
        }
        this.layer.batchDraw();
      },
      handleMouseUp() {
        if (!this.isDrawing) return;
        this.isDrawing = false;
      },
    },
  };
  </script>
  