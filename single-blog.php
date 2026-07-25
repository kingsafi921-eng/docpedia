<?php 
include("connect.php"); 

// Get blog ID
$hid = isset($_GET['hid']) ? intval($_GET['hid']) : 0;

if($hid == 0) {
    header('Location: blogs.php');
    exit();
}

// Get blog details
$info = mysqli_query($connect, "SELECT * FROM blogs WHERE blog_id = '$hid'");
$blog = mysqli_fetch_assoc($info);

if(!$blog) {
    header('Location: blogs.php');
    exit();
}

$title = $blog['blog_title'];
include("header.php"); 
?>

<style type="text/css">
    .single-blog-page {
        padding-top: 20px;
    }
    hr {
        margin-top: 1rem;
        margin-bottom: 1rem;
        border: 0;
        border-top: 1px solid rgba(0,0,0,.1);
    }
    .entry-content {
        font-family: 'Poppins', sans-serif;
        font-size: 15px;
        line-height: 1.8;
        color: #4a5568;
    }
    .entry-content p {
        margin-bottom: 1.2rem;
    }
    .entry-content img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        margin: 15px 0;
    }
    .blog-featured-image {
        width: 100%;
        max-height: 450px;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .comment-item {
        border-bottom: 1px solid #e5e7eb;
        padding: 15px 0;
        transition: all 0.3s ease;
    }
    .comment-item:last-child {
        border-bottom: none;
    }
    .comment-item:hover {
        background: #f9fafb;
        padding-left: 10px;
        border-radius: 8px;
    }
    .comment-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        float: left;
        margin-right: 15px;
    }
    .comment-body {
        margin-left: 60px;
    }
    .comment-author {
        font-weight: 600;
        color: #1a1a2e;
        font-size: 14px;
    }
    .comment-date {
        font-size: 12px;
        color: #9ca3af;
    }
    .comment-text {
        margin-top: 6px;
        color: #4b5563;
        font-size: 14px;
        line-height: 1.6;
    }
    .delete-comment {
        color: #ef4444;
        font-size: 14px;
        transition: all 0.3s ease;
        float: right;
    }
    .delete-comment:hover {
        color: #dc2626;
        transform: scale(1.1);
    }
    .btn-submit-comment {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
    }
    .btn-submit-comment:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }
    .meta-info {
        font-size: 13px;
        color: #9ca3af;
    }
    .meta-info i {
        color: #667eea;
        margin-right: 4px;
    }
    .meta-info a {
        color: #667eea;
        text-decoration: none;
    }
    .meta-info a:hover {
        text-decoration: underline;
    }
    .breadcrumb-custom {
        background: transparent;
        padding: 0;
    }
    .breadcrumb-custom li {
        display: inline-block;
        font-size: 14px;
    }
    .breadcrumb-custom li a {
        color: #667eea;
        text-decoration: none;
    }
    .breadcrumb-custom li a:hover {
        text-decoration: underline;
    }
    .breadcrumb-custom li.active {
        color: #4b5563;
    }
    .breadcrumb-custom li+li::before {
        content: '/';
        padding: 0 8px;
        color: #d1d5db;
    }
    .shadow-hover {
        transition: all 0.3s ease;
    }
    .shadow-hover:hover {
        box-shadow: 0 10px 40px rgba(0,0,0,0.08) !important;
    }
    .sidebar-widget {
        background: white;
        padding: 25px;
        border-radius: 10px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .sidebar-widget h4 {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 15px;
    }
    .sidebar-widget h4 i {
        color: #667eea;
        margin-right: 8px;
    }
    .sidebar-widget hr {
        margin: 12px 0 18px;
    }
    .sidebar-widget ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .sidebar-widget ul li {
        padding: 6px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .sidebar-widget ul li:last-child {
        border-bottom: none;
    }
    .sidebar-widget ul li a {
        color: #6b7280;
        text-decoration: none;
        transition: all 0.3s ease;
        display: block;
    }
    .sidebar-widget ul li a:hover {
        color: #667eea;
        padding-left: 8px;
    }
    .sidebar-widget ul li a i {
        font-size: 10px;
        color: #667eea;
        margin-right: 6px;
    }
    .recent-post-item {
        display: flex;
        gap: 12px;
        margin-bottom: 15px;
        align-items: center;
    }
    .recent-post-item:last-child {
        margin-bottom: 0;
    }
    .recent-post-item img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 8px;
        flex-shrink: 0;
    }
    .recent-post-item .post-info {
        flex: 1;
    }
    .recent-post-item .post-info a {
        font-weight: 600;
        font-size: 13px;
        color: #1a1a2e;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .recent-post-item .post-info a:hover {
        color: #667eea;
    }
    .recent-post-item .post-info .post-date {
        font-size: 11px;
        color: #9ca3af;
    }
    .tags-container .btn-tag {
        font-size: 12px;
        padding: 4px 14px;
        border-radius: 30px;
        border: 1px solid #e5e7eb;
        background: white;
        color: #6b7280;
        margin: 3px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .tags-container .btn-tag:hover {
        background: #667eea;
        border-color: #667eea;
        color: white;
    }
    .search-input-group {
        display: flex;
    }
    .search-input-group input {
        flex: 1;
        padding: 10px 15px;
        border: 1px solid #e5e7eb;
        border-radius: 8px 0 0 8px;
        font-size: 14px;
        outline: none;
        transition: all 0.3s ease;
    }
    .search-input-group input:focus {
        border-color: #667eea;
    }
    .search-input-group button {
        padding: 10px 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 0 8px 8px 0;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
    }
    .search-input-group button:hover {
        opacity: 0.9;
    }
    .no-comments {
        text-align: center;
        padding: 30px;
        color: #9ca3af;
    }
    .no-comments i {
        font-size: 40px;
        display: block;
        margin-bottom: 10px;
        color: #e5e7eb;
    }
    .empty-state {
        text-align: center;
        padding: 50px 20px;
    }
    .empty-state i {
        font-size: 60px;
        color: #ddd;
        display: block;
        margin-bottom: 15px;
    }
    .empty-state h4 {
        color: #4b5563;
    }
    .empty-state p {
        color: #9ca3af;
    }

    @media (max-width: 768px) {
        .blog-featured-image {
            max-height: 250px;
        }
        .comment-body {
            margin-left: 0;
        }
        .comment-avatar {
            float: none;
            display: block;
            margin: 0 auto 10px;
        }
        .delete-comment {
            float: none;
            display: inline-block;
            margin-top: 5px;
        }
        .recent-post-item {
            flex-direction: column;
            text-align: center;
        }
        .recent-post-item img {
            width: 100%;
            height: 150px;
        }
        .sidebar-widget {
            padding: 18px;
        }
    }
</style>

<div class="single-blog-page margin">
    
    <!-- ===== PAGE TITLE ===== -->
    <div id="page-title" class="padding-tb-30px gradient-white">
        <div class="container">
            <ol class="breadcrumb-custom">
                <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="blogs.php">Blog</a></li>
                <li class="active"><?php echo htmlspecialchars($blog['blog_title']); ?></li>
            </ol>
            <h1 class="font-weight-300 margin-top-15px"><?php echo htmlspecialchars($blog['blog_title']); ?></h1>
        </div>
    </div>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="margin-tb-30px">
        <div class="container">
            <div class="row">

                <!-- ===== LEFT COLUMN - BLOG CONTENT ===== -->
                <div class="col-lg-8">
                    
                    <!-- ===== BLOG POST ===== -->
                    <div class="border-radius-10 background-white margin-bottom-35px padding-30px shadow-hover">
                        
                        <!-- Blog Title -->
                        <h4 class="font-weight-700">
                            <i class="fas fa-file-alt margin-right-10px text-main-color"></i>
                            <?php echo htmlspecialchars($blog['blog_title']); ?>
                        </h4>
                        <hr>

                        <!-- Meta Info -->
                        <div class="meta-info">
                            <span class="margin-right-20px">
                                <i class="fas fa-user"></i> By <a href="#">Admin</a>
                            </span>
                            <span class="margin-right-20px">
                                <i class="fas fa-calendar-alt"></i> 
                                <?php 
                                $date = isset($blog['blog_date']) ? $blog['blog_date'] : (isset($blog['created_at']) ? $blog['created_at'] : date('Y-m-d'));
                                echo date("F d, Y", strtotime($date)); 
                                ?>
                            </span>
                            <?php if(isset($blog['category']) && !empty($blog['category'])): ?>
                            <span>
                                <i class="fas fa-tag"></i> 
                                <a href="#"><?php echo htmlspecialchars($blog['category']); ?></a>
                            </span>
                            <?php endif; ?>
                        </div>
                        <hr>

                        <!-- Featured Image -->
                        <?php 
                        $blog_img = !empty($blog['blog_img']) ? $blog['blog_img'] : 'no-image.jpg';
                        if(file_exists("images/uploads/".$blog_img)) { 
                        ?>
                            <img src="images/uploads/<?php echo $blog_img; ?>" alt="<?php echo htmlspecialchars($blog['blog_title']); ?>" class="blog-featured-image">
                        <?php } else { ?>
                            <img src="assets/img/no-image.png" alt="No Image" class="blog-featured-image">
                        <?php } ?>

                        <!-- Blog Content -->
                        <div class="entry-content">
                            <?php 
                            $content = isset($blog['blog_desc']) ? $blog['blog_desc'] : (isset($blog['blog_content']) ? $blog['blog_content'] : '');
                            echo nl2br(htmlspecialchars_decode($content)); 
                            ?>
                        </div>
                        
                        <hr>
                        
                        <!-- Post Tags -->
                        <?php if(isset($blog['tags']) && !empty($blog['tags'])): ?>
                        <div class="post-tags">
                            <strong><i class="fas fa-tags"></i> Tags:</strong>
                            <?php 
                            $tags = explode(',', $blog['tags']);
                            foreach($tags as $tag) {
                                echo '<a href="#" class="btn-tag">' . trim(htmlspecialchars($tag)) . '</a> ';
                            }
                            ?>
                        </div>
                        <hr>
                        <?php endif; ?>
                        
                        <!-- Share Buttons -->
                        <div class="share-buttons">
                            <strong><i class="fas fa-share-alt"></i> Share:</strong>
                            <a href="#" class="btn btn-sm btn-outline-primary"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="btn btn-sm btn-outline-primary"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="btn btn-sm btn-outline-primary"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="btn btn-sm btn-outline-primary"><i class="fab fa-whatsapp"></i></a>
                        </div>
                        
                    </div>

                    <!-- ===== COMMENTS SECTION ===== -->
                    <div class="margin-bottom-30px">
                        <div class="padding-30px background-white border-radius-10 shadow-hover">
                            
                            <?php 
                            // Get comments count
                            $comment_count_query = mysqli_query($connect, "SELECT COUNT(*) as total FROM blog_comments WHERE blog_id = '$hid'");
                            $comment_count = mysqli_fetch_assoc($comment_count_query);
                            $total_comments = $comment_count['total'];
                            ?>
                            
                            <h4>
                                <i class="fas fa-comment-alt margin-right-10px text-main-color"></i> 
                                Comments (<?php echo $total_comments; ?>)
                            </h4>
                            <hr>
                            
                            <ul class="commentlist padding-0px margin-0px list-unstyled">
                                <?php 
                                $comments_query = mysqli_query($connect, "SELECT * FROM blog_comments WHERE blog_id = '$hid' ORDER BY comment_id DESC");
                                
                                if(mysqli_num_rows($comments_query) > 0) {
                                    while($comment = mysqli_fetch_assoc($comments_query)) { 
                                ?>
                                <li class="comment-item">
                                    <img src="images/uploads/no.png" class="comment-avatar" alt="Avatar">
                                    <div class="comment-body">
                                        <div>
                                            <span class="comment-author"><?php echo htmlspecialchars($comment['name']); ?></span>
                                            <span class="comment-date">
                                                <i class="far fa-calendar-alt"></i> 
                                                <?php 
                                                $comment_date = isset($comment['created_at']) ? $comment['created_at'] : date('Y-m-d H:i:s');
                                                echo date("F d, Y", strtotime($comment_date)); 
                                                ?>
                                            </span>
                                            <a href="delete-com.php?id=<?php echo $comment['comment_id']; ?>&hid=<?php echo $hid; ?>" class="delete-comment" onclick="return confirm('Are you sure you want to delete this comment?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                        <p class="comment-text"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                                    </div>
                                </li>
                                <?php 
                                    } // end while
                                } else { 
                                ?>
                                <div class="no-comments">
                                    <i class="fas fa-comment-slash"></i>
                                    <p>No comments yet. Be the first to comment!</p>
                                </div>
                                <?php } ?>
                            </ul>
                            
                        </div>
                    </div>

                    <!-- ===== ADD COMMENT FORM ===== -->
                    <div class="margin-bottom-30px">
                        <div class="padding-30px background-white border-radius-10 shadow-hover">
                            <h4><i class="fas fa-comment-alt margin-right-10px text-main-color"></i> Add Comment</h4>
                            <hr>
                            
                            <?php if(isset($_GET['comment']) && $_GET['comment'] == 'success'): ?>
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle"></i> Your comment has been added successfully!
                                </div>
                            <?php endif; ?>
                            
                            <?php if(isset($_GET['comment']) && $_GET['comment'] == 'error'): ?>
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-circle"></i> Failed to add comment. Please try again.
                                </div>
                            <?php endif; ?>
                            
                            <form action="blog-comment-action.php" method="post" enctype="multipart/form-data">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label><i class="fas fa-user"></i> Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" placeholder="Your Name" name="name" required>
                                    </div>
                                    <input type="hidden" name="addcom" value="<?php echo $blog['blog_id']; ?>">
                                    <div class="form-group col-md-6">
                                        <label><i class="fas fa-envelope"></i> Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" placeholder="Your Email" name="email" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-comment"></i> Comment <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="comment" rows="4" placeholder="Write your comment here..." required></textarea>
                                </div>
                                <button type="submit" name="com" class="btn-submit-comment">
                                    <i class="fas fa-paper-plane"></i> Post Comment
                                </button>
                            </form>
                        </div>
                    </div>
                    
                </div>

                <!-- ===== RIGHT COLUMN - SIDEBAR ===== -->
                <div class="col-lg-4">
                    
                    <!-- ===== SEARCH ===== -->
                    <div class="sidebar-widget">
                        <h4><i class="fas fa-search"></i> Search</h4>
                        <hr>
                        <form method="GET" action="blogs.php">
                            <div class="search-input-group">
                                <input type="text" name="search" placeholder="Search blogs..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                <button type="submit"><i class="fas fa-search"></i></button>
                            </div>
                        </form>
                    </div>

                    <!-- ===== CATEGORIES ===== -->
                    <div class="sidebar-widget">
                        <h4><i class="far fa-folder-open"></i> Categories</h4>
                        <hr>
                        <ul>
                            <li><a href="index.php"><i class="fas fa-chevron-right"></i> HOME</a></li>
                            <li><a href="doctor.php"><i class="fas fa-chevron-right"></i> DOCTORS</a></li>
                            <li><a href="laboratories.php"><i class="fas fa-chevron-right"></i> LABS</a></li>
                            <li><a href="pharmacies.php"><i class="fas fa-chevron-right"></i> PHARMACIES</a></li>
                            <li><a href="medicines.php"><i class="fas fa-chevron-right"></i> MEDICINES</a></li>
                        </ul>
                    </div>

                    <!-- ===== RECENT POSTS ===== -->
                    <div class="sidebar-widget">
                        <h4><i class="fas fa-clock"></i> Recent Posts</h4>
                        <hr>
                        <?php 
                        $recent_blogs = mysqli_query($connect, "SELECT * FROM blogs ORDER BY blog_id DESC LIMIT 4");
                        if(mysqli_num_rows($recent_blogs) > 0) {
                            while($row = mysqli_fetch_assoc($recent_blogs)) { 
                        ?>
                        <div class="recent-post-item">
                            <?php 
                            $img = !empty($row['blog_img']) ? $row['blog_img'] : 'no-image.jpg';
                            if(file_exists("images/uploads/".$img)) { 
                            ?>
                                <img src="images/uploads/<?php echo $img; ?>" alt="<?php echo htmlspecialchars($row['blog_title']); ?>">
                            <?php } else { ?>
                                <img src="assets/img/no-image.png" alt="No Image">
                            <?php } ?>
                            <div class="post-info">
                                <a href="single-blog.php?hid=<?php echo $row['blog_id']; ?>">
                                    <?php echo htmlspecialchars(substr($row['blog_title'], 0, 35)) . '...'; ?>
                                </a>
                                <div class="post-date">
                                    <i class="far fa-calendar-alt"></i> 
                                    <?php 
                                    $date = isset($row['blog_date']) ? $row['blog_date'] : (isset($row['created_at']) ? $row['created_at'] : date('Y-m-d'));
                                    echo date("M d, Y", strtotime($date)); 
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

                    <!-- ===== TAGS ===== -->
                    <div class="sidebar-widget">
                        <h4><i class="fas fa-tags"></i> Tags</h4>
                        <hr>
                        <div class="tags-container">
                            <button class="btn-tag">Health</button>
                            <button class="btn-tag">Medical</button>
                            <button class="btn-tag">Fitness</button>
                            <button class="btn-tag">Nutrition</button>
                            <button class="btn-tag">Mental Health</button>
                            <button class="btn-tag">Wellness</button>
                            <button class="btn-tag">COVID-19</button>
                        </div>
                    </div>

                </div>
                <!-- ===== END SIDEBAR ===== -->
                
            </div>
        </div>
    </div>

</div>

<?php include("footer.php"); ?>