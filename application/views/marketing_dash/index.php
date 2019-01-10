<link class="include" rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/jplot/jquery.jqplot.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/jplot/examples/examples.min.css" />
<link type="text/css" rel="stylesheet" href="<?php echo base_url() ?>assets/jplot/examples/syntaxhighlighter/styles/shCoreDefault.min.css" />
<link type="text/css" rel="stylesheet" href="<?php echo base_url() ?>assets/jplot/examples/syntaxhighlighter/styles/shThemejqPlot.min.css" />

<style>
.jqplot-axis { font-size:9pt}
.jqplot-point-label { font-size:7pt}
.jqplot-target {
    margin: 30px;
}
#customTooltipDiv {
    position: absolute; 
    display: nonez; 
    color: #333333;
    font-size: 0.8em;
    border: 1px solid #666666; 
    background-color: rgba(160, 160, 160, 0.2);
    padding: 2px;
}
</style>

<div class="breadLine">    
    <?php echo $breadcrumb; ?>                        
</div>

<div class="workplace">     

    <div class="row-fluid">
        <div class="span12">                                        
            
            <div class="span6">
                <div class="head">
                    <div class="isw-graph"></div>
                    <h1>Top Industry (This Month) [<?php echo date('Y-m-01').' - '.date('Y-m-d') ?>]</h1>
                    <div class="clear"></div>
                </div>
                <div>
                    <span>Mouse Hover: </span>
                    <span id="info1">Top Industry (This Month)</span>
                </div>
                <div class="block">
                    <div id="chart-topindustrymonth" style="width:100%; height:350px;"></div>
                </div>
                <div class="clear"></div>  
            </div>
            
            <div class="span6">
                <div class="head">
                    <div class="isw-graph"></div>
                    <h1>Top Industry (This Year)  [<?php echo date('Y-m-01').' - '.date('Y-m-d') ?>]</h1>
                    <div class="clear"></div>
                </div>
                <div>
                    <span>Mouse Hover: </span>
                    <span id="info2">Top Industry (This Year)</span>
                </div>
                <div class="block">
                    <div id="chart-topindustryyear" style="width:100%; height:350px;"></div>
                </div>
                <div class="clear"></div>  
            </div>
            
        </div>

    </div> 
    
    <div class="row-fluid">    
        <div class="span12">  
        
            <div class="span12">
                <div class="head">
                    <div class="isw-graph"></div>
                    <h1>Top Industry Year End Trend  [<?php echo date('Y-01-01').' - '.date('Y-m-d') ?>]</h1>
                    <div class="clear"></div>
                </div>
                <!--<div>
                    <span>Mouse Hover: </span>
                    <span id="info3">Top Industry (Year End Trend)</span>
                </div>-->
                <div class="block">
                    <div id="chart-topindustryyeartrend" style="width:100%; height:350px;"></div>        
                </div>
                <div>
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion1" id="#accordion1" href="#accordion-topindustryyeartrend">
                       View Details
                    </a>
                </div>
                <div id="accordion-topindustryyeartrend" class="accordion-body collapse">
                    <table class="table table-fixed" style="margin-bottom:0px; border: black;">
                       <thead class="widget-title" style="background-color: #CCCCCC;">
                           <th>Code</th>
                           <th>Name</th>
                           <th>Jan</th>
                           <th>Feb</th>
                           <th>Mar</th>
                           <th>Apr</th>
                           <th>May</th>
                           <th>Jun</th>
                           <th>Jul</th>
                           <th>Aug</th>
                           <th>Sep</th>
                           <th>Oct</th>
                           <th>Nov</th>
                           <th>Dec</th>
                           <th>Total</th>
                        </thead>
                        <tbody class="table table-bordered" id="viewchart-topindustryyeartrend">
                        </tbody>
                    </table>
                </div>
                <div class="clear"></div>  
            </div>
                                                  
        </div>
    </div>           
    
    <div class="row-fluid">    
        <div class="span12"> 
            <div class="span6">
                <div class="head">
                    <div class="isw-graph"></div>
                    <h1>Top Industry (Previous Month) [<?php echo date('Y-m-1', strtotime(date('Y-m-01').' -1 months')).' - '.date('Y-m-t', strtotime(date('Y-m-d').' -1 months')) ?>]</h1>
                    <div class="clear"></div>
                </div>
                <div>
                    <span>Mouse Hover: </span>
                    <span id="info4">Top Industry (Previous Month)</span>
                </div>
                <div class="block">
                    <div id="chart-topindustrymonthprevious" style="width:100%; height:350px;"></div>
                </div>
                <div class="clear"></div>  
            </div> 
            
            <div class="span6">
                <div class="head">
                    <div class="isw-graph"></div>
                    <h1>Top Industry (Next Month) [<?php echo date('Y-m-1', strtotime(date('Y-m-01').' +1 months')).' - '.date('Y-m-t', strtotime(date('Y-m-d').' +1 months')) ?>] Forecast</h1>
                    <div class="clear"></div>
                </div>
                <div>
                    <span>Mouse Hover: </span>
                    <span id="info5">Top Industry (Next Month) Forecast</span>
                </div>
                <div class="block">
                    <div id="chart-topindustrymonthnext" style="width:100%; height:350px;"></div>
                </div>
                <div class="clear"></div>  
            </div> 
        </div>
    </div>                                        
    <div class="dr"><span></span></div>      

</div>  


<script class="code" language="javascript" type="text/javascript">
$(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});   


$('#accordion1 h3 a').bind('click', function (e) {
  // bind to the the header / anchor clicks
  
  if (!condition) {
      alert('test');   
    //e.preventDefault();
    //e.stopPropagation();
  }
});
//$('#viewmore-topindustryyeartrend'). 

$(function () {    
    var datefrom = '<?php echo date('Y-m-01') ?>';//$('#datefrom').val();
    var dateto = '<?php echo date('Y-m-d') ?>';//$('#dateto').val();
    (function request() {
        $.ajax({
            url: '<?php echo site_url('marketing_dash/realTimeRetrieve') ?>',
            type: 'post',
            data: {datefrom: datefrom, dateto: dateto},
            success:function(response) {   
                
                var $response = $.parseJSON(response);

                topindustrymonth($response);  
                topindustryyear($response);  
                topindustryyeartrend($response);  
                topindustrymonthprevious($response);
                topindustrymonthnext($response);
            }
        });
         //calling the anonymous function after 10000 milli seconds
        setTimeout(request, 180000);  //second   180000
    })(); //self Executing anonymous function
});

function topindustryyeartrend($response) {
    var s1 = new Array(); 
    var s2 = new Array(); 
    var c1 = new Array(); 
    var cn = new Array(); 
    var labels = [];
    
    $.each($response['topindustryyeartrend'],function(i) {
        var data1 = $response['topindustryyeartrend'][i];  

        s1.push(parseFloat(data1['jantotalsales']));
        s1.push(parseFloat(data1['febtotalsales']));
        s1.push(parseFloat(data1['martotalsales']));
        s1.push(parseFloat(data1['aprtotalsales']));
        s1.push(parseFloat(data1['maytotalsales']));
        s1.push(parseFloat(data1['juntotalsales']));
        s1.push(parseFloat(data1['jultotalsales']));
        s1.push(parseFloat(data1['augtotalsales']));
        s1.push(parseFloat(data1['septotalsales']));
        s1.push(parseFloat(data1['octbtotalsales']));
        s1.push(parseFloat(data1['novtotalsales']));
        s1.push(parseFloat(data1['decetotalsales']));   
        s2.push(s1);  
        s1 = new Array();         
        c1.push(data1['industry']);
        //labels.push(data1['industry']);
        //labels = labels+"{ label: '"+data1['industry']+"'},";   
        cn.push(data1['industryname']);

    }); 
    $('#chart-topindustryyeartrend').empty(); 
    $.jqplot.postDrawHooks.push(function() {       
        var swatches = $('table.jqplot-table-legend tr td.jqplot-table-legend-swatch');
        var labels = $('table.jqplot-table-legend tr td.jqplot-table-legend-label');
        labels.each(function(index) {
            //turn the label's text color to the swatch's color
            var color = $(swatches[index]).find("div div").css('background-color');
            $(this).css('color',color );
            //set type name as the label's text
            $(this).css('white-space', 'nowrap'); //otherwise Heavy Industry M12 will be in 3 lines
            $(this).html(types[index]);
        });
    });
    
    var types = c1; 
    
    var ticks = [[1,'Jan'], [2,'Feb'], [3,'Mar'], [4,'Apr'], [5,'May'], [6,'Jun'], [7,'Jul'], [8,'Aug'], [9,'Sep'], [10,'Oct'], [11,'Nov'], [12,'Dec']];  
 
    plot2 = $.jqplot('chart-topindustryyeartrend',s2,{
        stackSeries: false,
        showMarker: true,
        highlighter: {
        show: true,
        showTooltip: true
        },
        legend: {
        show: true,
        placement: 'outsideGrid'
        },
        grid: {
        drawBorder: false,
        shadow: false
        },  
        axes: {
           xaxis: {
              ticks: ticks,
              tickRenderer: $.jqplot.CanvasAxisTickRenderer,
              tickOptions: {
                angle: -90 
              },
              drawMajorGridlines: false
          }           
        }
    });
    
    $('#viewchart-topindustryyeartrend').html($response['viewchart_topindustryyeartrend']);

}

function topindustryyear($response) {

    var s1 = new Array();           
    var c1 = new Array(); 
    var cn = new Array(); 
    
    $.each($response['topindustryyear'],function(i) {
        var data1 = $response['topindustryyear'][i];  

        s1.push(parseFloat(data1['totalsales']));
        c1.push(data1['industry']);
        cn.push(data1['industryname']);

    }); 

    $('#chart-topindustryyear').empty();
    var plot1 = $.jqplot('chart-topindustryyear', [s1], {
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
    
    
    $('#chart-topindustryyear').bind('jqplotDataHighlight',
        function (ev, seriesIndex, pointIndex, labels) {
            //console.debug(pointIndex);
            //console.debug(cn[pointIndex]);
            var amount = s1[pointIndex];

            $('#info2').html(cn[pointIndex]+', Amount: '+amount.format(2));

        }
    );
         
    $('#chart-topindustryyear').bind('jqplotDataUnhighlight',
        function (ev) {
            $('#info2').html('Nothing');
            
        }
    );
    
}

function topindustrymonthprevious($response) {

    var s1 = new Array();           
    var c1 = new Array(); 
    var cn = new Array(); 
    
    $.each($response['topindustrymonthprevious'],function(i) {
        var data1 = $response['topindustrymonthprevious'][i];  

        s1.push(parseFloat(data1['totalsales']));
        c1.push(data1['industry']);
        cn.push(data1['industryname']);

    }); 

    $('#chart-topindustrymonthprevious').empty();
    var plot1 = $.jqplot('chart-topindustrymonthprevious', [s1], {
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
    
    
    $('#chart-topindustrymonthprevious').bind('jqplotDataHighlight',
        function (ev, seriesIndex, pointIndex, labels) {
            //console.debug(pointIndex);
            //console.debug(cn[pointIndex]);
            var amount = s1[pointIndex];

            $('#info4').html(cn[pointIndex]+', Amount: '+amount.format(2));

        }
    );
         
    $('#chart-topindustrymonthprevious').bind('jqplotDataUnhighlight',
        function (ev) {
            $('#info4').html('Nothing');
            
        }
    );
    
}

function topindustrymonthnext($response) {

    var s1 = new Array();           
    var c1 = new Array(); 
    var cn = new Array(); 
    
    $.each($response['topindustrymonthnext'],function(i) {
        var data1 = $response['topindustrymonthnext'][i];  

        s1.push(parseFloat(data1['totalsales']));
        c1.push(data1['industry']);
        cn.push(data1['industryname']);

    }); 

    $('#chart-topindustrymonthnext').empty();
    var plot1 = $.jqplot('chart-topindustrymonthnext', [s1], {
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
    
    
    $('#chart-topindustrymonthnext').bind('jqplotDataHighlight',
        function (ev, seriesIndex, pointIndex, labels) {
            //console.debug(pointIndex);
            //console.debug(cn[pointIndex]);
            var amount = s1[pointIndex];

            $('#info5').html(cn[pointIndex]+', Amount: '+amount.format(2));

        }
    );
         
    $('#chart-topindustrymonthnext').bind('jqplotDataUnhighlight',
        function (ev) {
            $('#info5').html('Nothing');
            
        }
    );
    
}

function topindustrymonth($response) {

    var s1 = new Array();           
    var c1 = new Array(); 
    var cn = new Array(); 
    
    $.each($response['topindustrymonth'],function(i) {
        var data1 = $response['topindustrymonth'][i];  

        s1.push(parseFloat(data1['totalsales']));
        c1.push(data1['industry']);
        cn.push(data1['industryname']);

    }); 

    $('#chart-topindustrymonth').empty();
    var plot1 = $.jqplot('chart-topindustrymonth', [s1], {
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
    
    
    $('#chart-topindustrymonth').bind('jqplotDataHighlight',
        function (ev, seriesIndex, pointIndex, labels) {
            //console.debug(pointIndex);
            //console.debug(cn[pointIndex]);
            var amount = s1[pointIndex];

            $('#info1').html(cn[pointIndex]+', Amount: '+amount.format(2));

        }
    );
         
    $('#chart-topindustrymonth').bind('jqplotDataUnhighlight',
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


 