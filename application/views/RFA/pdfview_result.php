Generating pdf file

<div class="report_generator" style="height:500px;margin-left: 10px">
    <iframe style="width:99%;height:99%" id="source"></iframe>
</div>

<script>

$(function() {     
  
    var complaint = $(":input[name='complaint']").val();
    var advertisername = $(":input[name='advertisername']").val();
    var agencyname = $(":input[name='agencyname']").val();
    var accountexec = $(":input[name='accountexec']").val();
    var invoiceno = $(":input[name='invoiceno']").val();
    var issuedatefrom = $(":input[name='issuedatefrom']").val();
    var issuedateto = $(":input[name='issuedateto']").val();
    var rfano = $(":input[name='rfano']").val();
    var rfadatefrom = $(":input[name='rfadatefrom']").val();
    var rfadateto = $(":input[name='rfadateto']").val();
    var person = $(":input[name='searchperson']").val();
    var responsible = $(":input[name='responsible']").val();
    var rfatypes = $(":input[name='rfatypes']").val();
        
    /*var advertisername: $(":input[name='advertisername']").val(),   
    var agencyname: $(":input[name='agencyname']").val(),   
    var accountexec: $(":input[name='accountexec']").val(),   
    var invoiceno: $(":input[name='invoiceno']").val(),   
    var issuedatefrom: $(":input[name='issuedatefrom']").val(),   
    var issuedateto: $(":input[name='issuedateto']").val(),   
    var rfano: $(":input[name='rfano']").val(),   
    var rfadatefrom: $(":input[name='rfadatefrom']").val(),   
    var rfadateto: $(":input[name='rfadateto']").val(),
    var person: $(":input[name='searchperson']").val(),
    var responsible: $(":input[name='responsible']").val(), 
    var  rfatypes: $(":input[name='rfatypes']").val()*/  

    if (complaint == "") {
        complaint = "null";
    } 
    if (advertisername == "") {
        advertisername = "null";
    }
    if (agencyname == "") {
        agencyname = "null";
    }
    if (accountexec ==""){
        accountexec = "null"
    }
    if (invoiceno ==""){
        invoiceno = "null"
    }
    if (issuedatefrom ==""){
        issuedatefrom = "null"
    }
    if(issuedateto ==""){
        issuedateto = "null"
    }
    if(rfano ==""){
        rfano = "null"
    }
    if(rfadatefrom ==""){
        rfadatefrom = "null"
    }
    if(rfadateto ==""){
        rfadateto = "null"
    }
    if(person == ""){
        person = "null"
    }
    if(responsible ==""){
        responsible = "null"
    }
    if(rfatypes ==""){
        rfatypes = "null"
    }
     
    $("#source").attr('src', "<?php echo site_url('rfa/createPDFFile') ?>/"+complaint+"/"+advertisername+"/"+agencyname+"/"+accountexec+"/"+invoiceno+
    "/"+issuedatefrom+"/"+issuedateto+"/"+rfano+"/"+rfadatefrom+"/"+rfadateto+"/"+person+"/"+responsible+"/"+rfatypes);
});
</script>