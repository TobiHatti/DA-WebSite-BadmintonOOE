<?php
    require("data/mysql_connect.php");

     echo '
            </main>
            <footer>

                <script>
                    $(window).on("scroll load resize", function() {
                    	var scrollHeight = $(document).height();
                    	var scrollPosition = $(window).height() + $(window).scrollTop();
                    	if ((scrollHeight - scrollPosition) / scrollHeight === 0) {
                    	    document.getElementById("sponsorSlide").style.opacity = 1;
                    	}
                        else document.getElementById("sponsorSlide").style.opacity = 0;
                    });

                    window.setInterval(function(){
                      UpdateSponsors();
                    }, 5000);

                    function UpdateSponsors()
                    {
                        var sponsors_img = [
                            ';

                            $i=0;
                            $strSQL = "SELECT * FROM sponsors";
                            $rs=mysqli_query($link,$strSQL);
                            while($row=mysqli_fetch_assoc($rs))
                            {
                                if($i++ == 0) echo '"'.$row['image'].'"';
                                else echo ',"'.$row['image'].'"';
                            }

                            echo '
                        ];

                        var sponsors_link = [
                            ';

                            $i=0;
                            $strSQL = "SELECT * FROM sponsors";
                            $rs=mysqli_query($link,$strSQL);
                            while($row=mysqli_fetch_assoc($rs))
                            {
                                if($i++ == 0) echo '"'.$row['link'].'"';
                                else echo ',"'.$row['link'].'"';
                            }

                            echo '
                        ];

                        var imgId1, imgId2, imgId3;

                        imgId1 = Math.floor(Math.random()*sponsors_img.length);

                        do { imgId2 = Math.floor(Math.random()*sponsors_img.length); }
                        while(imgId1 == imgId2);

                        do { imgId3 = Math.floor(Math.random()*sponsors_img.length); }
                        while(imgId1 == imgId3 || imgId2 == imgId3);

                        document.getElementById("sponsorImg1").src="/content/sponsors/" + sponsors_img[imgId1];
                        document.getElementById("sponsorImg2").src="/content/sponsors/" + sponsors_img[imgId2];
                        document.getElementById("sponsorImg3").src="/content/sponsors/" + sponsors_img[imgId3];

                        document.getElementById("sponsorLink1").href = sponsors_link[imgId1];
                        document.getElementById("sponsorLink2").href = sponsors_link[imgId2];
                        document.getElementById("sponsorLink3").href = sponsors_link[imgId3];
                    }


                </script>

                Unsere Partner:<br><br>

                <div id="sponsorSlide" class="footer_sponsor_container ease50">

                    ';

                    $i=1;
                    $strSQL = "SELECT * FROM sponsors ORDER BY RAND() LIMIT 3";
                    $rs=mysqli_query($link,$strSQL);
                    while($row=mysqli_fetch_assoc($rs))
                    {
                        echo '<a href="'.$row['link'].'" taget="_blank" id="sponsorLink'.$i.'"><img id="sponsorImg'.$i++.'" class="footer_sponsors ease1s" src="/content/sponsors/'.$row['image'].'" alt="" /></a>';
                    }

                    echo '
                </div>
                <br>


                <br>
                <span style="color: #696969">&copy; Copyright 2018-2019 Tobias Hattinger & Paul Luger</span> | <a href="/impressum">Impressum</a> | <a href="https://development.endix.at/de/projekte/badminton-ooe/support">Fehler melden</a>
            </footer>

            <script>
                $( document ).ready(function() {
                    var doc = document.getElementsByClassName("fr-wrapper");
                    var nodes = doc[0].childNodes;
                    var nodeNodes = nodes[0].childNodes;
                    if(nodeNodes[0].tagName == "A") nodeNodes[0].style.display = "none";
                });
            </script>
        </body>
    ';


?>


<!--

       _
       \`*-.
        )  _`-.
       .  : `. .
       : _   '  \
       ; *` _.   `*-._
       `-.-'          `-.
         ;       `       `.
         :.       .        \
         . \  .   :   .-'   .
         '  `+.;  ;  '      :
         :  '  |    ;       ;-.
         ; '   : :`-:     _.`* ;
      .*' /  .*' ; .*`- +'  `*'
      `*-*   `*-*  `*-*'

This cat makes shure all the Bugs stay in this bucket down there.
          _______
      _.-()______)-._
    .'               '.
   /                   \
  :      _________      :
  |.--'''         '''--.|
  (                     )
  :'--..._________...--':
  :                     :
   :                   :
   :                   :
    :                 :
    :                 :
     :               :
     :               :
      :             :
      :_           _:
        '''-----'''

Please feed it some mice or fishes once in a while or it will let out all
the bugs and nothing works anymore.

          /"*._         _
      .-*'`    `*-.._.-'/
    < * ))     ,       (
      `*-._`._(__.--*"`.\


-->