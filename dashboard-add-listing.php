<?php include("connect.php")?>
<?php include("function.php")?>
<?php
session_start();
session_regenerate_id( true); 

if(!isset($_SESSION['admin_id'])) {
    header('location:page-login.php');
}elseif(isset($_SESSION['admin_id']) && $_SESSION['role'] == 1){
    


/*$uid = $_SESSION['uid'];
$query = mysqli_query($connect, "select * from admin where admin_id = '$admin_id'");
$record = mysqli_fetch_array($query);*/
$user2 = fetch($connect,$_SESSION['admin_id']);
    $user = fetch($connect, $_SESSION['admin_id']);
?>



<?php 
    $title = 'Dashboard Adding';
  include('dashboard-header.php');?>









    <div class="content-wrapper">
        <div class="container-fluid overflow-hidden">
            <div class="row margin-tb-90px margin-lr-10px sm-mrl-0px">

               <div class="col-lg-7">
                    <div class="row">
                        <div class="col-md-3 col-6 sm-mb-30px wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                            <a href="add-doc.php" class="d-block border-radius-15 hvr-float hvr-sh2">
                                <div class="background-main-color text-white border-radius-15 padding-20px text-center opacity-hover-7">
                                    <div class="icon margin-bottom-15px opacity-7">
                                        <img src="assets/img/icon/categorie-1.png" alt="">
                                    </div>
                                    Add Doctors
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-6 wow fadeInUp" data-wow-delay="0.4s" style="visibility: visible; animation-delay: 0.4s; animation-name: fadeInUp;">
                            <a href="add-lab.php" class="d-block border-radius-15 hvr-float hvr-sh2">
                                <div class="background-main-color text-white border-radius-15 padding-20px text-center opacity-hover-7">
                                    <div class="icon margin-bottom-15px">
                                        <img src="assets/img/icon/categorie-3.png" alt="">
                                    </div>
                                    Add Labs
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-6 wow fadeInUp" data-wow-delay="0.6s" style="visibility: visible; animation-delay: 0.6s; animation-name: fadeInUp;">
                            <a href="add-pharmacy.php" class="d-block border-radius-15 hvr-float hvr-sh2">
                                <div class="background-main-color text-white border-radius-15 padding-20px text-center opacity-hover-7">
                                    <div class="icon margin-bottom-15px opacity-7">
                                        <img src="assets/img/icon/categorie-4.png" alt="">
                                    </div>
                                    Add Pharmacies
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3 col-6 sm-mb-30px wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                            <a href="add-blog.php" class="d-block border-radius-15 hvr-float hvr-sh2">
                                <div class="background-main-color text-white border-radius-15 padding-20px text-center opacity-hover-7">
                                    <div class="icon margin-bottom-15px opacity-7">
                                        <img src="assets/img/icon/categorie-1.png" alt="">
                                    </div>
                                    Add Blogs
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                    </div>



                </div>
            </div>


        </div>
        <!-- /.container-fluid-->
        <!-- /.content-wrapper-->
       
<?php include("dashboard-footer.php"); }else{
header("location:index.php");
}?>