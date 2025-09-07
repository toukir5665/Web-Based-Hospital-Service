<?php
require_once 'config.php';
// helper functions

function current_user() {
    return isset($_SESSION['user']) ? $_SESSION['user'] : null;
}
function current_admin() {
    return isset($_SESSION['admin']) ? $_SESSION['admin'] : null;
}
?>