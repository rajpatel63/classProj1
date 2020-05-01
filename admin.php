<html>
<head>
<title>CSC155 001DR Survey Thing</title>
<link rel="stylesheet" type="text/css" href="library/styles.css">
<?php
require ('library/functions.php');

// depending on the zone, call one of
//checkAccount("none");
//checkAccount("user");
checkAccount("admin");

$conn = getDBConnection();
?>
<body>
<?php echo $_SESSION['username'] ?>, Welcome to your page, You are and Admin.Here's a list of accounts <a href='showAccounts.php'>here</a>

</body>
