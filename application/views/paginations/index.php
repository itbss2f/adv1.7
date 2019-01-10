<div class="breadLine">
    
    <?php echo $breadcrumb; ?>
                        
</div>

<div class="workplace">

	<div class="row-fluid">

		<div class="span6">
		<div class="head">
		    <div class="isw-list"></div>
		    <h1>Mass Pagination</h1>
		    <div class="clear"></div>
		</div>
		<div class="block-fluid">                        
		    
		    <div class="row-form">
			   <div class="span2">Products:</div>
			   <div class="span3">
				<select class='select' name='product' id='product'>                
					<option value=''>All Product</option>
					<?php
					foreach ($product as $product) : ?>
					<option value="<?php echo $product['id']?>"><?php echo $product['prod_name'] ?></option>
					<?php endforeach; ?>
				</select>
			   </div>
               <div class="span2">Booking Type:</div> 
               <div class="span3">
                <select class='select' name='booktype' id='booktype'>                
                    <option value='D'>Display</option>
                    <option value='C'>Classified</option>
                </select>
               </div>
			   <div class="clear"></div>
		    </div>                                            

		    <div class="row-form">
			   <div class="span2">Issue Date</div>
			   <div class="span3"><input type="text" placeholder="Date From" name="fromdate" id="fromdate"></div>
			   <div class="span3"><input type="text" placeholder="Date To" name="todate" id="todate"></div>
			   <div class="clear"></div>
		    </div>                         
		    
		    <div class="row-form">		
			   <div class="span2"><button class="btn btn-success" type="button" name="mass_paginate" id="mass_paginate">Paginate</button></div>
			   <div class="span2"><button class="btn btn-danger" type="button" name="clear_mass_paginate" id="clear_mass_paginate">Clear Date</button></div>	   
			   <div class="clear"></div>
		    </div>     
		    <div class="block-fluid table-sorting">
				<table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable1">
					<thead>
					<tr>					   
                       <th width="15%"># Counter</th>
					   <th width="25%">AO Number</th>
                       <th width="30%">Issue Date</th>                                                              
                       <th width="20%">Dummy Flow</th>                                                              
					   <th width="20%">Unlock</th>                                                              
					</tr>
					</thead>
					<tbody id="mass_result"></tbody>
				</table>
				<div class="clear"></div>
		    </div>	      
		    		  
		</div>
		</div>

		<div class="span6">
		<div class="head">
		    <div class="isw-target"></div>
		    <h1>Single Pagination / Unpagination</h1>
		    <div class="clear"></div>
		</div>
		<div class="block-fluid">                        
		    <div class="row-form">
			   <div class="span10">
				<span class="label label-info" style="color:#fff">Use this module to unpaginate</span>
			   </div>
			   <div class="clear"></div>
		    </div> 
		    <div class="row-form">
			   <div class="span2">AO Number</div>
			   <div class="span3"><input type="text" class="text" name="adnumber" id="adnumber"></div>
			   <div class="span2">Issue Date</div>
			   <div class="span3"><input type="text" class="text" name="issuedate" id="issuedate"></div>
			   <div class="clear"></div>
		    </div>                          
		    
		    <div class="row-form">		
			   <div class="span3"><button class="btn btn-success" type="button" name="single_paginate" id="single_paginate">Paginate</button></div>
			   <div class="span3"><button class="btn btn-danger" type="button" name="single_unpaginate" id="single_unpaginate">UnPaginate</button></div>	   
			   <div class="clear"></div>
		    </div> 

		    <div class="block-fluid table-sorting">
				<table cellpadding="0" cellspacing="0" width="100%" class="table" id="tSortable2">
					<thead>
					<tr>					   
					   <th width="25%">AO Number</th>
					   <th width="30%">Issue Date</th>                                                              
					   <th width="50%">Remarks</th> 
					</tr>
					</thead>
					<tbody id="single_result"></tbody>
				</table>
				<div class="clear"></div>
		    </div>	 
		    
		</div>
	</div>                

	<div class="dr"><span></span></div>                

</div>  

<script>
//$('#tSortable1, #tSortable2').dataTable({});

$('#tSortable1, #tSortable2 ').dataTable( {
        "bPaginate": false,
        "bLengthChange": false,
        "bFilter": false,
        "bInfo": false,
    } );
$("#fromdate").datepicker({dateFormat: 'yy-mm-dd'});
$("#todate").datepicker({dateFormat: 'yy-mm-dd'});
$("#issuedate").datepicker({dateFormat: 'yy-mm-dd'});

$("#single_paginate").click(function(){
    var $adnumber = $("#adnumber").val();
    var $issuedate = $("#issuedate").val();
    if ($adnumber == "" || $issuedate == "") {
        alert ("Date must not be empty!");
        return false;
    } else {
        var ans = confirm ("Are you sure you want to Paginate this?");
    
        if (ans) {
        $.ajax({
            url: "<?php echo site_url('pagination/adpaginate/p') ?>",
            type: "post",
            data: {adnumber: $adnumber, issuedate: $issuedate},
            success: function(response) {
                var $response = $.parseJSON(response);
                if ($response['singleresult'] == "") {
                    alert("No Date Record Found! ");
                    return false;
                } else {
                    $("#single_result").html($response['singleresult']);      
                }
            }
        });
        }
    }
});

$("#single_unpaginate").click(function(){
    var $adnumber = $("#adnumber").val();
    var $issuedate = $("#issuedate").val();
    if ($adnumber == "" || $issuedate == "") {
        alert ("Date must not be empty!");
        return false;
    } else {
        var ans = confirm ("Are you sure you want to Unpaginate this?");
    
        if (ans) {
        $.ajax({
            url: "<?php echo site_url('pagination/adpaginate/u') ?>",
            type: "post",
            data: {adnumber: $adnumber, issuedate: $issuedate},
            success: function(response) {
                var $response = $.parseJSON(response);
                if ($response['singleresult'] == "") {
                    alert("No Date Record Found! ");
                    return false;
                } else {
                    $("#single_result").html($response['singleresult']);
                }
            }
        });
        }
    }
});

$("#mass_paginate").click(function(){
    var $fromdate = $("#fromdate").val();
    var $todate = $("#todate").val();
    var $product = $("#product").val();
    var $booktype = $("#booktype").val();
    
    var ans = confirm ("Are you sure you want to Mass Paginate?");
    
    if (ans) {

        if ($fromdate == "" || $todate == "") {
            alert ("Date must not be empty!");
            return false;
        } else {
        
            if ($fromdate > $todate) {
                    alert ("The to date must be greater than your from date");
                    return false;
            }    
            $.ajax({
                url: "<?php echo site_url('pagination/masspaginate') ?>",
                type: "post",
                data: {product:$product, fromdate: $fromdate, todate: $todate, booktype: $booktype},
                success: function(response) {
                    var $response = $.parseJSON(response);
                    
                    if ($response['massresult'] == "") {
                        alert("No Date Record Found! OR the Data is already paginated!");
                        return false;
                    } else {
                        $('#mass_result').html($response['massresult']);
                    }
                }
            });  
        }
    }

});

$("#clear_mass_paginate").click(function(){
    $("#fromdate").val('');    
    $("#todate").val('');    
    $('#mass_result').html('');
});

</script>
