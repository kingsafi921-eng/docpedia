<?php
include('connect.php');
include('header.php');

$doc_id = isset($_GET['doc_id']) ? intval($_GET['doc_id']) : 0;
$message = '';
$message_type = '';

// Get doctor details
$doctor_query = mysqli_query($connect, "SELECT * FROM doctors WHERE doc_id = '$doc_id'");
$doctor = mysqli_fetch_assoc($doctor_query);

if(!$doctor) {
    header('Location: doctor.php');
    exit();
}

// Handle form submission
if(isset($_POST['book_appointment'])) {
    $patient_name = mysqli_real_escape_string($connect, $_POST['patient_name']);
    $patient_email = mysqli_real_escape_string($connect, $_POST['patient_email']);
    $patient_phone = mysqli_real_escape_string($connect, $_POST['patient_phone']);
    $appointment_date = mysqli_real_escape_string($connect, $_POST['appointment_date']);
    $appointment_time = mysqli_real_escape_string($connect, $_POST['appointment_time']);
    $message_text = mysqli_real_escape_string($connect, $_POST['message']);
    $doc_id = mysqli_real_escape_string($connect, $_POST['doc_id']);
    
    $insert = "INSERT INTO appointments (doc_id, patient_name, patient_email, patient_phone, appointment_date, appointment_time, message) 
               VALUES ('$doc_id', '$patient_name', '$patient_email', '$patient_phone', '$appointment_date', '$appointment_time', '$message_text')";
    
    if(mysqli_query($connect, $insert)) {
        $message = "Appointment booked successfully! Doctor will contact you soon.";
        $message_type = "success";
    } else {
        $message = "Error: " . mysqli_error($connect);
        $message_type = "danger";
    }
}

$title = 'Book Appointment - ' . $doctor['doc_name'];
?>

<style>
    .appointment-section {
        padding: 120px 0 50px 0;
        background: #f4f6f9;
        min-height: 100vh;
    }
    .appointment-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        padding: 30px;
        margin-bottom: 30px;
    }
    .doctor-info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 30px;
        color: white;
        text-align: center;
    }
    .doctor-info-card img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255,255,255,0.3);
    }
    .form-control {
        border-radius: 10px;
        padding: 12px 15px;
        border: 1px solid #ddd;
    }
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.25);
    }
    .btn-book {
        background: #667eea;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 15px 30px;
        font-weight: bold;
        width: 100%;
        transition: all 0.3s;
    }
    .btn-book:hover {
        background: #5a67d8;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102,126,234,0.4);
    }
    .timing-badge {
        display: inline-block;
        background: rgba(255,255,255,0.2);
        padding: 5px 15px;
        border-radius: 20px;
        margin: 3px;
        font-size: 12px;
    }
</style>

<div class="appointment-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-calendar-check" style="color: #667eea;"></i> Book Appointment</h2>
                    <a href="single.php?hid=<?php echo $doc_id; ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Profile
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Doctor Info -->
            <div class="col-lg-4">
                <div class="doctor-info-card">
                    <?php if(!empty($doctor['doc_image']) && file_exists($doctor['doc_image'])): ?>
                        <img src="<?php echo $doctor['doc_image']; ?>" alt="<?php echo $doctor['doc_name']; ?>">
                    <?php else: ?>
                        <img src="images/uploads/no.png" alt="<?php echo $doctor['doc_name']; ?>">
                    <?php endif; ?>
                    
                    <h3 class="mt-3"><?php echo $doctor['doc_name']; ?></h3>
                    <p style="opacity: 0.9;">
                        <i class="fas fa-stethoscope"></i> <?php echo $doctor['spec']; ?>
                    </p>
                    <p style="opacity: 0.9;">
                        <i class="fas fa-hospital"></i> <?php echo $doctor['hos_address']; ?>
                    </p>
                    <?php if(!empty($doctor['fee'])): ?>
                        <p style="opacity: 0.9;">
                            <i class="fas fa-money-bill"></i> Rs. <?php echo $doctor['fee']; ?>
                        </p>
                    <?php endif; ?>
                    
                    <hr style="border-color: rgba(255,255,255,0.2);">
                    
                    <h5><i class="fas fa-clock"></i> Available Timings</h5>
                    <?php if(!empty($doctor['days']) && !empty($doctor['time'])): ?>
                        <p style="opacity: 0.9; font-size: 14px;">
                            <?php echo $doctor['days']; ?><br>
                            <?php echo $doctor['time']; ?>
                        </p>
                    <?php else: ?>
                        <p style="opacity: 0.9; font-size: 14px;">
                            Mon-Fri: 9:00 AM - 5:00 PM
                        </p>
                    <?php endif; ?>
                    
                    <?php if(!empty($doctor['exp'])): ?>
                        <p style="opacity: 0.9; font-size: 14px;">
                            <i class="fas fa-calendar-alt"></i> <?php echo $doctor['exp']; ?> years experience
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Appointment Form -->
            <div class="col-lg-8">
                <div class="appointment-card">
                    <h3><i class="fas fa-pen"></i> Fill Appointment Details</h3>
                    <hr>
                    
                    <?php if($message): ?>
                        <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                            <?php echo $message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="book-appointment.php?doc_id=<?php echo $doc_id; ?>">
                        <input type="hidden" name="doc_id" value="<?php echo $doc_id; ?>">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label><i class="fas fa-user"></i> Full Name *</label>
                                    <input type="text" name="patient_name" class="form-control" placeholder="Enter your full name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label><i class="fas fa-envelope"></i> Email *</label>
                                    <input type="email" name="patient_email" class="form-control" placeholder="Enter your email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label><i class="fas fa-phone"></i> Phone *</label>
                                    <input type="text" name="patient_phone" class="form-control" placeholder="Enter your phone number" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label><i class="fas fa-calendar-day"></i> Appointment Date *</label>
                                    <input type="date" name="appointment_date" class="form-control" min="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label><i class="fas fa-clock"></i> Preferred Time *</label>
                                    <select name="appointment_time" class="form-control" required>
                                        <option value="">Select Time</option>
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
                                        <option value="06:30 PM">06:30 PM</option>
                                        <option value="07:00 PM">07:00 PM</option>
                                        <option value="07:30 PM">07:30 PM</option>
                                        <option value="08:00 PM">08:00 PM</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label><i class="fas fa-comment"></i> Additional Message</label>
                                    <textarea name="message" class="form-control" rows="4" placeholder="Any special requirements or message for the doctor..."></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" name="book_appointment" class="btn-book">
                                    <i class="fas fa-calendar-check"></i> Book Appointment
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>