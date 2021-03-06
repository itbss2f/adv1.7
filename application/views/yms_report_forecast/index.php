<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>
<div class="workplace">
	<div class="row-fluid">
		<div class="span12">
			<div class="head">
				<div class="isw-grid"></div>
					<h1>Yield Management System - Forecast Daily Ad Summary Report</h1>
					<ul class="buttons">
						<li class="c_loader" style="display:none"><img src="<?php echo base_url()?>themes/img/loaders/loader_bw.gif" title="Generating"/></li>						
					</ul>    			    
				<div class="clear"></div>
			</div>					
		</div>
		<div class="block-fluid">                        
			<div class="row-form" style="padding: 2px 2px 2px 10px;">
				<div class="span2" style="width:70px;margin-top:12px">Entered Date</div>
				<div class="span1" style="width:80px;margin-top:12px"><input type="text" placeholder="From" id="datefrom" name="datefrom" class="datepicker"/></div>		
                <div class="span1 view4"  style="width:80px;margin-top:12px"><input type="text" placeholder="To" id="dateto" name="dateto" class="datepicker" value="<?php echo date('Y-m-d')?>"/></div>    
				<div class="span1" style="width:80px;margin-top:12px">YMS - Edition</div>
				<div class="span2" style="margin-top:12px">
					<select name="edition" id="edition">
						<option value="0">-- All --</option>
						<?php
						foreach ($ymsedition as $row) : ?>
						<option value="<?php echo $row['id'] ?>"><?php echo $row['code'].' - '.$row['name'] ?></option>
						<?php
						endforeach;
						?>
					</select>
				</div>
				<div class="span1" style="width:70px;margin-top:12px">Report Type</div>
				<div class="span2" style="margin-top:12px">
					<select name="reporttype" id="reporttype">
						<option value="1">Detailed Per Section</option>
						<option value="2">Summary Per Section</option>
						<option value="3">Pages Per Section</option>
						<option value="4">Ad Page Per Section</option>
						<option value="5">Ad Load Per Section</option>
						<option value="6">Color Per Page</option>
						<option value="7">Color Per Issue</option>
						<option value="8">Section Summary</option>
						<option value="9">Classification Summary</option>
					</select>
				</div>
                <div class="span2" style="width: 80px; margin-top:12px"><button class="btn btn-success" id="generatereport" type="button">Generate</button></div>                
				<div class="span2" style="width: 80px; margin-top:12px"><button class="btn btn-success" id="generate_excel" type="button">Export</button></div>				
				<div class="clear"></div>
			</div>   
			<div class="row-form" style="padding: 1px 2px 1px 10px;height:27px;">				
				<!--<div class="span2 view4" style="width:80px;">Entered Date</div>-->
				
				<div class="span1 view5" style="width:70px;">Book Name</div>
				<div class="span2 view5" style="width:100px;margin-top:-2px">
					<select name="bookname" id="bookname">
						<option value="">-- All --</option>
						<?php
						foreach ($bookmaster as $bookmaster) :?>
						<option value="<?php echo $bookmaster['book_name'] ?>"><?php echo $bookmaster['book_name'] ?></option>
						<?php
						endforeach;
						?>
					</select>
				</div>
				<div class="span2 view6" style="width:70px; margin-left: 0px;">Class Name</div>
				<div class="span2 view6" style="width:100px;margin-top:-2px">
					<select name="classification" id="classification">
						<option value="">-- All --</option>
						<?php
						foreach ($class as $class) :?>
						<option value="<?php echo $class['class_code'] ?>"><?php echo $class['class_code'] ?></option>
						<?php
						endforeach;
						?>
					</select>
				</div>
				<div class="span2 view1"><input type="radio" name="du" id="du" value="1" checked="checked">Dummied/Undummied</div>
				<div class="span2 view1"><input type="radio" name="du" id="du" value="2">Dummied Only</div>				
				<div class="span2 view2"><input type="radio" name="pnc" id="pnc" value="1" checked="checked">Paid & No Charge</div>
				<div class="span2 view2" style="border-right:2px solid #CCCCCC;"><input type="radio" name="pnc" id="pnc" value="2">Paid Only</div>
				<div class="span2 view3"><input type="checkbox" name="exclude" id="exclude" value="1">Exclude Page Back</div>
				<div class="clear"></div>
			</div>
			<div class="report_generator" style="height:500px;padding-left:7px"><iframe style="width:99%;height:99%" id="source"></iframe></div>
		</div>		
	</div>            

	<div class="dr"><span></span></div>
</div>  

<script>
$(document).ready(function(){
	$(".view2").hide(); $(".view3").hide(); $(".view4").hide(); $(".view5").hide();$(".view6").hide();
});
$("#reporttype").change(function(){
	var number = $("#reporttype").val();	
	if (number == 1 || number == 2) {
		$(".view1").show(); $(".view2").hide();$(".view3").hide();$(".view4").hide();$(".view5").hide();$(".view6").hide();
	} else if (number == 3 || number == 6) {
		$(".view1").hide(); $(".view2").hide();$(".view3").show(); $(".view4").hide();$(".view5").hide();$(".view6").hide
		if (number == 6) {
			$(".view4").show();
		} 
	} else if (number == 4 || number == 5 || number == 7 || number == 8 || number == 9) {
		$(".view1").hide(); $(".view2").show();$(".view3").show();$(".view4").hide();$(".view5").hide();$(".view6").hide();
		if (number == 7) {
			$(".view4").show();
		} else if (number == 8) {
			$(".view4").show();$(".view5").show();
		} else if (number == 9) {		
			$(".view4").show();$(".view6").show();
		}
	} 
	
});
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 

$("#generatereport").click(function(){
	var $r = $("#reporttype").val();
	var countValidate = 0;  
	var validate_fields = ['#datefrom'];
	if ($r == 7) {
		var validate_fields = ['#datefrom', '#dateto'];
	}

	for (x = 0; x < validate_fields.length; x++) {			
		if($(validate_fields[x]).val() == "") {                        
			$(validate_fields[x]).css(errorcssobj);          
		  	countValidate += 1;
		} else {        
		  	$(validate_fields[x]).css(errorcssobj2);       
		}        
	}   
	if (countValidate == 0) {
		$(".c_loader").show();
		var datefrom = $("#datefrom").val();
		var dateto = $("#dateto").val();
		var reporttype = $("#reporttype").val();
		var edition = $("#edition").val();
		var dummy = $("#du:checked").val();
		var pay = $("#pnc:checked").val();
		var exclude = $("#exclude:checked").val();
		var bookname = $("#bookname").val();		
		var classification = $("#classification").val();	
		$.ajax({
			url: "<?php echo site_url('yms_report_forecast/generatereport') ?>",
			type: "post",
			//data: {datefrom: datefrom, dateto: dateto, reporttype: reporttype, edition: edition, dummy: dummy, pay: pay, exclude: exclude},
			data: {},
			success:function(response) {
					$("#source").attr('src', "<?php echo site_url('yms_report_forecast/generatereport') ?>/"+datefrom+"/"+reporttype+"/"+edition+"/"+dummy+"/"+pay+"/"+exclude+"/"+dateto+"/"+bookname+"/"+classification);
					$(".c_loader").hide();
			}
		});		
	} else {			
		return false;
	}	


$("#generate_excel").die().live("click",function() {
        var $r = $("#reporttype").val();
        var countValidate = 0;  
        var validate_fields = ['#datefrom'];
    if ($r == 7) {
    var validate_fields = ['#datefrom', '#dateto'];
    }

    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    if (countValidate == 0) 
    $(".c_loader").show();
        var datefrom = $("#datefrom").val();
        var dateto = $("#dateto").val();
        var reporttype = $("#reporttype").val();
        var edition = $("#edition").val();
        var dummy = $("#du:checked").val();
        var pay = $("#pnc:checked").val();
        var exclude = $("#exclude:checked").val();
        var bookname = $("#bookname").val();        
        var classification = $("#classification").val();
    
        { 
        window.open("<?php echo site_url('yms_report_forecast/generate_excel/')?>?datefrom="+datefrom+"&dateto="+dateto+"&reporttype="+reporttype+"&edition="+edition+"&bookname="+bookname+"&classification="+classification+"&dummy="+dummy+"&pay="+pay, '_blank');     
        window.focus();
        }
    
    });   

});

</script>

