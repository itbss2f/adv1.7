<div class="block-fluid">      
    <form action="<?php echo site_url('chartofacct/save') ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">
        <div class="span2" style="width:160px"><b>Chart of Account No.</b></div>    
        <!--<div class="span1"><input type="text" name="acct_codeno" id="acct_codeno" readonly="readonly"></div>   -->     
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking"> 
        <div class="span1"><input type="text" name="acct_main" class="acctno" id="acct_main" placeholder="Main"></div>        
        <div class="span1"><input type="text" name="acct_class" class="acctno" id="acct_class" placeholder="Class"></div>        
        <div class="span1"><input type="text" name="acct_item" class="acctno" id="acct_item" placeholder="Item"></div>        
        <div class="span1"><input type="text" name="acct_cont" class="acctno" id="acct_cont" placeholder="Cont"></div>        
        <div class="span1"><input type="text" name="acct_sub" class="acctno2" id="acct_sub" placeholder="Sub"></div>        
        <div class="clear"></div>       
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Acct Title</b></div>    
        <div class="span3"><input type="text" name="acct_title" id="acct_title"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Acct Desc</b></div>    
        <div class="span3"><input type="text" name="acct_des" id="acct_des"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Acct Code</b></div>    
        <div class="span2" style="width:190px">
            <select name="acct_code" id="acct_code">
                <option value='D'>Debit</option>
                <option value='C'>Credit</option>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Acct Type</b></div>    
        <div class="span2" style="width:190px">
            <select name="acct_type" id="acct_type">
                <option value='P'>P</option>   
                <option value='T'>T</option>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Acct CTAX</b></div>    
        <div class="span2" style="width:190px">
            <select name="acct_ctax" id="acct_ctax">
                <option value='N'>No</option>
                <option value='Y'>Yes</option>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Acct FAS</b></div>    
        <div class="span2" style="width:190px">
            <select name="acct_fas" id="acct_fas">
                <option value='N'>No</option>
                <option value='Y'>Yes</option>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span3"><button class="btn btn-success" type="button" name="save" id="save">Save Chart Acct button</button></div>        
        <div class="clear"></div>        
    </div>
    </form>
</div>
<script>
$(".acctno").mask('9');
$(".acctno2").mask('99');
$("#edition_totalccm").autoNumeric();
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
    var countValidate = 0;  
    var validate_fields = ['#acct_main', '#acct_class', '#acct_item', '#acct_cont', '#acct_sub', '#acct_title', '#acct_des', '#acct_code', '#acct_type'];

    for (x = 0; x < validate_fields.length; x++) {            
        if($(validate_fields[x]).val() == "") {                        
            $(validate_fields[x]).css(errorcssobj);          
              countValidate += 1;
        } else {        
              $(validate_fields[x]).css(errorcssobj2);       
        }        
    }   
    if (countValidate == 0) {
        var code = $('#acct_main').val()+$('#acct_class').val()+$('#acct_item').val()+$('#acct_cont').val()+$('#acct_sub').val(); 
        $.ajax({
            url: "<?php echo site_url('chartofacct/validateCode') ?>",
            type: "post",            
            data: {code : code},
            success: function(response) {
                if (response == "true") {                    
                       alert("Acct Code No. must be unique!.");
                       $(".acctno").val('');
                       $(".acctno2").val('');
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
