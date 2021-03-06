<?php
session_start();
require_once("user.php");
require_once("profile.php");
function registration()
{
    mysqli_report(MYSQLI_REPORT_STRICT);
    try
    {
        $mysqli = new mysqli("localhost", "airline_bradg", "1CODingF\$\$L", "airline_bradg");
    }
    catch(mysqli_sql_exception $exception)
    {
        echo "Unable to connect to mySQL:" . $exception->getMessage();
    }
    $email = $_POST["email"];
    $email = trim($email);
    $password = $_POST["password"];
    $firstName = $_POST["firstName"];
    $firstName = trim($firstName);
    $lastName = $_POST["lastName"];
    $lastName = trim($lastName);
    $year = $_POST["year"];
    $month = $_POST["month"];
    $day = $_POST["day"];
    $birthday = "$year-$month-$day";
    $specialNeeds = $_POST["specialNeeds"];
    if($specialNeeds == "")
    {
        $specialNeeds = 0;
    }
	else
	{
		$specialNeeds = 1;
	}
    if($_POST["password"] !== $_POST["confirmPassword"])
    {
        echo"<p style='color: red'>Password do not match.</p>";
        return;
    }
    $bytes = openssl_random_pseudo_bytes(32, $cstrong);
    $salt = bin2hex($bytes);
    $passSalt = $password . $salt;
    $hash = hash("sha512", $passSalt, false);
    $user = new User(-1, $email, $hash, $salt);
    try
    {
        $user->insert($mysqli);
    }
    catch(Exception $exception)
    {
        echo "<p style='color: red'>Email already in use.</p>";
        return;
    }
    $id = $user->getId();
    $profile = new Profile(-1, $id, $firstName, $lastName, $birthday, $specialNeeds);
    $profile->insert($mysqli);
    $_SESSION["id"] = $id;
	header("location: profileForm.php");
    $mysqli->close();
}
registration();
?>
    
    
    
