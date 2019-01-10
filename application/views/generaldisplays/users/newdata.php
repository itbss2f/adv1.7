<style>
#result{
    margin-left:5px;
}
 
#register .short{
    color:#FF0000;
}
 
#register .weak{
    color:#E66C2C;
}
 
#register .good{
    color:#2D98F3;
}
 
#register .strong{
    color:#006400;
}


.ui-autocomplete {
    z-index: 10000 !important;
}

</style>
<div class="block-fluid">      
	<form action="<?php echo site_url('user/saveUser') ?>" method="post" name="formsave" id="formsave"> 
    <div class="row-form-booking">        
        <div class="span4"><input type="text" placeholder="Search Employee" name="search" id="search"></div>        
        <div class="clear"></div>    
    </div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Employee No.</b></div>	
		<div class="span2" style="width:120px"><input type="text" name="empno" id="empno"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>First Name</b></div>	
		<div class="span2" style="width:190px"><input type="text" name="firstname" id="firstname"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Middle Name</b></div>	
		<div class="span2" style="width:190px"><input type="text" name="middlename" id="middlename"></div>		
		<div class="clear"></div>	
	</div>
	<div class="row-form-booking">
		<div class="span2" style="width:120px"><b>Last Name</b></div>	
		<div class="span2" style="width:190px"><input type="text" name="lastname" id="lastname"></div>		
		<div class="clear"></div>	
	</div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>User Level</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="userlevel" id="userlevel"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Branch</b></div>    
        <div class="span2" style="width:190px">
            <select name="branch" id="branch">                
                <?php foreach ($branch as $branch) : ?>
                <option value="<?php echo $branch['branch_code'] ?>"><?php echo $branch['branch_name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Department</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="department" id="department"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Position</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="position" id="position"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Email Address</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="emailadd" id="emailadd"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Username</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="username" id="username"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking" id="register">
        <div class="span2" style="width:120px"><b>Password</b></div>    
        <div class="span2" style="width:190px"><input type="password" name="password" id="password"></div>        
        <div class="span1" style="width:50px"><span id="result"></span></div>
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Re-Password</b></div>    
        <div class="span2" style="width:190px"><input type="password" name="password2" id="password2"></div>        
        <div class="clear"></div>    
    </div>
    <div class="row-form-booking">
        <div class="span2" style="width:120px"><b>Expiry Date</b></div>    
        <div class="span2" style="width:190px"><input type="text" name="expirydate" id="expirydate"></div>        
        <div class="clear"></div>    
    </div>
	<div class="row-form-booking">
		<div class="span2"><button class="btn btn-success" type="button" name="save" id="save">Save User button</button></div>		
		<div class="clear"></div>		
	</div>
	</form>
</div>

<script>
$("#edition_totalccm").autoNumeric();
$("#expirydate").die().live().datepicker({dateFormat:'yy-mm-dd'}); 
var errorcssobj = {'background': '#E1CECE','border' : '1px solid #FF8989'};
var errorcssobj2 = {'background': '#E0ECF8','border' : '1px solid #D7D7D7'}; 
$("#save").click(function() {
	var countValidate = 0;  
	var validate_fields = ['#empno', '#firstname', '#middlename', '#lastname', '#userlevel', '#branch', '#department', '#position', '#emailadd', '#username', '#password', '#password2', '#expirydate'];

	for (x = 0; x < validate_fields.length; x++) {			
		if($(validate_fields[x]).val() == "") {                        
			$(validate_fields[x]).css(errorcssobj);          
		  	countValidate += 1;
		} else {        
		  	$(validate_fields[x]).css(errorcssobj2);       
		}        
	}   
    countValidate = 0;
	if (countValidate == 0) {
        
        var pass1 = $("#password").val();
        var pass2 = $("#password2").val();
        
        if (pass1 != pass2) {
            alert('Password is not match!');
        } else {
            
            var pass_str = $("#result").attr('class');
            if (pass_str != 'strong') {
                alert('Password must be strong');                   
            } else {
                //alert('good password');           
                // TO DO: save new data
                $.ajax({
                    url: "<?php echo site_url('user/validateUsername') ?>",
                    type: "post",
                    data: {username: $("#username").val()},
                    success: function(response) {
                        if (response == "true") {                    
                            alert("Username Exist!.");
                            $('#username').val('');
                        } else {
                            $('#formsave').submit();
                        }
                    }
                })                
            }
        }

        return false;

	} else {			
		return false;
	}	
});

$(document).ready(function() {
    
      $( "#search" ).autocomplete({            
            source: function( request, response ) {
                $.ajax({
                    //url: 'http://erm.inquirer.com.ph/hris/public/api/json/employees',
                    url: 'https://erm.inquirer.com.ph/api/json/employees',
                    dataType: "jsonp",
                    data: {   search: request.term
                           },
                    success: function(data) {
                        
                        response($.map(data.employees, function(item) {
                             return {
                                    label: item.code + " " + item.first_name + " " + item.middle_name + " " + item.last_name,
                                    value: item.first_name,                                    
                                    item: item                                     
                             }
                        }))
                    }
                });                
            },
            autoFocus: false,
            minLength: 2,
            delay: 300,
            select: function(event, ui) {
                $(':input[name=empno]').val(ui.item.item.code);
                $(':input[name=firstname]').val(ui.item.item.first_name);
                $(':input[name=middlename]').val(ui.item.item.middle_name);
                $(':input[name=lastname]').val(ui.item.item.last_name);
                $(':input[name=position]').val(ui.item.item.position);                                
                $(':input[name=department]').val(ui.item.item.department);                                
                $(':input[name=userlevel]').val(ui.item.item.level);                                
                $(':input[name=emailadd]').val(ui.item.item.email);                
            }
        });

    $('#password').keyup(function(){
        $('#result').html(checkStrength($('#password').val()))
    }) 
 
    function checkStrength(password){
 
    //initial strength
    var strength = 0
 
    //if the password length is less than 8, return message.
    if (password.length < 8) {
        $('#result').removeClass()
        $('#result').addClass('short')
        return 'MAX 8'
    }
 
    //length is ok, lets continue.
 
    //if length is 8 characters or more, increase strength value
    if (password.length > 7) strength += 1
 
    //if password contains both lower and uppercase characters, increase strength value
    if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))  strength += 1
 
    //if it has numbers and characters, increase strength value
    if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/))  strength += 1
 
    //if it has one special character, increase strength value
    if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/))  strength += 1
 
    //if it has two special characters, increase strength value
    if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,",%,&,@,#,$,^,*,?,_,~])/)) strength += 1
 
    //now we have calculated strength value, we can return messages
 
    //if value is less than 2
    if (strength < 2 ) {
        $('#result').removeClass()
        $('#result').addClass('weak')
        return 'WEAK'
    } else if (strength == 2 ) {
        $('#result').removeClass()
        $('#result').addClass('good')
        return 'GOOD'
    } else {
        $('#result').removeClass()
        $('#result').addClass('strong')
        return 'STRONG'
    }
}
});
</script>
