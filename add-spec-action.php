<?php
include('connect.php');
if(isset($_POST['add'])){
    $specName = $_POST['specname'];

         $insert = mysqli_query($connect, "INSERT INTO `disease`(`disease_name`) 
         VALUES
         ('$specName')");
            
            if($insert == true){
                header("location:add-speciality.php?success=1");
            }
            else{
            header('location:add-speciality.php?success=0');
            }
        }

?>