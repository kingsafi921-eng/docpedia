<?php
// For XAMPP - using root account and docpedia database
$connect = mysqli_connect("localhost", "root", "", "docpedia");

if(mysqli_connect_error()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    die();
}

// Optional: Set charset
mysqli_set_charset($connect, "utf8");

// ==========================================
// IMPORTANT: NO session_start() HERE!
// Session should be started in header files
// ==========================================

// Check if functions already exist before declaring
if(!function_exists('tableExists')) {
    function tableExists($connect, $table) {
        $check = mysqli_query($connect, "SHOW TABLES LIKE '$table'");
        return mysqli_num_rows($check) > 0;
    }
}

if(!function_exists('columnExists')) {
    function columnExists($connect, $table, $column) {
        $check = mysqli_query($connect, "SHOW COLUMNS FROM $table LIKE '$column'");
        return mysqli_num_rows($check) > 0;
    }
}

if(!function_exists('addColumnIfNotExists')) {
    function addColumnIfNotExists($connect, $table, $column, $definition) {
        if(!columnExists($connect, $table, $column)) {
            $query = "ALTER TABLE $table ADD COLUMN $column $definition";
            return mysqli_query($connect, $query);
        }
        return true;
    }
}
?>