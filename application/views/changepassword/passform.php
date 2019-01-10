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

td {
    height: 20px;;
}

</style>
  
  <table>
    
        <tr>
        
            <td colspan="2">Note: password must be atleast 8 characters in length.</td>
            
        </tr>

        <tr>

            <td colspan="1" style="color:red">At least 1 number.</td>
                            
        </tr>

        <tr>

            <td colspan="2" style="color:red">At least 1 special character ($ @ ! % * # ? &)</td>

        </tr>
        
        <tr>
        
            <td>Old Password : </td>
            
            <td><input type="password" name="oldpass" id="oldpass"></td>
        
        </tr>
        
          <tr id="register">
        
            <td>New Password : </td>
            
            <td ><input type="password" name="newpass" id="newpass"><span ></span></td>
            <td style="width:100px;text-align: right;" id="result"></td>
        
        </tr>
        
        <tr>
        
            <td>Confirm Password : </td>
            
            <td><input type="password" name="confirmpass" id="confirmpass"></td>
        
        </tr>
        
        
    
    </table>
    
    