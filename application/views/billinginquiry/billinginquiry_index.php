        <style type="text/css" title="currentStyle">
            @import "<?php echo base_url() ?>/assets/css/demo_page.css";
            @import "<?php echo base_url() ?>/assets/css/demo_table.css";
            @import "<?php echo base_url() ?>/assets/css/switch.css";
            #from_date{ z-index: 999999; }
            .FixedHeader_Cloned th { background-color: white; }
            .fixedHeader th {font-size:10px !important;}
            .fixedHeader td {padding-left:5px;padding-right: 5px !important;}
        </style>

        
        
 <script type="text/javascript" charset="utf-8" src="<?php echo base_url() ?>/assets/js/FixedHeader.js"></script>       
 
 <div class="workplace" >
            
    <div class="row-fluid" >

        <div class="span12" >  
           
            <div class="head">
            
                <h1>Inquiries</h1>
                                                          
                <div class="clear"></div>
                
            </div> 
            
            <div class="block-fluid">   
      
                <form id="inquiry_form" action="<?php echo site_url("exdeal_inquiry/generate") ?>" method="POST">
      
                  <div class="row-form-booking">
                   
                      <div class="span1" ><b>Date</b></div>    
                  
                      <div class="span1"><input type="text" name="from_date" id="from_date" class="datepicker"></div>
                     
                      <!--<div class="span1" ><b>To Date</b></div>    
                  
                      <div class="span2"><input type="text" name="to_date" id="to_date" class="datepicker"></div>-->                

                      <div class="span2" style="margin-right:0px;">         
                    
                               <select name='report_type' id='report_type' >
                               
                                       <option value='D'>Display</option>
                                       
                                       <option value='C'>Classifieds</option>
                                       
                               </select>
                                             
                        </div> 
                     
                       <div class="span2">         
                            
                                <select name="product_type" id="product_type">
                                    
                                    <option value=''>All</option>    
                                    
                                    <!--<option value='PD'>PDI Broadsheet</option>
                                    
                                    <option value='LI'>Libre</option>    -->
                                    <?php  for($ctr=0;$ctr<count($product);$ctr++){?>
                                    
                                        <option <?php if($product[$ctr]['prod_code']=='PD') { echo "selected='selected'"; } ?> value='<?php echo $product[$ctr]['prod_code'] ?>'><?php echo $product[$ctr]['prod_name'] ?></option>   
                                        
                                    <?php } ?>
                                
                                </select>
                         
                         </div>
                         
                       <div class="span3" style="width:100px;" >         
                            
                                <select name="pay_type" id="pay_type" style="display: none;" >
                                    
                                    <option value=''>All</option>    
                                    
                                    <!--<option value='PD'>PDI Broadsheet</option>
                                    
                                    <option value='LI'>Libre</option>    -->
                                    <?php  for($ctr=0;$ctr<count($paytype);$ctr++){?>
                                    
                                        <option  value='<?php echo $paytype[$ctr]['id'] ?>'><?php echo $paytype[$ctr]['paytype_name'] ?></option>   
                                        
                                    <?php } ?>
                                
                                </select>
                         
                         </div> 
                         
                         
                         
                 <div class="span3" style="width:100px">Exclude NS <input type="checkbox" id="is_ns" name="is_ns" value="NS" ></div>     
                            
                 <div class="span1"><button class="btn" id="generatereport" type="button">Generate</button> </div>
                 <div class="span1"><button style="width: 80px;" class="btn" id="sort" type="button">Sort</button></div>  
                 <div class="span1"><button style="width: 80px;" class="btn" id="savebtn" type="button">Save</button></div>

                 <div class="clear"></div>
                 
            </div>
              
               
            </form>  
         
    </div>
    
   <!-- <div id="billinginquirytabs" style="">
        
        <ul>
           <li><a href="#layout"  class='atabs'>Layout</a></li>                                
           <li><a href="#section" class='atabs'>Section</a></li>                                
           <li><a href="#adtype"  class='atabs'>Adtype</a></li>                                
           <li><a href="#subtype" class='atabs'>Subtype</a></li>                                
           <li><a href="#color"   class='atabs'>Color</a></li>                                
        </ul>
        
       
         
    </div>
              -->
    <div class="row-form-booking" style="background: white;border: 2px solid #DDDDDD;border-top:none;">            
    <div class="container">
        <label class="switch">
          <input type="radio" name="switch-radio" class="switch-input" id="sw_layout" value="Layout" checked="checked">
          <span class="switch-label"  data-on="Layout" data-off="Layout"></span>
         <!-- <span class="switch-handle"></span>    -->
        </label>

        <label class="switch">
          <input type="radio" name="switch-radio" class="switch-input" id="sw_section" value="Section">
          <span class="switch-label" data-on="Section" data-off="Section"></span>
          <!--  <span class="switch-handle"></span>   -->
        </label>
        
        <label class="switch">
          <input type="radio" name="switch-radio" class="switch-input" id="sw_adtype" value="Adtype">
          <span class="switch-label" data-on="Ad Type" data-off="Ad Type"></span>
        <!--  <span class="switch-handle"></span> -->
        </label>
        
         <label class="switch">
          <input type="radio" name="switch-radio" class="switch-input" id="sw_subtype" value="Subtype">
          <span class="switch-label" data-on="Sub Type" data-off="Sub Type"></span>
        <!--  <span class="switch-handle"></span> -->
        </label>
        
        <label class="switch">
          <input type="radio" name="switch-radio" class="switch-input" id="sw_color" value="Color">
          <span class="switch-label" data-on="Color" data-off="Color"></span>
          <!--  <span class="switch-handle"></span> -->
        </label>
    </div>
    </div>
   <form action="" id="myform" method="post">   
   <input type='hidden' name='inquiry_type' id='inquiry_type' >    
    
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
    <thead>
      
           
    </thead>
    <tbody id="result" class="tbody">
     
    </tbody>
    </table>  
    
    </form>
      
    <div id="jscript"></div>       
        
    </div>

    
    <div class="dialog" id="b_popup_4" style="display: none;"></div>   

    <div id="loading"></div> 

    <div id="selectdialog"></div>  
  
    <div id="sortdialog"></div>
   
<script>

$(".switch-input").die().live("click",function()
{
    $(".switch-input").prop('checked', false);
    $(this).prop('checked', true);
    if($(this).val()=='Layout')
    {
        $("#sort").show();
    }
    else
    {
        $("#sort").hide();
    }
});

$se_d =  $("#selectdialog").dialog({
   
    resizable: false,
    autoOpen:false,
    height:200,
    width:300,
    modal: true

});

$(".datepicker").datepicker({ dateFormat: 'yy-mm-dd' });

$("#billinginquirytabs").tabs();

$(".atabs").click(function(){ 

    $report_type = $(this).html();
    
    $sort = '';  

    validate($report_type,$sort);

}); 

$(".switch-input").click(function(){
   
    $report_type = $(this).val();
     
    $sort = '';  

    validate($report_type,$sort);
});

function validate(report_type,sort)
 {
            
      var validate_fields = ['#from_date', '#to_date'];  
      
      var errorcssobj = {'background': '#E1CECE', 'border' : '1px solid #FF8989'};
      
      var errorcssobj2 = {'background': '#E5E5E5', 'border' : '1px solid #E9EAEE'};
         
      var countValidate = 0;  
           
       for (x = 0; x < validate_fields.length; x++) {
           
            if($(validate_fields[x]).val() == "") {    
                                
                 if (validate_fields[x] == "#aostartdate" || validate_fields[x] == "#aoenddate"){
                     
                    $(validate_fields[x]).css({'border' : '1px solid #FF8989'});
                    
                } else {
                    
                    $(validate_fields[x]).css(errorcssobj); 
                }            
                
                countValidate += 1;
                
            } else {    
                
                if (validate_fields[x] == "#aostartdate" || validate_fields[x] == "#aoenddate"){
                    
                    $(validate_fields[x]).css({'border' : '1px solid #BBBBBB'});
                    
                } else {
                    
                    $(validate_fields[x]).css(errorcssobj2); 
                    
                }            
            }        
        }
        
        if (countValidate == 0) {
        
           generate(report_type,sort)           
    
        }        
 }
 
$("#generatereport").die().live('click',function(){
  
  $report_type = $("input[name=switch-radio]:radio:checked").val(); 

  $sort = '';   

  validate($report_type,$sort);

});
 
$sd =  $("#sortdialog").dialog({
   
    resizable: false,
    autoOpen:false,
    height:600,
    width:600,
    modal: true,
    buttons: {
            "Generate": function() {
                $report_type = $("input[name=switch-radio]:radio:checked").val();
                generatesortreport($report_type);
            },
            
            Cancel: function() {
            
             $( this ).dialog( "close" );
            
            }
    }

}); 
 
 
$("#sort").die().live('click',function(){

 /* $report_type = $('#billinginquirytabs .ui-state-active').text();*/ 
  
  $report_type = $("input[name=switch-radio]:radio:checked").val();   
   
  $report_class = $("#report_type option:selected").val();
  
  $.ajax({
      url:'<?php echo site_url('billinginquiry/createsort'); ?>',
      type:'post',
      data:{
            report_type:$report_type,
            report_class:$report_class,
            },
      success: function(response)
      {
            $sd.html($.parseJSON(response));
            $sd.dialog("open");  
      }
  });
   
});
 
 
function generate(report_type,sort)
{
   
    if(report_type == null || report_type =='')
    {
            $report_type = $("input[name=switch-radio]:radio:checked").val(); 
    }
  
    $from_date   = $("#from_date").val();

    $to_date     = $("#to_date").val();
    
   
    $report_class = $("#report_type option:selected").val();
    
    $product_type = $("#product_type option:selected").val();  
    
    $pay_type = $("#pay_type option:selected").val();  
    
    $ns = $("#is_ns").is(':checked') ? 'NS' : '';

     $.ajax({
           
           url:'<?php echo site_url('billinginquiry/generatereport') ?>',
           type:'post',
           data:{report_type:report_type,
                 from_date:$from_date,
                 to_date: $from_date,
                 report_class:$report_class,
                 product_type:$product_type,
                 pay_type:$pay_type,
                 sort:sort,
                 ns:$ns,
           },
           success:function(response)
            {       
               
                $result =  $.parseJSON(response);
                
                $newreport_type = report_type.toLowerCase();
                
                 $("#example > thead").html($result['headers']);
                 
                 $("#inquiry_type").val(report_type);
                 
                    
                 new FixedHeader( document.getElementById('example') );  
                 
                 $('#result').html($result['result']); 
                 
                 $('#jscript').html($result['jscript']); 
           
                
            }
           
       }); 

}
    
    
$(function() {
    
    $( "#loading" ).dialog({
        
        dialogClass:'transparent', 
        
        autoOpen: false,
        
        height: 65,
        
        width: 150,
        
        modal: true,
        
        resizable: false,
       
        overlay: {
       
            opacity: 0,
            
            background: "black"
        
        }
       
    });
    
    $("#loading").parents(".ui-dialog").css("border", 0); 
     
    $('#loading').css('display','');

});   

function changevalue(field,ao_ip_id,value)
{
      $(field).val(value+" : "+ao_ip_id);
} 

 $("#print").die().live('click',function()
    {  
        $report_type = $('#billinginquirytabs .ui-state-active').text();

        $from_date   = $("#from_date").val();

        $to_date     = $("#to_date").val();

        $report_class = $("#report_type option:selected").val();          
        
        if($from_date != '' && $to_date != '')
        {
            myWindow=window.open('<?php echo site_url('billinginquiry/printdailyadsummary') ?>/'+$from_date+'/'+$to_date+'/'+$report_class+'/'+$report_type,'','');
            //   myWindow.document.write("<p>This is 'myWindow'</p>");
            myWindow.focus(); 
        }
   
    });
    
    $("#excel").die().live('click',function()
    {  
        $report_type = $('#billinginquirytabs .ui-state-active').text();

        $from_date   = $("#from_date").val();

        $to_date     = $("#to_date").val();

        $report_class = $("#report_type option:selected").val();          
        
        if($from_date != '' && $to_date != '')
        {
            myWindow=window.open('<?php echo site_url('billinginquiry/excelexport') ?>/'+$from_date+'/'+$to_date+'/'+$report_class+'/'+$report_type,'','');
            //   myWindow.document.write("<p>This is 'myWindow'</p>");
            myWindow.focus(); 
        }
        

    });
    
    $("#text").die().live('click',function()
    {  
        $report_type = $('#billinginquirytabs .ui-state-active').text();

        $from_date   = $("#from_date").val();

        $to_date     = $("#to_date").val();

        $report_class = $("#report_type option:selected").val();          
        
        if($from_date != '' && $to_date != '')
        {
           myWindow=window.open('<?php echo site_url('billinginquiry/textexport') ?>/'+$from_date+'/'+$to_date+'/'+$report_class+'/'+$report_type,'','');
           //   myWindow.document.write("<p>This is 'myWindow'</p>");
           myWindow.focus(); 
        }
    
    });
  
  $("#report_type").die().live("change",function()
  {
      if($(this).val()=='C')
      {
          $("#pay_type").show();
      }
      else
      {
          $("#pay_type").hide();  
      }
  });
  
 </script>

 
