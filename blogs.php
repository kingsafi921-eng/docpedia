<?php 
$title = 'Health Blogs';
$keyword = 'blogs,health blogs,health,mental health,health questions,fitness blog,health topics';
include("header.php"); 
include("connect.php");
?>

<div class="margin">
    
    <!-- ===== PAGE TITLE ===== -->
    <div id="page-title" class="padding-tb-30px gradient-white">
        <div class="container">
            <ol class="breadcrumb opacity-5">
                <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="blogs.php">Blog</a></li>
                <li class="active">All Blogs</li>
            </ol>
            <h1 class="font-weight-300">Health Blogs</h1>
            <p class="text-muted">Read latest health tips, medical news, and wellness articles</p>
        </div>
    </div>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="margin-tb-30px">
        <div class="container">
            <div class="row">
                
                <!-- ===== LEFT COLUMN - BLOG LIST ===== -->
                <div class="col-lg-8">
                    
                    <?php 
                    // Get all blogs
                    $blogs_query = mysqli_query($connect, "SELECT * FROM blogs ORDER BY blog_id DESC");
                    
                    if(mysqli_num_rows($blogs_query) > 0) {
                        while($row = mysqli_fetch_array($blogs_query)) { 
                    ?>
                    
                    <div class="blog-entry background-white border-1 border-grey-1 margin-bottom-35px border-radius-10 overflow-hidden shadow-hover">
                        <div class="row no-gutters">
                            
                            <!-- Blog Image -->
                            <div class="img-in col-lg-5">
                                <a href="single-blog.php?hid=<?php echo $row['blog_id']; ?>">
                                    <?php 
                                    $blog_img = !empty($row['blog_img']) ? $row['blog_img'] : 'no-image.jpg';
                                    
                                    // Check multiple possible image locations
                                    $image_found = false;
                                    $image_path = '';
                                    
                                    // Check in images/uploads/
                                    if(file_exists("images/uploads/".$blog_img)) {
                                        $image_found = true;
                                        $image_path = "images/uploads/".$blog_img;
                                    }
                                    // Check in uploads/
                                    elseif(file_exists("uploads/".$blog_img)) {
                                        $image_found = true;
                                        $image_path = "uploads/".$blog_img;
                                    }
                                    // Check in assets/img/
                                    elseif(file_exists("assets/img/".$blog_img)) {
                                        $image_found = true;
                                        $image_path = "assets/img/".$blog_img;
                                    }
                                    // Check direct path
                                    elseif(file_exists($blog_img)) {
                                        $image_found = true;
                                        $image_path = $blog_img;
                                    }
                                    
                                    if($image_found) { 
                                    ?>
                                        <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($row['blog_title']); ?>" class="img-fluid blog-list-image">
                                    <?php } else { ?>
                                        <img src="assets/img/no-image.png" alt="No Image" class="img-fluid blog-list-image">
                                    <?php } ?>
                                </a>
                            </div>
                            
                            <!-- Blog Content -->
                            <div class="col-lg-7">
                                <div class="padding-25px">
                                    <a class="d-block h4 text-capitalize margin-bottom-8px text-dark font-weight-700" href="single-blog.php?hid=<?php echo $row['blog_id']; ?>">
                                        <?php echo htmlspecialchars($row['blog_title']); ?>
                                    </a>
                                    <p class="text-muted" style="font-size:14px;line-height:1.6;">
                                        <?php 
                                        // Show short description
                                        $description = isset($row['blog_description']) ? $row['blog_description'] : (isset($row['blog_content']) ? $row['blog_content'] : '');
                                        echo htmlspecialchars(substr(strip_tags($description), 0, 120)) . '...'; 
                                        ?>
                                    </p>
                                    <div class="meta">
                                        <span class="margin-right-20px text-extra-small">
                                            <i class="fas fa-user text-main-color"></i> 
                                            <a href="#" class="text-main-color">Admin</a>
                                        </span>
                                        <span class="margin-right-20px text-extra-small">
                                            <i class="fas fa-calendar-alt text-main-color"></i> 
                                            <a href="#" class="text-main-color">
                                                <?php 
                                                $date = isset($row['blog_date']) ? $row['blog_date'] : (isset($row['created_at']) ? $row['created_at'] : date('Y-m-d'));
                                                echo date('M d, Y', strtotime($date)); 
                                                ?>
                                            </a>
                                        </span>
                                        <?php if(isset($row['category']) && !empty($row['category'])): ?>
                                        <span class="text-extra-small">
                                            <i class="fas fa-tag text-main-color"></i> 
                                            <a href="#" class="text-main-color"><?php echo htmlspecialchars($row['category']); ?></a>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <a href="single-blog.php?hid=<?php echo $row['blog_id']; ?>" class="btn btn-sm btn-outline-primary mt-3 border-radius-30">
                                        Read More <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    
                    <?php 
                        } // end while
                    } else { 
                    ?>
                    
                    <!-- No Blogs Found -->
                    <div class="text-center padding-50px background-white border-radius-10">
                        <i class="fas fa-blog" style="font-size:60px;color:#ddd;display:block;margin-bottom:20px;"></i>
                        <h4>No Blogs Found</h4>
                        <p class="text-muted">Stay tuned for upcoming health articles and medical news.</p>
                    </div>
                    
                    <?php } ?>
                    
                    <!-- ===== PAGINATION ===== -->
                    <nav aria-label="Page navigation" class="margin-top-30px">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Previous</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                    
                </div>
                
                <!-- ===== RIGHT COLUMN - SIDEBAR ===== -->
                <div class="col-lg-4">
                    
                    <!-- ===== SEARCH ===== -->
                    <div class="margin-bottom-30px">
                        <div class="padding-30px background-white border-radius-10 shadow-sm">
                            <h4><i class="fas fa-search margin-right-10px text-main-color"></i> Search</h4>
                            <hr>
                            <form method="GET" action="blogs.php">
                                <div class="input-group mb-3">
                                    <input type="text" name="search" class="form-control border-radius-0" placeholder="Search blogs..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary text-white background-main-color border-radius-0" type="submit">
                                            <i class="fas fa-search"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- ===== CATEGORIES ===== -->
                    <div class="widget widget_categories">
                        <div class="margin-bottom-30px">
                            <div class="padding-30px background-white border-radius-10 shadow-sm">
                                <h4><i class="far fa-folder-open margin-right-10px text-main-color"></i> Categories</h4>
                                <hr>
                                <ul class="list-unstyled">
                                    <li class="padding-5px-0"><a href="index.php" class="text-muted hover-main-color"><i class="fas fa-chevron-right text-main-color" style="font-size:10px;"></i> HOME</a></li>
                                    <li class="padding-5px-0"><a href="doctor.php" class="text-muted hover-main-color"><i class="fas fa-chevron-right text-main-color" style="font-size:10px;"></i> DOCTORS</a></li>
                                    <li class="padding-5px-0"><a href="laboratories.php" class="text-muted hover-main-color"><i class="fas fa-chevron-right text-main-color" style="font-size:10px;"></i> LABS</a></li>
                                    <li class="padding-5px-0"><a href="pharmacies.php" class="text-muted hover-main-color"><i class="fas fa-chevron-right text-main-color" style="font-size:10px;"></i> PHARMACIES</a></li>
                                    <li class="padding-5px-0"><a href="medicines.php" class="text-muted hover-main-color"><i class="fas fa-chevron-right text-main-color" style="font-size:10px;"></i> MEDICINES</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- ===== RECENT POSTS ===== -->
                    <div class="widget widget_categories">
                        <div class="margin-bottom-30px">
                            <div class="padding-30px background-white border-radius-10 shadow-sm">
                                <h4><i class="fas fa-clock margin-right-10px text-main-color"></i> Recent Posts</h4>
                                <hr>
                                <?php 
                                $recent_blogs = mysqli_query($connect, "SELECT * FROM blogs ORDER BY blog_id DESC LIMIT 4");
                                if(mysqli_num_rows($recent_blogs) > 0) {
                                    while($row = mysqli_fetch_array($recent_blogs)) { 
                                ?>
                                <div class="row margin-bottom-15px recent-post-item">
                                    <div class="col-4">
                                        <a href="single-blog.php?hid=<?php echo $row['blog_id']; ?>">
                                            <?php 
                                            $blog_img = !empty($row['blog_img']) ? $row['blog_img'] : 'no-image.jpg';
                                            
                                            // Check multiple possible image locations
                                            $image_found = false;
                                            $image_path = '';
                                            
                                            if(file_exists("images/uploads/".$blog_img)) {
                                                $image_found = true;
                                                $image_path = "images/uploads/".$blog_img;
                                            } elseif(file_exists("uploads/".$blog_img)) {
                                                $image_found = true;
                                                $image_path = "uploads/".$blog_img;
                                            } elseif(file_exists("assets/img/".$blog_img)) {
                                                $image_found = true;
                                                $image_path = "assets/img/".$blog_img;
                                            } elseif(file_exists($blog_img)) {
                                                $image_found = true;
                                                $image_path = $blog_img;
                                            }
                                            
                                            if($image_found) { 
                                            ?>
                                                <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($row['blog_title']); ?>" class="border-radius-10 recent-post-img">
                                            <?php } else { ?>
                                                <img src="assets/img/no-image.png" alt="No Image" class="border-radius-10 recent-post-img">
                                            <?php } ?>
                                        </a>
                                    </div>
                                    <div class="col-8">
                                        <a href="single-blog.php?hid=<?php echo $row['blog_id']; ?>" class="text-dark font-weight-600" style="font-size:13px;">
                                            <?php echo htmlspecialchars(substr($row['blog_title'], 0, 40)) . '...'; ?>
                                        </a>
                                        <div class="text-extra-small text-muted">
                                            <i class="fas fa-calendar-alt"></i> 
                                            <?php 
                                            $date = isset($row['blog_date']) ? $row['blog_date'] : (isset($row['created_at']) ? $row['created_at'] : date('Y-m-d'));
                                            echo date('M d, Y', strtotime($date)); 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                    } // end while
                                } else { 
                                ?>
                                <p class="text-muted text-center">No recent posts.</p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <!-- ===== TAGS ===== -->
                    <div class="widget widget_categories">
                        <div class="margin-bottom-30px">
                            <div class="padding-30px background-white border-radius-10 shadow-sm">
                                <h4><i class="fas fa-tags margin-right-10px text-main-color"></i> Tags</h4>
                                <hr>
                                <div class="tags-container">
                                    <a href="#" class="btn btn-sm btn-outline-secondary m-1 border-radius-30">Health</a>
                                    <a href="#" class="btn btn-sm btn-outline-secondary m-1 border-radius-30">Medical</a>
                                    <a href="#" class="btn btn-sm btn-outline-secondary m-1 border-radius-30">Fitness</a>
                                    <a href="#" class="btn btn-sm btn-outline-secondary m-1 border-radius-30">Nutrition</a>
                                    <a href="#" class="btn btn-sm btn-outline-secondary m-1 border-radius-30">Mental Health</a>
                                    <a href="#" class="btn btn-sm btn-outline-secondary m-1 border-radius-30">COVID-19</a>
                                    <a href="#" class="btn btn-sm btn-outline-secondary m-1 border-radius-30">Wellness</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- ===== END SIDEBAR ===== -->
                
            </div>
        </div>
    </div>

</div>

<!-- ======================================================
    STYLES
    ====================================================== -->
<style>
    /* ===== BLOG IMAGE STYLES ===== */
    .blog-list-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        transition: all 0.5s ease;
    }
    
    .blog-list-image:hover {
        transform: scale(1.03);
    }
    
    .recent-post-img {
        width: 100%;
        height: 80px;
        object-fit: cover;
        transition: all 0.3s ease;
    }
    
    .recent-post-img:hover {
        transform: scale(1.05);
    }
    
    .img-in {
        overflow: hidden;
        position: relative;
    }
    
    .img-in::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(102,126,234,0.1), rgba(118,75,162,0.1));
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 1;
        pointer-events: none;
    }
    
    .blog-entry:hover .img-in::before {
        opacity: 1;
    }

    /* ===== SHADOW AND HOVER EFFECTS ===== */
    .shadow-hover {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 15px rgba(0,0,0,0.04);
    }
    
    .shadow-hover:hover {
        box-shadow: 0 15px 50px rgba(0,0,0,0.1) !important;
        transform: translateY(-6px);
    }

    .background-main-color {
        background: #667eea !important;
    }
    
    .text-main-color {
        color: #667eea !important;
    }
    
    .hover-main-color:hover {
        color: #667eea !important;
        text-decoration: none;
    }
    
    .border-radius-10 {
        border-radius: 10px !important;
    }
    
    .border-radius-30 {
        border-radius: 30px !important;
    }
    
    .padding-5px-0 {
        padding: 5px 0 !important;
    }
    
    .padding-50px {
        padding: 50px !important;
    }
    
    .shadow-sm {
        box-shadow: 0 2px 8px rgba(0,0,0,0.04) !important;
    }

    /* ===== BUTTON STYLES ===== */
    .btn-outline-primary {
        color: #667eea !important;
        border-color: #667eea !important;
        transition: all 0.3s ease;
        font-weight: 600;
    }
    
    .btn-outline-primary:hover {
        background: #667eea !important;
        color: white !important;
        transform: translateX(4px);
    }
    
    .btn-outline-secondary {
        color: #6c757d !important;
        border-color: #dee2e6 !important;
        transition: all 0.3s ease;
    }
    
    .btn-outline-secondary:hover {
        background: #667eea !important;
        border-color: #667eea !important;
        color: white !important;
        transform: translateY(-2px);
    }

    /* ===== PAGINATION ===== */
    .page-item.active .page-link {
        background: #667eea !important;
        border-color: #667eea !important;
        color: white !important;
    }
    
    .page-link {
        color: #667eea !important;
        transition: all 0.3s ease;
    }
    
    .page-link:hover {
        color: #5a67d8 !important;
        transform: translateY(-2px);
    }

    /* ===== META STYLES ===== */
    .meta .text-extra-small {
        font-size: 12px;
        color: #9ca3af;
    }
    
    .meta .text-extra-small a {
        transition: all 0.3s ease;
    }
    
    .meta .text-extra-small a:hover {
        text-decoration: none;
        color: #667eea !important;
    }

    /* ===== TAGS ===== */
    .tags-container .btn {
        font-size: 12px;
        padding: 4px 14px;
        transition: all 0.3s ease;
    }
    
    .tags-container .btn:hover {
        background: #667eea !important;
        border-color: #667eea !important;
        color: white !important;
        transform: translateY(-2px);
    }

    /* ===== SIDEBAR LINKS ===== */
    .widget ul li a {
        transition: all 0.3s ease;
        display: block;
        padding: 5px 0;
    }
    
    .widget ul li a:hover {
        padding-left: 8px;
        color: #667eea !important;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {
        .blog-list-image {
            height: 200px;
        }
        .img-in {
            max-height: 200px;
        }
    }

    @media (max-width: 768px) {
        .blog-list-image {
            height: 180px;
        }
        .img-in {
            max-height: 180px;
        }
        .recent-post-img {
            height: 60px;
        }
        .padding-30px {
            padding: 20px !important;
        }
        .row.no-gutters {
            flex-direction: column;
        }
        .img-in {
            width: 100%;
        }
        .blog-entry .col-lg-7 {
            width: 100%;
            max-width: 100%;
            flex: 0 0 100%;
        }
        .padding-25px {
            padding: 20px !important;
        }
    }

    @media (max-width: 480px) {
        .blog-list-image {
            height: 150px;
        }
        .img-in {
            max-height: 150px;
        }
        .recent-post-img {
            height: 50px;
        }
        .padding-30px {
            padding: 15px !important;
        }
        .padding-25px {
            padding: 15px !important;
        }
        .blog-entry .h4 {
            font-size: 18px;
        }
        .meta .text-extra-small {
            font-size: 11px;
        }
        .meta span {
            display: inline-block;
            margin-right: 10px !important;
        }
    }
</style>

<?php include("footer.php"); ?>