<?php

function fetch($connect, $admin_id){
    
    $query = mysqli_query($connect, "select * from admin where admin_id = '$admin_id'");
    
    $record = mysqli_fetch_array($query);
    
    return $record;
    
}


?>