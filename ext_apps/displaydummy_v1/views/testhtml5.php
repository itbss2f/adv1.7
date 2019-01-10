<!DOCTYPE HTML>
<html>
  <head>
    <style>
      #myCanvas {
        border: 1px solid #9C9898;
      }
      body {
        margin: 0px;
        padding: 0px;
      }
    </style>
  </head>
  <body>
    <canvas id="myCanvas" width="578" height="200"></canvas>
    <script>
      var canvas = document.getElementById('myCanvas');
      var context = canvas.getContext('2d');

      context.beginPath();
      context.rect(188, 50, 200, 100);
      context.fillStyle = 'yellow';
      context.fill();
      context.lineWidth = 7;
      context.strokeStyle = 'black';
      context.stroke();
      
      
      context.beginPath();
      context.rect(10, 50, 100, 100);
      context.fillStyle = 'yellow';
      context.fill();
      context.lineWidth = 2;
      context.strokeStyle = 'black';
      context.stroke();
    </script>
  </body>
</html>