<div class="block-fluid">      
    <form action="<?php echo site_url('customer/save') ?>" method="post" name="formsave" id="formsave"> 
    <div id="tab_view">
        <ul>
        <li><a href="#basic">Basic Info</a></li>
        <li><a href="#detail">Detail Info</a></li>
        </ul>
        <div id="basic">
            <div class="row-form-booking">
                <div class="span1"><b>Customer</b></div>    
                <div class="span2" style="width:100px"><input type="text" placeholder="Code" name="customercode" id="customercode" style="text-transform:uppercase;"></div>        
                <div class="span4" style="width:340px"><input type="text" placeholder="Name" name="customername" id="customername" style="text-transform:uppercase;"></div>    
                <div class="clear"></div>    
            </div>
            <div class="row-form-booking">
                 <div class="span2"><b>Bldg/Strt No.</b></div> 
                 <div class="span3"><input type="text" name="address1" id="address1"></div>  
                 <div class="span1"><b>Title</b>&nbsp;<input type="text" name="title" id="title" style="width:30px" style="text-transform:uppercase;"></div>                  
                 <div class="clear"></div>     
            </div>
            <div class="row-form-booking">
                 <div class="span2"><b>Brgy / City / Town</b></div> 
                 <div class="span4"><input type="text" name="address2" id="address2"></div>    
                 <div class="clear"></div>     
            </div>
            <div class="row-form-booking">
                 <div class="span1"><b>Province</b></div> 
                 <div class="span2"><input type="text" name="address3" id="address3"></div>    
                 <div class="span1"><b>Branch</b></div>
                 <div class="span2">
                     <select name="branch" id="branch">
                     <option value=''>--</option>
                     <?php foreach ($branch as $branch) : ?>
                     <option value='<?php echo $branch['id'] ?>'><?php echo $branch['branch_name'] ?></option>             
                     <?php endforeach; ?>
                     </select>
                 </div>    
                 <div class="clear"></div>     
            </div>
            <div class="row-form-booking">
                 <div class="span1"><b>Country</b></div> 
                 <div class="span2">
                     <select name="country" id="country">
                     <option value=''>--</option>
                     <?php foreach ($country as $country) : ?>
                     <option value='<?php echo $country['id'] ?>'><?php echo $country['country_name'] ?></option>             
                     <?php endforeach; ?>
                     </select>
                 </div>    
                 <div class="span1"><b>Zip Code</b></div> 
                 <div class="span2">
                    <select name="zipcode" id="zipcode">
                    <option value=''>--</option>
                    <?php foreach ($zipcode as $zipcode) : ?>
                    <option value='<?php echo $zipcode['id'] ?>'><?php echo $zipcode['zip_name'] ?></option>             
                    <?php endforeach; ?>
                    </select>
                 </div>    
                 <div class="clear"></div>     
            </div>
            <div class="row-form-booking">  
                <div class="span1"><b>TelNo 1</b></div>         
                <div class="span2"><input type="text" name="telephone1" id="telephone1" style="width:50px">&nbsp;<input type="text" name="telephoneno1" id="telephoneno1" style="width:165px"></div> 


                <div class="span1"><b>TelNo 2</b></div>         
                <div class="span2"><input type="text" name="telephone2" id="telephone2" style="width:50px">&nbsp;<input type="text" name="telephoneno2" id="telephoneno2" style="width:165px"></div>    
                <div class="clear"></div>     
            </div>
            <div class="row-form-booking">  
                <div class="span1"><b>Celphone</b></div>         
                <div class="span2"><input type="text" name="cellphone" id="cellphone" style="width:50px">&nbsp;<input type="text" name="cellphoneno" id="cellphoneno" style="width:115px"></div>                
                <div class="span1"><b>Fax No.</b></div>         
                <div class="span2"><input type="text" name="fax" id="fax" style="width:50px">&nbsp;<input type="text" name="faxno" id="faxno" style="width:115px"></div>    
                <div class="clear"></div>     
            </div>
            <div class="row-form-booking">
                 <div class="span1"><b>Agency/Direct</b></div> 
                 <div class="span2">
                    <select name="agencydirect" id="agencydirect">
                    <option value=''>--</option>
                    <?php foreach ($catad as $catad) : ?>
                    <option value='<?php echo $catad['id'] ?>'><?php echo $catad['catad_name'] ?></option>             
                    <?php endforeach; ?>
                    </select>
                 </div>    
                 <div class="span1"><b>Pay Type</b></div> 
                 <div class="span2">
                    <select name="paytype" id="paytype">
                    <option value=''>--</option>
                    <?php foreach ($paytype as $paytype) : ?>
                    <option value='<?php echo $paytype['id'] ?>'><?php echo $paytype['paytype_name'] ?></option>             
                    <?php endforeach; ?>
                    </select>
                 </div>    
                 <div class="clear"></div>     
            </div>
            <div class="row-form-booking">
                 <div class="span1"><b>VAT Code</b></div> 
                 <div class="span2">
                    <select name="vatcode" id="vatcode">
                    <option value=''>--</option>
                    <?php foreach ($vat as $vat) : ?>
                    <option value='<?php echo $vat['id'] ?>'><?php echo $vat['vat_name'] ?></option>             
                    <?php endforeach; ?>
                    </select>
                 </div>    
                 <div class="span1"><b>Acct. Exec</b></div> 
                 <div class="span2">
                    <select name="acctexec" id="acctexec">
                    <option value=''>--</option>
                    <?php foreach ($acctexec as $acctexec) : ?>
                    <option value='<?php echo $acctexec['user_id'] ?>'><?php echo $acctexec['empprofile_code'].' - '.$acctexec['firstname'].' '.$acctexec['lastname'] ?></option>             
                    <?php endforeach; ?>
                    </select>
                 </div>    
                 <div class="clear"></div>     
            </div>
            <div class="row-form-booking">
                 <div class="span1"><b>Collector</b></div> 
                 <div class="span2">
                    <select name="collector" id="collector">
                    <option value=''>--</option>
                    <?php foreach ($collector as $collector) : ?>
                    <option value='<?php echo $collector['user_id'] ?>'><?php echo $collector['empprofile_code'].' - '.$collector['firstname'].' '.$collector['lastname'] ?></option>             
                    <?php endforeach; ?>
                    </select>
                 </div>    
                 <div class="span1"><b>Coll Area</b></div> 
                 <div class="span2">
                    <select name="collectorarea" id="collectorarea">
                    <option value=''>--</option>
                    <?php foreach ($collectorarea as $collectorarea) : ?>
                    <option value='<?php echo $collectorarea['id'] ?>'><?php echo $collectorarea['collarea_code'].' - '.$collectorarea['collarea_name'] ?></option>             
                    <?php endforeach; ?>
                    </select>
                 </div>    
                 <div class="clear"></div>     
            </div>
            <div class="row-form-booking">
                 <div class="span1"><b>Coll Asst.</b></div> 
                 <div class="span2">
                    <select name="collectorasst" id="collectorasst">
                    <option value=''>--</option>
                    <?php foreach ($collectorasst as $collectorasst) : ?>
                    <option value='<?php echo $collectorasst['user_id'] ?>'><?php echo $collectorasst['empprofile_code'].' - '.$collectorasst['firstname'].' '.$collectorasst['lastname'] ?></option>             
                    <?php endforeach; ?>
                    </select>
                 </div>    
                 <div class="span1" style="width: 120px"><!--<input type="checkbox" style="width: 20px;" name="pana" id="pana" value="1"><b>Pana/Non-Pana</b>   -->
                    <select name="pana" id="pana">
                        <option value='1'>Pana</option>
                        <option value='0' selected="selected">Non-Pana</option>
                    </select>
                 
                 </div> 
                 <div class="span1" style="width: 120px"><!--<input type="checkbox" style="width: 20px;" name="govt" id="govt" value="1"><b>Gov't/Non-Gov't</b></div>--> 
                    <select name="govt" id="govt">
                        <option value='1'>Gov't</option>
                        <option value='0' selected="selected">Non-Gov't</option>
                    </select>
                 <div class="clear"></div>     
            </div>
                <div class="row-form-booking">       
                    <div class="span1"><b>TIN No</b></div> 
                    <div class="span2"><input type="text" name="tin" id="tin"></div>   

                    <div class="span1"><b>Industry</b></div> 
                    <div class="span2">
                    <select name="industry" id="industry">
                    <option value=''>--</option>
                    <?php foreach ($industry as $industry) : ?>
                    <option value='<?php echo $industry['id'] ?>'><?php echo $industry['ind_name'] ?></option>             
                    <?php endforeach; ?>
                    </select>
                </div>    
                <div class="clear"></div>     
            </div>
        </div>
        </div>    
        <div id="detail">
            <div class="row-form-booking">        
                <div class="span1"><b>C. Person</b></div> 
                <div class="span2"><input type="text" name="contactperson" id="contactperson"></div>    
                <div class="span1"><b>Position</b></div> 
                <div class="span2"><input type="text" name="position" id="position"></div>            
                <div class="clear"></div>     
            </div>
            <div class="row-form-booking">  
                <div class="span1"><b>Title</b></div> 
                <div class="span2"><input type="text" name="title" id="title"></div>    
                <div class="span1"><b>Email Add</b></div> 
                <div class="span2"><input type="text" name="email" id="email"></div>   
                <div class="clear"></div>               
            </div>
            <div class="row-form-booking">  
                <div class="span1"><b>URL Link</b></div> 
                <div class="span5"><input type="text" name="url" id="url"></div>                    
                <div class="clear"></div>               
            </div>
            <div class="row-form-booking">  
                <div class="span1"><b>WTAX</b></div> 
                <div class="span2">
                    <select name="wtax" id="wtax">
                    <option value=''>--</option>
                    <?php foreach ($wtax as $wtax) : ?> 
                    <option value='<?php echo $wtax['id'] ?>'><?php echo $wtax['wtax_name'] ?></option>             
                    <?php endforeach; ?>
                    </select>
                </div>  
                <div class="span1"><b>Remarks</b></div> 
                <div class="span2"><input type="text" name="remarks" id="remarks"></div>                    
                <div class="clear"></div>               
            </div>
            <div class="row-form-booking">  
                <div class="span1"><b>Cardholder</b></div> 
                <div class="span2"><input type="text" name="cardholder" id="cardholder"></div> 
                <div class="span1"><b>Card Type</b></div> 
                <div class="span2">
                    <select name="cardtype" id="cardtype">
                    <option value=''>--</option>
                    <?php foreach ($creditcard as $creditcard) :  ?>
                          <option value='<?php echo $creditcard['id'] ?>'><?php echo $creditcard['creditcard_name'] ?></option>             
                    <?php endforeach; ?>
                    </select>
                </div>              
                <div class="clear"></div>               
            </div>
            <div class="row-form-booking">  
                <div class="span1"><b>Card No.</b></div> 
                <div class="span2"><input type="text" name="cardno" id="cardno" ></div>    
                <div class="span1"><b>Authorization#</b></div> 
                <div class="span2"><input type="text" name="authorizationno" id="authorizationno" ></div>   
                <div class="clear"></div>               
            </div>
            <div class="row-form-booking">  
                <div class="span1"><b>Expiry Date</b></div> 
                <div class="span2"><input type="text" name="expirydate" id="expirydate"></div>    
                <div class="span1"><b>Crdt Status</b></div> 
                <div class="span2">
                    <select name="creditstatus" id="creditstatus">
                    <!--<option value=""></option> -->
                    <option value="Y">YES</option>
                    <!--<option value="N">NO</option>   -->
                    <option value="B">BAD</option>
                    <!--<option value="O">AUTO-OVERRIDE</option> -->
                    <option value="A" selected="selected">AUTO-CF</option>
                    </select>
                </div> 
                <div class="clear"></div>               
            </div>
            <div class="row-form-booking">  
                <div class="span1"><b>Credit Limit</b></div> 
                <div class="span2"><input type="text" name="creditlimit" id="creditlimit" style="text-align:right"></div>    
                <div class="span1"><b>Crdt Terms</b></div> 
                <div class="span2">
                    <select name="creditterms" id="creditterms">
                    <option value=''>--</option>
                    <?php foreach ($creditterm as $creditterm) : ?>
                    <option value='<?php echo $creditterm['id'] ?>'><?php echo $creditterm['crf_name'] ?></option>             
                    <?php endforeach; ?>
                    </select>
                </div> 
                <div class="clear"></div>               
            </div>
            <div class="row-form-booking">  
                <div class="span1"><b>Credit Rem.</b></div> 
                <div class="span5"><input type="text" name="creditrem" id="creditrem"></div>                    
                <div class="clear"></div>               
            </div>
            <div class="row-form-booking">  
                <div class="span1"><b>Ad Rem.</b></div> 
                <div class="span5"><input type="text" name="adrem" id="adrem"></div>                    
                <div class="clear"></div>               
            </div>
        </div>
    </div>

    <div class="row-form-booking">
        <div class="span2"><button class="btn btn-success" type="button" name="save" id="save">Save New Customer button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#creditlimit").autoNumeric();

$("#tin").mask("999-999-999-999");

$("#telephone1").mask("999"); 
//$("#telephoneno1").mask("999-9999"); 

$("#telephone2").mask("999");
//$("#telephoneno2").mask("999-9999");

$("#cellphone").mask("9999");
$("#cellphoneno").mask("999-99-99");

$("#fax").mask("999");
$("#faxno").mask("999-99-99");


$("#tab_view").tabs();
$("#edition_totalccm").autoNumeric();
$("#expirydate").datepicker({dateFormat: 'yy-mm-dd'});
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
    
    var paytype = $('#paytype').val();
    
    var countValidate = 0;  
    var validate_fields = ['#customercode', '#customername', '#address1', '#telephone1', '#telephoneno1', '#paytype', '#vatcode', 
                           '#acctexec', '#agencydirect', '#tin', '#collector', '#collectorarea', '#branch',
                           '#collectorasst', '#industry'];
                           
    if (paytype == 4) {
        alert('Credit Card Details must not be empty!.');
        validate_fields = ['#customercode', '#customername', '#address1', '#telephone1', '#telephoneno1', '#paytype', '#vatcode', 
                           '#acctexec', '#agencydirect', '#tin', '#collector', '#collectorarea', '#branch',
                           '#collectorasst', '#industry', '#cardholder', '#cardtype', '#cardno', '#authorizationno'];
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
        $.ajax({
            url: "<?php echo site_url('customer/validateCode') ?>",
            type: "post",
            data: {code : $("#customercode").val()},
            success: function(response) {
                if (response == "true") {                    
                       alert("Customer Code must be unique!.");
                       $('#customercode').val('');
                   } else {
                       $('#formsave').submit();
                   }
            }
        });        
    } else {            
        return false;
    }    
});
</script>