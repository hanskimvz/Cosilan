
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Minimal Configuration Example</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="index, follow" />
  <meta name="description" content="This example shows the minimal required configuration you need to configure a heatmap.js instance." />
  <meta name="keywords" content="minimal config heatmap, heatmap.js, heatmap config" />
  <!-- <link rel="stylesheet" href="https://www.patrick-wied.at/static/heatmapjs/assets/css/commons.css" /> -->
  <!-- <link rel="stylesheet" href="https://www.patrick-wied.at/static/heatmapjs/assets/css/example-commons.css" /> -->

  <!-- <link rel="stylesheet" href="/css/app.css"> -->
<style>

.hot { color:#ec1e1e; }
.demo-wrapper { height:400px; background:rgba(0,0,0,.03); border:3px solid black; }
.heatmap { width:100%; height:100%; }
</style>
</head>
<body>
  <div class="wrapper">
    
    <div class="demo-wrapper">
      <div class="heatmap">

      </div>
    </div>
    <div class="demo-controls">
      <button class="trigger-refresh btn">re-generate data</button>
      <br style="clear:both" />
    </div>
    <h2>Code</h2>
    
  </div>
  <script src="/js/heatmap.js"></script>
  <script>
    window.onload = function() {

      function generateRandomData(len) {
        // generate some random data
        var points = [];
        var max = 0;
        var width = 24;
        var height = 7;

        for (x=0; x<width; x++) {
            for(y=0; y<height; y++){
                var val = Math.floor(Math.random()*10);    
                var val = 1;
                if (val >max ){
                    max = val;
                }
                // max = Math.max(max, val);  
                var point = {
                    x: x,
                    y: y,
                    value: val
                };
                points.push(point);
            }
        }
        console.log(max);
        max = max*2;
        // while (len--) {
        //   var val = Math.floor(Math.random()*100);
        //   max = Math.max(max, val);
        //   var point = {
        //     x: Math.floor(Math.random()*width),
        //     y: Math.floor(Math.random()*height),
        //     value: val
        //   };
        //   points.push(point);
        // }

        var data = { max:max, data: points };
        console.log(data);
        return data;
      }

      var heatmapInstance = h337.create({
        container: document.querySelector('.heatmap')
      });

      // generate 200 random datapoints
      var data = generateRandomData(200);
      heatmapInstance.setData(data);
    };
  </script>
</body>
</html>