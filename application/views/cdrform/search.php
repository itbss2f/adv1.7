<div class="block-fluid">
    <div class="row-form-booking">
        <div class="span1" style="width:100px">CDR No.</div>
        <div class="span1"><input type="text" id="cdr_no" name="cdr_no"/></div>    
        <div class="span1" style="width:50px">Date</div>    
        <div class="span1" style="width:80px"><input type="text" placeholder="From" id="datefrom" name="datefrom"/></div>
        <div class="span1" style="width:80px"><input type="text" placeholder="To" id="dateto" name="dateto"/></div>
        <div class="clear"></div>
    </div>
    <div class="row-form-booking">
        <div class="span1" style="width:100px">Client Name</div>
       <!-- <div class="span1"><input type="text" placeholder="Code" id="lookup_payeecode" name="lookup_payeecode"/></div>    -->
        <div class="span3"><input type="text" placeholder="Client" value="<?php //echo $result->client_name?>" id="client_name" name="client_name"/></div>    
        <div class="clear"></div>
    </div>   
    <div class="row-form-booking">
        <div class="span1" style="width:100px">Agency Name</div>
<!--        <div class="span1"><input type="text" placeholder="Code" id="lookup_payeecode" name="lookup_payeecode"/></div> -->   
        <div class="span3"><input type="text" placeholder="Agency" value="<?php //echo $result->agency_name?>" id="agency_name" name="agency_name"/></div>    
        <div class="clear"></div>
    </div>
    <div class="row-form-booking">
        <div class="span1" style="width:100px">Type of Ad</div>
        <div class="span3"><input type="text" placeholder="Type" value="<?php //echo $result->Type_ad?>"  id="type_ad" name="type_ad"/></div>    
        
        <div class="span2"><button class="btn" id="retrieve" type="button">CDR Retrieving</button></div>    
        <div class="span2"><button class="btn" id="load" type="button">Load CDR</button></div>    
        <div class="clear"></div>
    </div>
    <div class="block-fluid">    
<div class="row-form-booking mCSB_draggerContainer" style="overflow:auto;height:250px"> 
    <table cellpadding="0" cellspacing="0" style="white-space:nowrap;width:400px" class="table" id="tSortable_2">
        <thead>
            <tr>
                <th width="40px">CDR No.#</th>
                <th width="100px">Client Name</th>                                    
                <th width="100px">Agency Name</th>     
                <th width="80px">Date From</th>     
                <th width="80px">Date to</th>     
                <th width="40px">Type of Ad</th> 
            </tr>
        </thead>
        <tbody class="lookup_list">                                                                             
        </tbody>
    </table>
    <div class="clear"></div>
</div>
</div>
</div>     
</div>     

<!--<script>    
$("#cdr_no").mask('99999999');   

$("#datefrom").datepicker({dateFormat: 'yy-mm-dd'});
$("#dateto").datepicker({dateFormat: 'yy-mm-dd'});


///validate from///

$("#retrieve").die().live('click',function(){   
    
  $report_type = $("input[name=swith-radio]:radio:checked").val(); 

  $sort = '';   

  validate();

});   
   function validate(){ 
    var validate_fields = ['#cdr_no'];
    var errorcssobj = {'background': '#E1CECE', 'border' : '1px solid #FF8989'}; 
    var errorcssobj2 = {'background': '#E5E5E5', 'border' : '1px solid #E9EAEE'}; 
      var countValidate = 0;
       for (x = 0; x < validate_fields.length; x++) {
           
            if($(validate_fields[x]).val() == "") {    
                                
                 if (validate_fields[x] == "#datefrom" || validate_fields[x] == "#dateto"){
                     
                    $(validate_fields[x]).css({'border' : '1px solid #FF8989'});
                    
                } else {
                    
                    $(validate_fields[x]).css(errorcssobj); 
                }            
                
                countValidate += 1;
                
            } else {    
                
                if (validate_fields[x] == "#datefrom" || validate_fields[x] == "#dateto"){
                    
                    $(validate_fields[x]).css({'border' : '1px solid #BBBBBB'});
                    
                } else {
                    
                    $(validate_fields[x]).css(errorcssobj2); 
                    
                }            
            }        
        }
        
        if (countValidate == 0) {
        
            generate();
    
        }        
 }

 $("#search").click(function() {
    var $cdrid = $('#cdr_no').val();
    var $datefrom = $('#datefrom').val();
    var $dateto = $('#dateto').val();
    var $client_name = $('#client_name').val();
    var $agency_name = $('#agency_name').val();
    var $type_ad = $('#type_ad').val();
    var $cdrtype = $('#cdrtype').val();
                                                
    $.ajax({
        url: "<?php echo site_url('cdrforms/search')?>",
        type: 'post',
        data: {$cdr_no: cdr_no,
               $datefrom: datefrom,
               $dateto: dateto,
               $client_name: client_name,
               $agency_name: agency_name,
               $type_ad: type_ad,
              
        },
        success:function (response) {
          var $response = $.parseJSON(response);
          
          $('.searchdata2').html($response['searchdata2'])    
        }
    });
    
    
    $("#retrieve").click(function(){   
        $.ajax({
            url:"<?php echo site_url("cdrfroms/search")?>",
            type:"post",
            date:{},
            success:function(response){
                $response = $.parseJSON(response);
                $("#modal_searchdata2").html($response['search']).dialog('open');      
            } 
        })
    })
       
});
 
</script>-->