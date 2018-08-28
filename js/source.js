function bgenScroll()
{
    // DESCRIPTION:
    // When using CSS-Targets, a <a href="#anchor"> is required.
    // To prevent the page from jumping to the anchor and therefor putting
    // it on top of the page, the <a>-Tag needs to contain the following event:
    // onclick="bgenScroll();"

    if (window.pageYOffset!= null)
    {
        st=window.pageYOffset+"";
    }

    if (document.body.scrollWidth!= null)
    {
        if (document.body.scrollTop)
        {
            st=document.body.scrollTop;
        }
        st=document.documentElement.scrollTop;
    }
    setTimeout("window.scroll(0,st)",10);
}

function ChangeFrameLink(loc)
{
    document.getElementById("chframe").src = loc;
}

function LoadAnimation()
{
    document.getElementById("loadAnim1").style.opacity=0.3;
    document.getElementById("loadAnim2").style.opacity=1;
}

function TagInsert(e)
{
    var tagText = document.getElementById("tagText").value;
    var tagStr = "||" + document.getElementById("tag_str").value;

    if (e.keyCode == 13 && tagText!= "")
    {
        if(tagStr.replace("||" + tagText + "||") == ("||" + document.getElementById("tag_str").value))
        {
            var block_to_insert ;
            var container_block ;
            var tag_nr = document.getElementById("tag_nr").value;

            document.getElementById("tag_nr").value = parseInt(tag_nr) + 1;

            block_to_insert = document.createElement( 'div' );
            block_to_insert.innerHTML = '<div id="tagID' + tag_nr + '"><input type="hidden" id="tagVal' + tag_nr + '" value="' + tagText + '"/><a onclick="RmTag(' + tag_nr + ');">&#128500;</a>&nbsp;' + tagText + '</div>';

            container_block = document.getElementById( 'tagContainer' );
            container_block.appendChild( block_to_insert );

            document.getElementById("tag_str").value = document.getElementById("tag_str").value + tagText + '||';
            document.getElementById("tagText").value="";
        }
    }
}

function RmTag(tagID)
{
    var tagText = document.getElementById("tagVal" + tagID).value;

    var oldStr = document.getElementById("tag_str").value;
    var newStr = oldStr.replace(tagText + "||","");

    document.getElementById("tag_str").value = newStr;

    document.getElementById("tagID" + tagID).remove();
}

function TagList()
{
    var listpre = document.getElementById("tagList");
    var tagText = listpre.options[listpre.selectedIndex].value;
    var tagStr = "||" + document.getElementById("tag_str").value;

    if(tagStr.replace("||" + tagText + "||") == ("||" + document.getElementById("tag_str").value))
    {
        var block_to_insert ;
        var container_block ;
        var tag_nr = document.getElementById("tag_nr").value;

        document.getElementById("tag_nr").value = parseInt(tag_nr) + 1;

        block_to_insert = document.createElement( 'div' );
        block_to_insert.innerHTML = '<div id="tagID' + tag_nr + '"><input type="hidden" id="tagVal' + tag_nr + '" value="' + tagText + '"/><a onclick="RmTag(' + tag_nr + ');">&#128500;</a>&nbsp;' + tagText + '</div>';

        container_block = document.getElementById( 'tagContainer' );
        container_block.appendChild(block_to_insert);

        document.getElementById("tag_str").value = document.getElementById("tag_str").value + tagText + '||';
    }
    document.getElementById("tagList").value="none";

}

function ListMoveRight()
{
    if(document.getElementById("offsetIdx").value < 0)
    {
    var offsetIndex = parseInt(document.getElementById("offsetIdx").value) + parseInt(1);
    var scrollWidth = document.getElementById("scrollWidth").value;

    document.getElementById("offsetIdx").value = offsetIndex;

    document.getElementById("YearSlider").style.marginLeft = (offsetIndex * scrollWidth) + "px";
    }

}

function ListMoveLeft()
{
    var offsetIndex = parseInt(document.getElementById("offsetIdx").value) - parseInt(1);
    var scrollWidth = document.getElementById("scrollWidth").value;

    document.getElementById("offsetIdx").value = offsetIndex;

    document.getElementById("YearSlider").style.marginLeft = (offsetIndex * scrollWidth) + "px";
}

function ArchiveSelectYear(year)
{
    var lastSelection = document.getElementById("selectedYear").value;

    document.getElementById("selectedYear").value = year;

    document.getElementById("year" + lastSelection).classList.remove("active");
    document.getElementById("year" + year).classList.add("active");

    var validMonths = document.getElementById("valMonth_" + year).value


    if(validMonths.includes("|01|"))
    {
        document.getElementById("month1").className = "";
        document.getElementById("month1").onclick = function() { ArchiveSelectMonth(01); }
    }
    else
    {
        document.getElementById("month1").className = "invalid";
        document.getElementById("month1").onclick = "";
    }

    if(validMonths.includes("|02|"))
    {
        document.getElementById("month2").className = "";
        document.getElementById("month2").onclick = function() { ArchiveSelectMonth(02); }
    }
    else
    {
        document.getElementById("month2").className = "invalid";
        document.getElementById("month2").onclick = "";
    }

    if(validMonths.includes("|03|"))
    {
        document.getElementById("month3").className = "";
        document.getElementById("month3").onclick = function() { ArchiveSelectMonth(03); }
    }
    else
    {
        document.getElementById("month3").className = "invalid";
        document.getElementById("month3").onclick = "";
    }

    if(validMonths.includes("|04|"))
    {
        document.getElementById("month4").className = "";
        document.getElementById("month4").onclick = function() { ArchiveSelectMonth(04); }
    }
    else
    {
        document.getElementById("month4").className = "invalid";
        document.getElementById("month4").onclick = "";
    }

    if(validMonths.includes("|05|"))
    {
        document.getElementById("month5").className = "";
        document.getElementById("month5").onclick = function() { ArchiveSelectMonth(05); }
    }
    else
    {
        document.getElementById("month5").className = "invalid";
        document.getElementById("month5").onclick = "";
    }

    if(validMonths.includes("|06|"))
    {
        document.getElementById("month6").className = "";
        document.getElementById("month6").onclick = function() { ArchiveSelectMonth(06); }
    }
    else
    {
        document.getElementById("month6").className = "invalid";
        document.getElementById("month6").onclick = "";
    }

    if(validMonths.includes("|07|"))
    {
        document.getElementById("month7").className = "";
        document.getElementById("month7").onclick = function() { ArchiveSelectMonth(07); }
    }
    else
    {
        document.getElementById("month7").className = "invalid";
        document.getElementById("month7").onclick = "";
    }

    if(validMonths.includes("|08|"))
    {
        document.getElementById("month8").className = "";
        document.getElementById("month8").onclick = function() { ArchiveSelectMonth(08); }
    }
    else
    {
        document.getElementById("month8").className = "invalid";
        document.getElementById("month8").onclick = "";
    }

    if(validMonths.includes("|09|"))
    {
        document.getElementById("month9").className = "";
        document.getElementById("month9").onclick = function() { ArchiveSelectMonth(09); }
    }
    else
    {
        document.getElementById("month9").className = "invalid";
        document.getElementById("month9").onclick = "";
    }

    if(validMonths.includes("|10|"))
    {
        document.getElementById("month10").className = "";
        document.getElementById("month10").onclick = function() { ArchiveSelectMonth(10); }
    }
    else
    {
        document.getElementById("month10").className = "invalid";
        document.getElementById("month10").onclick = "";
    }

    if(validMonths.includes("|11|"))
    {
        document.getElementById("month11").className = "";
        document.getElementById("month11").onclick = function() { ArchiveSelectMonth(11); }
    }
    else
    {
        document.getElementById("month11").className = "invalid";
        document.getElementById("month11").onclick = "";
    }

    if(validMonths.includes("|12|"))
    {
        document.getElementById("month12").className = "";
        document.getElementById("month12").onclick = function() { ArchiveSelectMonth(12); }
    }
    else
    {
        document.getElementById("month12").className = "invalid";
        document.getElementById("month12").onclick = "";
    }


    UpdateArchiveFrame();

}

function ArchiveSelectMonth(month)
{
    var lastSelection = document.getElementById("selectedMonth").value;

    document.getElementById("selectedMonth").value = month;

    document.getElementById("month" + lastSelection).classList.remove("active");   
    document.getElementById("month" + month).classList.add("active");

    UpdateArchiveFrame();
}

function UpdateArchiveFrame()
{
    var month = document.getElementById("selectedMonth").value;
    var year = document.getElementById("selectedYear").value;

    if(document.getElementById("showDetail").checked) check = 1;
    else check = 0;



    document.getElementById("archiveFrame").src = "news-archiv-content?year=" + year + "&month=" + month + "&detail=" + check;

    document.getElementById("loaderSprite").style.opacity = 1;

    setTimeout(function() {
        document.getElementById("loaderSprite").style.opacity = 0;
    }, 800);

}

function ResizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
}