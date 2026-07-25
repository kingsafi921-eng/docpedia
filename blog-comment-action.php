<?php
include('connect.php');


if(isset($_POST['com'])){    
    $id = filter_var($_POST['addcom'] , FILTER_SANITIZE_STRING);   
    $name = filter_var($_POST['name'] , FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'] , FILTER_SANITIZE_STRING);
    $comment = filter_var($_POST['comment'] , FILTER_SANITIZE_STRING);

  $insert = mysqli_query($connect, "INSERT INTO `blog_comments`(`blog_id`, `name`, `email`, `comment`) 
         VALUES
         ('$id','$name','$email','$comment')");

            
            if($insert == true){
                header("location:single-blog.php?hid=$id");
            }
            else{
            header('location:single-blog.php?hid=$id');
            }
        }

?>