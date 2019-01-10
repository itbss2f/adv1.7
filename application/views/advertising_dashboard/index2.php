<link class="include" rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/jplot/jquery.jqplot.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/jplot/examples/examples.min.css" />
<link type="text/css" rel="stylesheet" href="<?php echo base_url() ?>assets/jplot/examples/syntaxhighlighter/styles/shCoreDefault.min.css" />
<link type="text/css" rel="stylesheet" href="<?php echo base_url() ?>assets/jplot/examples/syntaxhighlighter/styles/shThemejqPlot.min.css" />



<div id="chart1" style="height:300px; width:700px;"></div>

<script>

$(document).ready(function(){
  var line1=[['23-May-08', 578.55], ['20-Jun-08', 566.5], ['25-Jul-08', 480.88], ['22-Aug-08', 509.84],
      ['26-Sep-08', 454.13], ['24-Oct-08', 379.75], ['21-Nov-08', 303], ['26-Dec-08', 308.56],
      ['23-Jan-09', 299.14], ['20-Feb-09', 346.51], ['20-Mar-09', 325.99], ['24-Apr-09', 386.15]];
      
  var plot1 = $.jqplot('chart1', [line1], {
      title:'Data Point Highlighting',
      seriesDefaults:{
          renderer:$.jqplot.BarRenderer, 
          rendererOptions: {barWidth: 15},
      },
      axes:{
        xaxis:{
          renderer:$.jqplot.DateAxisRenderer,
          tickOptions:{
            formatString:'%b&nbsp;%#d'
          } 
        },
        yaxis:{
          tickOptions:{
            formatString:'$%.2f'
            }
        }
      },
      highlighter: {
        show: true,
        sizeAdjust: 7.5
      },
      cursor: {
        show: false
      }
  });
});


</script>