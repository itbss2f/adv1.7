<?php
$date = @$_POST['date'] ? @$_POST['date'] : date('Y-m-d');
$product = @$_POST['product'];

if ($_POST) {
    $sql = "SELECT -- p.*, m.*,
     -- c.*,
     c.id,
     c.class_subtype,
     c.class_type,
     c.class_code,
     c.class_name,
     c.class_header,
     p.ao_class,
     p.ao_width,
     p.ao_length,
     p.ao_adtyperate_code,

     m.ao_eps,
     m.ao_payee,
     m.ao_branch,
     m.ao_num,
     m.ao_class,
     m.ao_subclass,
     m.ao_part_classsort,
     m.ao_adsize,
     m.ao_adtext,
     m.ao_adtype,
     m.ao_width AS aw,
     m.ao_length AS al
    FROM misclass c
     LEFT JOIN (ao_m_tm m INNER JOIN ao_p_tm AS p ON p.ao_num = m.ao_num AND p.ao_type = 'C')
      ON TRUE
       AND m.ao_adtype IN (3, 4, 10, 12, 14) -- classified lines
       AND m.ao_prod = '$product' -- national classifieds
       AND m.ao_class = c.id
       AND m.status NOT IN ('C', 'F')
       AND '{$date}' BETWEEN DATE(p.ao_issuefrom) AND DATE(p.ao_issuefrom)
       -- AND p.ao_issuefrom = '$date'
    WHERE TRUE
     -- AND m.ao_prod ='$product'
     AND c.class_type = 'C'
     AND c.class_subtype = 'L'
     -- AND c.class_header IS NOT NULL
     -- AND m.ao_num = 1668
    ORDER BY c.class_sort
    ";
    //echo '<pre>';echo $sql;echo '</pre>';
    mysql_connect("128.1.44.4", "root", "roottoor1!") or die("NOT CONNECTED!");
    mysql_select_db("advprod_db02") or die("NO DATABASE!");
    $result = mysql_query($sql) or die(mysql_error());

    $code = '';
    $data = array("NCPS");
    while($row = mysql_fetch_assoc($result))
    {
        $type = "{$row['class_subtype']}{$row['class_type']}";
        //$code = $row['class_code'];
        $name = $row['class_name'];
        $head = $row['class_header']; // *nhead*PROFESSIONAL/c SERVICES@

        if ($code != $row['class_code']) {
            $code = $row['class_code'];
            $data[] = "[LTH] [{$type}($code)] [LEN][LS($name)][MF1,1]{$head}[LN]";
        }

        if ($row['ao_num']) {
            switch ($row['ao_adtype']) {
                case 12: // line
                    if (in_array($row['ao_adtyperate_code'], array('LI', 'NL'))) {
                        $head = parseHtml($row['ao_adtext']);
                        $name = getPart($row['ao_part_classsort'], $row['ao_adtext']);
                        $data[] = "[LTA] [{$type}($code)] [LEN][LS($name)][MF1,1]{$head}[LN]";   
                    } else {
                        $sub  = substr($row['ao_payee'],0,16);
                        $name = "#{$row['ao_eps']}.eps {$sub}";

                        $length = (float) $row['ao_length'];
                        $width  = (float) $row['ao_width'];
                        $adsize = str_pad($row['ao_adsize'],2,0,STR_PAD_LEFT);

                        $size = "MD{$length}.{$adsize}MF{$width}";
                        $data[] = "[LTD] [{$type}($code)] [LEY][LS($name)][{$size}][LN]";     
                    }
                    break;                
                case 4:
                    $head = parseHtml($row['ao_adtext']);
                    $name = getPart($row['ao_part_classsort'], $row['ao_adtext']);
                    $data[] = "[LTA] [{$type}($code)] [LEN][LS($name)][MF1,1]{$head}[LN]";
                    break;
                case 10:
                case 14:
                case 3:
                    $sub  = substr($row['ao_payee'],0,16);
                    $name = "#{$row['ao_eps']}.eps {$sub}";

                    $length = (float) $row['ao_length'];
                    $width  = (float) $row['ao_width'];
                    $adsize = str_pad($row['ao_adsize'],2,0,STR_PAD_LEFT);

                    $size = "MD{$length}.{$adsize}MF{$width}";
                    $data[] = "[LTD] [{$type}($code)] [LEY][LS($name)][{$size}][LN]";
            }
        }
    }

    if ($_POST['submit'] == 'DOWNLOAD') {
        $filename = "NC" . date("dmy", strtotime($date));
        if ($product == 3) {
            $filename = "LI" . date("dmy", strtotime($date));
        } elseif ($product == 13) {
            $filename = "JM" . date("dmy", strtotime($date));
        }
        header("Content-disposition: attachment; filename=$filename.srt");
        header('Content-type: text/plain');
        echo implode("\n", $data);
        exit;
    }
}

/*echo '<pre>';
echo implode("\n", $data);
echo '</pre>';*/

function parseHtml($html) {
    $html = trim($html);
    $html = strip_tags($html);
    $html = str_replace("\r\n", '/c', $html);


    //$dom = DOMDocument::loadHTML($html);
    /*echo '<pre>';
    print_r($dom);
    echo '</pre>';*/
    return $html;
    //return htmlentities($html);
}

function getPart($part, $name) {
    $name = strip_tags($name);
    return $part ? $part : substr($name, 0, 15);
}
?>
<div class="breadLine">
    
    <?php echo $breadcrumb; ?>
                        
</div>
<div class="workplace">
    <div class="row-fluid">     
    <form method="post">

        <div>
            <select name="product">
                <?php foreach ($prod as $prod) : ?>
                <?php if ($prod['id'] == $product) : ?>
                <option value="<?php echo $prod['id'] ?>" selected="selected"><?php echo $prod['prod_name'] ?></option>
                <?php else : ?>
                <option value="<?php echo $prod['id'] ?>"><?php echo $prod['prod_name'] ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
            DATE: <input id='datepicker' type="text" name="date" value="<?php echo $date; ?>" />
            <input name="submit" type="submit" value="GENERATE" />
            <input name="submit" type="submit" value="DOWNLOAD" />
        </div>
        <textarea cols="200" rows="15" style="width: 699px; height: 337px"><?php echo @implode("\n", $data); ?></textarea>
    </form>
    </div>
</div>

<script>
$(':input[name=date]#datepicker').datepicker({
    dateFormat: 'yy-mm-dd'
});
</script>