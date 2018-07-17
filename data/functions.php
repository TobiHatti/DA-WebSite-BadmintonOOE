<?php
function PreventAutoScroll()
{
    // DESCRIPTION:
    // When using CSS-Targets, a <a href="#anchor"> is required.
    // To prevent the page from jumping to the anchor and therefor putting
    // it on top of the page, the <a>-Tag needs to contain the following event:
    // onclick="bgenScroll();"

    return '
        <script language="JavaScript" type="text/javascript">
            //Prevent Autoscroll for target-anchors
            function bgenScroll() {
             if (window.pageYOffset!= null){
              st=window.pageYOffset+"";
             }
             if (document.body.scrollWidth!= null){
              if (document.body.scrollTop){
              st=document.body.scrollTop;
              }
              st=document.documentElement.scrollTop;
             }
              setTimeout("window.scroll(0,st)",10);
            }
        </script>';
}

function PageTitle($string)
{
    // DESCRIPTON:
    // Changes the Page-Title in the Tab
    // Since it is changed in the Middle of the page, and not in the <head>-part,
    // Javascript is required to do so.

    echo '<script>document.title = "'.$string.' | O\u00d6. Badmintonverband";</script>';
}
?>