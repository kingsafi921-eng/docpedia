    <?php include('header.php');?>
<?php include('connect.php'); ?>
<style type="text/css">
    pre{
        background-color: #fff;
    }
    .img-in img{height: 185px;}
</style>

<div class="margin">
    <div id="page-title" class="padding-tb-30px gradient-white">
        <div class="container">
            <ol class="breadcrumb opacity-5">
                <li><a href="#">Home</a></li>
                <li><a href="#">Pages</a></li>
                <li class="active">Doctors List</li>
            </ol>
            <h1 class="font-weight-300">Doctors List</h1>
        </div>
    </div>


    <div class="margin-tb-30px">
        <div class="container">
            <div class="row">

                <div class="col-lg-8">
<?php 
           $limit = 20;  
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
$start_from = ($page-1) * $limit;  
  
$sql = "SELECT * FROM doctors ORDER BY doc_id ASC LIMIT $start_from, $limit";  
$rs_result = mysqli_query($connect, $sql);   
while ($row = mysqli_fetch_array($rs_result)) {  ?>
                    <div class="blog-entry background-white border-1 border-grey-1 margin-bottom-35px">
                        <div class="row no-gutters">
                            <div class="img-in col-lg-5"><a href="single.php?hid=<?= $row['doc_id']; ?>"><img src="images/uploads/<?php if($row['img']){
                                echo $row['img'];}
                                else echo("no.png");
                             ?>" alt=""></a></div>
                            <div class="col-lg-7" style="height: auto;">
                                <div class="padding-25px">
                                    <a class="d-block h4  text-capitalize margin-bottom-8px" href="single.php?hid=<?= $row['doc_id']; ?>"><?php echo $row['doc_name']?></a>
                                    <br>
                                    <p><?php echo $row['spec']?></p>
                                    <br>
                                    <?php if ($row['exp'] == '') { 
                                    echo ""; } else { ?>
                                    <h3><pre><?php echo $row['exp']; ?></pre></h3>
                                    <?php } ?>

                                    <div class="meta">
                                        <span class="margin-right-20px text-extra-small">By : <a href="#" class="text-main-color">Admin</a></span>
                                        <span class="margin-right-20px text-extra-small">Date :  <a href="#" class="text-main-color">July 15, 2016</a></span>
                                        <span class="text-extra-small">Categorie :  <a href="#" class="text-main-color">Doctors</a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <?php }
$sql = "SELECT COUNT(doc_id) FROM doctors";  
$rs_result = mysqli_query($connect, $sql);  
$row = mysqli_fetch_row($rs_result);  
$total_records = $row[0];  
$total_pages = ceil($total_records / $limit);  
$pagLink = "<div class='pagination'>";  
for ($i=1; $i<=$total_pages; $i++) {
             $pagLink .= "<ul class='pagination pagination-md'><li  class='page-item'><a  class='page-link'   href='pagination.php?page=".$i."'>".$i."</a></li></ul>";  
};  
echo $pagLink . "</div>";  
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