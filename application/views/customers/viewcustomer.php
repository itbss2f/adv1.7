<?php #print_r2($data); ?>
<h3><?php echo $data['custname'] ?></h3>
<div class="block-fluid">
    <table cellpadding="0" cellspacing="0" width="100%" class="table">
        <thead>
            <tr>
                <th width="15%">Basic Info</th>
                <th width="35%"></th>                                                 
                <th width="15%">Detailed Info</th>                                                 
                <th width="35%"></th>                                                 
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><b>TIN</b></td>
                <td><?php echo $data['tin'] ?></td>
                <td><b>Acct. Exec</b></td>
                <td><?php echo $data['ae'] ?></td>                
            </tr>  
            <tr>
                <td><b>Address 1</b></td>
                <td><?php echo $data['cmf_add1'] ?></td>
                <td><b>Coll. Ast</b></td>
                <td><?php echo $data['collasst'] ?></td>                
            </tr>    
            <tr>
                <td><b>Address 2</b></td>
                <td><?php echo $data['cmf_add2'] ?></td>
                <td><b>Coll. Area</b></td>
                <td><?php echo $data['collarea_code'] ?></td>                
            </tr>    
            <tr>
                <td><b>Address 3</b></td>
                <td><?php echo $data['cmf_add3'] ?></td>
                <td><b>Category</b></td>
                <td><?php echo $data['catad_name'] ?></td>                
            </tr> 
            <tr>
                <td><b>Country</b></td>
                <td><?php echo $data['country_name'] ?></td>
                <td><b>Contact P.</b></td>
                <td><?php echo $data['cmf_contact'] ?></td>                
            </tr> 
            <tr>
                <td><b>ZIP</b></td>
                <td><?php echo $data['zip_code'] ?></td>
                <td><b>Person Sal.</b></td>
                <td><?php echo $data['cmf_position'] ?></td>                
            </tr>  
            <tr>
                <td><b>Tel 1</b></td>
                <td><?php echo $data['tel1'] ?></td>
                <td><b>Cel</b></td>
                <td><?php echo $data['cel'] ?></td>                
            </tr>  
            <tr>
                <td><b>Tel 2</b></td>
                <td><?php echo $data['tel2'] ?></td>
                <td><b>Fax</b></td>
                <td><?php echo $data['fax'] ?></td>                
            </tr>                                                                                      
        </tbody>
    </table>
</div>