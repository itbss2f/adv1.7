<?php   foreach ($results as $result) : ?>    
    <tr>
        <td><?php echo $result->contract_no ?></td>
        <td><?php echo $result->contract_type ?></td>
        <td><?php echo $result->contract_date ?></td>
        <td><?php echo $result->group_name ?></td>
        <td><?php echo number_format($result->amount,2,'.',',')?></td>
        <td><?php echo $result->contact_person ?></td>
        <td>
            <span class="icon-pencil edit"  id="<?php echo $result->id ?>" title="Edit"></span>
            <span class="icon-trash remove" id="<?php echo $result->id ?>" title="Remove"></span>
            <?php if ($result->attachment_file != null OR $result->attachment_file != "") { ?>
            <span class="picture" id="<?php echo $result->attachment_file ?>">
             <a href="<?php echo base_url() ?>uploads/exdeal/<?php echo $result->attachment_file ?>">
                <img  style="width:20px;height:20px" src="<?php echo base_url() ?>assets/images/pdf.png" >
             </a>    
             <?php  } ?>
             <a href="<?php echo site_url('exdeal_contract/contract_form/'.$result->id) ?>">[FORM]</a>
        </td>   
    </tr>
<?php endforeach;   ?>   