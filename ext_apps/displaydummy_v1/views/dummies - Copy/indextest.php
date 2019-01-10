<!DOCTYPE HTML>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <link href="<?php echo base_url() ?>plugins/jquery-ui-1.8.13.custom/css/custom-theme/jquery-ui-1.8.17.custom.css" media="screen" rel="stylesheet" type="text/css" />	
	<script src="<?php echo base_url() ?>plugins/jquidragcollide/jquery-1.5.1.js"></script>
	<script src="<?php echo base_url() ?>plugins/jquidragcollide/ui/jquery.ui.core.js"></script>
	<script src="<?php echo base_url() ?>plugins/jquidragcollide/ui/jquery.ui.widget.js"></script>
	<script src="<?php echo base_url() ?>plugins/jquidragcollide/ui/jquery.ui.mouse.js"></script>
	<script src="<?php echo base_url() ?>plugins/jquidragcollide/ui/jquery.ui.draggable.js"></script>
	<script src="<?php echo base_url() ?>plugins/jquidragcollide/jquery-collision-1.0.1.js"></script>
	<script src="<?php echo base_url() ?>plugins/jquidragcollide/jquery-ui-draggable-collision-1.0.1.js"></script>
    <script src="<?php echo base_url() ?>plugins/jquery-ui-1.8.13.custom/js/jquery-ui-1.8.13.custom.min.js" type="text/javascript"></script>	
    <style type="text/css">      
	            .pageArea {
	                background-color: Blue;
	                opacity: 0.5;
	                width: 310px;
	                height: 530px;	                
                    float:left;
                    border: 1px solid ;
                    margin: 10px 10px auto;
	            }
	             
	            .flowArea {
	                background-color: gray;
	                opacity: 0.5;
                    width: 300px;
	                height: 400px;
	                padding-top:10px;
	                padding-bottom:10px;
	                padding-right:10px;
	                padding-left:10px;
                    float:left;
                    border: 1px solid ;
	            }              
                
                .dragableBox{
                    width:80px;
                    height:20px;
                    border:1px solid #000;
                    background-color:#FFF;
                    margin-bottom:5px;
                    padding:10px;
                    font-weight:bold;
                    text-align:center;
                }
	             	            
	        </style>
	</head>
	<body>	
            <div class="pageArea" id="page1">	         
	        </div>
            <div class="pageArea" id="page2">	         
	        </div>
            <div class="pageArea" id="page3">	         
	        </div>
	         
	        <div class="flowArea">	           
                <div class="dragableBox" id="a1">Ads 1</div>
                <div class="dragableBox" id="a2">Ads 2</div>
                <div class="dragableBox" id="a3">Ads 3</div>
                <div class="dragableBox" id="a4">Ads 4</div>
                <div class="dragableBox" id="a5">Ads 5</div>
            </div>
	         
        
        <!-- Javascriopt -->
        <script type="text/javascript">                	
            var ad = "";
            var page = "";
        
            $(function() {
                $(".dragableBox").draggable({                    
                    opacity: 0.40,
                    //hoverClass: 'hoveringover',
                    //helper: 'clone',
                    //refreshPositions: true                             
                    cursor: "crosshair",                    
                    //revert: "invalid",
                    /*revert:  function(dropped) {
                                  
                                  var dropped = dropped && dropped[0].id == "droppable";
                                  alert(dropped);
                                   // if(!dropped) alert("I'm reverting!");
                                   return !dropped;
                                } ,*/
                    stop: function(event, ui) {                         
                                                                    
                        ad =  ($(this).attr("id")); 
                           
                        /* Start Algorithm */
                        //$("#"+ad).draggable({
                        //  containment: "#"+page ,                           
                        //});
                        /* End Algorithm */
                        
                    },
                    revert: function (event, ui) {
                    //overwrite original position
                    $(this).data("draggable").originalPosition = {
                        top: 0,
                        left: 0
                    };
                    //return boolean
                    return !event;
                    }
                });
                $( ".pageArea" ).droppable({
                    accept: ".dragableBox",
                    //activeClass: "ui-state-hover",
                    //hoverClass: "ui-state-active",
                    tolerance: "fit", // to fit in the page div
                    drop: function( event, ui ) {                                                                
                        //alert(pagenum);
                        page = $(this).attr("id")                                                                     
                        //$( this )
                            //.addClass( "ui-state-highlight" )
                           // .find( "p" )
                            //    .html( "Dropped!" );
                    }
                });
            });
            
        </script>
        
	</body>
</html>

