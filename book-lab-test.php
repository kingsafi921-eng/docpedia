<?php
$title = 'Book Lab Test - Doctorpedia';
include('header.php');
include('connect.php');

$lab_id = isset($_GET['lab_id']) ? intval($_GET['lab_id']) : 0;
$message = '';
$message_type = '';

// Get lab details
$lab_query = mysqli_query($connect, "SELECT * FROM labs WHERE lab_id = '$lab_id'");
$lab = mysqli_fetch_assoc($lab_query);

if(!$lab) {
    header('Location: laboratories.php');
    exit();
}

// Handle form submission
if(isset($_POST['book_test'])) {
    $patient_name = mysqli_real_escape_string($connect, $_POST['patient_name']);
    $patient_email = mysqli_real_escape_string($connect, $_POST['patient_email']);
    $patient_phone = mysqli_real_escape_string($connect, $_POST['patient_phone']);
    $test_name = mysqli_real_escape_string($connect, $_POST['test_name']);
    $test_date = mysqli_real_escape_string($connect, $_POST['test_date']);
    $test_time = mysqli_real_escape_string($connect, $_POST['test_time']);
    $message_text = mysqli_real_escape_string($connect, $_POST['message']);
    $lab_id = mysqli_real_escape_string($connect, $_POST['lab_id']);
    
    $insert = "INSERT INTO lab_bookings (lab_id, patient_name, patient_email, patient_phone, test_name, test_date, test_time, message) 
               VALUES ('$lab_id', '$patient_name', '$patient_email', '$patient_phone', '$test_name', '$test_date', '$test_time', '$message_text')";
    
    if(mysqli_query($connect, $insert)) {
        $message = "Lab test booked successfully! You will receive confirmation shortly.";
        $message_type = "success";
    } else {
        $message = "Error: " . mysqli_error($connect);
        $message_type = "danger";
    }
}

// Common lab tests
$common_tests = [
    'Complete Blood Count (CBC)',
    'Lipid Profile',
    'Liver Function Test (LFT)',
    'Kidney Function Test (KFT)',
    'Thyroid Profile (T3, T4, TSH)',
    'Blood Sugar (Fasting/Random)',
    'HbA1c',
    'Vitamin D',
    'Vitamin B12',
    'Iron Profile',
    'Urine Complete Examination',
    'Stool Complete Examination',
    'X-Ray',
    'Ultrasound',
    'CT Scan',
    'MRI',
    'ECG',
    'Hepatitis B & C Screening',
    'Dengue Test',
    'Malaria Test',
    'Typhoid Test',
    'Cholesterol Test',
    'Triglycerides Test',
    'HDL/LDL Test',
    'Uric Acid Test',
    'Calcium Test',
    'Magnesium Test',
    'Sodium/Potassium Test',
    'Pregnancy Test',
    'Pap Smear',
    'CBC with Differential',
    'Platelet Count',
    'ESR',
    'CRP',
    'Ferritin',
    'Total Protein',
    'Albumin/Globulin Ratio',
    'Bilirubin',
    'Alkaline Phosphatase (ALP)',
    'SGOT/AST',
    'SGPT/ALT',
    'GGT',
    'Amylase',
    'Lipase',
    'Creatinine',
    'Urea',
    'BUN',
    'eGFR',
    'Hb Electrophoresis',
    'G6PD'
];
?>

<style>
    .book-test-section {
        padding: 120px 0 50px 0;
        background: #f0f2f5;
        min-height: 100vh;
    }
    .book-test-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.06);
        padding: 35px;
        margin-bottom: 30px;
    }
    .book-test-card .lab-info {
        display: flex;
        align-items: center;
        gap: 20px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
        margin-bottom: 25px;
    }
    .book-test-card .lab-info .lab-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: white;
        flex-shrink: 0;
    }
    .book-test-card .lab-info .lab-icon img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }
    .book-test-card .lab-info .lab-details h3 {
        font-weight: 700;
        color: #1a1a2e;
        margin: 0;
    }
    .book-test-card .lab-info .lab-details p {
        color: #7f8c8d;
        margin: 0;
        font-size: 14px;
    }
    .form-control-premium {
        width: 100%;
        padding: 12px 18px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s;
        outline: none;
        background: white;
    }
    .form-control-premium:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102,126,234,0.1);
    }
    .form-control-premium select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
    }
    .form-label-premium {
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 5px;
        display: block;
        font-size: 14px;
    }
    .btn-book-test {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 14px 40px;
        border-radius: 30px;
        font-weight: 700;
        font-size: 16px;
        transition: all 0.3s;
        cursor: pointer;
        width: 100%;
    }
    .btn-book-test:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(102,126,234,0.4);
        color: white;
    }
    .btn-back-lab {
        background: #6c757d;
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 30px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-back-lab:hover {
        background: #5a6268;
        color: white;
        transform: translateY(-2px);
    }
    .alert-premium {
        padding: 15px 20px;
        border-radius: 12px;
        font-weight: 500;
        margin-bottom: 20px;
    }
    .alert-premium.alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .alert-premium.alert-danger {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .test-select-wrap {
        position: relative;
    }
    .test-select-wrap select {
        width: 100%;
        padding: 12px 18px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 14px;
        background: white;
        transition: all 0.3s;
        outline: none;
    }
    .test-select-wrap select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102,126,234,0.1);
    }
    @media (max-width: 768px) {
        .book-test-card { padding: 20px; }
        .book-test-card .lab-info { flex-direction: column; text-align: center; }
    }
</style>

<section class="book-test-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <a href="laboratories.php" class="btn-back-lab mb-4">
                    <i class="fas fa-arrow-left"></i> Back to Laboratories
                </a>

                <div class="book-test-card">
                    <!-- Lab Info -->
                    <div class="lab-info">
                        <div class="lab-icon">
                            <?php if(!empty($lab['img']) && file_exists("images/uploads/".$lab['img'])): ?>
                                <img src="images/uploads/<?php echo $lab['img']; ?>" alt="<?php echo $lab['lab_name']; ?>">
                            <?php else: ?>
                                <i class="fas fa-flask"></i>
                            <?php endif; ?>
                        </div>
                        <div class="lab-details">
                            <h3><i class="fas fa-flask" style="color: #667eea;"></i> <?php echo htmlspecialchars($lab['lab_name']); ?></h3>
                            <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($lab['address'] ?? 'Address not available'); ?></p>
                            <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($lab['phone'] ?? 'Phone not available'); ?></p>
                        </div>
                    </div>

                    <h4><i class="fas fa-calendar-check" style="color: #667eea;"></i> Book a Lab Test</h4>
                    <p class="text-muted">Fill in the details below to book your test at this laboratory.</p>

                    <?php if($message): ?>
                        <div class="alert-premium alert-<?php echo $message_type; ?>">
                            <i class="fas fa-<?php echo ($message_type == 'success') ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>

                    <?php if(!$message || $message_type == 'danger'): ?>
                    <form method="POST" action="book-lab-test.php?lab_id=<?php echo $lab_id; ?>">
                        <input type="hidden" name="lab_id" value="<?php echo $lab_id; ?>">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label-premium"><i class="fas fa-user"></i> Full Name *</label>
                                    <input type="text" name="patient_name" class="form-control-premium" placeholder="Enter your full name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label-premium"><i class="fas fa-envelope"></i> Email *</label>
                                    <input type="email" name="patient_email" class="form-control-premium" placeholder="Enter your email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label-premium"><i class="fas fa-phone"></i> Phone *</label>
                                    <input type="text" name="patient_phone" class="form-control-premium" placeholder="Enter your phone number" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label-premium"><i class="fas fa-flask"></i> Test Name *</label>
                                    <div class="test-select-wrap">
                                        <select name="test_name" class="form-control-premium" required>
                                            <option value="">Select a test</option>
                                            <?php foreach($common_tests as $test): ?>
                                                <option value="<?php echo $test; ?>"><?php echo $test; ?></option>
                                            <?php endforeach; ?>
                                            <option value="other">Other (Specify below)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label-premium"><i class="fas fa-calendar-day"></i> Preferred Date *</label>
                                    <input type="date" name="test_date" class="form-control-premium" min="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label-premium"><i class="fas fa-clock"></i> Preferred Time *</label>
                                    <select name="test_time" class="form-control-premium" required>
                                        <option value="">Select Time</option>
                                        <option value="08:00 AM">08:00 AM</option>
                                        <option value="08:30 AM">08:30 AM</option>
                                        <option value="09:00 AM">09:00 AM</option>
                                        <option value="09:30 AM">09:30 AM</option>
                                        <option value="10:00 AM">10:00 AM</option>
                                        <option value="10:30 AM">10:30 AM</option>
                                        <option value="11:00 AM">11:00 AM</option>
                                        <option value="11:30 AM">11:30 AM</option>
                                        <option value="12:00 PM">12:00 PM</option>
                                        <option value="12:30 PM">12:30 PM</option>
                                        <option value="01:00 PM">01:00 PM</option>
                                        <option value="01:30 PM">01:30 PM</option>
                                        <option value="02:00 PM">02:00 PM</option>
                                        <option value="02:30 PM">02:30 PM</option>
                                        <option value="03:00 PM">03:00 PM</option>
                                        <option value="03:30 PM">03:30 PM</option>
                                        <option value="04:00 PM">04:00 PM</option>
                                        <option value="04:30 PM">04:30 PM</option>
                                        <option value="05:00 PM">05:00 PM</option>
                                        <option value="05:30 PM">05:30 PM</option>
                                        <option value="06:00 PM">06:00 PM</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label-premium"><i class="fas fa-comment"></i> Additional Message</label>
                                    <textarea name="message" class="form-control-premium" rows="3" placeholder="Any special requirements or instructions..."></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" name="book_test" class="btn-book-test">
                                    <i class="fas fa-calendar-check"></i> Book Lab Test
                                </button>
                            </div>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('footer.php'); ?>