<!--<img src="<?php #echo base_url().'/uploads/fileattachment/'.$file['material_filename']?>">--> 
<?php if (empty($file['material_filename'])) : ?>
<div style="color: red;"><h1>NO MATERIAL</h1></div>
<?php else: ?>
<img src="<?php echo 'http://ies.inquirer.com.ph/'.'materialupload/'.$file['material_filename']?>" > 
<?php endif; ?>
                                                                                                 

     



