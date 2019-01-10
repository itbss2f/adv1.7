<?php

mysql_connect("128.1.44.2", "root", "mysqld3vserver") or die("NOT CONNECTED!");
mysql_select_db("test") or die("NO DATABASE!");

if ($_POST) {
    if ($_POST['ad_text']) {
        $adtext = $_POST['ad_text'];
        mysql_query("INSERT INTO ad_lines (ad_text) VALUES ('$adtext')");
    }
}
if ($_GET) {
    if ($_GET['id']) {
        $id = mysql_escape_string($_GET['id']);
        $line = mysql_query("SELECT * FROM ad_lines WHERE id = '$id'");
        $line = mysql_fetch_assoc($line);
        //print_r($line);
    }
}
$lines = mysql_query("SELECT * FROM ad_lines") or die(mysql_error());

?>

<link rel="stylesheet" href="assets/switzerland/stylesheet.css">
<?php $size = 10; $scale = 3.2; ?>
<style type="text/css">

    body {font-family: Arial;}
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
<script src="assets/plugins/jquery/jquery.min.js"></script>

<h1>CLASSIFIED AD LINE (TESTING)</h1>

<div class="left">
    <form method="post">
        <textarea name="ad_text" class="txt-editor" style="" cols="60" rows="20"><?php echo @$line['ad_text']; ?></textarea>
        <br />
        <button>Save</button>
    </form>
</div>
<div class="txt-editor-preview" style=""></div>
<div class="left">
    <p>
        <span>START</span> = *s8b*<br />
        <span>FONT SIZE</span> = [s?%] (? = size, eg:75)<br />
        <span>CENTER</span> = /C<br />
        <span>JUSTIFY</span> = /J<br />
        <span>BULLET</span> = /B<br />
        <span>NEW LINE</span> = /?<br />
    </p>

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
<div class="clear"></div>
<div class="list">
    <table border="1" style="position:absolute;margin-top:-50px;width:1000px;border:1px solid #CCC;border-collapse:collapse;">
        <?php while($line = mysql_fetch_assoc($lines)): ?>
        <tr>
            <td><?php echo $line['ad_text']; ?></td>
            <td><a href="?id=<?php echo $line['id']; ?>">Edit</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
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

        txt = txt.replace(/\*s(\d+)(b?)\*(.+)/gi, function(a, b, c, d) {
            if (c == 'b' || c == 'B') bold = true;
            font = b;
            return d;
        });

        txt = txt.replace(/(?:\[s(.+?)%\])((?:(?!\[s.+?%\]).)+)/gi, function(a, b, c) {
            b = b/100;
            var sz = "font-size:"+b+"em;";//+"line-height:"+(b /4)+"em;";
            var ot = "<span style='"+sz+"'>", ct = "</span>";
            return ot+c.replace(/\s+/g,ct+" "+ot)+ct;
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

    $('.txt-editor').each(function(i, that) {
        execute($(that));
    })

    $('.txt-editor').keyup(function() {
        execute($(this));
    });
});
</script>