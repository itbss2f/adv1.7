<link class="include" rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/jplot/jquery.jqplot.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/jplot/examples/examples.min.css" />
<link type="text/css" rel="stylesheet" href="<?php echo base_url() ?>assets/jplot/examples/syntaxhighlighter/styles/shCoreDefault.min.css" />
<link type="text/css" rel="stylesheet" href="<?php echo base_url() ?>assets/jplot/examples/syntaxhighlighter/styles/shThemejqPlot.min.css" />

<style>
.jqplot-axis { font-size:9pt}
.jqplot-point-label { font-size:7pt}
</style>

<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>


<div class="workplace">          


    <div class="row-fluid">    
        <div class="input-append"> 
        <h3 id="note">Collectible as of 02:35 am <?php echo date('Y-m-d') ?></h3>
        <button class="btn  btn-success" style="width: 250px;" type="button" id="retrieve">Retrieve Up to date</button>       
        </div>
        <div class="input-append">
        
        </div>
        
        
        <div class="clear"></div>  
    </div>   

    <div class="row-fluid">
        <div class="span12">                                        
            
            <div class="span12">
                <div class="head">
                    <div class="isw-graph"></div>
                    <h1>Top Client Collectible</h1>
                    <div class="clear"></div>
                </div>
                <div>
                
                <div>
                <span>Mouse Hover: </span>
                <span id="info1">Top Client</span>
                </div>
                    
                </div>
                <div class="block">
                
                    <div id="chart-client" style="width:100%; height:350px;"></div>
                </div>
            </div>
            
        </div>

    </div>            
    
    <div class="row-fluid">
        <div class="span12">                                        
            
            <div class="span12">
                <div class="head">
                    <div class="isw-graph"></div>
                    <h1>Top Agency Collectible</h1>
                    
                    <div class="clear"></div>
                </div>
                <div>
                
                <div>
                <span>Mouse Hover: </span>
                <span id="info2">Top Agency</span>
                </div>
                    
                </div>
                <div class="block">
                    <div id="chart-agency" style="width:100%; height:350px;"></div>
                </div>
            </div>
            
        </div>

    </div>                                                                                                                                                                                                               

    <div class="dr"><span></span></div>     

</div>     

<script>

$('#retrieve').click(function(){
    $.ajax({
        url: '<?php echo site_url('collection_dash/realTimeRetrieve') ?>',
        type: 'post',
        data: {},
        success:function(response) {   
            
            var $response = $.parseJSON(response);
            alert('Done Retrieving Data!');
            $('#note').hide();
            top_client($response);  
            top_agency($response);  
            //top_industry($response);  
        }
    });
});
 
$(function () {
    
    $.ajax({
        url: '<?php echo site_url('collection_dash/realTimeRetrieveDefault') ?>',
        type: 'post',
        data: {},
        success:function(response) {   
            
            var $response = $.parseJSON(response);

            top_client($response);  
            top_agency($response);  
            //top_industry($response);  
        }
    });
});
// CONVERT TO NUMBER FORMAT
Number.prototype.format = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};

function tooltipContentEditor(str, seriesIndex, pointIndex, plot) {
    // display series_label, x-axis_tick, y-axis value
    return plot.series[seriesIndex]["label"] + ", " + plot.data[seriesIndex][pointIndex];
}

function top_client($response) {

    var s1 = new Array();      
    var c1 = new Array(); 
    var cn = new Array(); 
    
    $.each($response['topclient'],function(i) {
        var data1 = $response['topclient'][i];  

        s1.push(parseFloat(data1['totalsales']));
        c1.push(data1['clientcode']);
        cn.push(data1['clientname']);

    }); 

    $('#chart-client').empty();
    var plot1 = $.jqplot('chart-client', [s1], {
        // The "seriesDefaults" option is an options object that will
        // be applied to all series in the chart.
        animate: !$.jqplot.use_excanvas,
        seriesDefaults:{
            renderer:$.jqplot.BarRenderer,
            rendererOptions: {fillToZero: true, varyBarColor: true},  
            //pointLabels: { show: true },
        },
        
        axes: {
            // Use a category axis on the x axis and use our custom ticks.
            xaxis: {
                renderer: $.jqplot.CategoryAxisRenderer,
                ticks: c1,
            
            },
            // Pad the y axis just a little so bars can get close to, but
            // not touch, the grid boundaries.  1.2 is the default padding.
            yaxis: {
                pad: 1.05,
                tickOptions:  {formatString: "P %'.2f"} 
   
            }
        },
        
        highlighter:{
            show:true,
            tooltipContentEditor:tooltipContentEditor
        }

    }); 
    
    $('#chart-client').bind('jqplotDataHighlight',
        function (ev, seriesIndex, pointIndex, labels) {

            var amount = s1[pointIndex];

            $('#info1').html(cn[pointIndex]+', Amount: '+amount.format(2));

        }
    );
         
    $('#chart-client').bind('jqplotDataUnhighlight',
        function (ev) {
            $('#info1').html('Nothing');
            
        }
    );


}

function top_agency($response) {

    var s1 = new Array();      
    var c1 = new Array(); 
    var cn = new Array();  
    
    $.each($response['topagency'],function(i) {
        //alert(i);
        var data1 = $response['topagency'][i];  
        //alert(data1['totalsales']);
        s1.push(parseFloat(data1['totalsales']));
        c1.push(data1['agencycode']);
        cn.push(data1['agencyname']);

    });  

    $('#chart-agency').empty();
    var plot1 = $.jqplot('chart-agency', [s1], {
        // The "seriesDefaults" option is an options object that will
        // be applied to all series in the chart.
        animate: !$.jqplot.use_excanvas,
        seriesDefaults:{
            renderer:$.jqplot.BarRenderer,
            rendererOptions: {fillToZero: true, varyBarColor: true},
            //pointLabels: { show: true }
        },
         
        axes: {
            // Use a category axis on the x axis and use our custom ticks.
            xaxis: {
                renderer: $.jqplot.CategoryAxisRenderer,
                ticks: c1,
            },
            // Pad the y axis just a little so bars can get close to, but
            // not touch, the grid boundaries.  1.2 is the default padding.
            yaxis: {
                pad: 1.05,
                tickOptions:  {formatString: "P %'.2f"}     
                  
            }
        } ,
        
        highlighter:{
            show:true,
            tooltipContentEditor:tooltipContentEditor
        }
    }); 
    
    $('#chart-agency').bind('jqplotDataHighlight',
        function (ev, seriesIndex, pointIndex, labels) {

            var amount = s1[pointIndex];

            $('#info2').html(cn[pointIndex]+', Amount: '+amount.format(2));

        }
    );
         
    $('#chart-agency').bind('jqplotDataUnhighlight',
        function (ev) {
            $('#info2').html('Nothing');
            
        }
    );
}


           
</script>

<!-- Don't touch this! -->

<script class="include" type="text/javascript" src="<?php echo base_url() ?>assets/jplot/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/jplot/examples/syntaxhighlighter/scripts/shCore.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/jplot/examples/syntaxhighlighter/scripts/shBrushJScript.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/jplot/examples/syntaxhighlighter/scripts/shBrushXml.min.js"></script>

<!-- End Don't touch this! -->

<!-- Additional plugins go here -->

<script class="include" language="javascript" type="text/javascript" src="<?php echo base_url() ?>assets/jplot/plugins/jqplot.barRenderer.min.js"></script>
<script class="include" language="javascript" type="text/javascript" src="<?php echo base_url() ?>assets/jplot/plugins/jqplot.categoryAxisRenderer.min.js"></script>
<script class="include" language="javascript" type="text/javascript" src="<?php echo base_url() ?>assets/jplot/plugins/jqplot.pointLabels.min.js"></script>

<!-- End additional plugins --> 


 