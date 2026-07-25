<?php
include('connect.php');
    $search = $_GET['search'];
    
    $spec = mysqli_query($connect,"SELECT * FROM `doctors` where doctors.spec = '$search'");
 	while($row = mysqli_fetch_array($spec)) { 
        echo $row['doc_name'];
        echo "<br>";
        echo $row['spec'];
           } 

?>

