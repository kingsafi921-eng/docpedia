<?php include('header.php'); ?>

<!-- Contact Us Content -->
<section class="contact-section" style="padding: 80px 0; background: #f8f9fa;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="contact-form" style="background: white; padding: 50px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                    <h2 style="color: #333; margin-bottom: 30px;">Contact Us</h2>
                    <p style="color: #666; margin-bottom: 30px;">Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
                    
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="name" placeholder="Your Name" required style="width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 5px;">
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="email" placeholder="Your Email" required style="width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 5px;">
                            </div>
                        </div>
                        <input type="text" name="subject" placeholder="Subject" required style="width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 5px;">
                        <textarea name="message" rows="5" placeholder="Your Message" required style="width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 5px;"></textarea>
                        <button type="submit" style="background: #667eea; color: white; padding: 12px 40px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('footer.php'); ?>