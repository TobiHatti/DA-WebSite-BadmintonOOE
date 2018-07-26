<?php
    require("header.php");

//=================================================================================
//=================================================================================
//      POST - SECTION
//=================================================================================
//=================================================================================

if(isset($_POST['add_vorstand_member']))
{
    
}

//=================================================================================
//=================================================================================
//      PAGE - SECTION
//=================================================================================
//=================================================================================

    echo '
        <a href="__tempform"><h1 class="stagfade1">Temporary Form-File</h1></a>
        <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">
    ';

    if(!isset($_GET['page']))
    {
        echo '<h3 class="stagfade2">Use temporarily for SQL/PHP Forms and Database insertions</h3> ';

        echo '
            <ul>
                <li><a href="?page=vorstand">[SQL-INSERT]: Vorand</a></li>
            </ul>
        ';
    }
    else if(isset($_GET['page']) AND $_GET['page']=='vorstand')
    {

    }

    echo '</form>';

    require("footer.php");
?>