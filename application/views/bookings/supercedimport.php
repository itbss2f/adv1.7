<div class="row-form-booking">			   		
	<div class="span3"><strong>Reference Invoice Number</strong></div>
	<div class="clear"></div>
</div>
<div class="row-form-booking">			   		
	<div class="span1" style="width:100px"><input type="text" name="refinvoice" id="refinvoice" style="text-align:right"></div>
	<div class="span2"><button class="btn btn-success" type="button" name="btn_import_invoice" id="btn_import_invoice">Import Invoice Data</button></div>
	<div class="clear"></div>
</div>
<script>
$("#refinvoice").mask('99999999');
$("#btn_import_invoice").click(function(){
	var $refinvoice = $("#refinvoice").val();

	if ($refinvoice == "") {
		alert("Reference Invoice must not be empty!"); return false;	
	} else {
		$.ajax({
			url: "<?php echo site_url('booking/getImportInvoice') ?>",
			type: "post",
			data:{refinvoice: $refinvoice},
			success:function(response){
				$response = $.parseJSON(response);

				window.location.href = "<?php echo base_url()?>booking/superceding/"+$response['aonum']['ao_num']+"/"+$response['refinvoice'];
			}
		});	
	}
});
</script>
