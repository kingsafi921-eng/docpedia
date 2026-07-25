<?php
include('connect.php');
if(isset($_POST['add'])){
    $title = $_POST['title'];
    $description = $_POST['area2'];






    $imgtype=$_FILES['img']['type'];
    $imgname=$_FILES['img']['name'];
    $imgtemp=$_FILES['img']['tmp_name'];


    if($imgtype=='image/jpg' or $imgtype=='image/jpeg' or $imgtype=='image/png' or $imgtype=='image/gif') {

        $upload=move_uploaded_file($imgtemp, 'images/uploads/'.$imgname);

        if($upload==true) {

         $insert = mysqli_query($connect, "INSERT INTO `blogs`(`blog_img`, `blog_title`, `blog_desc`) 
         VALUES
         ('$imgname','$title','$description')");
            
            if($insert == true){
                header("location:add-blog.php?success=1");
            }
            else{
            header('location:add-blog.php?success=0');
            }
            
            } else {
            echo "Image Upload Error";
        }
        }
    }

?>