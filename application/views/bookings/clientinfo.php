<table border="2" cellpadding="2" cellspacing="2" width="100%" style="background-color: #CCC;">
    <thead>
        <th>Code</th>
        <th>Name</th>
        <th>Credit Limit</th>
        <th>Unbilled Amount</th>
        <th>Credit Status</th>
        
    </thead>
        <tbody>


        <tr>
            <td style="text-align: left;"><?php echo $info['cmf_code'] ?></td>
            <td style="text-align: left;"><?php echo $info['cmf_name'] ?></td>
            <td style="text-align: right;"><?php echo $info['creditlimit'] ?></td>
            <td style="text-align: right;"><?php echo $info['unbilledamt'] ?></td>
            <td style="text-align: center;"><?php echo $info['cfrstatus'] ?></td> 
        </tr>

    </tbody>
</table> 
    
<table border="2" cellpadding="2" cellspacing="2" width="100%" style="background-color: #CCC; font-size: 10px; margin-top: 12px;">
    <thead>
        <th>Current</th>
        <th>Age 30</th>
        <th>Age 60</th>
        <th>Age 90</th>
        <th>Age 120</th>
        <th>Age Over 120</th>
        <th>Total Age</th>
    </thead>
    
    <tbody>


        <tr>
            <td style="text-align: right;"><?php echo $info['current'] ?></td>
            <td style="text-align: right;"><?php echo $info['age30'] ?></td>
            <td style="text-align: right;"><?php echo $info['age60'] ?></td>
            <td style="text-align: right;"><?php echo $info['age90'] ?></td>
            <td style="text-align: right;"><?php echo $info['age120'] ?></td>
            <td style="text-align: right;"><?php echo $info['ageover120'] ?></td>
            <td style="text-align: right;"><?php echo $info['totalage'] ?></td>    
        </tr>

    </tbody>
</table>