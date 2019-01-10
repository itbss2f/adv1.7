<link rel='stylesheet' type='text/css' href='<?php echo base_url() ?>themes/js/plugins/Gritter-master/css/jquery.gritter.css'/>
<script type='text/javascript' src='<?php echo base_url() ?>themes/js/jquery1-7.min.js'></script>
<script type='text/javascript' src='<?php echo base_url() ?>themes/js/jqueryui1-8.min.js'></script>

<script type='text/javascript' src='<?php echo base_url() ?>themes/js/plugins/Gritter-master/js/jquery.gritter.min.js'></script>

<script type='text/javascript'>
$(function(){
alert('test');
$.extend($.gritter.options, { 
        
        //position: 'bottom-right',
        
        fade_in_speed: 1000,
        
        fade_out_speed: 500,
        
        time: 6000
        
    });

	$.gritter.add({
		// (string | mandatory) the heading of the notification
		title: 'This is a sticky notice!',
		// (string | mandatory) the text inside the notification
		//text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus eget tincidunt velit. Cum sociis natoque penatibus et <a href="#" style="color:#ccc">magnis dis parturient</a> montes, nascetur ridiculus mus.',

		text: 'ASDASDASD'
		// (string | optional) the image to display on the left
		//image: 'http://s3.amazonaws.com/twitter_production/profile_images/132499022/myface_bigger.jpg',
		// (bool | optional) if you want it to fade out on its own or just sit there
	//	sticky: true,
		// (int | optional) the time you want it to be alive for before fading out
		//time: '',
		// (string | optional) the class name you want to apply to that specific message
		//class_name: 'my-sticky-class'
	});
});

</script>
