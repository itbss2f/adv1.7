    <?php foreach($advertiser as $advertiser) { ?>  
        <?php $advrtsr = $advertiser->cmf_name ; ?>                
        <div class="item">
            <p><?php echo $advertiser->cmf_name ?></p>
            <div class="controls">                                    
                <a href="#" id="<?php echo $advertiser->id ?>" class="icon-forward"></a>
            </div>   
        </div>
    <?php } ?> 