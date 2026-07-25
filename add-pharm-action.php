<?php
include('connect.php');

if(isset($_POST['add'])) {


    $docName=$_POST['name'];
    $address=$_POST['address'];
    $spec=$_POST['spec'];
    $exp=$_POST['exper'];
    $number=$_POST['number'];
    $email=$_POST['email'];
    $about=$_POST['about'];
    $qual=$_POST['qual'];
    $cat=$_POST['category'];





    
    $imgtype=$_FILES['img']['type'];
    $imgname=$_FILES['img']['name'];
    $imgtemp=$_FILES['img']['tmp_name'];

if ($cat=='Doctors') {


    if($imgtype=='image/jpg' or $imgtype=='image/jpeg' or $imgtype=='image/png' or $imgtype=='image/gif') {

        $upload=move_uploaded_file($imgtemp, 'images/uploads/'.$imgname);

        if($upload==true) {
            
            $insert=mysqli_query($connect, "INSERT INTO `doctors`(`img`, `doc_name`, `spec`, `number`, `hos_address`, `exp`, `about`,`qual`) VALUES ('$imgname','$docName','$spec','$number','$address','$exp', '$about','$qual')");

             if($insert) 
            {
            header('location:add-doc.php?success=1');
            }
            else 
            {
            header('location:add-doc.php?success=0');
            }
        } else {
            echo "Image Upload Error";
        }
    }

    }
}
else
echo "not in doctors";

?>