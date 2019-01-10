     
<?php foreach($result as $result) : ?>

    <div class="item">
        <p><?php echo $result->cmf_name ?></p>
        <div class="controls">   
        <?php if ($canDELETE) : ?>                                 
            <a href="#" id="<?php echo $result->id ?>" class="icon-trash"></a>
        <?php endif; ?>
        </div>   
    </div>

<?php endforeach; ?>