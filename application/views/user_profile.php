

<div class="breadLine">            
    <div class="arrow"></div>
    <div class="adminControl active">
        Hi, <?php echo ucfirst($this->session->userdata('authsess')->sess_fullname);?>
    </div>
</div>

<div class="admin">
    <div class="image">
        <img src="<?php echo base_url() ?>themes/img/users/alexander.jpg" class="img-polaroid"/>                
    </div>
    <ul class="control">                
        <li><span class="icon-comment"></span> <a href="#messages">Messages</a> <a href="messages.html" class="caption red">12</a></li>
        <li><span class="icon-cog"></span> <a href="#" id="changepassword">Change Password</a></li>
        <li><span class="icon-share-alt"></span> <a href="<?php echo site_url('auth/logout') ?>">Logout</a></li>
    </ul>
    <div class="info">
        <span>Welcome! Today is <?php echo date('F d, Y') ?></span>
    </div>
</div>

<div id="changepass" title="Change Password">

</div>

<script>

 $( "#changepass" ).dialog({
         autoOpen: false,
        height: 300,
        width:450,
        modal: true,
        buttons: {
       "Submit" : function()
                {  
                   
                  if($("#newpass").val() == $("#confirmpass").val())
                  {
                       var pass_str = $("#result").attr('class');
                       
                       if(pass_str == 'strong')
                       {
                           newpassword(); 
                       }
                       else
                       {
                           alert('Password must be strong.');
                       }
                                   
                  }
                  else
                  {
                      alert("Password do not match.");
                  }
                },
       "Cancel" : function()
       {
        $( this ).dialog( "close" );   
       }         
          
    }
    });
    
    function newpassword()
    {
        $.ajax({
            url:"<?php echo site_url("user/changepassword"); ?>",
            type:"post",
            data:{newpassword:$("#newpass").val(),oldpassword:$("#oldpass").val()},
            success: function(response)
            {
                $response = $.parseJSON(response);
                
                alert($response['message']);
                     
                 if($response['status'] == 'success')
                 {
                     $("#newpass").val("");
                     $("#oldpass").val("");
                     $("#confirmpass").val("");
                    $("#changepass").dialog( "close" );  
                 }
            
            }
        });
    }  
        

    $("#changepassword").die().live("click",function(){
        
       $.ajax({
           url:"<?php echo site_url("user/passform"); ?>",
           type:"post",
           success: function(response)
           {
              $("#changepass").html($.parseJSON(response)); 
              $("#changepass").dialog( "open" );   
              
           }
       });
       
    });
    
    
   $('#newpass').die().live("keyup",function(){
        $('#result').html(checkStrength($('#newpass').val()))
    }) 
    
   function checkStrength(password){
 
    //initial strength
    var strength = 0
 
    //if the password length is less than 8, return message.
    if (password.length < 8) {
        $('#result').removeClass()
        $('#result').addClass('short')
        return 'MIN 8'
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

</script>
