<?php include('header.php');?>
<?php include('connect.php');?>
<?php
if(!isset($_GET['hid'])){
    header('location:index.php');
}

?>
<div class="container margin">
	

<div class="row">
    <div class="doc">
    	
    	<?php 
    	$hid = $_GET['hid'];
            $info = mysqli_query($connect,"SELECT * FROM `doctors` where doctors.doc_id = '$hid'");
            while($row = mysqli_fetch_array($info)){ ?>
            	<div class="doc col-md-11 infoHeight" style="width: 1350px; float: left;">
            	<img src="images/uploads/<?php echo $row['img']?>">
            	<div class="col-md-3" style="float: right;">hello</div>
            	<div class="col-md-7" style="float: right; border-right: solid 1px black;">
            	<h1><?php echo $row['doc_name']?></h1>
            <h4><?php echo $row['spec'] ?></h4><br>
            <h4><?php echo $row['exp'] ?> experince</h4>


            </div>
            <hr>
            </div>


            <?php
            }
            ?>
    </div>




</div>
<?php include('footer.php');?>

