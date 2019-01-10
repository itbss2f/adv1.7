<table cellpadding="0" cellspacing="0" width="100%" class="table"> 
<?php foreach ($logs as $text) : ?>   
<div class="item">
    <div class="image"><img src="<?php echo base_url() ?>themes/img/users/alexander.jpg" class="img-polaroid"/></div>
    <div class="info">
        <a href="#" class="name"> 
        <?php echo $text['firstname'].' '.$text['lastname'] ?></a>
        <p><?php echo $text['logs'] ?></p>        
        <span style="color: red; font-style: italic;"><?php echo date_format(date_create($text['dtime']),"l jS \of F Y h:i:s A") ?></span>
    </div>
    <div class="clear"></div>
</div> 
<?php endforeach; ?> 
