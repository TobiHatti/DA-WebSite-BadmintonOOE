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
    var tagStr = ";" + document.getElementById("tag_str").value;

    if (e.keyCode == 13 && tagText!= "")
    {
        if(tagStr.replace(";" + tagText + ";") == (";" + document.getElementById("tag_str").value))
        {
            var block_to_insert ;
            var container_block ;
            var tag_nr = document.getElementById("tag_nr").value;

            document.getElementById("tag_nr").value = parseInt(tag_nr) + 1;

            block_to_insert = document.createElement( 'div' );
            block_to_insert.innerHTML = '<div id="tagID' + tag_nr + '"><input type="hidden" id="tagVal' + tag_nr + '" value="' + tagText + '"/><a onclick="RmTag(' + tag_nr + ');">&#128500;</a>&nbsp;' + tagText + '</div>';

            container_block = document.getElementById( 'tagContainer' );
            container_block.appendChild( block_to_insert );

            document.getElementById("tag_str").value = document.getElementById("tag_str").value + tagText + ';';
            document.getElementById("tagText").value="";
        }
    }
}

function RmTag(tagID)
{
    var tagText = document.getElementById("tagVal" + tagID).value;

    var oldStr = document.getElementById("tag_str").value;
    var newStr = oldStr.replace(tagText + ";","");

    document.getElementById("tag_str").value = newStr;

    document.getElementById("tagID" + tagID).remove();
}