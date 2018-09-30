<html>

<head>
    <script type="text/javascript" language="javascript" src="js/canvas2image.js"></script>
    <script type="text/javascript" language="javascript" src="js/base64.js"></script>
    <script type="text/javascript" language="javascript" src="js/jquery.js"></script>
    <script type="text/javascript">
        function to_png(){
            Canvas2Image.saveAsPNG(document.getElementsByTagName("CANVAS")[0]);
        }
        function to_jpg(){
            Canvas2Image.saveAsJPEG(document.getElementsByTagName("CANVAS")[0]);
        }
    </script>
</head>

<body>
    <div>
        <button onclick="to_jpg()">Save as JPEG</button>
    </div>
    <div>
        <button onclick="to_png()">Save as PNG</button>
    </div>
    <canvas width="800" height="500" style="display: block;"></canvas>
</body>

</html>