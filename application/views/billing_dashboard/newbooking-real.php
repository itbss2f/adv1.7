<div class="dSpace">
    <h3>Total Booking</h3>
    <span class="number"><?php echo$totalbooking['totalbook'] ?></span>                    
    <span><?php echo $totalbookingenu[0]['totalbook'] ?> <b>Display</b></span>
    <span><?php echo $totalbookingenu[1]['totalbook'] ?> <b>Classified</b></span>
    <span><?php echo $totalbookingenu[2]['totalbook'] ?> <b>Superced</b></span>
    <span><?php echo $totalbookingenu[3]['totalbook'] ?> <b>Killed</b></span>
</div>
<div class="rSpace">
    <h3>Today</h3>
    <span class="mChartBar" sparkType="bar" sparkBarColor="white"><!--240,234,150,290,310,240,210,400,320,198,250,222,111,240,221,340,250,190--></span>
    <span>&nbsp;</span>
    <span><?php echo $totalbookingenutoday[0]['totalbook'] ?> <b>Display Book</b></span>
    <span><?php echo $totalbookingenutoday[1]['totalbook'] ?> <b>Classified Book </b></span>
    <span><?php echo $totalbookingenutoday[2]['totalbook'] ?> <b>Superced Book </b></span>
    <span><?php echo $totalbookingenutoday[3]['totalbook'] ?> <b>Killed Book </b></span>
</div>