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

    if (e.keyCode == 13 && tagText!= "")
    {
        var block_to_insert ;
        var container_block ;

        block_to_insert = document.createElement( 'div' );
        block_to_insert.innerHTML = '<a onclick="rmTag();">&#128500;</a>&nbsp;' + tagText ;

        container_block = document.getElementById( 'tagContainer' );
        container_block.appendChild( block_to_insert );
    }
}