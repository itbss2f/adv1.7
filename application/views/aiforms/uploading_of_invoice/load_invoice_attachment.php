<?php if ($file['filetype'] == '.jpg') { ?>

<img src="https://ies.inquirer.com.ph/invoiceattachment/<?php echo $file['filename']?>">

<?php } else if ($file['filetype'] == '.pdf') { ?>

<iframe src="https://ies.inquirer.com.ph/invoiceattachment/<?php echo $file['filename'] ?>" width="900px" height="1200px"></iframe>

<?php } else if ($file['filetype'] == '.gif') { ?>

<img src="https://ies.inquirer.com.ph/invoiceattachment/<?php echo $file['filename']?>"> 
    
<?php } else if ($file['filetype'] == '.png') { ?>
<img src="https://ies.inquirer.com.ph/invoiceattachment/<?php echo $file['filename'] ?>">

<?php } else if ($file['filetype'] == '.doc') { ?>

<iframe src="https://ies.inquirer.com.ph/invoiceattachment/<?php echo $file['filename'] ?>" width="900px" height="1200px"></iframe> 

<?php } else if ($file['filetype'] == '.xls') { ?>

<iframe src="https://ies.inquirer.com.ph/invoiceattachment/<?php echo $file['filename'] ?>" width="900px" height="1200px"></iframe> 

<?php } else if ($file['filetype'] == '.csv') { ?>

<iframe src="https://ies.inquirer.com.ph/invoiceattachment/<?php echo $file['filename'] ?>" width="900px" height="1200px"></iframe> 

<?php } else if ($file['filetype'] == '.xml') { ?>

<iframe src="https://ies.inquirer.com.ph/invoiceattachment/<?php echo $file['filename'] ?>" width="900px" height="1200px"></iframe>

<?php } else if ($file['filetype'] == '.txt') { ?>

<iframe src="https://ies.inquirer.com.ph/invoiceattachment/<?php echo $file['filename'] ?>" width="900px" height="1200px"></iframe> 

<?php } 



     



