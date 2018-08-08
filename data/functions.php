<?php
function PreventAutoScroll()
{
    // DESCRIPTION:
    // When using CSS-Targets, a <a href="#anchor"> is required.
    // To prevent the page from jumping to the anchor and therefor putting
    // it on top of the page, the <a>-Tag needs to contain the following event:
    // onclick="bgenScroll();"

    return '
        <script language="JavaScript" type="text/javascript">
            //Prevent Autoscroll for target-anchors
            function bgenScroll() {
             if (window.pageYOffset!= null){
              st=window.pageYOffset+"";
             }
             if (document.body.scrollWidth!= null){
              if (document.body.scrollTop){
              st=document.body.scrollTop;
              }
              st=document.documentElement.scrollTop;
             }
              setTimeout("window.scroll(0,st)",10);
            }
        </script>';
}

function Checkbox($name, $id, $checked = 0)
{
    // DESCRIPTION:
    // Returns a Checkbox Form-Element
    // $name    Form-Element-Name
    // $id      Unique ID. Required since it uses a label
    // $checked Default: 0. Sets the checkbox to checked (1)

    return '<input type="checkbox" name="'.$name.'" id="'.$id.'" class="slidecheckbox" '.(($checked) ? 'checked' : '').'/><label class="checkbox_toggle_lable" for="'.$id.'">Toggle</label>';
}

function RadioButton($title, $name, $checked = 0)
{
    // DESCRIPTION:
    // Returns a Radio-Button Form-Element
    // $title   Text beside the Radio-Button
    // $name    Form-Element-Name
    // $checked Default: 0. Sets the Button to checked (1)

    return '
        <label class="radiolabel">'.$title.'
            <input type="radio" name="'.$name.'" '.(($checked) ? 'checked' : '').'>
            <span class="radiocheckmark"></span>
        </label>
    ';
}

function FileButton($name, $id, $multiple=0)
{
    // DESCRIPTION:
    // Returns a File-Upload Form-Element
    // $name      Form-Element-Name
    // $id        Unique ID. Required since it uses a label
    // $multiple  Default: 0. Defines if multiple files can be selected

    return  '
        <input type="file" name="'.$name.'[]" id="'.$id.'" class="inputfile" data-multiple-caption="{count} Dateien" '.(($multiple) ? 'multiple' : '').' hidden/>
        <label for="'.$id.'"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span style="color:#FEFEFE;">Datei ausw&auml;hlen</span></label>
        <script src="/js/filebutton.js"></script>
    ';
}

function TextareaPlus($name)
{
    return '
        <script src="/js/froala/de.js"></script>

        <textarea id="froala-editor">Initialize the Froala WYSIWYG HTML Editor on a textarea.</textarea>

        <div id="edit"></div>
        <script>
            $(function() {
                $("textarea#froala-editor").froalaEditor({
                    // Set the language code.
                    language: "de"
                })
            });
        </script>


        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>
        <script type="text/javascript" src="/js/froala/froala_editor.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/align.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/code_beautifier.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/code_view.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/draggable.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/image.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/image_manager.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/link.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/lists.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/paragraph_format.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/paragraph_style.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/table.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/video.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/url.min.js"></script>
        <script type="text/javascript" src="/js/froala/plugins/entities.min.js"></script>
    ';
}

function PageTitle($string)
{
    // DESCRIPTON:
    // Changes the Page-Title in the Tab
    // Since it is changed in the Middle of the page, and not in the <head>-part,
    // Javascript is required to do so.

    echo '<script>document.title = "'.$string.' | O\u00d6. Badmintonverband";</script>';
}

function PageContent($paragraph_index)
{
    // DESCRIPTION:
    // Gets the text/description for the current page
    // With $paragraph_index, several entries can be saved in one page.

    $page = ThisPage();
    return nl2br(MySQLSkalar("SELECT text AS x FROM page_content WHERE page = '$page' AND paragraph_index = '$paragraph_index'"));
}

function Loader()
{
    return '
        <center>
            <img src="/content/silhouette.png" alt="" class="ease10" style="height: 100px;" id="loadAnim1"/>
            <img src="/content/loader.gif" alt="" class="ease10" style="height: 60px; opacity: 0; margin-left:-80px;margin-bottom:20px;" id="loadAnim2"/>
        </center>
    ';
}
?>