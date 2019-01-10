<style type="text/css" title="currentStyle">
    @import "<?php echo base_url() ?>assets/media/css/demo_page.css";
    @import "<?php echo base_url() ?>assets/media/css/demo_table.css";
    .FixedHeader_Cloned th { background-color: white; }
    .fixedHeader th {font-size:10px !important;} 
   
     #inq_btn  ul
    {
        list-style: none;
        margin:0px;
    }  
    
    #inq_btn  li
    {
        display: inline;
        list-style-type: none;
        padding-right: 5px;
    } 
</style>

        <script type="text/javascript" charset="utf-8" src="<?php echo base_url() ?>assets/js/FixedHeader.js"></script> 
        
        <script type="text/javascript" charset="utf-8">
       
        </script>   
        
       
<link rel='stylesheet' type='text/css' href="<?php echo base_url('assets/css/jquery-ui-1.8.17.custom.css'); ?>" />    
 <div class="workplace">
            
    <div class="row-fluid">

        <div class="span12">  
           
            <div class="head">
            
                <h1>Inquiries</h1>
                                                          
                <div class="clear"></div>
                
            </div> 
            
            <div class="block-fluid">   
      
                <form id="inquiry_form" action="<?php echo site_url("exdeal_inquiry/generate") ?>" method="POST">
      
                  <div class="row-form-booking">
                   
                      <div class="span1" ><b>From Date</b></div>    
                  
                      <div class="span2"><input type="text" name="from_date" id="from_date" class="dates"></div>
                  
                      <div class="span1" ><b>To Date</b></div>    
                  
                      <div class="span2"><input type="text" name="to_date" id="to_date" class="dates"></div>                
                      
                      <div class="span1" ><b>From</b></div> 
                      
                      <div class="span2"><input type="text" name="from_select" id="from_select"></div>  
                      
                      <div class="span1" ><b>To </b></div> 
                      
                      <div class="span2"><input type="text" name="from_select" id="to_select" class="to_select"></div> 

   
                </div>
                  <div class="clear"></div>   

             
            
             <div class="row-form-booking">   
             
                   <div class="span1" ><b>P.O. #</b></div> 
                      
                      <div class="span2"><input type="text" name="po_number" id="po_number"></div> 
                      <div class="span1" ><button class="btn" id="generate_btn" type="button">Generate</button></div>
                      <div class="span1" ><button class="btn" id="export_btn" type="button">Export</button></div>
                      <!--                        <li><button class="btn" id="filter_btn" type="button">Filter</button></li>  -->  
                 
                 <div class="clear"></div>
            </div>
                              
            </div> 
             
         
            </form>  
         
    </div>
 

    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
      
           
    </thead>
    <tbody id="result">
     
    </tbody>
    </table>



        
    </div>
    
      <div class="dialog" id="b_popup_4" style="display: none;"></div>     
      <div class="filters" id="filters" style="display: none;"></div>     
   
<script> 

     $( document ).ready(function() {
       //  headerselect();
     });
     
     $( ".dates" ).datepicker( { dateFormat: 'yy-mm-dd' } );  
       
     var xhr = null;
       
     $("#generate_btn").die().live("click", function()
     {
        validate();            
     });
     
     $(".radio_inquiry").die().live("click",function()
     {
        validate(); 
     });
     
     
     
     $("#filter_btn").die().live("click",function()
     {
           xhr && xhr.abort(); 
           xhr = $.ajax({
               url:"<?php echo site_url("exdeal_inquiry/filters"); ?>",
               type:"post",
               data:{},
               success: function(response)
               {
                   //   $response = $.parseJSON(response);
                     var options = {
                            buttons: {
                                },width:600
                            
                           };                            
                
                      $("#b_popup_4").dialog('option', options);
                      $("#b_popup_4").dialog('option', 'title', 'Inquiry Data');    
                      $("#b_popup_4").html($.parseJSON(response)).dialog("open");    
               }
           }); 
         
       
     });   
     
    
     
     function validate()
     {
          var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
        
          var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
            
          var countValidate = 0;  
  
          var validate_fields = ["#from_date","#to_date"];

            for (x = 0; x < validate_fields.length; x++) {            
                if($(validate_fields[x]).val() == "") {                        
                    $(validate_fields[x]).css(errorcssobj);          
                      countValidate += 1;
                } else {        
                      $(validate_fields[x]).css(errorcssobj2);       
                }        
            } 
   
             if (countValidate == 0) {
        
                    //  generate_inq();
                      generatewithfilters();
                 
               
                } else {            
               
                 return false;
               
                }   
     }
     
     function generate_inq()
     {
          
         xhr && xhr.abort();
         
         xhr = $.ajax({
             url:"<?php echo site_url('exdeal_inquiry/generate') ?>",
             type:"post",
             data:$("#inquiry_form").serialize(),
             success: function (response)
             {
                 
                 $response = $.parseJSON(response);

                 $("#example > thead").html($response['header']);
                    
                 $("#example > tbody").html($response['result']);
                    
                 new FixedHeader( document.getElementById('example') );   

              }
          });
     }   
     
     $('.inquiry_tr').die().live("dblclick",function() {
         
        $from_date    = $("#from_date").val();
        $to_date      = $("#to_date").val();
        
         xhr && xhr.abort();
         xhr = $.ajax({
                      url: "<?php echo site_url('exdeal_inquiry/getinquirydata') ?>",
                      type: "POST",
                      data: {id:$(this).attr('val'),
                             from_date:$from_date,
                             to_date:$to_date},
                      success : function(response)
                      {
                              var options = {
                                    buttons: {
                                      /*  Submit: function () {  
                                             saveinquiryupdate();
                                              $(this).dialog('close');  
                                            
                                        }, */
                                         Close: function () {
                                            $(this).dialog('close');
                                        }
                                    },width:900
                                    
                                   };                            
                        
                              $("#b_popup_4").dialog('option', options);
                              $("#b_popup_4").dialog('option', 'title', 'Filters');    
                              $("#b_popup_4").html($.parseJSON(response)).dialog("open");    
                      }
             });
    });
    
    function saveinquiryupdate()
    {  
       xhr && xhr.abort(); 
       
       $ao_id =  new Array();
       $exdeal_status =  new Array();
       $exdeal_percent =  new Array();
       $exdeal_amount =  new Array();
       $contract_no =  new Array();
       $exdeal_remarks =  new Array();
       $trigger_box =  new Array();
       
       $.removeData($ao_id);

       
        $.each($("input[name='ao_id[]']"), function() {
        $ao_id.push($(this).val());
        
        });
        $.each($("input[name='exdeal_status[]']:checked"), function() {
        $exdeal_status.push($(this).val());  
        }); 
        $.each($("input[name='trigger_box[]']:checked"), function() {
        $trigger_box.push($(this).val());
        });
        $.each($("input[name='exdeal_percent[]']"), function() {
        $exdeal_percent.push($(this).val());
        });
        $.each($("input[name='exdeal_amount[]']"), function() {
        $exdeal_amount.push($(this).val());
        });
        $.each($("input[name='contract_no[]']"), function() {
        $contract_no.push($(this).val());
        });
        $.each($("input[name='exdeal_remarks[]']"), function() {
        $exdeal_remarks.push($(this).val());
        });
        
        $ao_ref = $("#ao_ref").val();

        xhr = $.ajax({
              url:"<?php echo site_url('exdeal_inquiry/update_inquiry'); ?>",
              type:"POST",
              cache:false,
              data:{ao_id:$ao_id,exdeal_status:$exdeal_status,trigger_box:$trigger_box,
                    exdeal_percent:$exdeal_percent,exdeal_amount:$exdeal_amount,
                    contract_no:$contract_no,exdeal_remarks:$exdeal_remarks,ao_ref:$ao_ref},
              success : function(response)
              {       
                      $("#b_popup_4").dialog("close");
                      generatewithfilters();  
                       
              }
       });  
    }
    
    $("#export_btn").die().live("click",function()
    {
        xhr && xhr.abort();  
        
        xhr = $.ajax({
              url : "<?php echo site_url("exdeal_inquiry/exportselection") ?>",
              type: "POST" ,
              data:{},
              success : function(response)
              {
                      var options = {
                            buttons: {
                                 Close: function () {
                                    $(this).dialog('close');
                                }
                            },width:400
                            
                           };                            
                
                      $("#b_popup_4").dialog('option', options);
                      $("#b_popup_4").dialog('option', 'title', 'Export');    
                      $("#b_popup_4").html($.parseJSON(response)).dialog("open");    
              }
        });
          
    });
    
    $("#inquiry_type").die().live("change",function(){
        
     //   headerselect();
        
    });
    
    function headerselect()
    {
       $header = $("#inquiry_type option:selected").val(); 
     
        if($header != "")
        {
             $.ajax({
                url:"<?php echo site_url("exdeal_inquiry/selectheader") ?>",
                type:"POST",
                data:{header:$header},
                success:function (response){
                    
                    $html = $.parseJSON(response);
                    
                    $("#example > thead").html($html);
                    
                     new FixedHeader( document.getElementById('example') );   
                }
            });   
        } 
    }  
     
     function generatewithfilters()
     {
                       
           var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
        
          var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
            
          var countValidate = 0;  
        
          var validate_fields = ['#inquiry_type',"#from_date","#to_date","#from_select","#to_select","#po_number"];   
          
          $inquiry_type = $("#inquiry_type").val();
          $from_date    = $("#from_date").val();
          $to_date      = $("#to_date").val();
          $from_select  = $("#from_select").val();
          $to_select    = $("#to_select").val();
          $po_number    = $("#po_number").val();
          $radio_filter = $('input[name=filter_type]');
          $filter_type =  $radio_filter.filter(':checked').val();
          $radio_status =   $('input[name=status]'); 
          $status_type =  $radio_status.filter(':checked').val();  

            for (x = 0; x < validate_fields.length; x++) {            
                if($(validate_fields[x]).val() == "") {                        
                    $(validate_fields[x]).css(errorcssobj);          
                      countValidate += 1;
                } else {        
                      $(validate_fields[x]).css(errorcssobj2);       
                }        
            } 
   
             if (countValidate == 0) {
        
                      xhr && xhr.abort(); 
              
                         xhr = $.ajax({
                                     url:"<?php echo site_url('exdeal_inquiry/generate') ?>",
                                     type:"post",
                                     data:{inquiry_type:$inquiry_type,
                                                   from_date:$from_date,
                                                   to_date:$to_date,
                                                   from_select:$from_select,
                                                   to_select:$to_select,
                                                   po_number:$po_number,
                                                   filter_type:$filter_type
                                                   },
                                     success: function (response)
                                     {
                                         
                                         $response = $.parseJSON(response);

                                         $("#example > thead").html($response['header']);
                                            
                                         $("#example > tbody").html($response['result']);
                                            
                                         new FixedHeader( document.getElementById('example') );   
                                          $("#filters").html($("#b_popup_4").html());
                                         // $("#b_popup_4").dialog("close");    

                                      }
                                  });
               
                } else {            
               
                 return false;
               
                }  
     } 
     
     
     
      $('#from_select').die().live("focus",function(){  
  
       $(this).autocomplete({
        
        autoFocus: true,
        
        source: function( request, response ) {
            
            $.post('<?php echo site_url('exdeal_inquiry/createFilter'); ?>', {
                
                search : request.term,
                
                filter_type: "direct_client",     
                
                parent_val: $("#from_select").val(),     
            
                }, function(data) {

                response($.map(data,function(item) {
                    
                    return {
                        
                        label: item.label,
                    
                        value: item.value
                        
                     //   item : id
                    }
                }));
                
            }, 'json');
        },
        
        minLength: 2,
        
        select: function(event, ui) {
            
            //location.href = '<?php echo current_url(); ?>'+'/?employee='+ui.item.item.code;
        }
        
        });
        
     });
     
     

     
$('#to_select').die().live("focus",function(){  
          
          $(this).autocomplete({
        
        autoFocus: true,
        
        source: function( request, response ) {
            
            $.post('<?php echo site_url('exdeal_inquiry/createFilter'); ?>', {
                
                search : request.term ,
                
                filter_type:"direct_client", 
                
                parent_val: $("#to_select").val(),     
            
                }, function(data) {

                response($.map(data,function(item) {
                    
                    return {
                        
                        label: item.label,
                    
                        value: item.value
                        
                     //   item : id
                    }
                }));
                
            }, 'json');
        },
        
        minLength: 2,
        
        select: function(event, ui) {
            
            //location.href = '<?php echo current_url(); ?>'+'/?employee='+ui.item.item.code;
        }
        
         });
     });

    var xhr = "";
    
    $(".filter_type").click(function ()
    {
           $type = $(this).val();
        
           switch ($type) 
           {
               case "agency":
               
                    $("#from_select").prop("disabled",false);
                    
                    $("#to_select").prop("disabled",false);
                    
                    $("#select_from").html("From");
                    
                    $("#select_to").html("To");
                
               break;
               
               case "agency_client":
               
                    $("#from_select").prop("disabled",false);
                    
                    $("#to_select").prop("disabled",false);
                    
                    $("#select_from").html("Agency");
                    
                    $("#select_to").html("Client");
                
               break;
               
               case "all_client":
               
                    $("#from_select").prop("disabled",false);
                    
                    $("#to_select").prop("disabled",false);
                    
                    $("#select_from").html("From");
                    
                    $("#select_to").html("To");
                    
               break;
               
               case "client_agency":
               
                    $("#from_select").prop("disabled",false);
                    
                    $("#to_select").prop("disabled",false);
                    
                    $("#select_from").html("Client");
                    
                    $("#select_to").html("Agency");
                
               break;
               
               
                case "direct_client":
               
                    $("#from_select").prop("disabled",false);
                    
                    $("#to_select").prop("disabled",false);
                    
                    $("#select_from").html("From");
                    
                    $("#select_to").html("To");
                
               break;
                        
           }
  
    });

</script>

 