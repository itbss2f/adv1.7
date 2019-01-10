

<div style="position:absolute;left:650px;float:right;"><b>No.<span style="margin-left:15px;font-size:15px"><?php echo $result->contract_no ?></span></b></div>
<div style="position:absolute;left:650px;top:1px;font-size:18px;"><b>____________</b></div>  
<div style="position:absolute;left:650px;top:20px;float:right;"><b>Date : <?php echo $result->contract_date ?></b></div>

<div style="position:absolute;left:270px;top:40px;font-size:18px"><b>PHILIPPINE DAILY INQUIRER</b></div>
<div style="position:absolute;left:295px;top:60px;"><b>BARTER/EX-DEAL AGREEMENT</b></div>

<div style="position:absolute;left:10px;top:100px;font-size:13px;"><b>NAME OF CLIENT :</b></div> 
<?php if(strlen($result->advertiser) >= 30) { ?>
<div style="position:absolute;left:135px;top:80px;font-size:13px;"><b><?php echo substr($result->advertiser,0,30) ?></b></div> 
<div style="position:absolute;left:135px;top:100px;font-size:13px;"><b><?php echo substr($result->advertiser,30,-1) ?></b></div> 
<?php }else { ?>
<div style="position:absolute;left:135px;top:100px;font-size:13px;"><b><?php echo $result->advertiser ?></b></div>  
<?php } ?>
<div style="position:absolute;left:135px;top:100px;font-size:13px;"><b>_______________________________</b></div>   
<div style="position:absolute;left:370px;top:100px;font-size:13px;"><b>ADVERTISING AGENCY : </b></div> 
<div style="position:absolute;left:535px;top:100px;font-size:13px;;"><b><?php echo $result->advertising_agency ?></b></div> 
<div style="position:absolute;left:535px;top:100px;font-size:13px;"><b>_________________________________</b></div> 
<div style="position:absolute;left:535px;top:130px;font-size:13px;"><b>_________________________________</b></div> 

<div style="position:absolute;left:58px;top:130px;font-size:13px;"><b>ADDRESS :</b></div>  
<div style="position:absolute;left:135px;top:130px;font-size:13px;"><b><?php echo strtoupper($result->cmf_add1) ?></b></div> 
<div style="position:absolute;left:135px;top:130px;font-size:13px;"><b>_______________________________</b></div>   
<div style="position:absolute;left:135px;top:155px;font-size:13px;"><b><?php echo strtoupper($result->cmf_add2) ?></b></div>  
<div style="position:absolute;left:135px;top:155px;font-size:13px;"><b>_______________________________</b></div>  
<div style="position:absolute;left:135px;top:180px;font-size:13px;"><b><?php echo strtoupper($result->cmf_add3) ?></b></div> 
<div style="position:absolute;left:135px;top:180px;font-size:13px;"><b>_______________________________</b></div>  

<div style="position:absolute;left:461px;top:155px;font-size:13px;"><b>AMOUNT : </b></div> 
<div style="position:absolute;left:535px;top:155px;font-size:13px;"><b>PhP&nbsp;<?php echo number_format($result->amount,2,'.',','); ?></b></div> 
<div style="position:absolute;left:535px;top:155px;font-size:13px;"><b>_________________________________</b></div> 

<div style="position:absolute;left:0px;top:205px;font-size:13px;"><b>CONTACT PERSON :</b></div> 
<div style="position:absolute;left:135px;top:205px;font-size:13px;"><b><?php echo $result->contact_person ?></b></div> 
<div style="position:absolute;left:135px;top:205px;font-size:13px;"><b>_______________________________</b></div> 

<div style="position:absolute;left:439px;top:180px;font-size:13px;"><b>TELEPHONE : </b></div> 
<div style="position:absolute;left:535px;top:180px;font-size:13px;"><b><?php echo $result->telephone ?></b></div> 
<div style="position:absolute;left:534px;top:180px;font-size:13px;"><b>_________________________________</b></div> 



<div style="position:absolute;left:376px;top:205px;font-size:13px;"><b>ACCOUNT EXECUTIVE :</b></div>
<div style="position:absolute;left:534px;top:205px;font-size:13px;"><b><?php echo $result->acct_exec ?></b></div>  
<div style="position:absolute;left:534px;top:205px;font-size:13px;"><b>_________________________________</b></div> 

<div style="position:absolute;left:41px;top:230px;font-size:13px;"><b>CASH RATIO :</b></div> 
<div style="position:absolute;left:135px;top:230px;font-size:13px;"><b><?php echo $result->cash_ratio ?>%</b></div> 
<div style="position:absolute;left:135px;top:230px;font-size:13px;"><b>_______________________________</b></div>       



<div style="position:absolute;left:418px;top:230px;font-size:13px;"><b>BARTER RATIO :</b></div> 
<div style="position:absolute;left:535px;top:230px;font-size:13px;"><b><?php echo $result->barter_ratio ?>%</b></div> 
<div style="position:absolute;left:532px;top:230px;font-size:13px;"><b>_________________________________</b></div>      


<div style="position:absolute;left:180px;top:255px;font-size:13px;"><b>UNCONSUMED BARTER AMOUNT :</b></div> 
<div style="position:absolute;left:410px;top:255px;font-size:13px;"><b>__________________________________</b></div> 


<div style="position:absolute;left:40px;top:280;font-size:13px;"><b>BARTER REQUESTED BY:</b></div> 
<div style="position:absolute;left:210px;top:280;font-size:13px;"><b>(<?Php if($result->barter_request == '1'){ echo " X "; }  ?>) CLIENT &nbsp;( <?Php if($result->barter_request == '2'){ echo " X "; }  ?> ) PDI </b></div>  

<div style="position:absolute;left:370px;top:280;font-size:13px;"><b>TYPE :</b></div> 
<div style="position:absolute;left:420px;top:280;font-size:13px;"><b>( X ) BILLABLE ( ) NON-BILLABLE </b></div>  

<div style="position:absolute;left:5px;top:310px;font-size:13px;"><b>DOCUMENTS REQUIRED :</b></div> 
<div style="position:absolute;left:180px;top:310px;font-size:13px;"><b><?php echo $result->doc_name ?></b></div>  
<!--<div style="position:absolute;left:230px;top:380px;font-size:12px;"><b>( ) LETTER OF CONTRACT WITH CLIENT</b></div>  
<div style="position:absolute;left:230px;top:405px;font-size:12px;"><b>( ) LIST OF ITEMS AND PRICE LIST</b></div> --> 


<div style="position:absolute;left:5px;top:340px;font-size:13px;"><b>REMARKS :</b></div> 
<?php $itop = '340;' ?>
<?php $rem = explode("*",$result->remarks); ?>
<?php for($i=0;$i<count($rem);$i++) { ?>
<?php if(!empty($rem[$i])) { ?>
       <div style="position:absolute;left:80px;top:<?php echo $itop."px" ?>;font-size:13px;"><b> <?php echo "*".$rem[$i] ?></b></div>   
<?php  $itop += 20; } ?>
<?php } ?>

 

<!--<div style="position:absolute;left:83px;top:430px;font-size:12px;"><b>ACCOUNT EXECUTIVE :</b></div>
<div style="position:absolute;left:230px;top:430px;font-size:12px;"><b>__________________________________</b></div>  

<div style="position:absolute;left:155px;top:455px;font-size:12px;"><b>REMARKS :</b></div> 
<div style="position:absolute;left:230px;top:455px;font-size:12px;"><b><?php echo $result->remarks ?></b></div>  --> 

<fieldset style="position:absolute;left:0px;top:430px;font-size:12px;width:100%;height:280px;">
  
   <div style="position:absolute;left:40px;top:20px;font-size:13px;"><b>CONDITIONS FOR BARTER/EXDEAL PROPOSAL</b></div> 
   
   <?php $top=30; ?>
   <?php $top2=40; ?>
   <?php $top3=40; ?>

   <?php foreach($condition_list as $con ) : ?>
    
   <?php if($con->condition_type == '1') { ?>
    <div style="position:absolute;left:40px;top:40px;font-size:12px;">FOR COMMODITIES :</div>  
    <div style="position:absolute;left:195px;top:<?php echo $top."px" ?>;font-size:12px;"><b>
    <p>* <?php  echo $con->barter_condition ?></p>
    </b></div>  
   <?php  }?>
   <?php if($con->condition_type == '2') { ?>              
    <div style="position:absolute;left:40px;top:80px;font-size:12px;">FOR GIFT CERTIFICATES :</div> 
        <div style="position:absolute;left:195px;top:<?php echo $top2."px"; ?>;font-size:12px;"><b>
        <p>* <?php  echo $con->barter_condition ?></p></b>      
        </div> 
    <?php } ?>
    
       <?php if($con->condition_type == '3') { ?>              
    <div style="position:absolute;left:40px;top:<?php echo $top3."px"; ?>;font-size:12px;">FOR HOTELS/RESORTS :</div> 
        <div style="position:absolute;left:195px;top:<?php echo $top3."px"; ?>;font-size:12px;"><b>
        <p>* <?php  echo $con->barter_condition ?></p></b>      
        </div> 
    <?php } ?>

  <?php $top += 15; ?>
 <?php $top2 += 15; ?>
 <?php $top3 += 15; ?>
  <?php endforeach; ?>

  <div style="position:absolute;left:40px;top:<?php echo ($top3+20)."px"; ?>;font-size:11px;"><b>CONFORME :</b></div>
     
  <div style="position:absolute;left:190px;top:<?php echo ($top3+20)."px"; ?>;font-size:11px;"><b>Client ( )</b></div>   
  
  <div style="position:absolute;left:250px;top:<?php echo ($top3+20)."px"; ?>;font-size:11px;"><b>Agency ( )</b></div> 
  
  <div style="position:absolute;left:40px;top:<?php echo ($top3+50)."px"; ?>;font-size:11px;"><b>________________________________</b></div>  
  
  <div style="position:absolute;left:55px;top:<?php echo ($top3+65)."px"; ?>;font-size:11px;"><b>(Signature over Printed Name)</b></div> 
  
  <div style="position:absolute;left:400px;top:<?php echo ($top3+50)."px"; ?>;font-size:11px;"><b>____________________</b></div>  
  
  <div style="position:absolute;left:450px;top:<?php echo ($top3+65)."px"; ?>;font-size:11px;"><b>(Date)</b></div> 
  
  <div style="position:absolute;left:260px;top:<?php echo ($top3+100)."px"; ?>;font-size:11px;"><b>Subject for Approval</b></div>         
  
</fieldset>

<div style="position:absolute;left:50px;top:730px;font-size:11px;"><b>Recommended by:</b></div>
<div style="position:absolute;left:50px;top:780px;font-size:13px;"><b><?php echo $result->recommended_by ?> </b></div>  
<div style="position:absolute;left:50px;top:800px;font-size:11px;"><b><?php echo $result->rec_position ?></b></div>  
                                                                                                   
<div style="position:absolute;left:575px;top:730px;font-size:11px;"><b>Approved by:</b></div>
<div style="position:absolute;left:565px;top:780px;font-size:13px;"><b><?php echo $result->noted_by ?>  </b></div>  
<div style="position:absolute;left:565px;top:800px;font-size:11px;"><b><?php echo $result->not_position ?></b></div>   


<div style="position:absolute;left:50px;top:880px;font-size:13px;"><b><?php echo $result->recommended_by2 ?>  </b></div>  
<div style="position:absolute;left:50px;top:900px;font-size:11px;"><b><?php echo $result->rec_position2 ?></b></div>  

<div style="position:absolute;left:565px;top:880px;font-size:13px;"><b><?php echo $result->noted_by2 ?></b></div>  
<div style="position:absolute;left:565px;top:900px;font-size:11px;"><b><?php echo $result->not_position2 ?></b></div>  

<!--<div style="position:absolute;left:345px;top:930px;font-size:11px;"><b>Approved by:</b></div>
<div style="position:absolute;left:50px;top:965px;font-size:13px;"><b><?php echo $result->approved_by ?></b></div>  
<div style="position:absolute;left:50px;top:985px;font-size:11px;"><b><?php echo $result->app_position ?></b></div>  

<div style="position:absolute;left:565px;top:965px;font-size:13px;"><b><?php echo $result->approved_by2 ?></b></div>  
<div style="position:absolute;left:565px;top:985px;font-size:11px;"><b><?php echo $result->app_position2 ?></b></div>                                                                                                
                                                                                                                        -->
