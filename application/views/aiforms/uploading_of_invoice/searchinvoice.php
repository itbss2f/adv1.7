<div class="block-fluid">      
    <form action="<?php echo site_url('aiform/search') ?>" method="post" name="formsearch" id="formsearch"> 
            <div class="row-form-booking">
                <div class="span1"><b>Invoice</b></div>    
                <div class="span2" style="width:200px"><input type="text" placeholder="Enter Invoice Number" name="invoicenum" id="invoicenum" style="text-transform:uppercase;"></div>           
                <div class="clear"></div>     
            </div>
        </div>
        </div>
    </div>

    <div class="row-form-booking">
        <div class="span2"><button class="btn btn-success" type="button" name="search" id="search">Search</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$("#creditlimit").autoNumeric();
$("#tab_view").tabs();
$("#edition_totalccm").autoNumeric();
$("#expirydate").datepicker({dateFormat: 'yy-mm-dd'});
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#search").click(function() {

    var countValidate = 0;  
    var validate_fields = ['#invoicenum'];

    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    } 
    if (countValidate == 0) {
        $('#formsearch').submit();
    } else {            
        return false;
    }      
});
</script>
