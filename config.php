<?php
// Database config - change to match your XAMPP settings
define('DB_HOST','127.0.0.1');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','hospital_db');

// Admin registration passcode - CHANGE THIS before going live
define('ADMIN_REG_PASSCODE','hospital2025');

// NOTE: Default admin passcode is set to 'hospital2025'. Change this in config.php before production.
// Start session
if(!session_id()) session_start();

// PDO connection
try {
    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Database connection failed: '.$e->getMessage());
}
?>
