 <?php $prod_name = ""; ?>
 
 <?php $grand_total = 0; ?>
 
 <?php $pr_code = ""; ?>
 
 <?php $prod_name2 = ""; ?>
 
<?php  for($ctr1=0;$ctr1<count($result);$ctr1++) { ?>



<?php if($result[$ctr1]['prod_name'] !=  $prod_name) { ?>

<page orientation='L' format='LEGAL' backtop='18mm' backbottom='7mm' backleft='0mm' backright='10mm'> 

            <page_header>
                      
                        <div style='margin-bottom: 8px;font-size:20px' id='company_name'><b>PHILIPPINE DAILY INQUIRER</b></div>     
                      
                        <div style='margin-bottom: 8px;' id='report_name'><b>AD LIST REPORT</b></div>  
                         
                        <div style='margin-bottom: 8px;' id='report_name'><b>From : <?php echo $from_date ?> To : <?php echo $to_date ?></b></div>  
                     
                        <div style='margin-bottom: 8px;position:relative;top:-22px;left:300px' id='report_name'><b>Edition : <?php echo $result[$ctr1]['prod_name'] ; ?> </b></div>  
                      
                        <div style='margin-bottom: 8px;position:relative;top:-22px;left:550px' id='report_name'><b>Total Ads : <?php echo $result[$ctr1]['count_prod']; ?></b></div>  
                        
                        <div style='text-align:right; position:relative;top:-30px;right:0px' id='pages'><b> Pages : [[page_cu]] of [[page_nb]]</b> </div> 
                        
                        <div style='text-align:right; position:relative;top:-50px;right:10px;' id='dates'><b>Rundate : <?php echo DATE('d-m-Y h:i:s'); ?></b> </div> 
      
            </page_header> 
            
            <table  cellpadding="0" cellspacing="0" style="">
            
                <thead>
                    
                    <tr style="white-space: nowrap;margin-top:100px">
                    
                        <th style="width:80px;">Issue Date</th>
                        
                        <th style="width:80px;">Class</th>
                        
                        <th style="width:70px;">AE</th>
                        
                        <th style="width:80px;">RN No.</th>
                        
                        <th style="width:150px;">Advertiser</th>
                        
                        <th style="width:80px;">Status</th>
                        
                        <th style="width:80px;">Size</th>
                        
                        <th style="width:80px;">CCM</th>
                        
                        <th style="width:80px;">Start Date</th>
                        
                        <th style="width:80px;">Stop Date</th>
                        
                        <th style="width:80px;">Branch</th>
                        
                        <th style="width:80px;">EPS File</th>
                        
                        <th style="width:80px;">Sort</th>
                        
                        <th style="width:80px;">Paginated</th>
                        
                        <th style="width:80px;">Color</th>
                    
                    </tr>
                
                </thead>
            
            
                <tbody>
                
                    <?php $rate_code = ""; ?>
                    
                    <?php $sub_ccm = 0; ?>
                    
                    <?php $total_ccm = 0; ?>
                
                    <?php for($ctr=0;$ctr<count($result);$ctr++) { ?>
                    
                    <?php $sub_ccm += $result[$ctr]['ccm']; ?>  
                    <?php $total_ccm += $result[$ctr]['ccm']; ?>  
                    <?php $grand_total += $result[$ctr]['ccm']; ?>  
                    <?php $pr_code = $result[$ctr]['prod_code']; ?>  
                    <?php $prod_name2 = $result[$ctr]['prod_name']; ?>  
                    
                            <?php if($rate_code != $result[$ctr]['ao_adtyperate_code'] AND $result[$ctr]['prod_code'] == $result[$ctr1]['prod_code']) { ?>
                            
                                <tr>
                                    
                                    <td><b>Rate Code :</b></td>
                                    
                                    <td><b><?php echo  $result[$ctr]['ao_adtyperate_code']  ?></b></td>
                                    
                                    <td> </td>
                                    <td><b>No. of Ads</b></td>
                                    <td><?php echo $result[$ctr]['count_rate_code'] ?></td>
                                
                                </tr>
                            
                            <?php } ?>
                            
                           <?php if($result[$ctr]['prod_code'] == $result[$ctr1]['prod_code'] ) { ?>
                        
                            <tr style="white-space: nowrap;">
                            
                                <td style="text-align: center;"><?php echo $result[$ctr]['issue_date'] ?></td>
                                
                                <td style="text-align: left;"><?php echo $result[$ctr]['class_name'] ?></td>
                                
                                <td style="text-align: center;"><?php echo $result[$ctr]['empprofile_code'] ?></td>
                                
                                <td style="text-align: center;"><?php echo $result[$ctr]['rn_number'] ?></td>
                                
                                <td style="text-align: left;"><?php echo $result[$ctr]['advertiser'] ?></td>
                                
                                <td style="text-align: center;"><?php echo $result[$ctr]['status'] ?></td>
                                
                                <td style="text-align: center;"><?php echo $result[$ctr]['size'] ?></td>
                                
                                <td style="text-align: right;"><?php echo $result[$ctr]['ccm'] ?></td>
                                
                                <td style="text-align: center;"><?php echo $result[$ctr]['start_date'] ?></td>
                                
                                <td style="text-align: center;"><?php echo $result[$ctr]['end_date'] ?></td>
                                
                                <td style="text-align: center;"><?php echo $result[$ctr]['branch_code'] ?></td>
                                
                                <td style="text-align: center;"><?php echo $result[$ctr]['ao_eps'] ?></td>
                                
                                <td style="text-align: left;"><?php echo $result[$ctr]['part_recods'] ?></td>
                                
                                <td style="text-align: center;"><?php echo $result[$ctr]['paginated_date'] ?></td>
                                
                                <td style="text-align: center;"><?php echo $result[$ctr]['color_code'] ?></td>
                            
                            </tr>
                            
                            <?php } ?>
                                                        
                              <?php if(!empty($result[$ctr+1]['ao_adtyperate_code']) and $result[$ctr+1]['ao_adtyperate_code'] != $result[$ctr]['ao_adtyperate_code'] AND $result[$ctr]['prod_code'] == $result[$ctr1]['prod_code']) { ?>
                            
                                <tr>
                                    
                                    <td colspan="6" style="text-align: right;"><b>Total</b></td>
                                    <td style="text-align: center;"><b><?php echo $result[$ctr]['ao_adtyperate_code'] ?></b></td>
                                    <td style="text-align: right;padding-top:10px;padding-bottom:10px;border-top:1px solid #000;border-bottom:1px solid #000;"><?php echo number_format($sub_ccm,2,'.',',') ?></td>
                                
                                </tr>
                                
                                <?php $sub_ccm = 0; ?> 
                            
                            <?php } ?>

                          
                           <?php $rate_code = $result[$ctr]['ao_adtyperate_code'] ?>   
                           
                           <?php if(!empty($result[$ctr+1]['prod_name'])  and $result[$ctr+1]['prod_name'] != $result[$ctr]['prod_name'] AND $prod_name2 == $result[$ctr1]['prod_name']) { ?>
                      
                          <tr>
                                    
                                    <td colspan="6" style="text-align: right;"><b>Total</b></td>
                                    <td style="text-align: center;"><b><?php echo $prod_name2 ?></b></td>
                                    <td style="text-align: right;padding-top:10px;padding-bottom:10px;border-top:1px solid #000;border-bottom:1px solid #000;"><?php echo number_format($total_ccm,2,'.',',') ?></td>
                                
                         </tr>
                         
                   <?php } ?>
                    
                    <?php } ?> 
                    
                    <?php if($pr_code == $result[$ctr1]['prod_code']) { ?>
                      
                          <tr>
                                    
                                    <td colspan="6" style="text-align: right;"><b>Total</b></td>
                                    <td style="text-align: center;"><b><?php echo $rate_code ?></b></td>
                                    <td style="text-align: right;padding-top:10px;padding-bottom:10px;border-top:1px solid #000;border-bottom:1px solid #000;"><?php echo number_format($sub_ccm,2,'.',',') ?></td>
                                
                         </tr>
                         
                   <?php } ?>
                   
                  <?php if($prod_name2 == $result[$ctr1]['prod_name']) { ?>
                      
                          <tr>
                                    
                                    <td colspan="6" style="text-align: right;"><b>Total</b></td>
                                    <td style="text-align: center;"><b><?php echo $prod_name2 ?></b></td>
                                    <td style="text-align: right;padding-top:10px;padding-bottom:10px;border-top:1px solid #000;border-bottom:1px solid #000;"><?php echo number_format($total_ccm,2,'.',',') ?></td>
                                
                         </tr>
                         
                   <?php } ?>
                   
                     <?php if($prod_name2 == $result[$ctr1]['prod_name']) { ?>
                      
                          <tr>
                                    
                                    <td colspan="7" style="text-align: right;"><b>Grand Total</b></td>
                                    <td style="text-align: right;padding-top:10px;padding-bottom:10px;border-top:1px solid #000;border-bottom:1px solid #000;"><?php echo number_format($grand_total,2,'.',',') ?></td>
                                
                         </tr>
                         
                   <?php } ?>
                   
                </tbody>
            
            </table>
              
</page>

<?php   } ?>

<?php $prod_name = $result[$ctr1]['prod_name'] ?>  

<?php   } ?>



 