<?php include('header.php');?>
<?php include('connect.php');?>




 <?php 
            $img = mysqli_query($connect,"SELECT img,doc_name,spec,exp,doc_id  FROM `doctors`");
            while($row = mysqli_fetch_array($img)){ ?>




<?php include('footer.php');?>










<div class="container margin">
  <div class="row">
    <div class="doc">
    	
    	<?php 
            $img = mysqli_query($connect,"SELECT img,doc_name,spec,exp,doc_id  FROM `doctors`");
            while($row = mysqli_fetch_array($img)){ ?>
            	<div class="doc col-md-10 infoHeight knockout-around ">
            		<a class="mar" href="single.php?hid=<?= $row['doc_id']; ?>">
            	<img src="images/uploads/<?php echo $row['img']?>">
            	</a>
            	<div class="col-md-9 mar" style="float: right;">
            		<a href="single.php?hid=<?= $row['doc_id']; ?>">
            	<h1><?php echo $row['doc_name']?></h1>
            	</a>
            <p><?php echo $row['spec'] ?></p><br>
            <p><?php echo $row['exp'] ?> experince</p>
            </div>
            </div>
            <hr>


            <?php
            }
            ?>
    </div>
  </div>


<?php include('footer.php');?>

	<?php /*
            $spec = mysqli_query($connect,"SELECT doc_name FROM `doctors`");
            while($row = mysqli_fetch_array($spec)){
            	echo "Name" . $row["doc_name"] . "<br>";
            }



<div class="container margin">
	<div class="row">
	<div class="docSpec col-md-2 float">
		<div class="docImg">
			<div class=" docImg">
				<?php 
            $img = mysqli_query($connect,"SELECT img FROM `doctors`");
            while($row = mysqli_fetch_array($img)){ ?>
            	<img src="images/uploads/<?php echo $row['img']?>">
            <?php
            }
            ?>
			</div>
			
		</div>
		<div class="docInfo col-md-6 float">
			hadhahsdfhasdfhhas hadfhjads adksjfhajkdsf adsfads
		</div>
			
			
		
	</div>
	</div>
</div>





            */









            ?>
