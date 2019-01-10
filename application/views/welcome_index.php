<!DOCTYPE html>
<html lang="en">
<head>        
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

    <title>Inquirer Enterprise Solutions - Advertising</title>

    <link rel="icon" type="image/ico" href="favicon.ico"/>
    
    <link href="<?php echo base_url() ?>themes/css/stylesheets.css" rel="stylesheet" type="text/css" />
    <link rel='stylesheet' type='text/css' href='<?php echo base_url() ?>themes/css/fullcalendar.print.css'/>
    <link rel='stylesheet' type='text/css' href='<?php echo base_url() ?>themes/js/plugins/Gritter-master/css/jquery.gritter.css' />

    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/jquery1-7.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/jqueryui1-8.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/jmask.js'></script>
    
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/jquery/jquery.mousewheel.min.js'></script>    
    
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/bootstrap.min.js'></script>
  
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/cookie/jquery.cookies.2.2.0.min.js'></script>
    <!--<script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/charts/excanvas.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/charts/jquery.flot.js'></script>    
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/charts/jquery.flot.stack.js'></script>    
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/charts/jquery.flot.pie.js'></script>
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/charts/jquery.flot.resize.js'></script>-->
    
<!--    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/sparklines/jquery.sparkline.min.js'></script> -->
    
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/fullcalendar/fullcalendar.min.js'></script>
    
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/select2/select2.min.js'></script>
    
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/uniform/uniform.js'></script>
    
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/maskedinput/jquery.maskedinput-1.3.min.js'></script>
    
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/validation/languages/jquery.validationEngine-en.js' charset='utf-8'></script>
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/validation/jquery.validationEngine.js' charset='utf-8'></script>
    
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/animatedprogressbar/animated_progressbar.js'></script> 
    
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/qtip/jquery.qtip-1.0.0-rc3.min.js'></script>
    
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/cleditor/jquery.cleditor.js'></script>
    
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/dataTables/jquery.dataTables.min.js'></script>    
    
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/fancybox/jquery.fancybox.pack.js'></script>

    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/Gritter-master/js/jquery.gritter.min.js'></script>
    
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/cookies.js'></script>
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/actions.js'></script>
    <!--<script type='text/javascript' src='<?php echo base_url() ?>themes/js/charts.js'></script>     -->
    <script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins.js'></script> 

                                                                                                    
    
</head>
<body>
    
    <div class="header">
        <a class="logo" href="index.html"><img src="<?php echo base_url() ?>themes/img/logo.png" alt="Inquirer Enterprise Solutions - Advertising" title="Inquirer Enterprise Solutions - Advertising"/></a>
        <ul class="header_menu">
            <li class="list_icon"><a href="#">&nbsp;</a></li>
        </ul>    
    </div>
    
    <div class="menu">                
        
        <?php include('user_profile.php'); ?>        
        <?php echo $navigation; ?>        
        <?php include('calendar.php'); ?>        

    </div>
        
    <div class="content">    
		<h4>Advertising Version04 - MAC LOCAL DATA</h4>
        <?php $katid = $this->session->userdata('authsess')->sess_id;   ?>
        <?php if ($katid == 119) : echo "<h4>Goodmorning Kat !. :) </h4>"; endif; ?>
        <?php echo $content; ?>     
    </div>   
   <div id="alert_dialog" title="Notification"></div>

<script type='text/javascript'>
$(function(){

	$.extend($.gritter.options, { 
	   
	   //position: 'bottom-right',
	   
	   fade_in_speed: 1000,
	   
	   fade_out_speed: 500,
	   
	   time: 6000
	   
	});

});
</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-61119337-1', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>
