<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>

<?php
date_default_timezone_set('America/New_York');
//From w3school to sanitize input
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
//clear variables
$username = $oldpass = $newpass1 = $newpass2 = $finalpass = $usererror = $newpass1error = $newpass2error = '';

if (!isset($_SERVER['PHP_AUTH_USER'])) {
header('Location: https://google.com/') ;
} else {
$username = $_SERVER['PHP_AUTH_USER'];
} 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
//Check for input errors
	if (empty($_POST['user'])) {
	$usererror = "Username required.";
	}
	if (empty($_POST['newpass1'])) {
	$newpass1error = "Password field required";
	}
	if (empty($_POST['newpass2'])) {
	$newpass2error = "Password field required";
	}
}

//ldap connection variables
$ldapserver = 'ad.domain.tld';
$ldapuser = 'ldap_user';
$ldappass = 'ldap_user_password';
$basedn = 'OU=Users,DC=domain,DC=tld';

//Check for user name, then check for email in using ldap.
if (isset($_POST['user'])) {
	$username = test_input($_POST['user']);
	$ldapconnect = ldap_connect($ldapserver) or die ("Connection to $ldapserver failed.");
	if ($ldapconnect) {
		$ldapbind = ldap_bind($ldapconnect, $ldapuser, $ldappass) or die ("Unable to bind to $ldapserver:".ldap_error($ldapconnect));
	}
	$usersearch = ldap_search($ldapconnect,$basedn, "(samaccountname=$username)") or die ("Unable to find $username via ldap".ldap_error($ldapconnect));
	$searchresult = ldap_get_entries($ldapconnect, $usersearch);
	$email = $searchresult[0]["mail"][0];
}


if (isset($_POST['newpass1']) && isset($_POST['newpass2'])) {
	$newpass1 = test_input($_POST['newpass1']);
	$newpass2 = test_input($_POST['newpass2']);
	if ( "$newpass1" == "$newpass2" ) {
		if (preg_match('/[a-z]/', "$newpass1") && preg_match('/[A-Z]/', "$newpass1") && (strlen($newpass1) >= '8')) {
			$finalpass = "$newpass1";
		}
	} else {
	$matcherror = "Passwords do not match.";
	}
}

if ( ("$email" !== '') && ("$finalpass" !== '')) {
        $output = shell_exec("/var/www/html/pass.sh $username $finalpass");
}




?>
<span class="error"> <?php echo nl2br("$matcherror"); ?> </span>
<form action="index.php" method="post">
User Name: <select name="user">
	<option value="<?php echo "$username"; ?>"><?php echo $username ?></option><span class="error"> <?php echo "$usererror"; ?></span><br>
</select><br>
New Password: <input type="password" name="newpass1"><span class="error"> <?php echo "$newpass1error"; ?></span><br>
New Password: <input type="password" name="newpass2"><span class="error"> <?php echo "$newpass2error"; ?></span><br>
<input type="submit">
</form>

</body>
</html>

<?php
if (isset($output)) {
echo nl2br("Output : $output");
}
#echo nl2br("User name: $username \n");
#echo nl2br("New password1 : $newpass1 \n");
#echo nl2br("New password2 : $newpass2 \n");
#echo nl2br("Final Password : $finalpass\n");
?>

</body>
</html>
