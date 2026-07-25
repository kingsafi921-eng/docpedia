<?php
$title = 'Home';
include('header.php');
include('connect.php');

// Get specialities for dropdown
$owner = mysqli_query($connect, 'SELECT disease_id, disease_name FROM disease ORDER BY disease_name');
?>
<style type="text/css">
    .banner{
        padding-bottom: 18px;
    }
    .category-box {
        transition: all 0.3s;
        cursor: pointer;
    }
    .category-box:hover {
        transform: translateY(-5px);
    }
    .search-btn {
        background: #667eea;
        border: none;
        color: white;
        padding: 12px 30px;
        border-radius: 0 10px 10px 0;
        cursor: pointer;
        font-weight: bold;
        transition: all 0.3s;
    }
    .search-btn:hover {
        background: #5a67d8;
    }
    .search-form-modern {
        display: flex;
        flex-wrap: wrap;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .search-form-modern .search-input {
        flex: 1;
        min-width: 150px;
        padding: 15px 20px;
        border: none;
        border-right: 1px solid #e0e0e0;
        font-size: 15px;
        outline: none;
    }
    .search-form-modern .search-select {
        min-width: 180px;
        padding: 15px 20px;
        border: none;
        border-right: 1px solid #e0e0e0;
        font-size: 15px;
        outline: none;
        background: white;
        cursor: pointer;
    }
    .search-form-modern .search-btn {
        padding: 15px 35px;
        background: #667eea;
        color: white;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    .search-form-modern .search-btn:hover {
        background: #5a67d8;
    }
    @media (max-width: 768px) {
        .search-form-modern .search-input,
        .search-form-modern .search-select {
            border-right: none;
            border-bottom: 1px solid #e0e0e0;
        }
        .search-form-modern .search-btn {
            width: 100%;
        }
    }
</style>

<section class="banner padding-tb-200px sm-ptb-80px background-overlay" style="background-image: url('assets/img/banner_1.jpg');">
    <div class="container z-index-2 position-relative">
        <div class="title">
            <h1 class="text-title-large text-main-color font-weight-300 margin-bottom-15px">Health Directory</h1>
            <h4 class="font-weight-300 text-main-color text-up-small">A better Doctors, Clinics &amp; Labs. We'll help you find it</h4>
        </div>
        <div class="row margin-tb-60px">
            <div class="col-lg-10">
                <div class="listing-search">
                    <form class="search-form-modern" action="search-doctors.php" method="get">
                        <input type="text" name="search" class="search-input" placeholder="Search doctors, labs, or medicines...">
                        <select name="speciality" class="search-select">
                            <option value="">All Specialities</option>
                            <?php 
                            mysqli_data_seek($owner, 0);
                            while($row = mysqli_fetch_array($owner)) { ?>
                                <option value="<?php echo $row['disease_name']; ?>"><?php echo $row['disease_name']; ?></option>
                            <?php } ?>
                        </select>
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="row">
                    <!-- Doctors -->
                    <div class="col-md-3 col-6 sm-mb-30px wow fadeInUp">
                        <a href="doctor.php" class="d-block border-radius-15 hvr-float hvr-sh2">
                            <div class="background-main-color text-white border-radius-15 padding-20px text-center opacity-hover-7 category-box">
                                <div class="icon margin-bottom-15px opacity-7">
                                    <img src="assets/img/icon/categorie-1.png" alt="">
                                </div>
                                Doctors
                            </div>
                        </a>
                    </div>
                    <!-- Labs -->
                    <div class="col-md-3 col-6 wow fadeInUp" data-wow-delay="0.4s">
                        <a href="laboratories.php" class="d-block border-radius-15 hvr-float hvr-sh2">
                            <div class="background-main-color text-white border-radius-15 padding-20px text-center opacity-hover-7 category-box">
                                <div class="icon margin-bottom-15px">
                                    <img src="assets/img/icon/categorie-3.png" alt="">
                                </div>
                                Labs
                            </div>
                        </a>
                    </div>
                    <!-- Pharmacies -->
                    <div class="col-md-3 col-6 wow fadeInUp" data-wow-delay="0.6s">
                        <a href="pharmacies.php" class="d-block border-radius-15 hvr-float hvr-sh2">
                            <div class="background-main-color text-white border-radius-15 padding-20px text-center opacity-hover-7 category-box">
                                <div class="icon margin-bottom-15px opacity-7">
                                    <img src="assets/img/icon/categorie-4.png" alt="">
                                </div>
                                Pharmacies
                            </div>
                        </a>
                    </div>
                    <!-- Medicines - NEW -->
                    <div class="col-md-3 col-6 wow fadeInUp" data-wow-delay="0.8s">
                        <a href="medicines.php" class="d-block border-radius-15 hvr-float hvr-sh2">
                            <div class="background-main-color text-white border-radius-15 padding-20px text-center opacity-hover-7 category-box">
                                <div class="icon margin-bottom-15px opacity-7">
                                    <i class="fas fa-pills" style="font-size: 30px;"></i>
                                </div>
                                Medicines
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="padding-tb-100px">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 sm-mb-30px wow fadeInUp">
                <div class="service text-center opacity-hover-7 hvr-bob">
                    <div class="icon margin-bottom-10px">
                        <img src="assets/img/icon/service-1.png" alt="">
                    </div>
                    <h3 class="text-second-color">Reliable Places</h3>
                    <p class="text-grey-2">Find trusted doctors, clinics, and labs verified by our team for quality healthcare services.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 sm-mb-30px wow fadeInUp" data-wow-delay="0.2s">
                <div class="service text-center opacity-hover-7 hvr-bob">
                    <div class="icon margin-bottom-10px">
                        <img src="assets/img/icon/service-2.png" alt="">
                    </div>
                    <h3 class="text-second-color">High Credibility</h3>
                    <p class="text-grey-2">All healthcare providers are verified and trusted by thousands of patients across Pakistan.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 sm-mb-30px wow fadeInUp" data-wow-delay="0.4s">
                <div class="service text-center opacity-hover-7 hvr-bob">
                    <div class="icon margin-bottom-10px">
                        <img src="assets/img/icon/service-3.png" alt="">
                    </div>
                    <h3 class="text-second-color">Quick Search</h3>
                    <p class="text-grey-2">Find the right doctor, lab, or pharmacy instantly with our smart search system.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 sm-mb-30px wow fadeInUp" data-wow-delay="0.6s">
                <div class="service text-center opacity-hover-7 hvr-bob">
                    <div class="icon margin-bottom-10px">
                        <img src="assets/img/icon/service-4.png" alt="">
                    </div>
                    <h3 class="text-second-color">Know Better</h3>
                    <p class="text-grey-2">Access detailed information about healthcare providers and make informed decisions.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Famous Doctors Section -->
<section class="padding-tb-100px background-grey-1">
    <div class="container">
        <!-- Title -->
        <div class="row justify-content-center margin-bottom-45px">
            <div class="col-lg-10">
                <div class="row">
                    <div class="col-md-4 wow fadeInUp">
                        <h1 class="text-second-color font-weight-300 text-sm-center text-lg-right margin-tb-15px">Famous Doctors</h1>
                    </div>
                    <div class="col-md-6 wow fadeInUp" data-wow-delay="0.2s">
                        <p class="text-grey-2">List of notable or famous physicians from Pakistan, with bios and photos, including the top physicians born in Pakistan and even some popular physicians who immigrated to Pakistan.</p>
                    </div>
                    <div class="col-md-2 wow fadeInUp" data-wow-delay="0.4s">
                        <a href="doctor.php" class="text-main-color margin-tb-15px d-inline-block">
                            <span class="d-block float-left margin-right-10px margin-top-5px">Show All</span> 
                            <i class="far fa-arrow-alt-circle-right text-large margin-top-7px"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- // Title -->

        <div class="row">
            <?php
            $rent = mysqli_query($connect, "SELECT doc_id, doc_image, doc_name, spec, number, hos_address, exp FROM doctors ORDER BY doc_id DESC LIMIT 4");
            
            if(mysqli_num_rows($rent) > 0) {
                while($row = mysqli_fetch_array($rent)) {
            ?>
            <div class="col-lg-3 col-md-6 hvr-bob sm-mb-45px">
                <div class="background-white box-shadow wow fadeInUp" data-wow-delay="0.2s" style="border-radius: 15px; overflow: hidden;">
                    <div class="thum" style="text-align: center; padding: 20px 20px 0 20px; background: #f8f9fa;">
                        <a href="single.php?hid=<?= $row['doc_id']; ?>">
                            <?php if(!empty($row['doc_image']) && file_exists($row['doc_image'])): ?>
                                <img src="<?= $row['doc_image']; ?>" alt="<?= $row['doc_name']; ?>" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 3px solid #667eea;">
                            <?php else: ?>
                                <img src="images/uploads/no.png" alt="<?= $row['doc_name']; ?>" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 3px solid #667eea;">
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="padding-30px" style="text-align: center;">
                        <span class="text-grey-2" style="font-size: 12px;">
                            <i class="fas fa-stethoscope" style="color: #667eea;"></i> <?= $row['spec']; ?>
                        </span>
                        <h5 class="margin-tb-15px">
                            <a class="text-dark" href="single.php?hid=<?= $row['doc_id']; ?>" style="font-weight: bold; font-size: 18px;">
                                <?= $row['doc_name']; ?>
                            </a>
                        </h5>
                        <p style="color: #666; font-size: 13px; margin-bottom: 5px;">
                            <i class="fas fa-hospital" style="color: #667eea;"></i> <?= $row['hos_address']; ?>
                        </p>
                        <p style="color: #666; font-size: 13px;">
                            <i class="fas fa-calendar-alt" style="color: #28a745;"></i> <?= $row['exp']; ?> years experience
                        </p>
                    </div>
                </div>
            </div>
            <?php 
                }
            } else {
                echo '<div class="col-12 text-center"><p>No doctors found.</p></div>';
            }
            ?>
        </div>
    </div>
</section>

<!-- Blog Section -->
<section class="padding-tb-100px background-grey-1">
    <div class="container">
        <!-- Title -->
        <div class="row justify-content-center margin-bottom-45px">
            <div class="col-lg-10">
                <div class="row">
                    <div class="col-md-3 wow fadeInUp">
                        <h1 class="text-second-color font-weight-300 text-sm-center text-lg-right margin-tb-15px">Our Blog</h1>
                    </div>
                    <div class="col-md-7 wow fadeInUp" data-wow-delay="0.2s">
                        <p class="text-grey-2">There are hundreds of medical professionals, fitness gurus, and health experts alike who have something unique to share with the world through their blogs.</p>
                    </div>
                    <div class="col-md-2 wow fadeInUp" data-wow-delay="0.4s">
                        <a href="blogs.php" class="text-main-color margin-tb-15px d-inline-block">
                            <span class="d-block float-left margin-right-10px margin-top-5px">Show All</span> 
                            <i class="far fa-arrow-alt-circle-right text-large margin-top-7px"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- // Title -->

        <div class="row">
            <?php
            $rent = mysqli_query($connect, "SELECT * FROM blogs ORDER BY blog_id DESC LIMIT 2");
            
            if(mysqli_num_rows($rent) > 0) {
                while($row = mysqli_fetch_array($rent)) {
            ?>
            <div class="col-lg-6 sm-mb-45px">
                <div class="background-white thum-hover box-shadow hvr-float full-width wow fadeInUp" style="border-radius: 15px; overflow: hidden;">
                    <div class="float-md-left margin-right-30px thum-xs">
                        <a href="single-blog.php?hid=<?= $row['blog_id']; ?>">
                            <img style="max-width: 222px; height: 150px; object-fit: cover;" src="images/uploads/<?php echo $row['blog_img']; ?>" alt="">
                        </a>
                    </div>
                    <div class="padding-25px">
                        <i class="far fa-folder-open text-main-color"></i>
                        <a href="#" class="text-main-color">News</a>,
                        <a href="#" class="text-main-color">Articles</a>
                        <h3>
                            <a class="d-block h4 text-capitalize margin-bottom-8px" href="single-blog.php?hid=<?= $row['blog_id']; ?>">
                                <?php echo substr($row['blog_title'], 0, 50); ?>...
                            </a>
                        </h3>
                        <span class="margin-right-20px text-extra-small">
                            <i class="far fa-user text-grey-2"></i> By: <a href="#"> Admin</a>
                        </span>
                        <span class="text-extra-small d-block d-sm-none">
                            <i class="far fa-clock text-grey-2"></i> Date: <a href="#"> <?php echo date('M d, Y'); ?></a>
                        </span>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <?php 
                }
            } else {
                echo '<div class="col-12 text-center"><p>No blogs found.</p></div>';
            }
            ?>
        </div>
    </div>
</section>

<?php include('footer.php'); ?>