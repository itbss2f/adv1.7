<?php if ($file['filetype'] == '.jpg') { ?>

<img src="<?php echo base_url().'/uploads/fileattachment/'.$file['filename']?>">

<?php } else if ($file['filetype'] == '.pdf') { ?>

<iframe src="http://ies.inquirer.com.ph/fileattachment/<?php echo $file['filename'] ?>" width="900px" height="1200px"></iframe>

<?php } else if ($file['filetype'] == '.gif') { ?>

<img src="<?php echo base_url().'/uploads/fileattachment/'.$file['filename']?>"> 
    
<?php } else if ($file['filetype'] == '.png') { ?>
<img src="http://ies.inquirer.com.ph/fileattachment/<?php echo $file['filename'] ?>">
<!--<img src="http://localhost/fileattachment/<?php #echo $file['filename']?>"> -->

<?php } else if ($file['filetype'] == '.doc') { ?>

<iframe src="http://ies.inquirer.com.ph/fileattachment/<?php echo $file['filename'] ?>" width="900px" height="1200px"></iframe> 

<?php } else if ($file['filetype'] == '.xls') { ?>

<iframe src="http://ies.inquirer.com.ph/fileattachment/<?php echo $file['filename'] ?>" width="900px" height="1200px"></iframe> 

<?php } else if ($file['filetype'] == '.csv') { ?>

<iframe src="http://ies.inquirer.com.ph/fileattachment/<?php echo $file['filename'] ?>" width="900px" height="1200px"></iframe> 

<?php } else if ($file['filetype'] == '.xml') { ?>

<iframe src="http://ies.inquirer.com.ph/fileattachment/<?php echo $file['filename'] ?>" width="900px" height="1200px"></iframe>

<?php } else if ($file['filetype'] == '.txt') { ?>

<iframe src="http://ies.inquirer.com.ph/fileattachment/<?php echo $file['filename'] ?>" width="900px" height="1200px"></iframe> 

<?php } 



     



