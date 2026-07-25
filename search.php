<?php
include('connect.php'); 
    $title = 'Search';
 
include("header.php");

?>


<style type="text/css">
    .img-in img{
        height: 185px;
    }
</style>
<div class="margin">
	
    <div id="page-title" class="padding-tb-30px gradient-white">
        <div class="container">
            <ol class="breadcrumb opacity-5">
                <li><a href="#">Home</a></li>
                <li class="active">Search</li>
            </ol>
            <h1 class="font-weight-300">Search List</h1>
        </div>
    </div>


    <div class="margin-tb-30px">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <?php 
                    $search = $_GET['search'];
    
                    
            $doc = mysqli_query($connect,"SELECT * FROM `doctors` where doctors.spec = '$search' ORDER BY doc_id DESC");
            while($row = mysqli_fetch_array($doc)){ ?>
                    <div class="blog-entry background-white border-1 border-grey-1 margin-bottom-35px">
                        <div class="row no-gutters">
                            <div class="img-in col-lg-5"><a href="single.php?hid=<?= $row['doc_id']; ?>"><img src="images/uploads/<?php if($row['img']){
                                echo $row['img'];}
                                else echo("no.png");
                             ?>" alt=""></a></div>
                            <div class="col-lg-7">
                                <div class="padding-25px">
                                    <a class="d-block h4  text-capitalize margin-bottom-8px" href="single.php?hid=<?= $row['doc_id']; ?>"><?php echo $row['doc_name']?></a>
                                    <p><?php echo $row['spec']?></p>
                                    <div class="meta">
                                        <span class="margin-right-20px text-extra-small">By : <a href="#" class="text-main-color">Admin</a></span>
                                        <span class="margin-right-20px text-extra-small">Date :  <a href="#" class="text-main-color"><?php echo "" . date("Y/m/d") . ""; ?></a></span>
                                        <span class="text-extra-small">Category :  <a href="doctor.php" class="text-main-color">Doctor</a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>

<?php }



 ?>
                

                </div>
                <div class="col-lg-4">

                    <div class="margin-bottom-30px">
                        <div class="padding-30px background-white border-radius-10">
                            <h4><i class="fas fa-search margin-right-10px text-main-color"></i> Search</h4>
                            <hr>
                            <div class="input-group mb-3">
                                <input type="text" value="Search..." class="form-control border-radius-0">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary text-white background-main-color border-radius-0" type="button">Search</button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="widget widget_categories">
                        <div class="margin-bottom-30px">
                            <div class="padding-30px background-white border-radius-10">
                                <h4><i class="far fa-folder-open margin-right-10px text-main-color"></i> Categories</h4>
                                <hr>
                                <ul>
                                    <li><a href="#">Tech</a></li>
                                    <li><a href="#">Gallary</a></li>
                                    <li><a href="#">UI Design </a></li>
                                    <li><a href="#">Shop</a></li>
                                    <li><a href="#">Wordpress  </a></li>
                                    <li><a href="#">Cultur</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="widget widget_categories">
                        <div class="margin-bottom-30px">
                            <div class="padding-30px background-white border-radius-10">
                                <h4><i class="fab fa-instagram margin-right-10px text-main-color"></i> Instagram</h4>
                                <hr>
                                <div class="row">
                                    <div class="col-6 margin-bottom-20px"><a href="#"><img class="border-radius-10" src="assets/img/instagram-1.jpg" alt=""></a></div>
                                    <div class="col-6 margin-bottom-20px"><a href="#"><img class="border-radius-10" src="assets/img/instagram-2.jpg" alt=""></a></div>
                                    <div class="col-6 margin-bottom-20px"><a href="#"><img class="border-radius-10" src="assets/img/instagram-3.jpg" alt=""></a></div>
                                    <div class="col-6 margin-bottom-20px"><a href="#"><img class="border-radius-10" src="assets/img/instagram-4.jpg" alt=""></a></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>


</div>
<?php include('footer.php');?>

