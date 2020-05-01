<?php

function checkAccount($zone)
{
    // zone is either 'user' or 'admin', anything else is considered 
    // 'none' or publicly accessible

    /// we start the session in checkAccount to make sure it's called.
    session_start();
    if ($zone == 'user')
    {
	if (!isset($_SESSION['username']))
	{
	    header('Location: welcome.php');
	}
    }	
    if ($zone == 'admin')
    {
	if (!isset($_SESSION['username']))
	{
	    header('Location: welcome.php');
	}
	if ($_SESSION['usergroup'] != 'admin')
	{
	    header('Location: welcome.php');
	}
    }		
    if ($zone == 'suser')
    {
	if (!isset($_SESSION['username']))
	{
	    header('Location: welcome.php');
	}
	if ($_SESSION['usergroup'] != 'suser')
	{
	    header('Location: welcome.php');
	}
    }		
}

function getDBConnection()
{
    $user = "rpatel63";
    $conn = mysqli_connect("localhost",$user,$user,$user);

    // Check connection and shutdown if broken
    if (mysqli_connect_errno()) {
	die("<b>Failed to connect to MySQL: " . mysqli_connect_error() . "</b>");
    }

    return $conn;
}

function printUserTable($conn)
{
    // build the SQL that pulls the data from the database
    $sql = "SELECT * FROM users;";
    $result = $conn->query($sql);

    echo "<table id='usershow'>";    
    if ($result->num_rows > 0) 
    {
	// column headers
	echo "<tr>";
        echo "<th>ID</th>" 
           . "<th>USERNAME</th>" 
           . "<th>ENCRYPTED PASSWORD</th>" 
           . "<th>GROUP</th>" 
           . "<th>EMAIL</th>"  
           . "<th>Address1</th>"
           . "<th>Address2</th>"
           . "<th>City,State, and Zip</th>" 
           . "<th></th>"    // edit button   
	   ;
	echo "</tr>";

	// loop through all the rows 
	while( $row = $result->fetch_assoc() ) 
	{
	    // output the data from each row
	    echo "<tr>";
	    echo "<td>" . $row["id"] . "</td>" 
               . "<td>" . $row["username"] . "</td>" 
               . "<td>" . $row["encrypted_password"] . "</td>" 
               . "<td>" . $row["usergroup"] . "</td>" 
               . "<td>" . $row["email"] . "</td>" 
               . "<td>" . $row["address1"] . "</td>"
               . "<td>" . $row["address2"] . "</td>" 
               . "<td>" . $row["statezip"] . "</td>" ;
	    echo "<td>";
	    printEditButton($row["id"]);
	    echo "</td>";
	    echo "</tr>";
	}
    } 
    else 
    {
	// empty table
	echo "<tr><td>0 results</td></tr>";
    }
    echo "</table>";
}

// print an edit button form.
function printEditButton($id)
{
    echo "<form action='modifyAccount.php' method='POST'>";
    echo "<input type='hidden' name='id' value='$id' />";
    echo "<input type='submit' name='selection' value='Edit' />";
    echo "</form>";
}

function displayError($mesg)
{
    echo "<div id='errorMessage'>";
    echo $mesg;
    echo "</div>";
}

function showPost( $name )
{
# check to see if it been used, if it has, return it
    if ( isset($_POST[$name]) ) 
    {
        return $_POST[$name];
    }
    return "";
}

function lookUpUserName($conn, $usernameToFind)
{
    $sql = "SELECT * FROM users WHERE username=? ;"; // SQL with parameters
    #    echo "<code>$sql</code>";
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("s", $usernameToFind);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    if ($result->num_rows == 1) 
    {
	return $result;
    }
    else
    {
	return FALSE;
    }
}

// get the record for a user by id
function lookUpUserNameByID($conn, $idToFind)
{
    $sql = "SELECT * FROM users WHERE id=? ;"; // SQL with parameters
    #    echo "<code>$sql</code>";
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("i", $idToFind);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    if ($result->num_rows == 1) 
    {
	return $result;
    }
    else
    {
	return FALSE;
    }
}

function checkAndStoreLogin( $conn, $usernameToTest, $passwordToTest )
{
    // setting $_SESSION['username'] and $_SESSION['usergroup']

    $result=lookupUserName($conn, $usernameToTest);
    if ($result != FALSE)
    {
	$row = $result->fetch_assoc();
	$encrpytedFromDB = $row['encrypted_password'];
	if ( password_verify($passwordToTest, $encrpytedFromDB) )
	{
	    $_SESSION['username'] = $row['username'];
	    $_SESSION['usergroup'] = $row['usergroup'];
	    return TRUE;
	}
    }
    return FALSE;
}


function updateUserRecord($conn)
{
    // we've already verified $_POST['id']
    // prepare since there's user input
    $stmt = $conn->prepare("UPDATE users SET email=?, usergroup=?, address1=?, address2=?, statezip=?
                                         WHERE id=?"); 
    // bind variable names and types
    $stmt->bind_param("sssssi", $email, $usergroup, $address1, $address2, $statezip, $id);
    
    // move the information from the form into 'bound' variables
    $email = $_POST['email'];
    $usergroup = $_POST['usergroup'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $statezip = $_POST['statezip'];
    $id    = $_POST['id'];
    
    // put the statement together and send it to the database
    $stmt->execute();
}




?>
