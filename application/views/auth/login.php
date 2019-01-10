<!DOCTYPE html>
<html lang="en">
<head>        
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

    <title>Inquirer Enterprise Solutions - Advertising</title>

    <link rel="icon" type="image/ico" href="favicon.ico"/>
    
    <link href="<?php echo base_url() ?>themes/css/stylesheets.css" rel="stylesheet" type="text/css" />
    <!--<link rel='stylesheet' type='text/css' href='<?php echo base_url() ?>themes/css/fullcalendar.print.css' media='print' />-->
    <link rel='stylesheet' type='text/css' href='<?php echo base_url() ?>themes/js/plugins/Gritter-master/css/jquery.gritter.css' />    
    
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/jquery1-7.min.js'></script>   
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/jqueryui1-8.min.js'></script>
    <!-- <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/jquery/jquery.mousewheel.min.js'></script>-->
    
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/cookie/jquery.cookies.2.2.0.min.js'></script>
    
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/bootstrap.min.js'></script>
    
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/Gritter-master/js/jquery.gritter.min.js'></script>   

</head>
<body>

    <?php   
    $msg = '';   
    if(isset($message['message']) and !empty($message['message'])) { $msg = $message['message']; }
    if ($msg != '') :
    ?>
    <script>
    $.gritter.add({
        title: 'Warning!',
        text: "<?php echo $msg ?>"

    });
    </script>
    <?php endif; ?> 
    <div class="loginBox">        
        <div class="loginHead">
            <img src="<?php echo base_url() ?>themes/img/logo.png" alt="Inquirer Enterprise Solutions - Advertising" title="Inquirer Enterprise Solutions - Advertising"/>
        </div>
        <form class="form-horizontal" action="" method="POST">            
            <div class="control-group">
                <label for="inputusername">Username</label>                
                <input type="text" id="username" name="username" value=""/>
            </div>
            <div class="control-group">
                <label for="inputPassword">Password</label>                
                <input type="password" id="password" name="password" value=""/>                
            </div>
            <div class="control-group" style="margin-bottom: 5px;">                
                                                                
            </div>
            <div class="form-actions">
                <input type="submit" class="btn btn-block" name='submit' value="Sign-in">
            </div>
        </form>        
        
    </div> 

</body>
</html>
