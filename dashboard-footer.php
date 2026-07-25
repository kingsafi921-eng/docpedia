<!-- ======================================================
    FOOTER - FIXED VERSION (Speciality Top Removed)
    ====================================================== -->
<footer class="sticky-footer" style="
    background: white;
    padding: 15px 0;
    border-top: 1px solid #e5e7eb;
    margin-top: 30px;
    position: relative;
    bottom: 0;
    width: 100%;
">
    <div class="container">
        <div class="text-center">
            <span style="color: #6b7280; font-size: 14px;">
                © <strong style="color: #4F46E5;">DoctorPedia</strong> | All Rights Reserved
                <a class="text-grey-2 margin-left-15px" href="#" target="_blank" style="color: #9ca3af; text-decoration: none; margin-left: 15px;">
                    <i class="fas fa-heart" style="color: #ef4444; font-size: 12px;"></i>
                </a>
            </span>
        </div>
    </div>
</footer>

<!-- ======================================================
    SCROLL TO TOP BUTTON
    ====================================================== -->
<a class="scroll-to-top rounded" href="#page-top" style="
    position: fixed;
    bottom: 30px;
    right: 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    transition: all 0.3s ease;
    z-index: 999;
    opacity: 0;
    visibility: hidden;
    transform: translateY(20px);
">
    <i class="fa fa-angle-up" style="font-size: 22px;"></i>
</a>

<!-- ======================================================
    LOGOUT MODAL
    ====================================================== -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
            <div class="modal-header" style="
                border-bottom: 1px solid #e5e7eb;
                padding: 20px 25px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 12px 12px 0 0;
            ">
                <h5 class="modal-title" id="exampleModalLabel" style="color: white; font-weight: 600;">
                    <i class="fas fa-sign-out-alt" style="margin-right: 10px;"></i> Ready to Leave?
                </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8; font-size: 28px;">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 25px; color: #4b5563; font-size: 15px; line-height: 1.6;">
                <i class="fas fa-info-circle" style="color: #667eea; font-size: 18px; margin-right: 10px;"></i>
                Select "Logout" below if you are ready to end your current session.
            </div>
            <div class="modal-footer" style="
                border-top: 1px solid #e5e7eb;
                padding: 15px 25px;
                display: flex;
                gap: 10px;
                justify-content: flex-end;
            ">
                <button class="btn btn-secondary" type="button" data-dismiss="modal" style="
                    padding: 8px 25px;
                    border-radius: 8px;
                    border: 1px solid #d1d5db;
                    background: white;
                    color: #6b7280;
                    font-weight: 500;
                    cursor: pointer;
                    transition: all 0.3s ease;
                ">
                    Cancel
                </button>
                <a class="btn btn-primary" href="logout.php" style="
                    padding: 8px 25px;
                    border-radius: 8px;
                    border: none;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    font-weight: 500;
                    text-decoration: none;
                    cursor: pointer;
                    transition: all 0.3s ease;
                ">
                    <i class="fas fa-sign-out-alt" style="margin-right: 6px;"></i> Logout
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================
    SCRIPTS - FIXED
    ====================================================== -->
<script src="assets/js/jquery-3.2.1.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/sticky-sidebar.js"></script>
<script src="assets/js/YouTubePopUp.jquery.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
<script src="assets/js/imagesloaded.min.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/custom.js"></script>

<!-- ======================================================
    SCROLL TO TOP BUTTON SCRIPT
    ====================================================== -->
<script>
    $(document).ready(function() {
        // ===== Scroll to Top Button =====
        $(window).scroll(function() {
            if ($(this).scrollTop() > 200) {
                $('.scroll-to-top').css({
                    'opacity': '1',
                    'visibility': 'visible',
                    'transform': 'translateY(0)'
                });
            } else {
                $('.scroll-to-top').css({
                    'opacity': '0',
                    'visibility': 'hidden',
                    'transform': 'translateY(20px)'
                });
            }
        });

        $('.scroll-to-top').on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: 0
            }, 600);
        });

        // ===== Auto-dismiss alerts =====
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    });
</script>

</body>
</html>