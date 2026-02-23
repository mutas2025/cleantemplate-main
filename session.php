<?php
session_start();

// Check if a user session exists
if (!isset($_SESSION['id'])) {
   // Redirect to the dashboard page
   header('location:../index.php');
   exit;
}

// include('.././config/scims.php');

$host = "192.168.3.5";
$db_name = "scims";
$username = "root";
$password = "adminPW12345";

try {
   // database connection
   $con_scims = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
   $con_scims->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   // echo 'Connected to database!';

} catch (PDOException $e) {
   // display error message if connection fails
   echo "Connection failed: " . $e->getMessage();
}

$user_id = $_SESSION['id'];
$db_fullname = '';
$db_user_entity_no = '';
$db_user_roles = '';

$default_password = '$2y$10$6LQCa7LPVmT9EhDKXs/Tpe7zD82JWJcw9ZUW7TXI3Kl8WOW2wuYmK';
$password_changer = 'https://vamosmobile.app/scims-v2/dashboard/changepassword';

// $get_user_sql = "SELECT first_name, middle_name, last_name FROM user_accounts WHERE user_id = :id";
$get_user_sql = "SELECT fullname,password,entity_no FROM user_accounts WHERE user_id = :id";
$user_data = $con_scims->prepare($get_user_sql);
$user_data->execute([':id' => $user_id]);
$result = $user_data->fetch(PDO::FETCH_ASSOC);

if ($result['password'] === $default_password) {
   echo 'password is default, please change';
   header("Location: $password_changer");
   exit;
}

if ($result) {
   $db_fullname = $result['fullname'];
   $db_user_entity_no = $result['entity_no'];
   $db_user_password = $result['password'];
}

$user_fullname = ucwords(strtolower($db_fullname));

// Get the column names ending with "_sys" from the account_type table
$stmt = $con_scims->prepare("SELECT COLUMN_NAME
                            FROM INFORMATION_SCHEMA.COLUMNS
                            WHERE TABLE_NAME = 'account_type'
                            AND COLUMN_NAME LIKE '%\\_sys'");
$stmt->execute();
$columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Construct the SQL query
$query = "SELECT b.entity_no, " . implode(", ", $columns) . "
        FROM user_accounts a
        INNER JOIN account_type b
        ON a.entity_no = b.entity_no
        WHERE a.entity_no = '$db_user_entity_no'";

$stmt = $con_scims->prepare($query);
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);

// include('../config/db_sccdrrmo.php');
$host = "34.92.117.58";
$db_name = "sccdrrmo";
$username = "root";
$password = "I0nvNUWNXoYI";

try {
   // database connection
   $con_sccdrrmo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
   $con_sccdrrmo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   // echo 'Connected to database!';

} catch (PDOException $e) {
   // display error message if connection fails
   echo "Connection failed: " . $e->getMessage();
}

$get_user_photo_sql = "SELECT photo FROM tbl_individual WHERE entity_no = :entity_no ";
$user_data = $con_sccdrrmo->prepare($get_user_photo_sql);
$user_data->execute([':entity_no' => $db_user_entity_no]);
$user_photo = $user_data->fetch(PDO::FETCH_ASSOC);

if ($user_photo) {
   $photo = $user_photo['photo'];
}
