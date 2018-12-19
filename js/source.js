/*
\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
///////////////////////////////////////////////////////////////////////////////////////

    Content:
    ====== Z - General ====================
    (Z01)   General > bgenScroll
    (Z02) 	General > LoadAnimation
    (Z03) 	General > ChangeFrameLink
    (Z04) 	General > ResizeIframe
    (Z05) 	General > ToggleTheme
    ====== A - News =======================
    (A01) 	News > TagInsert
    (A02) 	News > RmTag
    (A03) 	News > TagList
    ====== B - News-Archiv ================
    (B01) 	News-Archiv > ListMoveRight
    (B02) 	News-Archiv > ListMoveLeft
    (B03) 	News-Archiv > ArchiveSelectYear
    (B04) 	News-Archiv > ArchiveSelectMonth
    (B05) 	News-Archiv > UpdateArchiveFrame 


\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
|||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
///////////////////////////////////////////////////////////////////////////////////////
*/

/*+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+*/
/*-------------------------------|||----------------|||------------------------------*/
/*=================================== Z - General ===================================*/
/*-------------------------------|||----------------|||------------------------------*/
/*+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+*/

/*===================================================================================*/
/* (Z01) General > bgenScroll                                                        */
/*===================================================================================*/

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

/*===================================================================================*/
/* (Z02) General > LoadAnimation                                                     */
/*===================================================================================*/

function LoadAnimation()
{
    // DESCRIPTION:
    // Required for PHP-Function Loader();
    // For more info, see functions.php

    document.getElementById("loadAnim1").style.opacity=0.3;
    document.getElementById("loadAnim2").style.opacity=1;
}

/*===================================================================================*/
/* (Z03) General > ChangeFrameLink                                                   */
/*===================================================================================*/

function ChangeFrameLink(loc)
{
    // DESCRIPTION:
    // Chnages the link of an iframe
    // Required: frame-id: chframe
    // var loc  iframe-URL

    document.getElementById("chframe").src = loc;
}

/*===================================================================================*/
/* (Z04) General > ResizeIframe                                                      */
/*===================================================================================*/

function ResizeIframe(obj)
{
    // DESCRIPTION:
    // Auto resize the height
    // of an iframe to its contents
    // height.
    // include onload="ResizeIframe(this);" in iframe

    obj.style.height = obj.contentWindow.document.body.scrollHeight + 100 + 'px';
}

/*===================================================================================*/
/* (Z05) General > ToggleTheme                                                      */
/*===================================================================================*/

function ToggleTheme()
{
    // DESCRIPTION:
    // Changes between the Classic
    // and the modern Theme

    if(document.getElementById("toggle_theme").checked) document.querySelector("link[href='/css/layout_modern.css']").href = "/css/layout_classic.css";
    else document.querySelector("link[href='/css/layout_classic.css']").href = "/css/layout_modern.css";
}

/*+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+*/
/*-------------------------------|||----------------|||------------------------------*/
/*===================================== A - News ====================================*/
/*-------------------------------|||----------------|||------------------------------*/
/*+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+*/

/*===================================================================================*/
/* (A01) News > TagInsert                                                            */
/*===================================================================================*/

function TagInsert(e)
{
    // DESCRIPTION:
    // Adds the in the textbox written tag to the tag-list
    // Submits onEnter

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
            block_to_insert.innerHTML = '<div id="tagID' + tag_nr + '"><input type="hidden" id="tagVal' + tag_nr + '" value="' + tagText + '"/><a onclick="RmTag(' + tag_nr + ',\'' + tagText + '\',\'' + tagText + '\');">&#128500;</a>&nbsp;' + tagText + '</div>';

            container_block = document.getElementById( 'tagContainer' );
            container_block.appendChild( block_to_insert );

            document.getElementById("tag_str").value = document.getElementById("tag_str").value + tagText + '||';
            document.getElementById("tagText").value="";
        }
    }
}

/*===================================================================================*/
/* (A02) News > RmTag                                                                */
/*===================================================================================*/

function RmTag(tagID,tagText,tagValue)
{
    // DESCRIPTION:
    // Removes a tag from the tag-list

    // Remove
    // a) Custom Tags and Tags without special letters
    var oldStr = document.getElementById("tag_str").value;
    var newStr = oldStr.replace(tagText + "||","");

    // b) Tags with custom letters
    var oldStr = document.getElementById("tag_str").value;
    var newStr = oldStr.replace(tagValue + "||","");

    document.getElementById("tag_str").value = newStr;

    document.getElementById("tagID" + tagID).remove();
}

/*===================================================================================*/
/* (A03) News > TagList                                                              */
/*===================================================================================*/

function TagList()
{
    // DESCRIPTION:
    // Adds the in the selectbox chosen tag to the tag-list
    // Submits onClick

    var listpre = document.getElementById("tagList");
    var tagComb = listpre.options[listpre.selectedIndex].value;
    var tagStr = "||" + document.getElementById("tag_str").value;


    tagArray = tagComb.split('##');

    tagText = tagArray[0];
    tagValue = tagArray[1];

    if(tagStr.replace("||" + tagText + "||") == ("||" + document.getElementById("tag_str").value))
    {
        var block_to_insert ;
        var container_block ;
        var tag_nr = document.getElementById("tag_nr").value;

        document.getElementById("tag_nr").value = parseInt(tag_nr) + 1;

        block_to_insert = document.createElement( 'div' );
        block_to_insert.innerHTML = '<div id="tagID' + tag_nr + '"><input type="hidden" id="tagVal' + tag_nr + '" value="' + tagText + '"/><a onclick="RmTag(' + tag_nr + ',\'' + tagText + '\',\'' + tagValue + '\');">&#128500;</a>&nbsp;' + tagText + '</div>';

        container_block = document.getElementById( 'tagContainer' );
        container_block.appendChild(block_to_insert);

        document.getElementById("tag_str").value = document.getElementById("tag_str").value + tagValue + '||';
    }
    document.getElementById("tagList").value="none";

}


function LoadTags()
{
    // DESCRIPTION:
    // Loads existing Tags
    // Use for Edit

    var tagList = document.getElementById("tag_str").value;
    var tagArray = tagList.split("||");

    var block_to_insert ;
    var container_block ;
    var tag_nr = document.getElementById("tag_nr").value;

    for(var i=0 ; i < tagArray.length ; i++)
    {
        if(tagArray[i]!="")
        {
            tag_nr++;

            block_to_insert = document.createElement( 'div' );
            block_to_insert.innerHTML = '<div id="tagID' + tag_nr + '"><input type="hidden" id="tagVal' + tag_nr + '" value="' + tagArray[i] + '"/><a onclick="RmTag(' + tag_nr + ');">&#128500;</a>&nbsp;' + tagArray[i] + '</div>';

            container_block = document.getElementById( 'tagContainer' );
            container_block.appendChild( block_to_insert );
        }
    }

    document.getElementById("tag_nr").value = parseInt(tag_nr);
}

/*+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+*/
/*-------------------------------|||----------------|||------------------------------*/
/*================================= B - News-Archiv =================================*/
/*-------------------------------|||----------------|||------------------------------*/
/*+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+*/

/*===================================================================================*/
/* (B01) News-Archiv > ListMoveRight                                                 */
/*===================================================================================*/

function ListMoveRight()
{
    // DESCRIPTION:
    // Moves the Year-Selection list
    // to the right

    if(document.getElementById("offsetIdx").value < 0)
    {
    var offsetIndex = parseInt(document.getElementById("offsetIdx").value) + parseInt(1);
    var scrollWidth = document.getElementById("scrollWidth").value;

    document.getElementById("offsetIdx").value = offsetIndex;

    document.getElementById("YearSlider").style.marginLeft = (offsetIndex * scrollWidth) + "px";
    }
}

/*===================================================================================*/
/* (B02) News-Archiv > ListMoveLeft                                                  */
/*===================================================================================*/

function ListMoveLeft()
{
    // DESCRIPTION:
    // Moves the Year-Selection list
    // to the left

    var offsetIndex = parseInt(document.getElementById("offsetIdx").value) - parseInt(1);
    var scrollWidth = document.getElementById("scrollWidth").value;

    document.getElementById("offsetIdx").value = offsetIndex;

    document.getElementById("YearSlider").style.marginLeft = (offsetIndex * scrollWidth) + "px";
}

/*===================================================================================*/
/* (B03) News-Archiv > ArchiveSelectYear                                             */
/*===================================================================================*/

function ArchiveSelectYear(year)
{
    // DESCRIPTION:
    // Selects the year and
    // checks valid months

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

/*===================================================================================*/
/* (B04) News-Archiv > ArchiveSelectMonth                                            */
/*===================================================================================*/

function ArchiveSelectMonth(month)
{
    // DESCRIPTION:
    // Selects the month

    var lastSelection = document.getElementById("selectedMonth").value;

    document.getElementById("selectedMonth").value = month;

    document.getElementById("month" + lastSelection).classList.remove("active");   
    document.getElementById("month" + month).classList.add("active");

    UpdateArchiveFrame();
}

/*===================================================================================*/
/* (B05) News-Archiv > UpdateArchiveFrame                                            */
/*===================================================================================*/

function UpdateArchiveFrame()
{
    // DESCRIPTION:
    // Updates the iframe and refreshes
    // its content

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



function CopySliderTitle(maxSlides,sdmActive)
{
    var list = document.getElementsByClassName("ws-title")[0];
    document.getElementById("slider_news_title").value = list.getElementsByTagName("SPAN")[0].innerHTML;
    var currentTitle = list.getElementsByTagName("SPAN")[0].innerHTML;

    for(var i=1 ; i <= maxSlides ; i++)
    {
        if(document.getElementById("slideTitle" + i).value == currentTitle)
        {
            document.getElementById("sliderDate").value = document.getElementById("slideDate" + i).value;
            document.getElementById("sliderLink").href = "/news/artikel/" + document.getElementById("slideLink" + i).value;
        }
    }

    if(sdmActive)
    {
        if(document.getElementById("slideTitle99").value == currentTitle)
        {
            document.getElementById("sliderDate").value = document.getElementById("slideDate99").value;
            document.getElementById("sliderLink").href = "/spieler-des-monats/" + document.getElementById("slideLink99").value;
        }
    }
}


function SelectGalleryImage(imageID)
{
    var selectedImg = document.getElementById("galleryImg" + imageID).src;
    document.getElementById("galleryFullSized").src = selectedImg;
    if(document.getElementById("galleryAllowDownload").value=="1") document.getElementById("galleryDownload").href = selectedImg;
    document.getElementById("currentImageID").value = imageID;

    document.getElementById("galleryDeleteLink").href="/delete/gallery_images/Gallery/" + imageID;
}

function SelectNextImage()
{
    var selectedImgId = parseInt(document.getElementById("currentImageID").value);
    var selectedImg = document.getElementById("galleryImg" + (selectedImgId+1)).src;

    document.getElementById("galleryFullSized").src = selectedImg;
    document.getElementById("currentImageID").value = selectedImgId + 1;
}

function SelectLastImage()
{
    var selectedImgId = parseInt(document.getElementById("currentImageID").value);
    var selectedImg = document.getElementById("galleryImg" + (selectedImgId-1)).src;

    document.getElementById("galleryFullSized").src = selectedImg;
    document.getElementById("currentImageID").value = selectedImgId - 1;
}

function SelectZAKategory()
{
    var listpre = document.getElementById("zaKategory");
    var kategory = listpre.options[listpre.selectedIndex].value;


    if(kategory == "Landesmeisterschaft")
    {
        document.getElementById("zaTitleLineOut1").style.color = "#FF0000";
        document.getElementById("zaTitleLineOut2").style.color = "#FF0000";
    }
    else if(kategory == "Doppelturnier")
    {
        document.getElementById("zaTitleLineOut1").style.color = "#20B2AA";
        document.getElementById("zaTitleLineOut2").style.color = "#20B2AA";
    }
    else if(kategory == "Nachwuchs")
    {
        document.getElementById("zaTitleLineOut1").style.color = "#FFA500";
        document.getElementById("zaTitleLineOut2").style.color = "#FFA500";
    }
    else if(kategory == "SchuelerJugend")
    {
        document.getElementById("zaTitleLineOut1").style.color = "#9400D3";
        document.getElementById("zaTitleLineOut2").style.color = "#9400D3";
    }
    else if(kategory == "Senioren")
    {
        document.getElementById("zaTitleLineOut1").style.color = "#32CD32";
        document.getElementById("zaTitleLineOut2").style.color = "#32CD32";
    }

    if(kategory == "Training")
    {
        document.getElementById("zaOptions").style.display = "none";
        document.getElementById("zaTitleLineIn2").style.display = "none";

        document.getElementById("zaFieldS").style.display = "inline-flex";
        document.getElementById("zaFieldL").style.display = "none";
    }
    else
    {
        document.getElementById("zaOptions").style.display = "inline-table";
        document.getElementById("zaTitleLineIn2").style.display = "block";

        document.getElementById("zaFieldL").style.display = "inline-flex";
        document.getElementById("zaFieldS").style.display = "none";
    }

}

function CopyZADate()
{
    var monthNames = [
        "J\u00e4nner", "Februar", "M\u00e4rz",
        "April", "Mai", "Juni", "Juli",
        "August", "September", "Oktober",
        "November", "Dezember"
    ];

    var monthNamesS = [
        "Jan", "Feb", "M\u00e4r",
        "Apr", "Mai", "Jun", "Jul",
        "Aug", "Sep", "Okt",
        "Nov", "Dez"
    ];

    var dayNames = [
        "Sonntag","Montag","Dienstag","Mittwoch",
        "Donnerstag","Freitag","Samstag"
    ];

    var dayNamesS = [
        "So","Mo","Di","Mi","Do","Fr","Sa"
    ];

    var date1 = new Date(document.getElementById("datePick1").value);
    var date2 = new Date(document.getElementById("datePick2").value);


    if(document.getElementById("chTimespan").checked)
    {
        document.getElementById("rwTimespan").style.display = "table-row";
        document.getElementById("outTimespan").value = "Von: ";

        var dayIndex1 = date1.getDay();
        var day1 = date1.getDate();
        var dayIndex2 = date2.getDay();
        var day2 = date2.getDate();
        var monthIndex1 = date1.getMonth();
        var monthIndex2 = date2.getMonth();
        var year = date1.getFullYear();

        if(day1 == day2-1)
        {
            document.getElementById("zaDate").value = dayNamesS[dayIndex1] + '/' + dayNamesS[dayIndex2] +  ', ' + day1 + './' + day2 + '. ' + monthNames[monthIndex1] + ' ' + year;
            document.getElementById("zaDateS").value = dayNamesS[dayIndex1] + '/' + dayNamesS[dayIndex2] +  ', ' + day1 + './' + day2 + '. ' + monthNames[monthIndex1] + ' ' + year;
        }
        else
        {
            if(monthIndex1 == monthIndex2)
            {
                document.getElementById("zaDate").value = dayNamesS[dayIndex1] + ' ' + day1 + '. - ' + dayNamesS[dayIndex2] + ' ' + day2 + '. ' + monthNames[monthIndex1] + ' ' + year;
                document.getElementById("zaDateS").value = dayNamesS[dayIndex1] + ' ' + day1 + '. - ' + dayNamesS[dayIndex2] + ' ' + day2 + '. ' + monthNames[monthIndex1] + ' ' + year;
            }
            else
            {
                document.getElementById("zaDate").value = dayNamesS[dayIndex1] + ' ' + day1 + '. ' + monthNamesS[monthIndex1] + ' - ' + dayNamesS[dayIndex2] + ' ' + day2 + '. ' + monthNamesS[monthIndex2] + ' ' + year;
                document.getElementById("zaDateS").value = dayNamesS[dayIndex1] + ' ' + day1 + '. ' + monthNamesS[monthIndex1] + ' - ' + dayNamesS[dayIndex2] + ' ' + day2 + '. ' + monthNamesS[monthIndex2] + ' ' + year;
            }
        }
    }
    else
    {
        document.getElementById("rwTimespan").style.display = "none";
        document.getElementById("outTimespan").value = "";

        var dayIndex = date1.getDay();
        var day = date1.getDate();
        var monthIndex = date1.getMonth();
        var year = date1.getFullYear();

        document.getElementById("zaDateS").value = dayNames[dayIndex] +  ', ' + day + '. ' + monthNames[monthIndex] + ' ' + year;
        document.getElementById("zaDate").value = dayNames[dayIndex] +  ', ' + day + '. ' + monthNames[monthIndex] + ' ' + year;
    }


}

function CopyZATitle()
{
    document.getElementById("zaTitleLineOut1").value = document.getElementById("zaTitleLineIn1").value;
    document.getElementById("zaTitleLineOutS").value = document.getElementById("zaTitleLineIn1").value;
    document.getElementById("zaTitleLineOut2").value = document.getElementById("zaTitleLineIn2").value;
}

function ChangeZAExtraData(idx)
{
    if(document.getElementById("chid" + idx).checked) document.getElementById("edat" + idx).style.display = "table-row";
    else document.getElementById("edat" + idx).style.display = "none";
}

function DisableZAOption(idx)
{
    document.getElementById("edat" + idx).style.display = "none";
    document.getElementById("chid" + idx).checked = false;
}

function UpdateZAVerein()
{
    var listpre = document.getElementById("vereinSelection");
    var selected = listpre.options[listpre.selectedIndex].value;

    document.getElementById("verein_in").value = selected;
    document.getElementById("vereinSelection").selectedIndex = "0";

}

function JSColorUpdate(jscolor)
{
    document.getElementById('app_preview').style.backgroundColor = '#' + jscolor
}

function ChangeCalenderStyle()
{
    if(document.getElementById("changeListStyle").checked)
    {
        document.getElementById("CalenderList").style.display = "none";
        document.getElementById("CalenderGraphic").style.display = "block";

        window.sessionStorage.setItem("toggleCalendar",1);
    }
    else
    {
        document.getElementById("CalenderList").style.display = "block";
        document.getElementById("CalenderGraphic").style.display = "none";

        window.sessionStorage.setItem("toggleCalendar",0);
    }
}



function ChangeDateLocActive(e)
{
    if(!e.checked)
    {
        document.getElementById("galdate").disabled = true;
        document.getElementById("gallocation").disabled = true;
    }
    else
    {
        document.getElementById("galdate").disabled = false;
        document.getElementById("gallocation").disabled = false;
    }
}


function CheckToggleSession(buttonElement, sessionName)
{
    // WARNING: Only possible for 1 Toggleswitch per page
    var sessVal = window.sessionStorage.getItem(sessionName);

    if(sessVal == 1) document.getElementById(buttonElement).checked = true;
    else document.getElementById(buttonElement).checked = false;
}

function SetSearchSettings()
{
    var listpre = document.getElementById("ChangeSearchSubject");
    var subject = listpre.options[listpre.selectedIndex].value;

    var listpre = document.getElementById("ChangeSearchLimit");
    var limit = listpre.options[listpre.selectedIndex].value;

    var search = document.getElementById("seachVal").value;

    window.location.replace("/suche/" + subject + "/" + search + "/" + limit);
}

function RedirectListLink(e)
{
    var link = e.options[e.selectedIndex].value;

    e.selectedIndex = "0";

    window.open(link,'_blank');
}

function AddContentToTextarea(insertValElementId)
{
    var content = document.getElementsByClassName("fr-element")[0].innerHTML;
    document.getElementsByClassName("fr-element fr-view")[0].innerHTML = content + document.getElementById(insertValElementId).value;
}


function ConvertDiv2Base64Src(divID,exportSrcId)
{
    // Requires HTML2Canvas
    html2canvas(document.getElementById(divID)).then(function(canvas) {
        var base64image = canvas.toDataURL("image/png");

        document.getElementById(exportSrcId).src = base64image;
    });
}

function ConvertDiv2Base64Froala(divID)
{
    // Requires HTML2Canvas
    html2canvas(document.getElementById(divID)).then(function(canvas) {
        var base64image = canvas.toDataURL("image/png");

        var froalaContent = window.parent.document.getElementsByClassName("fr-element")[0].innerHTML;
        window.parent.document.getElementsByClassName("fr-element fr-view")[0].innerHTML = froalaContent + '<img src="' + base64image + '" alt="" style="width: 300px;"/>';
    });
}

function AddStringToFroala(string)
{
    var froalaContent = window.parent.document.getElementsByClassName("fr-element")[0].innerHTML;

    window.parent.document.getElementsByClassName("fr-element fr-view")[0].innerHTML = froalaContent + string;

}

function ChangeNewsImageCreatorLink()
{
    var listpre = document.getElementById("themeSelector");
    var template = listpre.options[listpre.selectedIndex].value;

    document.getElementById("imageCreatorFrame").src = "/newsimagecreator?template=" + template;
}

function CopyTextToSpan(e,spanID)
{
    document.getElementById(spanID).innerHTML = e.value;
}

function ChangeFontSize(e,divID)
{
    document.getElementById(divID).style.fontSize = e.value + "pt";
}

function ChangeImageSize(e,divID)
{
    document.getElementById(divID).style.width = e.value + "px";
}

function ReadURLDivBG(input,outputDiv)
{
    //input = this
    if (input.files && input.files[0])
    {
        var reader = new FileReader();
        reader.onload = function (e)
        {
            document.getElementById(outputDiv).style.backgroundImage = "url('" + e.target.result + "')";
            document.getElementById("saveOutput").value = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function ReadURL(input,outputImg)
{
    //input = this

    if (input.files && input.files[0])
    {
        var reader = new FileReader();
        reader.onload = function (e)
        {
            $('#' + outputImg)
                .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function ShowHideElement(e,elementId)
{
    if(e.checked) document.getElementById(elementId).style.display = "block";
    else document.getElementById(elementId).style.display = "none";
}

function ShowHideTableRow(e,elementId)
{
    if(e.checked) document.getElementById(elementId).style.display = "table-row";
    else document.getElementById(elementId).style.display = "none";
}

function ShowHideBGImage(e,divID)
{
    var saveImg = document.getElementById("saveOutput").value;

    if(e.checked)
    {
        document.getElementById(divID).style.backgroundImage = "";
    }
    else
    {
        document.getElementById(divID).style.backgroundImage = "url('" + saveImg + "')";
        document.getElementById(divID).style.backgroundSize = "cover";
    }

}

function ShowElement(elementID)
{
    document.getElementById(elementID).style.display = "block";

}

function ToggleSwitch(switchID)
{
    if(document.getElementById(switchID).checked) document.getElementById(switchID).checked = false;
    else document.getElementById(switchID).checked = true;
}

function ActivateSwitch(switchID)
{
    document.getElementById(switchID).checked = true;
}

function TemplatesUpdateBGRows()
{
    if(document.getElementById("chBg").checked)
    {
        document.getElementById("bgColorRowBGImage").style.display = "none";

        document.getElementById("bgColorRowGradientCheck").style.display = "table-row";

        if(document.getElementById("toggleBgStyle").checked)
        {
            document.getElementById("bgColorRowPicker1").style.display = "table-row";
            document.getElementById("bgColorRowGPicker2").style.display = "table-row";
            document.getElementById("bgColorRowGradientDirections").style.display = "table-row";
        }
        else
        {
            document.getElementById("bgColorRowPicker1").style.display = "table-row";
            document.getElementById("bgColorRowGPicker2").style.display = "none";
            document.getElementById("bgColorRowGradientDirections").style.display = "none";
        }
    }
    else
    {
        document.getElementById("bgColorRowBGImage").style.display = "table-row";

        document.getElementById("bgColorRowGradientCheck").style.display = "none";
        document.getElementById("bgColorRowPicker1").style.display = "none";
        document.getElementById("bgColorRowGPicker2").style.display = "none";
        document.getElementById("bgColorRowGradientDirections").style.display = "none";
    }

}

function GetCssValuePrefix()
{
    var rtrnVal = '';//default to standard syntax
    var prefixes = ['-o-', '-ms-', '-moz-', '-webkit-'];

    // Create a temporary DOM object for testing
    var dom = document.createElement('div');

    for (var i = 0; i < prefixes.length; i++)
    {
        // Attempt to set the style
        dom.style.background = prefixes[i] + 'linear-gradient(#000000, #ffffff)';

        // Detect if the style was successfully set
        if (dom.style.background)
        {
            rtrnVal = prefixes[i];
        }
    }

    dom = null;
    delete dom;

    return rtrnVal;
}

function TemplatesSetBGColor()
{
    var listpre = document.getElementById("bgGradientOrientation");
    var orientation = listpre.options[listpre.selectedIndex].value;

    var orientationParts = orientation.split("||");

    var orientation1 = orientationParts[0];
    var orientation2 = orientationParts[1];

    var colorOne = "#" + document.getElementById("colorBG1").value;
    var colorTwo = "#" + document.getElementById("colorBG2").value;

    if(document.getElementById("toggleBgStyle").checked)
    {
        $('#creatorContainer').css({
            background:  GetCssValuePrefix() + "gradient(linear, " + orientation1 + ", " + orientation2 + ", from(" + colorOne + "), to(" + colorTwo + "))"
        });
    }
    else
    {
        document.getElementById("creatorContainer").style.background = colorOne;
    }
}

function TemplatesGetPosition()
{
    document.getElementById("outT1x").value = document.getElementById("dragT1").style.left;
    document.getElementById("outT1y").value = document.getElementById("dragT1").style.top;

    document.getElementById("outT2x").value = document.getElementById("dragT2").style.left;
    document.getElementById("outT2y").value = document.getElementById("dragT2").style.top;

    document.getElementById("outT3x").value = document.getElementById("dragT3").style.left;
    document.getElementById("outT3y").value = document.getElementById("dragT3").style.top;

    document.getElementById("outImgx").value = document.getElementById("dragImg").style.left;
    document.getElementById("outImgy").value = document.getElementById("dragImg").style.top;
}

function CopyOptionToTextbox(e,textboxID)
{
    document.getElementById(textboxID).value = e.options[e.selectedIndex].value;
    e.selectedIndex = "0";
}

function EMailCheck(e,output,button)
{
    var email = e.value;
    var re = /\S+@\S+\.\S+/;
    var mail_ok = false;

    mail_ok = re.test(email);

    if(mail_ok == true || e.value == "")
    {
        document.getElementById(output).value = " ";
        document.getElementById(button).disabled = false;
    }
    else
    {
        document.getElementById(output).value = "Geben Sie eine g\u00fcltige E-Mail Adresse ein!";
        document.getElementById(button).disabled = true;
    }
}

function CheckPasswordPair(e, pairID, output, button)
{
    if(e.value == document.getElementById(pairID).value)
    {
        document.getElementById(output).value = " ";
        document.getElementById(button).disabled = false;
    }
    else
    {
        document.getElementById(output).value = "Passw\u00f6rter sind nicht identisch!";
        document.getElementById(button).disabled = true;
    }
}

function CheckSortableListState(ulListID,outputID)
{
    var myList = document.getElementById(ulListID);
    var myListItems = myList.getElementsByTagName("li");

    var output = document.getElementById(outputID);

    output.value="";

    for (i = 0; i < myListItems.length; ++i) {
        // Execute Event for every list-element
        output.value = output.value + (i+1) + "##" + myListItems[i].value + "||";
    }
}

function RedirectSelectBox(e,linkBase)
{
    var selectedOption = e.options[e.selectedIndex].value;

    window.location.replace(linkBase + selectedOption);
}

function RedirectSelectBoxSpielerrangliste(e,linkBase)
{
    var selectedOption = e.options[e.selectedIndex].value;

    if(selectedOption == "multi") document.getElementById("clubToggleList").style.display = "table-row";
    else
    {
        document.getElementById("clubToggleList").style.display = "none";
        window.location.replace(linkBase + selectedOption);
    }
}


function UpdateClubList(e,club)
{
    var list = document.getElementById("customList");

    if(e.checked)
    {

        list.value = list.value + club + "-";
    }
    else
    {
        list.value = list.value.replace(club + '-','')
    }
}

function RedirectCustomClubList(linkBase)
{
    var selectedClubs = document.getElementById("customList").value;
    selectedClubs = selectedClubs.substring(0, selectedClubs.length - 1);

    window.location.replace(linkBase + "M" + selectedClubs);
}