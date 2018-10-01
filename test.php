<script src="/js/html2canvas.js"></script>
<script src="/js/download.js"></script>

<body id="createImage">


    <div id="testDiv" style="width: 100px; height: 100px; background: lime;">

        Hallo
        <h3>I BIN COOL</h3>
    </div>

    <button onclick="ConvertDiv2Base64('testDiv','imagePaste');">Paste</button>
</body>

<script type="text/javascript">

function ConvertDiv2Base64Src(divID,exportSrcId)
{
    html2canvas(document.getElementById(divID)).then(function(canvas) {
        var base64image = canvas.toDataURL("image/png");

        document.getElementById(exportSrcId).src = base64image;
    });
}

function ConvertDiv2Base64Src(divID,exportSrcId)
{
    html2canvas(document.getElementById(divID)).then(function(canvas) {
        var base64image = canvas.toDataURL("image/png");

        document.getElementById(exportSrcId).src = base64image;
    });
}


</script>


<img src="" alt="" id="imagePaste" style="border: 5px solid red"/>