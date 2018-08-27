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
    document.getElementById("selectedYear").value = year;
    UpdateArchiveFrame();
}

function ArchiveSelectMonth(month)
{
    document.getElementById("selectedMonth").value = month;
    UpdateArchiveFrame();
}

function UpdateArchiveFrame()
{
    var month = document.getElementById("selectedMonth").value;
    var year = document.getElementById("selectedYear").value;

    document.getElementById("archiveFrame").src = "news-archiv-content?year=" + year + "&month=" + month;
}