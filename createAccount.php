<html>
<head>
<title>CSC155 001DR Survey Thing</title>
<link rel="stylesheet" type="text/css" href="library/styles.css">
<?php

require ('library/functions.php');

// depending on the zone, call one of
checkAccount("none");
//checkAccount("user");
//checkAccount("admin");

// get connection object
$conn = getDBConnection();
if (isset($_POST['selection'])) // form loaded itself
{    

    
    if ($_POST['selection'] == "Create Account") // insert new record chosen
    {  $username=mysqli_real_escape_string($conn,$_POST['username']);
       $check_users="SELECT username FROM users WHERE username ='$username'";
       $result = mysqli_query($conn,$check_users);
       $count = mysqli_num_rows($result);
       if($count == 0){ 
       
      	if ($_POST['password'] == $_POST['confirm'])
        {
	    // build SQL command SECURELY
            // prepare
	    $stmt = $conn->prepare("INSERT INTO users 
                       (username, encrypted_password, usergroup, email, address1, address2, statezip) 
                       VALUES (?, ?, ?, ?, ?, ?, ?)" );
	    // bind variable names and types
	    $stmt->bind_param("sssssss", $username, $encrypted_password, 
                                  $usergroup, $email, $address1, $address2, $statezip);
            

	    $username=$_POST['username'];
	    $encrypted_password=password_hash($_POST['password'], 
                                          PASSWORD_DEFAULT);
	    $usergroup=$_POST['usergroup'];
	    $email=$_POST['email'];
            $address1=$_POST['address1'];
            $address2=$_POST['address2'];
            $statezip=$_POST['statezip'];



	    // put the statement together and send it to the database
	    $stmt->execute();
            header("Location: welcome.php");
	}
        
	else 
	{
	    displayError("Passwords don't match");
        }
       }
     if($count > 0){
        displayError("Error: Username exists");
    }
    }
    if ($_POST['selection'] == "Cancel") // insert new record chosen
    {
        header("Location: welcome.php");
    }
}
?>

</head>
<body>

<form method='POST'>
<div style='border-width: 2px'>
<table id='userform'> 
<tr>
  <td>Username</td>
  <td><input type='text' name='username'  value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>" /></td>
</tr>
<tr>
  <td>Password</td>
  <td><input type='password' name='password'value="<?php if (isset($_POST['password'])) echo $_POST['password']; ?>" /> </td>
</tr>
<tr>
  <td>Confirm Password</td>
  <td> <input type='password' name='confirm'value="<?php if (isset($_POST['confirm'])) echo $_POST['confirm']; ?>" /></td>
</tr>
<tr>
  <td>Email</td>
  <td> <input type='text' name='email' value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" /> </td>
</tr>
<tr>
  <td>Address 1</td>
  <td> <input type='text' name='address1' /> </td>
</tr>
<tr>
  <td>Address 2</td>
  <td> <input type='text' name='address2' /> </td>
</tr>
<tr>
  <td>City State and Zip</td>
  <td> <input type='text' name='statezip' /> </td>
</tr>

<tr>
   <td>User Group</td>
   <td><input type="radio" name='usergroup' value="user" checked="checked"/>User
   <input type="radio" name='usergroup' value="admin"/>Admin
   <input type="radio" name='usergroup' value="suser">SUser</td>
</tr>
<tr>
  <td colspan='2' style='text-align: center; background-color: white;'> 
    <input type='submit' name='selection' value='Create Account' />
    &nbsp;
    <input type='submit' name='selection' value='Cancel' />
  </td>
</tr>
<tr>
  <td colspan='2' style='text-align: center; background-color: lightred;'>
    Warning: This is class project and is not secure!  
  </td>
</tr>
  
</table>
</div>
</form>

</body>
</html>
