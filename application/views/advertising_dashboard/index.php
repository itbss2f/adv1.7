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
        <input id="datefrom" type="text" style="width: 118px;" class="datepicker" value="<?php echo date('Y-m-01') ?>">
        <button class="btn" type="button" style="width: 118px;">Date From</button>  
        <input id="dateto" type="text" style="width: 118px;" class="datepicker" value="<?php echo date('Y-m-d') ?>">
        <button class="btn" type="button" style="width: 118px;margin-right: 50px;">Date To</button>
        <button class="btn  btn-success" style="width: 118px;" type="button" id="retrieve">Retrieve Data</button>       
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
                    <h1>Top Client</h1>
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
                    <h1>Top Agency</h1>
                    <ul class="buttons">
                        <li>
                            <a href="#" class="isw-settings"></a>
                        </li>    
                    </ul>                           
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
    
    <div class="row-fluid">
        <div class="span12">                                        
            
            <div class="span12">
                <div class="head">
                    <div class="isw-graph"></div>
                    <h1>Top Acct. Exec.</h1>
                    <div class="clear"></div>
                </div>
                 <div> 
                <div>
                <span>Moused Over: </span>
                <span id="info3">Top Acct. Exec.</span>
                </div>
                
                </div>
                <div class="block">
                    <div id="chart-ae" style="width:100%; height:350px;"></div>
                </div>
            </div>
            
        </div>

    </div>                                                        
    
    <div class="row-fluid">
        <div class="span12">                                        
            
            <div class="span12">
                <div class="head">
                    <div class="isw-graph"></div>
                    <h1>Top Industry</h1>
                    <div class="clear"></div>
                </div>
                 <div> 
                <div>
                <span>Moused Over: </span>
                <span id="info4">Top Industry</span>
                </div>
                    
                </div>
                <div class="block">
                    <div id="chart-industry" style="width:100%; height:350px;"></div>
                </div>
            </div>
            
        </div>

    </div>                                                                                                         
                                            
    <div class="dr"><span></span></div>      

</div>  

<div id="modal_aedata" title="Account Executive Data Information"></div>    


<!--<script type='text/javascript' src='<?php echo base_url() ?>assets/jplot/jquery.jqplot.min.js'></script>   
<script type='text/javascript' src='<?php echo base_url() ?>assets/jplot/jquery.jqplot.highlighter.js'></script>   
<script type='text/javascript' src='<?php echo base_url() ?>assets/jplot/jquery.jquery.jqplot.barrender.js'></script>   
       -->

<script> 
$('#modal_aedata').dialog({
   autoOpen: false, 
   closeOnEscape: false,
   draggable: true,
   width: 700,    
   height: 'auto',
   modal: true,
   resizable: false
});

$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});   

$("#retrieve").click(function() {
    var datefrom = $('#datefrom').val();
    var dateto = $('#dateto').val();
    
    $.ajax({
        url: '<?php echo site_url('advertising_dash/realTimeRetrieve') ?>',
        type: 'post',
        data: {datefrom: datefrom, dateto: dateto},
        success:function(response) {   
            
            var $response = $.parseJSON(response);

            top_client($response);  
            top_agency($response);  
            top_ae($response);  
            top_industry($response);  
        }
    });
});
 
$(function () { 
    var datefrom = $('#datefrom').val();
    var dateto = $('#dateto').val();
    (function request() {
        $.ajax({
            url: '<?php echo site_url('advertising_dash/realTimeRetrieve') ?>',
            type: 'post',
            data: {datefrom: datefrom, dateto: dateto},
            success:function(response) {   
                
                var $response = $.parseJSON(response);

                top_client($response);  
                top_agency($response);  
                top_ae($response);  
                top_industry($response);  
            }
        });
         //calling the anonymous function after 10000 milli seconds
        setTimeout(request, 180000);  //second   180000
    })(); //self Executing anonymous function
});

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
            //console.debug(pointIndex);
            //console.debug(cn[pointIndex]);
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

// CONVERT TO NUMBER FORMAT
Number.prototype.format = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};

function tooltipContentEditor(str, seriesIndex, pointIndex, plot) {
    // display series_label, x-axis_tick, y-axis value
    return plot.series[seriesIndex]["label"] + ", " + plot.data[seriesIndex][pointIndex];
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
            //console.debug(pointIndex);
            //console.debug(cn[pointIndex]);
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


function top_ae($response) {

    var s1 = new Array();    
    var c1 = new Array(); 
    var cn = new Array(); 
    var ss1 = new Array();
    
    $.each($response['topae'],function(i) {
        //alert(i);
        var data1 = $response['topae'][i];  
        //alert(data1['totalsales']);
        s1.push(parseFloat(data1['totalsales']));
        c1.push(data1['aename']);
        cn.push(data1['aenamefull']);
        ss1.push(data1['ae']);

    });  

    $('#chart-ae').empty();
    var plot1 = $.jqplot('chart-ae', [s1], {
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
    
    $('#chart-ae').bind('jqplotDataHighlight',
        function (ev, seriesIndex, pointIndex, labels) {
            //console.debug(pointIndex);
            //console.debug(cn[pointIndex]);
            var amount = s1[pointIndex];

            $('#info3').html(cn[pointIndex]+', Amount: '+amount.format(2));

        }
    );
    
    $('#chart-ae').bind('jqplotDataClick',
        function (ev, seriesIndex, pointIndex, data) {
            //console.debug(pointIndex);
            var aeid = ss1[pointIndex];
            
            //$('#modal_aedata').dialog('open');
            
            //window.open("<?php #echo site_url('sales_dash/getAEInfomation') ?>/"+aeid, "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=100, width=700, height=500");
            
            $.ajax({
                url: '<?php echo site_url('sales_dash/getAEInfomation') ?>/'+aeid,
                type: 'post',
                data: {aeid: aeid},
                success: function(response) {
                    var $response = $.parseJSON(response);     
                    
                    $('#modal_aedata').html($response['aeinfo']).dialog('open');
                }    
            });
            //alert(aeid);
          //$('#info3').html('series: '+seriesIndex+', point: '+pointIndex+', data: '+data);
        }
    ); 
         
    $('#chart-ae').bind('jqplotDataUnhighlight',
        function (ev) {
            $('#info3').html('Nothing');
            
        }
    );
}


function top_industry($response) {

    var s1 = new Array();    
    var c1 = new Array(); 
    var cn = new Array(); 
    
    $.each($response['topindustry'],function(i) {
        //alert(i);
        var data1 = $response['topindustry'][i];  
        //alert(data1['totalsales']);
        s1.push(parseFloat(data1['totalsales']));
        c1.push(data1['industry']);
        cn.push(data1['industryname']);

    });  

    $('#chart-industry').empty();
    var plot1 = $.jqplot('chart-industry', [s1], {
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
    
    $('#chart-industry').bind('jqplotDataHighlight',
        function (ev, seriesIndex, pointIndex, labels) {
            //console.debug(pointIndex);
            //console.debug(cn[pointIndex]);
            var amount = s1[pointIndex];

            $('#info4').html(cn[pointIndex]+', Amount: '+amount.format(2));

        }
    );
         
    $('#chart-industry').bind('jqplotDataUnhighlight',
        function (ev) {
            $('#info4').html('Nothing');
            
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


 