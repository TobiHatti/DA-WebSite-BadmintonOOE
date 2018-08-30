<?php
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

                        var imgId1, imgId2, imgId3;

                        imgId1 = Math.floor(Math.random()*sponsors_img.length);

                        do { imgId2 = Math.floor(Math.random()*sponsors_img.length); }
                        while(imgId1 == imgId2);

                        do { imgId3 = Math.floor(Math.random()*sponsors_img.length); }
                        while(imgId1 == imgId3 || imgId2 == imgId3);

                        document.getElementById("sponsorImg1").src="/content/sponsors/" + sponsors_img[imgId1];
                        document.getElementById("sponsorImg2").src="/content/sponsors/" + sponsors_img[imgId2];
                        document.getElementById("sponsorImg3").src="/content/sponsors/" + sponsors_img[imgId3];
                    }


                </script>

                Unsere Partner:<br><br>

                <div id="sponsorSlide" class="footer_sponsor_container ease50">

                    <img id="sponsorImg1" class="footer_sponsors ease1s" src="/content/sponsors/sporthilfe.png" alt="" />
                    <img id="sponsorImg2" class="footer_sponsors ease1s" src="/content/sponsors/sportooe.png" alt="" />
                    <img id="sponsorImg3" class="footer_sponsors ease1s" src="/content/sponsors/raiffeisen.png" alt="" />

                </div>
                <br>



                <span style="color: #696969">&copy; Copyright 2018 Tobias Hattinger & Paul Luger</span> | <a href="impressum">Impressum</a>
            </footer>
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