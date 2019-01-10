<div class="workplace">

	<div class="row-fluid">
	 
	 <div class="span4">
		<div class="head">
		    <div class="isw-cloud"></div>
		    <h1>Searching</h1>
		    <div class="clear"></div>
		</div>
		<div class="block-fluid">                        
	
			<div class="row-form-booking">
				<div class="span4"><b>Invoice No.</b></div>
				<div class="span5"><input type='text' name='invoiceno' id='invoiceno'></div>
				<div class="clear"></div>
			</div>
			<div class="row-form-booking">
				<div class="span4"><b>Issue Date</b></div>
				<div class="span4"><input type='text' placeholder="From" name='issuedatefrom' id='issuedatefrom' style='width:80px;'></div>
				<div class="span4"><input type='text' placeholder="To" name='issuedateto' id='issuedateto' style='width:80px;'></div>
				<div class="clear"></div>
			</div>    
			<div class="row-form-booking">
				<div class="span4"><b>RFA No.</b></div>
                <div class="span2"><input type='text' name='rfano' id='rfano'></div>
				<div class="span2"><input type='text' name='rfano2' id='rfano2'></div>
				<div class="clear"></div>
			</div>
			<div class="row-form-booking">
				<div class="span4"><b>RFA Date</b></div>
				<div class="span4"><input type='text' placeholder="From" name='rfadatefrom' id='rfadatefrom' style='width:80px;'></div>
				<div class="span4"><input type='text' placeholder="To" name='rfadateto' id='rfadateto' style='width:80px;'></div>
				<div class="clear"></div>
			</div>   
			<div class="row-form-booking">
				<div class="span4"><b>Complaint</b></div>
				<div class="span8"><input type='text' name='complaint' id='complaint'></div>
				<div class="clear"></div>
			</div>
			<div class="row-form-booking">
				<div class="span4"><b>Advertiser Name</b></div>
				<div class="span8"><input type='text' name='advertisername' id='advertisername'></div>
				<div class="clear"></div>
			</div>
			<div class="row-form-booking">
				<div class="span4"><b>Agency Name</b></div>
				<div class="span8"><input type='text' name='agencyname' id='agencyname'></div>
				<div class="clear"></div>
			</div>
			<div class="row-form-booking">
				<div class="span4"><b>Account Executive</b></div>
				<div class="span8">
				<select class='select' name='accountexec' id='accountexec' style='width:240px;'>
					<option value=''>--</option>
					<?php
					foreach ($aelist as $aelist) : ?>
					<option value="<?php echo $aelist['user_id']?>"><?php echo $aelist['employee'] ?></option>
					<?php
					endforeach;
					?>
				</select>
				</div>
				<div class="clear"></div>
			</div>
            <div class="row-form-booking">
                <div class="span4"><b>RFA Types</b></div>
                <div class="span8">
                <select class='select' name='rfatypes' id='rfatypes' style='width:240px;'>
                    <option value=''>--</option>
                    <?php
                    foreach ($rfatypes as $row) : ?>
                    <option value="<?php echo $row['id']?>"><?php echo $row['rfatype_name'] ?></option>
                    <?php
                    endforeach;
                    ?>
                </select>
                </div>
                <div class="clear"></div>
            </div>
			<div class="row-form-booking">
				<div class="span9"><b>Person / Agency / Client / Others Responsible	</b></div>
				<div class="clear"></div>
			</div>
			<div class="row-form-booking">
				<div class="span4"><select name='searchperson' id='searchperson' style='width:80px'>
                                            <option value=''>--</option>                       
                                            <option value='P'>Person</option>
                                            <option value='A'>Agency</option>
                                            <option value='C'>Client</option>
                                            <option value='O'>Others</option>
                                       </select>
				</div>
				<div class="span8"><input type='text' name='responsible' id='responsible'></div>
				<div class="clear"></div>
			</div>
			<div class="row-form-booking">
				<div class="span6"><button class="btn btn-success" type="button" name="search" id="search">Search button</button></div>
				<div class="span6"><button class="btn btn-block" type="button" name="aiform_rfa" id="aiform_rfa">RFA button</button></div>
				<div class="clear"></div>
			</div>
            <div class="row-form-booking">
                <div class="span6"><button class="btn btn-success" type="button" name="printtopdf" id="printtopdf" style="width:105px;">Print to PDF</button></div>
                <div class="span6"><button class="btn btn-block" type="button" name="printtoexcel" id="printtoexcel">Print to excel</button></div>
                <div class="clear"></div>
            </div>                    			
		</div>
	 </div>
	 
	 <div class="span8">
		<div class="head">
		    <div class="isw-ok"></div>
		    <h1>Results</h1>
		    <div class="clear"></div>
		</div>
		<div class="block-fluid table-sorting" style="overflow:auto;height:420px">
			<table cellpadding="0" cellspacing="0" width="100%" style="white-space:nowrap;width:1500px" class="table" id="tSortable1">
				<thead>
				<tr>					   
					<th style='width:20px;'><input type="radio"></th>                    
					<th style='width:75px;'>AO Number</th>                
					<th style='width:100px;'>Issue Date</th>                
					<th style='width:75px;'>RFA No.</th>                
					<th style='width:100px;'>RFA Date</th>                
					<th style='width:100px;'>Agency Name</th>                
					<th style='width:100px;'>Client Name</th>                
					<th style='width:100px;'>AE</th>                
					<th style='width:75px;'>Invoice No.</th>                
					<th style='width:100px;'>Invoice Date</th>                
                    <th style='width:150px;'>RFA Findings</th>                                                             
					<th style='width:150px;'>RFA Types</th>                                                             
				</tr>
				</thead>
				<tbody id="searchresult"></tbody>			
			</table>  
			<div class="clear"></div>
		</div>	
		<!--<div class="block-fluid table-sorting" style="overflow:auto;height:420px">
            <table cellpadding="0" cellspacing="0" width="100%" style="white-space:nowrap;width:1500px" class="table" id="tSortable1">
                <thead>
                <tr>                       
                    <th style='width:20px;'><input type="radio"></th>                    
                    <th style='width:75px;'>AO Number</th>                
                    <th style='width:100px;'>Issue Date</th>                
                    <th style='width:75px;'>RFA No.</th>                
                    <th style='width:100px;'>RFA Date</th>                
                    <th style='width:100px;'>Agency Name</th>                
                    <th style='width:100px;'>Client Name</th>                
                    <th style='width:100px;'>AE</th>                
                    <th style='width:75px;'>Invoice No.</th>                
                    <th style='width:100px;'>Invoice Date</th>                
                    <th style='width:150px;'>RFA Findings</th>                                                             
                </tr>
                </thead>
                <tbody id="pdfview"></tbody>            
            </table>  
            <div class="clear"></div>
        </div>  -->                                  
	 </div>                     
		            
	</div>

	<div class="dr"><span></span></div>
</div>
<div id='ai_rfa_view' title='Request For Adjustment'></div>    
<div id='printtopdf_view' title='Print to PDF'></div>    
<script>
$(function() {
    $('#ai_rfa_view, #printtopdf_view').dialog({
        autoOpen: false, 
        closeOnEscape: false,
        draggable: true,
        width: 650,    
        height:600,
        modal: true,
        resizable: false
    }); 
    
    $('#issuedatefrom, #issuedateto, #rfadatefrom, #rfadateto').datepicker({dateFormat: 'yy-mm-dd'});
    
    $( "#raftabs" ).tabs();
    
    $('#search').click(function(){
         $.ajax({
            url: '<?php echo site_url('rfa/searchRFA') ?>',
            type: 'post',
            data: { complaint: $(":input[name='complaint']").val(),
                    advertisername: $(":input[name='advertisername']").val(),   
                    agencyname: $(":input[name='agencyname']").val(),   
                    accountexec: $(":input[name='accountexec']").val(),   
                    invoiceno: $(":input[name='invoiceno']").val(),   
                    issuedatefrom: $(":input[name='issuedatefrom']").val(),   
                    issuedateto: $(":input[name='issuedateto']").val(),   
                    rfano: $(":input[name='rfano']").val(),   
                    rfano2: $(":input[name='rfano2']").val(),   
                    rfadatefrom: $(":input[name='rfadatefrom']").val(),   
                    rfadateto: $(":input[name='rfadateto']").val(),
                    person: $(":input[name='searchperson']").val(),
                    responsible: $(":input[name='responsible']").val(),
                    rfatypes: $(":input[name='rfatypes']").val()
                  },
                  
                  
            success: function(response) {
                var $response = $.parseJSON(response);
                
                $('#searchresult').html($response['searchresult']);    
            }
        }) 
    });
    
    

    /* Codes for genrating PDF form and Excel Form
    *  @Author: Xtian and Paul
    */
    
    $('#printtopdf').click(function(){
        
        $('#printtopdf_view').dialog('open');      
        
        $.ajax({
             url:'<?php echo site_url('rfa/printToPDF') ?>',
             type:'post',    
             data: {},
             success: function(response){
                var $response = $.parseJSON(response);
                $('#printtopdf_view').html($response['printtopdf_view']);
            }
        });
        
        return false;
        
        /*$.ajax({
            url:'<?php #echo site_url('rfa/printToPDF') ?>',
            type:'post',
            data: { complaint: $(":input[name='complaint']").val(),
                    advertisername: $(":input[name='advertisername']").val(),   
                    agencyname: $(":input[name='agencyname']").val(),   
                    accountexec: $(":input[name='accountexec']").val(),   
                    invoiceno: $(":input[name='invoiceno']").val(),   
                    issuedatefrom: $(":input[name='issuedatefrom']").val(),   
                    issuedateto: $(":input[name='issuedateto']").val(),   
                    rfano: $(":input[name='rfano']").val(),   
                    rfadatefrom: $(":input[name='rfadatefrom']").val(),   
                    rfadateto: $(":input[name='rfadateto']").val(),
                    person: $(":input[name='searchperson']").val(),
                    responsible: $(":input[name='responsible']").val(), 
                    rfatypes: $(":input[name='rfatypes']").val() 
    
            },
            
            success: function(response){
                var $response = $.parseJSON(response);
                //$('#pdfview_result').html($response['pdfview_result']);
                                                                           
            }
        }); */
    })
    
    

    $('#rfano').keyup(function(){
        var value = $('#rfano').val();
        
        $('#rfano2').val(value);
    });
    

});
        

var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
    
  $("#printtoexcel").die().live ("click",function() {
                                                        
        var complaint = $("#complaint").  val();
        var advertisername = $("#advertisername").val();
        var agencyname = $("#agencyname").val();
        var accountexec = $("#accountexec").val();
        var invoiceno = $("#invoiceno").val();
        var issuedatefrom = $("#issuedatefrom").val();
        var issuedateto = $("#issuedateto").val(); 
        var rfano = $("#rfano").val(); 
        var rfadatefrom = $("#rfadatefrom").val(); 
        var rfadateto = $("#rfadateto").val(); 
        var person = $("#searchperson").val(); 
        var responsible = $("#responsible").val(); 
        var rfatypes = $("#rfatypes").val(); 
        
        if (complaint == "") {
            complaint = "null";
        } 
        if (advertisername == "") {
            advertisername = "null";
        }
        if (agencyname == "") {
            agencyname = "null";
        }
        if (accountexec ==""){
            accountexec = "null"
        }
        if (invoiceno ==""){
            invoiceno = "null"
        }
        if (issuedatefrom ==""){
            issuedatefrom = "null"
        }
        if(issuedateto ==""){
            issuedateto = "null"
        }
        if(rfano ==""){
            rfano = "null"
        }
        if(rfadatefrom ==""){
            rfadatefrom = "null"
        }
        if(rfadateto ==""){
            rfadateto = "null"
        }
        if(person == ""){
            person = "null"
        }
        if(responsible ==""){
            responsible = "null"
        }
        if(rfatypes ==""){
            rfatypes = "null"
        }
        
       
        var countValidate = 0;  
        var validate_fields = ['issuedatefrom','issuedateto'];
                                               
    
    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }                 
    }
    if (countValidate == 0)    
       
    { 
    window.open("<?php echo site_url('rfa/exportToExcel/') ?>?complaint="+complaint+"&advertisername="+advertisername+"&agencyname="+agencyname+"&accountexec="+accountexec+"&invoiceno="+invoiceno+"&issuedatefrom="+issuedatefrom+"&issuedateto="+issuedateto+"&rfano="+rfano+"&rfadatefrom="+rfadatefrom+"&rfadateto="+rfadateto+"&searchperson="+person+"&responsible="+responsible+"&rfatypes="+rfatypes, '_blank');    
        window.focus();
    }                         
});     
</script>


