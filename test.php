<?php
    include("header.php");


    echo '



    <script>
        $( function() {
            $( "#sortable" ).sortable();
            $( "#sortable" ).disableSelection();
        } );


        function CheckListState()
        {
            var myList = document.getElementById("sortable");
            var myListItems = myList.getElementsByTagName("li");

            var output = document.getElementById("output");

            output.value="";

            for (i = 0; i < myListItems.length; ++i) {
                // Execute Event for every list-element
                output.value = output.value + myListItems[i].innerHTML + " is at position " + (i+1) + "\n";
            }
        }

        window.setInterval(function(){
            CheckListState();
        }, 100);

    </script>

    <ul class="dragSortList_posNumbers">
        <li>1</li>
        <li>2</li>
        <li>3</li>
        <li>4</li>
        <li>5</li>
        <li>6</li>
        <li>7</li>
    </ul>

    <ul class="dragSortList_values" id="sortable">
        <li>Item 1</li>
        <li>Item 2</li>
        <li>Item 3</li>
        <li>Adolf</li>
        <li>Item 5</li>
        <li>Item 6</li>
        <li>Item 7</li>
    </ul>


    <br><br><br>

    <textarea id="output"></textarea>


    ';


    include("footer.php");

?>



