<!--<div class="block-fluid" id="wysiwyg_container" style="padding-right:15px">
	<textarea name="editor" id="editor"><?php #echo $adtext ?></textarea>	
</div>    -->
<?php $size = 10; $scale = 3.2; ?> 
<style type="text/css">
                             
    .txt-editor {border:1px solid black;height:300px;width:500px;}
    .txt-editor-preview {margin:0 20px;float:left;border:1px solid black;height:300px;width:420px;}
    .txt-editor-preview {font-size:<?php echo $size * $scale; ?>px; font-family: "Helvetica","switzerlandcondblackregular"}
    /*.txt-editor-preview > div.line {width:<?php echo ($size * 12) * $scale; ?>px;-border-right:1px dashed black;}*/
    div.bold {font-weight:bold;}
    div.left {float:left;}
    div.justify {text-align:justify;}
    div.center {text-align:center;}
    div.right{text-align:right;}
    .clear {clear:both;}
    p span {width:100px;display:inline-block;font-weight:bold;}
    p.right span {-text-align:right;width:50px;}
</style>
<div style="float: left">
<textarea name="editorx" id="editorx" style="width: 279px; height: 277px;"><?php echo trim($adtext); ?></textarea> 
</div>                 
<div class='txt-editor-preview' style="float: left"></div>   
<div style="float: left; width: 100%;">
    <div style="float:left; width: 120px; height: 25px;margin-top:5px"><button class="btn btn-block" type="button" id="etextokb" name="etextokb">Ok Button</button></div>
    <div style="text-align: center;float:left; width: 150px; border: 1px solid #CCCCCC; height: 25px;margin-top:5px;margin-left:15px; font-size:20px; color: red">
        <label><span id="w"><?php echo $w ?></span> X <span id="l"><?php echo $l ?></span></label>
    </div>        
</div>
<div class="left" style="float: left; width: 45%;">
    <h4>BASIC CHARACTERS</h4> 
    <p>
        <span>START</span> = *s8b*<br />
        <span>FONT SIZE</span> = [s?%] (? = size, eg:75)<br />
        <span>CENTER</span> = /C<br />
        <span>JUSTIFY</span> = /J<br />
        <span>BULLET</span> = /B<br />
        <span>NEW LINE</span> = /?<br />
    </p>

    

</div>
<div class="left" style="float: left; width: 45%;">

    <h4>SPECIAL CHARACTERS</h4>
    <p class="right">
        <span>@</span> => /@ <br />
        <span>*</span> => /* <br />
        <span>^</span> => /^ <br />
        <span>{</span> => /{ <br />
        <span>}</span> => /} <br />
        <span>[</span> => /[ <br />
        <span>]</span> => /] <br />
        <span>/</span> => // <br />
        <span>\</span> => /\ <br />
    </p>
</div>

<script type="text/javascript">
$(document).ready(function() {

    var parse = function(txt) {
        if (!/^\*s\d+b?\*/i.test(txt))
            return '';

        var font = 8;
        var bold = false;
        var align = {c:"center",j:"justify",r:"right"};

        txt = $.trim(txt);
        txt = txt.replace(/\s/gi, ' ');

        txt = txt.replace(/\/b/gi, "&bull;");
        txt = txt.replace(/\/\?/gi, "<br/>");
        txt = txt.replace(/\/n/gi, "<br/>");
        txt = txt.replace(/[^\/]\^(.)/gi, "<sup>$1</sup>");

        /*txt = txt.replace(/\*s(\d+)(b?)\*(.+)/gi, function(a, b, c, d) {
            if (c == 'b' || c == 'B') bold = true;
            font = b;
            return d;
        });*/

        /*txt = txt.replace(/(?:\[s(.+?)%\])((?:(?!\[s.+?%\]).)+)/gi, function(a, b, c) {
            b = b/100;
            var sz = "font-size:"+b+"em;";//+"line-height:"+(b /4)+"em;";
            var ot = "<span style='"+sz+"'>", ct = "</span>";
            return ot+c.replace(/\s+/g,ct+" "+ot)+ct;
        });*/
        
        txt = txt.replace(/(?:\*s(\d+)(b?)\*)((?:(?!\*s\d+b?\*).)+)/gi, function(a, b, c, d) {
            var sz = "font-size:"+(b * <?php echo $scale; ?>)+"px;";
            if (c == 'b' || c == 'B') sz += "font-weight:bold;";

            d = d.trim().replace(/(?:\[s(.+?)%\])((?:(?!\[s.+?%\]).)+)/gi, function(a, b, c) {
                b = b/100;
                var sz = "font-size:"+b+"em;";
                var ot = "<span style='"+sz+"'>", ct = "</span>";
                return ot+c.replace(/\s+/g,ct+" "+ot)+ct;
            });

            var ot = "<span style='"+sz+"'>", ct = "</span>";
            return ot+
                d.replace(///\s+/g,
                    /\s+(?=[^<>]*<)/g,
                    ct+" "+ot)
                +ct;
        });

        txt = txt.replace(/(.+?)\/([cjr])/gi, function(a, b, c) {
            return "<div class='"+align[c.toLowerCase()]+"'>"+b+"</div>";
        });

        txt = txt.replace(/([^<])([@^*{}[\]\/\\])/gi, function(a, b, c) {
            if (b != '/') return b;
            var he = {
                '@' : "&#64;",
                '^' : "&#94;",
                '*' : "&#42;",
                '{' : "&#123;",
                '}' : "&#125;",
                '[' : "&#91;",
                ']' : "&#93;",
                '/' : "&#47;",
                '\\' : "&#92;"
            };
            return (he[c] ? he[c] : '');
        });
        //console.debug(bold);
        if (bold) txt = "<div class='bold'>"+txt+"</div>";
        if (font) txt = "<div style='font-size:"+(font*<?php echo $scale; ?>)+"px;'>"+txt+"</div>"; 
        return txt;
    }

    var execute = function(e) {
        var txt = parse(e.val());
        $('.txt-editor-preview').html(txt);
    }

    $('#editorx').each(function(i, that) {
        execute($(that));
    })

    $('#editorx').keyup(function() {  
        execute($(this));
    });
});
</script>
<script>
$(document).ready(function() {

   $("#editor").cleditor({
     width:        470, // width not including margins, borders or padding
     height:       250, // height not including margins, borders or padding
     controls:     // controls to add to the toolbar
                   "bold italic underline strikethrough | font size " +
                   //"| color highlight | bullets numbering | outdent indent | " +
                   "alignleft center alignright justify | undo redo | ",
     colors:       // colors in the color popup
                   "FFF FCC FC9 FF9 FFC 9F9 9FF CFF CCF FCF " +
                   "CCC F66 F96 FF6 FF3 6F9 3FF 6FF 99F F9F " +
                   "BBB F00 F90 FC6 FF0 3F3 6CC 3CF 66C C6C " +
                   "999 C00 F60 FC3 FC0 3C0 0CC 36F 63F C3C " +
                   "666 900 C60 C93 990 090 399 33F 60C 939 " +
                   "333 600 930 963 660 060 366 009 339 636 " +
                   "000 300 630 633 330 030 033 006 309 303",    
     fonts:        // font names in the font popup
                   "Arial,Arial Black,Comic Sans MS,Courier New,Narrow,Garamond," +
                   "Georgia,Impact,Sans Serif,Serif,Tahoma,Trebuchet MS,Verdana",
     sizes:        // sizes in the font size popup
                   "1,2,3,4,5,6,7",
     styles:       // styles in the style popup
                   [["Paragraph", "<p>"], ["Header 1", "<h1>"], ["Header 2", "<h2>"],
                   ["Header 3", "<h3>"],  ["Header 4","<h4>"],  ["Header 5","<h5>"],
                   ["Header 6","<h6>"]],
     useCSS:       false, // use CSS to style HTML when possible (not supported in ie)
     docType:      // Document type contained within the editor
                   '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">',
     docCSSFile:   // CSS file used to style the document contained within the editor
                   "", 
     bodyStyle:    // style to assign to document body contained within the editor
                   "margin:4px; font:10pt Arial,Verdana; cursor:text"

   });
   
    var $editor = $("#editor").cleditor()[0];            
    $(".cleditorMain iframe").contents().find('body').bind('keyup', function(){
        
        var $initial = 5;
        var text = $(this).text(); 
        var len = text.length;

        $("#charlen").html(len);
        
        
        var computeH = parseFloat(len) / 24;

        var wholeN =  parseInt(computeH);    
        if (wholeN == 0 || wholeN == ' NaN') {
            wholeN = 1;
        } else if (computeH > wholeN ) {     
            wholeN = wholeN + 1;
        }
        var counl = len / 24;
        $("#l").html(wholeN.toFixed(2));  
    });

    $("#etextokb").click(function() {   
        var len = $("#l").html();    
        $("#adtext").val($("#editorx").val());
        $("#length").val(len);
        
        var $w = $("#width").val();
        var $l = $("#length").val();
        var $total = parseFloat($w) * parseFloat($l);
        $("#totalsize").val($total.toFixed(2));
        $("#classifiedsline_view").dialog('close');
    });

 });
</script>
