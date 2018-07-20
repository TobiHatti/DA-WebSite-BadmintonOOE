<?php
    require("header.php");

    echo '
        <h1 class="stagfade1">Temporary Form-File</h1>

        <h3 class="stagfade2">Use temporarily for SQL/PHP Forms and Database insertions</h3>
        <br><br><br>

        <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data">

            button<input type="button" value="hallo i bims ein knopf"/><br><br>
            checkbox<input type="checkbox"/><br><br>
            color<input type="color"/><br><br>
            date<input type="date"/><br><br>
            datetime-local<input type="datetime-local"/><br><br>
            email<input type="email"/><br><br>
            file<input type="file"/><br><br>
            month<input type="month"/><br><br>
            number<input type="number"/><br><br>
            password<input type="password"/><br><br>
            radio<input type="radio"/><br><br>
            range<input type="range"/><br><br>
            reset<input type="reset"/><br><br>
            search<input type="search"/><br><br>
            submit<input type="submit"/><br><br>
            tel<input type="tel"/><br><br>
            text<input type="text"/><br><br>
            time<input type="time"/><br><br>
            url<input type="url"/><br><br>
            week<input type="week"/><br><br>


            <br><br><br><br>

            <button type="submit">BESTÄTIGEN</button>

        </form>




    ';

    require("footer.php");
?>