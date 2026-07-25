<?php $title = 'Add Blog';
include("connect.php")?>
<?php include("function.php")?>
<?php
session_start();

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
    $title = 'Add Blog';
 include('dashboard-header.php');?>
<?php include('connect.php');?>
<script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script> <script type="text/javascript">
    //<![CDATA[
bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
//]]>
</script>
    <div class="content-wrapper">
        <div class="container-fluid overflow-hidden">
            <div class="row margin-tb-90px margin-lr-10px sm-mrl-0px">
                <!-- Page Title -->
                <div id="page-title" class="padding-30px background-white full-width">
                    <div class="container">
                        <ol class="breadcrumb opacity-5">
                            <li><a href="#">Home</a></li>
                            <li><a href="#">Dashboard</a></li>
                            <li class="active">Add Blog</li>
                        </ol>
                        <h1 class="font-weight-300">Add Blog</h1>
                    </div>
                </div>
                <!-- // Page Title -->

                <div class="margin-tb-45px full-width">
                    <div class="padding-30px background-white border-radius-20 box-shadow">
                        <h3><i class="far fa-list-alt margin-right-10px text-main-color"></i> Basic Informations </h3>
                        <?php if(isset($_GET['success']) && $_GET['success']==0) { ?>
    <div class="alert alert-danger" role="alert">Blog is not publish try again</div>
    <?php } else if(isset($_GET['success']) && $_GET['success']==1) { ?>
    <div class="alert alert-success" role="alert">Published</div>
    <?php } ?>
                        <hr>
                        <form action="add-blog-action.php" method="post" enctype="multipart/form-data">
                            <div class="row">
                            <div class="form-group margin-bottom-20px col-md-6">
                                <label><i class="far fa-list-alt margin-right-10px"></i>Blog Title</label>
                                <input type="text" class="form-control form-control-sm" name="title" id="ListingTitle" placeholder="Blog Title">
                            </div>
                            </div>
                            <div class="form-group shadow-textarea">
                              <label for="exampleFormControlTextarea6">Description</label>
                                <textarea class="form-control z-depth-1" id="exampleFormControlTextarea6" rows="20" placeholder="Write something here..." name="area2" style="width: 100%;">
                                    Some Initial Content was in this textarea
                                    </textarea><br />
                              
                            </div>
                                                        <br>
                            <div class="file btn btn-lg btn-primary file btn btn-lg border-2 border-radius-15 padding-15px box-shadow">
                            Upload
                            <input type="file" class="ifile " name="img"/>
                        </div>
                            <br>
                            <br>
                            <input type="submit" name="add" class="btn btn-lg border-2  btn-primary btn-block border-radius-15 padding-15px box-shadow" value="Publish">

                        </form>
                    </div>
                </div>

                
            </div>
        </div>

<?php include("dashboard-footer.php"); }else{
header("location:index.php");
}?>