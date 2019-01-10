<ul class="navigation">      
<!--    <li class="active">
        <a href="<?php #echo site_url('welcome') ?>">
            <span class="isw-grid"></span><span class="text">DASHBOARD</span>
        </a>
    </li>    -->
    <?php echo $data;?>
    <li class="openable">
        <a href="#">
            <span class="isw-zoom"></span><span class="text">OTHERS</span>                    
        </a>
        <ul>
            <li>
                <a href="#" id="tams">
                    <span class="icon-picture"></span><span class="text">TAMS</span>
                </a>
            </li>
            <li>
                <a href="typography.html">
                    <span class="icon-pencil"></span><span class="text">UNION</span>
                </a>
            </li>
            <li>
                <a href="users.html">
                    <span class="icon-user"></span><span class="text">COOP</span>
                </a>
            </li>                    
        </ul>
    </li>                                              
</ul>

<div class="dr"><span></span></div>  

<script>
$("#tams").click(function() {
    var strWindowFeatures = "menubar=yes,location=yes,resizable=yes,scrollbars=yes,status=yes";
    window.open("http://121.58.195.157/hris/login?redirect=/hris/profile", "INQUIRER TAMS", strWindowFeatures);    
});

</script>