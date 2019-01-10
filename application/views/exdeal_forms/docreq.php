
<?php $my_id = "" ?>

<?php if($action == 'update') {  $my_id= $id; } ?>

<form id="doc_form" action="<?php echo site_url("exdeal_paramenterfile/".$action."/".$my_id) ?>" method="post" >
    <input type="hidden" name="t_id" value="<?php echo $my_id; ?>">
    <table>
        <tr>
            <td><div class="span1"><b>Document</b></div></td>
            <td> <div class="span3"><input type="text" name="doc_name" value="<?php if($action == 'update') { echo $result->doc_name; } ?>"/></div></td>
        </tr>
   </table>

</form>
