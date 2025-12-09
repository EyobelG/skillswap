<?php
    ini_set('session.cookie_domain', '.joonh.sgedu.site');
    ini_set('session.cookie_secure', 1);
    ini_set('session.cookie_httponly', 1);
    session_start();
    
    $db_host = 'localhost';
    $db_user = 'utnq9qzvkroxc';
    $db_pass = 'cs20finalproj';
    $db_name = 'dbdtf6cle3tkfo';

    $members_host = 'localhost';
    $members_user = 'utnq9qzvkroxc';
    $members_pass = 'cs20finalproj';
    $members_name = 'dbfxsgcb4otskb';

    session_start();
    
    $user_id = $_SESSION["user_id"];
    $password = $_SESSION["password"];

    $authdbrow;
    $detailsdbrow;

    $validcredentials = FALSE;
    $error = FALSE;

    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $mysqli_members = new mysqli($members_host, $members_user, $members_pass, $members_name);
    if ($mysqli_members->connect_error) {
        die("Connection failed: " . $mysqli_members->connect_error);
    }

    try {
        if ($user_id == NULL || $password == NULL) {
            if ($user_id == NULL) {
                echo "unull";
            }
            if ($password == NULL) {
                echo "pnull";
            }
            throw new Exception("You're probably seeing this message because you pressed the back button on your browser.");
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $authresult = mysqli_query($mysqli, "SELECT * FROM users WHERE user_id = '$user_id'");
        $detailsresult = mysqli_query($mysqli, "SELECT * FROM users WHERE user_id = '$user_id'");
        if ($authresult && $detailsresult) {
            if (mysqli_num_rows($authresult) == 1) { // check if username is in the db
                $authdbrow = $authresult->fetch_array();
                if (password_verify($password, $authdbrow["password"])) {
                    $validcredentials = true;
                    $detailsdbrow = $detailsresult->fetch_array();
                }
            }
        } else {
            throw new Exception("ERROR: database connection lost");
        }

        if (!$validcredentials) {
            echo "<p>You aren't logged in!.</p>";
            echo "<p>Please try to <a href=\"signin.html\">sign in</a> or <a href=\"signup.html\">sign up</a> to make an account.</p>";
            throw new Exception("<p>ERROR: no account</p>");
        }


    } catch (Exception $e) {
        echo $e->getMessage();
        $error = TRUE;
    }


    if (!$error) {

        // GET ACCOOUNT DETAILS
        $fullName = $authdbrow["name"];
        $email = $authdbrow["email"];
        $phone = $authdbrow["phone"];

        $age = $detailsdbrow["age"];
        $description = $detailsdbrow["description"];
        $categories = $detailsdbrow["categories"];
        $wantsToLearn = $detailsdbrow["wantsToLearn"];
        $image = $detailsdbrow["image"];
        $pos_ratings = $detailsdbrow["pos_ratings"];
        $total_ratings = $detailsdbrow["total_ratings"];


        echo " <!-- Main Wrapper -->
            <div class='main-wrapper'>
                <!-- Sidebar -->
                <div class='sidebar' id='sidebar'>
                    <div class='profile-card'>
                        <div>
                            <img id='profilePhoto' class='profile-photo' src='images/$image' alt='Profile Photo' />
                            <input type='file' id='photoUpload' style='display:none' accept='image/*' />
                            <button class='upload-btn' onclick='document.getElementById('photoUpload').click()'>Upload Photo</button>
                        </div>
                        <div>
                            <div class='profile-name' id='profileName'>$fullName</div>
                            <div class='profile-email' id='profileEmailDisplay'>$email</div>
                        </div>
                    </div>

                    <div class='menu-card'>
                        <button class='menu-item active' onclick='showSection('profile')'>Profile</button>
                        <button class='menu-item' onclick='showSection('billing')'>Billing & Payments</button>
                        <button class='menu-item' onclick='showSection('skills')'>Skills</button>
                        <button class='menu-item' onclick='showSection('security')'>Security</button>
                    </div>
                </div>

                <!-- Content Area -->
                <div class='content'>
                    <!-- PROFILE SECTION -->
                    <div id='profileSection' class='main-section'>
                        <h2 class='section-title'>Profile Information</h2>
                        <p class='section-description'>Manage your personal information and account details</p>

                        <div class='field-card'>
                            <div class='field-info'>
                                <div class='field-label'>Full Name</div>
                                <div id='nameValue' class='field-value'>$fullName</div>
                            </div>
                            <button class='edit-btn' onclick='openEditModal('name')'>Edit</button>
                        </div>

                        <div class='field-card'>
                            <div class='field-info'>
                                <div class='field-label'>Email</div>
                                <div id='emailValue' class='field-value'>$email</div>
                            </div>
                            <button class='edit-btn' onclick='openEditModal('email')'>Edit</button>
                        </div>

                        <div class='field-card'>
                            <div class='field-info'>
                                <div class='field-label'>Phone Number</div>
                                <div id='phoneValue' class='field-value'>$phone</div>
                            </div>
                            <button class='edit-btn' onclick='openEditModal('phone')'>Edit</button>
                        </div>

                        <div class='field-card'>
                            <div class='field-info'>
                                <div class='field-label'>Location</div>
                                <div id='locationValue' class='field-value'>Cambridge, MA</div>
                            </div>
                            <button class='edit-btn' onclick='openEditModal('location')'>Edit</button>
                        </div>
                        <div id='locationMapCard' class='main-section' style='margin-top: 2rem; padding: 2.5rem;'>
                            <h2 class='section-title' style='margin-bottom: 0.5rem;'>📍 Set Your Public Location</h2>
                                <p class='section-description' style='margin-bottom: 1rem;'>
                                    Use the search box or drag the marker to set your approximate public meeting location.
                                </p>

                            <div id='geocoder-container' style='margin-bottom: 1rem;'></div> 

                            <div id='profileMap' style='height: 400px; border-radius: 0.75rem; border: 2px solid rgba(251, 191, 36, 0.3);'></div>
                            <button class='save-btn' style='margin-top: 1.5rem;' onclick='saveMapLocation()'>Save Location</button>
                        </div>
                    </div>

                    <!-- BILLING SECTION -->
                    <div id='billingSection' class='main-section'>
                        <h2 class='section-title'>Billing & Payments</h2>
                        <p class='section-description'>Manage your payment methods and billing information</p>

                        <button class='add-method-btn' onclick='openPaymentPopup()'>Add Payment Method</button>

                        <div id='paymentList'>
                            <p style='color: #e9d5ff; text-align: center; padding: 2rem;'>No payment methods added yet.</p>
                        </div>
                    </div>

                    <!-- SKILLS SECTION -->
                    <div id='skillsSection' class='main-section'>
                        <h2 class='section-title'>Skills</h2>
                        <p class='section-description'>Manage your skills and expertise levels</p>

                        <button class='add-method-btn' onclick='openSkillModal()'>Manage Skills</button>";

                    foreach ($categories as $category) {
                        echo "<div class='field-card'>
                                <div class='field-info'>
                                    <div class='field-label'>$category</div>
                                </div>
                            </div>";
                    }
                        
                    echo "</div>

                    <!-- SECURITY SECTION -->
                    <div id='securitySection' class='main-section'>
                        <h2 class='section-title'>Security Settings</h2>
                        <p class='section-description'>Manage your password and security preferences</p>

                        <div class='field-card'>
                            <div class='field-info'>
                                <div class='field-label'>Password</div>
                                <div class='field-value'>••••••••••</div>
                            </div>
                            <button class='edit-btn' onclick='openEditModal('password')'>Change</button>
                        </div>

                        <div class='field-card'>
                            <div class='field-info'>
                                <div class='field-label'>Two-Factor Authentication</div>
                                <div class='field-value'>Disabled</div>
                            </div>
                            <button class='edit-btn' onclick='alert('2FA setup coming soon!')'>Enable</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class='mobile-menu-toggle' onclick='toggleMobileMenu()'>☰</button>

            <!-- EDIT MODAL -->
            <!-- <div id='editModal' class='modal hidden'>
                <div class='modal-content'>
                    <button class='close-btn' onclick='closeEditModal()'>×</button>
                    <div class='modal-header' id='modalTitle'></div>
                    <input id='modalInput' type='text' placeholder='Enter new value' />
                    <button class='save-btn' onclick='saveEdit()'>Save Changes</button>
                </div>
            </div> -->

            <div id='editModal' class='modal hidden'> 
            <div class='modal-content'>
                <div class='modal-header'>
                    <h3 id='modalTitle'>Edit Field</h3> 
                    <span class='close-btn' onclick='closeEditModal()'>×</span>
                </div>
                <div class='modal-body'>
                    <input type='text' id='modalInput' class='text-input' placeholder='Enter new value'> 
                </div>
                <div class='modal-footer'>
                    <button class='btn cancel-btn' onclick='closeEditModal()'>Cancel</button>
                    <button class='btn save-btn' onclick='saveEdit()'>Save Changes</button> 
                </div>
            </div>
        </div>

            <!-- PAYMENT MODAL -->
            <div id='paymentModal' class='modal hidden'>
                <div class='modal-content'>
                    <button class='close-btn' onclick='closePaymentPopup()'>×</button>
                    <div class='modal-header'>Add Payment Method</div>

                    <input type='text' id='cardName' placeholder='Name on Card' />
                    <input type='text' id='cardNumber' placeholder='Card Number' maxlength='19' />
                    
                    <div class='payment-grid'>
                        <input type='text' id='expiry' placeholder='MM/YY' maxlength='5' />
                        <input type='text' id='cvv' placeholder='CVV' maxlength='4' />
                    </div>

                    <input type='text' id='billingAddress' placeholder='Billing Address' />

                    <button class='save-btn' onclick='savePayment()'>Save Payment Method</button>
                </div>
            </div>

            <!-- SKILL MODAL -->
            <div id='skillModal' class='modal hidden'>
                <div class='modal-content'>
                    <button class='close-btn' onclick='closeSkillModal()'>×</button>
                    <div class='modal-header'>Manage Skills</div>

                    <div class='skill-list' id='skillList'></div>

                    <div style='background: rgba(107, 33, 168, 0.4); border: 2px solid rgba(251, 191, 36, 0.3); border-radius: 0.75rem; padding: 1.5rem; margin-top: 1rem;'>
                        <h4 style='color: #fbbf24; font-size: 1.125rem; margin-bottom: 1rem;'>Add New Skill</h4>
                        <input type='text' id='newSkillName' placeholder='Skill Name (e.g., Tennis, Guitar)' />
                        <select id='newSkillLevel'>
                            <option value='Beginner'>Beginner</option>
                            <option value='Intermediate'>Intermediate</option>
                            <option value='Advanced'>Advanced</option>
                            <option value='Expert'>Expert</option>
                        </select>
                        <button class='save-btn' onclick='addSkill()'>Add Skill</button>
                    </div>
                </div>
            </div>
        <div id='footer-container'></div>`";
    }


    $mysqli->close();

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="main.js" defer></script>
    <script src="header.js" defer></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    
    <title>My Account</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            background: linear-gradient(135deg, #e9d5ff 0%, #c4b5fd 50%, #a78bfa 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(167, 139, 250, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(196, 181, 253, 0.3) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }
        
        .container {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            flex: 1;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #6b21a8 0%, #5b21b6 100%);
            padding: 2rem 0;
            box-shadow: 0 8px 20px rgba(91, 33, 182, 0.3);
            position: relative;
            z-index: 5;
        }

        .page-header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #fbbf24;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .sign-out-btn {
            padding: 0.75rem 2rem;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #1e293b;
            border: none;
            border-radius: 2rem;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);
        }

        .sign-out-btn:hover {
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(251, 191, 36, 0.5);
        }

        /* Main Layout */
        .main-wrapper {
            display: flex;
            flex: 1;
            width: 100%;
            position: relative;
            z-index: 1;
            padding-left: 0;
            margin: 0;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #6b21a8 0%, #5b21b6 100%);
            padding: 2rem 1rem;
            box-shadow: 4px 0 20px rgba(91, 33, 182, 0.3);
            display: flex;
            flex-direction: column;
            gap: 2rem;
            overflow-y: auto;
            flex-shrink: 0;
        }

        .profile-card {
            text-align: center;
            padding: 1.5rem;
            background: rgba(107, 33, 168, 0.4);
            border-radius: 1rem;
            border: 2px solid rgba(251, 191, 36, 0.3);
        }

        .profile-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fbbf24;
            margin: 0 auto 1rem;
            display: block;
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);
        }

        .upload-btn {
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #1e293b;
            border: none;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        }

        .upload-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);
        }

        .profile-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #fbbf24;
            margin-top: 0.5rem;
        }

        .profile-email {
            font-size: 0.875rem;
            color: #e9d5ff;
            margin-top: 0.25rem;
            word-break: break-word;
        }

        .menu-card {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .menu-item {
            padding: 1rem 1.25rem;
            color: #e9d5ff;
            font-size: 1.05rem;
            font-weight: 500;
            cursor: pointer;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            border: none;
            background: transparent;
            text-align: left;
            border: 2px solid transparent;
        }

        .menu-item:hover {
            background: rgba(251, 191, 36, 0.1);
            color: #fbbf24;
            border-color: rgba(251, 191, 36, 0.3);
        }

        .menu-item.active {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #1e293b;
            font-weight: 700;
        }

        /* Content Area */
        .content {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
            background: transparent;
        }

        .main-section {
            margin-bottom: 20px;
            background: linear-gradient(135deg, #6b21a8 0%, #5b21b6 100%);
            border-radius: 1rem;
            padding: 2.5rem;
            box-shadow: 0 8px 20px rgba(91, 33, 182, 0.3);
        }

        .main-section.hidden {
            display: none;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 700;
            color: #fbbf24;
            margin-bottom: 0.75rem;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .section-description {
            font-size: 1rem;
            color: #e9d5ff;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .field-card {
            background: rgba(107, 33, 168, 0.4);
            border-radius: 0.75rem;
            padding: 1.5rem;
            border: 2px solid rgba(251, 191, 36, 0.3);
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .field-card:hover {
            border-color: #fbbf24;
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3);
            background: rgba(107, 33, 168, 0.6);
        }

        .field-info {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .field-label {
            font-size: 0.9rem;
            color: #c4b5fd;
            font-weight: 600;
        }

        .field-value {
            font-size: 1.125rem;
            color: #fbbf24;
            font-weight: 700;
        }

        .edit-btn {
            padding: 0.5rem 1.5rem;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #1e293b;
            border: none;
            border-radius: 0.5rem;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .edit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);
        }

        .add-method-btn {
            padding: 0.875rem 2rem;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #1e293b;
            border: none;
            border-radius: 0.75rem;
            font-size: 1.125rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);
            margin-bottom: 2rem;
        }

        .add-method-btn:hover {
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(251, 191, 36, 0.5);
        }

        /* Modal Styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            backdrop-filter: blur(4px);
        }

        .modal.hidden {
            display: none;
        }

        .modal-content {
            background: linear-gradient(135deg, #6b21a8 0%, #5b21b6 100%);
            border-radius: 1rem;
            padding: 2.5rem;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            border: 2px solid rgba(251, 191, 36, 0.3);
            position: relative;
        }

        .modal-header {
            font-size: 1.75rem;
            font-weight: 700;
            color: #fbbf24;
            margin-bottom: 1.5rem;
        }

        .close-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(239, 68, 68, 0.8);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-btn:hover {
            background: #ef4444;
            transform: scale(1.1);
        }

        .modal input,
        .modal select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid rgba(251, 191, 36, 0.3);
            border-radius: 0.5rem;
            font-size: 1rem;
            font-family: inherit;
            color: white;
            background: rgba(76, 29, 149, 0.6);
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .modal input:focus,
        .modal select:focus {
            outline: none;
            border-color: #fbbf24;
            background: rgba(76, 29, 149, 0.8);
            box-shadow: 0 0 0 4px rgba(251, 191, 36, 0.2);
        }

        .modal input::placeholder {
            color: #c4b5fd;
        }

        .save-btn {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            border: none;
            border-radius: 0.75rem;
            font-size: 1.125rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4);
        }

        .save-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(34, 197, 94, 0.5);
        }

        .payment-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .skill-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .skill-item {
            background: rgba(107, 33, 168, 0.4);
            border: 2px solid rgba(251, 191, 36, 0.3);
            border-radius: 0.75rem;
            padding: 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .skill-item:hover {
            border-color: #fbbf24;
            background: rgba(107, 33, 168, 0.6);
        }

        .skill-name {
            font-size: 1.125rem;
            font-weight: 700;
            color: #fbbf24;
        }

        .skill-level {
            font-size: 0.95rem;
            color: #e9d5ff;
            margin-top: 0.25rem;
        }

        .skill-actions {
            display: flex;
            gap: 0.5rem;
        }

        .icon-btn {
            padding: 0.5rem;
            background: rgba(251, 191, 36, 0.2);
            border: none;
            border-radius: 0.5rem;
            color: #fbbf24;
            cursor: pointer;
            transition: all 0.2s ease;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-btn:hover {
            background: rgba(251, 191, 36, 0.4);
            transform: scale(1.1);
        }

        .icon-btn.delete:hover {
            background: rgba(239, 68, 68, 0.4);
            color: #ef4444;
        }

        .hidden {
            display: none;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #1e293b;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);
            z-index: 100;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .main-wrapper {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 1.5rem;
                flex-direction: row;
                gap: 1.5rem;
                overflow-x: auto;
            }

            .profile-card {
                display: flex;
                flex-direction: column;
                align-items: center;
                min-width: 200px;
                flex-shrink: 0;
            }

            .profile-photo {
                width: 80px;
                height: 80px;
            }

            .profile-name {
                font-size: 1.125rem;
            }

            .profile-email {
                font-size: 0.875rem;
            }

            .menu-card {
                display: flex;
                flex-direction: row;
                gap: 0.5rem;
                flex-wrap: nowrap;
                overflow-x: auto;
            }

            .menu-item {
                white-space: nowrap;
                min-width: 150px;
                text-align: center;
            }
        }

        @media (max-width: 768px) {
            .nav-button {
                padding: 0.75rem 1rem;
                font-size: 1rem;
            }

            .page-header {
                padding: 1.5rem 1rem;
            }

            .page-header-content {
                flex-direction: column;
                gap: 1rem;
                padding: 0;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .content {
                padding: 1rem;
            }

            .main-section {
                padding: 1.5rem;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .field-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .edit-btn {
                width: 100%;
            }

            .payment-grid {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: -100%;
                height: 100vh;
                width: 280px;
                z-index: 200;
                transition: left 0.3s ease;
                flex-direction: column;
                padding: 2rem 1rem;
                overflow-y: auto;
                overflow-x: hidden;
            }

            .sidebar.active {
                left: 0;
            }

            .sidebar .profile-card {
                display: flex;
                flex-direction: column;
                align-items: center;
                min-width: auto;
                width: 100%;
            }

            .sidebar .menu-card {
                flex-direction: column;
                width: 100%;
            }

            .sidebar .menu-item {
                width: 100%;
                text-align: left;
                min-width: auto;
            }

            .mobile-menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .modal-content {
                padding: 2rem 1.5rem;
            }

            /* Overlay when mobile menu is open */
            .sidebar.active::before {
                content: '';
                position: fixed;
                top: 0;
                left: 280px;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: -1;
            }
        }

        @media (max-width: 480px) {
            .nav-container {
                padding: 0 0.5rem;
            }

            .nav-button {
                padding: 0.75rem 0.75rem;
                font-size: 0.9rem;
            }

            .sign-out-btn {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }

            .page-header {
                padding: 1rem;
            }

            .main-section {
                padding: 1rem;
            }

            .section-title {
                font-size: 1.25rem;
            }

            .section-description {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
        <!-- Navbar -->
    <nav class="topnav" id="myTopnav">
      <a href="index.html" class="logo">SkillSwap</a>
      <div class="nav-links">
        <a href="account.html" class = "active">Account</a>
        <a href="members.html">Members</a>
        <a href="contact.html">Contact Us</a>
        <a href="signup.html">Sign Up</a>
        <a href="signin.html">Sign In</a>
      </div>
        <button class="icon" onclick="toggleMenu()" aria-label="Menu">
            <i class="fa fa-bars"></i>
        </button>
    </nav>

    <script>
    
        let currentField = '';
        let skills = [
            { id: 1, name: 'Tennis', level: 'Intermediate' },
            { id: 2, name: 'Guitar', level: 'Beginner' }
        ];
        let nextSkillId = 3;
        let editingSkillId = null;

        // Section Navigation
        function showSection(section) {
            document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
            document.querySelector(`[onclick="showSection('${section}')"]`).classList.add('active');

            document.querySelectorAll('.main-section').forEach(s => s.classList.add('hidden'));
            document.getElementById(section + 'Section').classList.remove('hidden');

            // Close mobile menu
            if (window.innerWidth <= 768) {
                document.getElementById('sidebar').classList.remove('active');
            }
        }

        // Mobile Menu Toggle
        function toggleMobileMenu() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        // Profile Photo Upload
        document.getElementById('photoUpload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('profilePhoto').src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Edit Modal Functions
        function openEditModal(field) {
            currentField = field;
            const modal = document.getElementById('editModal');
            const profileSection = document.getElementById('profileSection');
            const mapCard = document.getElementById('locationMapCard'); // Reference to the map container

            if (field === 'location') {
                // 1. Hide the main profile section
                profileSection.classList.add('hidden');
                // 2. Show the map card
                mapCard.classList.remove('hidden');
                // 3. Force the map to resize/re-render if it was hidden
                if (map) { // Assumes 'map' is globally defined and initialized
                    setTimeout(() => { map.invalidateSize(); }, 50);
                }
                return; // Exit, as we don't open the standard modal
            }


            const titles = {
                name: 'Edit Full Name',
                email: 'Edit Email',
                phone: 'Edit Phone Number',
                location: 'Edit Location',
                password: 'Change Password'
            };

            const currentValues = {
                name: document.getElementById('nameValue').textContent.trim(),
                email: document.getElementById('emailValue').textContent.trim(),
                phone: document.getElementById('phoneValue').textContent.trim(),
                password: '' 
            };

            document.getElementById('modalTitle').textContent = titles[field];
            document.getElementById('modalInput').value = currentValues[field] || '';
            
            if (field === 'password') {
                document.getElementById('modalInput').type = 'password';
                document.getElementById('modalInput').placeholder = 'Enter new password';
            } else if (field === 'email') {
                document.getElementById('modalInput').type = 'email';
                document.getElementById('modalInput').placeholder = 'Enter new email';
            } else {
                document.getElementById('modalInput').type = 'text';
                document.getElementById('modalInput').placeholder = 'Enter new value';
            }

            // Show the standard text modal for name, email, phone, and password
            modal.classList.remove('hidden');

            setTimeout(() => {
                const modalInput = document.getElementById('modalInput');
                if (modalInput) {
                    modalInput.focus();
                }
            }, 100);
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            currentField = '';
        }

        function closeLocationEdit() {
            document.getElementById('locationMapCard').classList.add('hidden');
            document.getElementById('profileSection').classList.remove('hidden');
        }


        function saveEdit() {
            const newValue = document.getElementById('modalInput').value.trim();
            
            if (!newValue) {
                alert('Please enter a value');
                return;
            }

            // 1. Update the display elements
            if (currentField === 'name') {
                document.getElementById('nameValue').textContent = newValue;
                document.getElementById('profileName').textContent = newValue;
            } else if (currentField === 'email') {
                document.getElementById('emailValue').textContent = newValue;
                document.getElementById('profileEmailDisplay').textContent = newValue;
            } else if (currentField === 'phone') {
                document.getElementById('phoneValue').textContent = newValue;
            } else if (currentField === 'password') {
                alert('Password changed successfully!');
            }
            
            closeEditModal();
        }

        function openPaymentPopup() {
            document.getElementById('paymentModal').classList.remove('hidden');
        }

        function closePaymentPopup() {
            document.getElementById('paymentModal').classList.add('hidden');
            clearPaymentForm();
        }

        function formatCardNumber(e) {
            let value = e.target.value.replace(/\s/g, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        }

        function formatExpiryDate(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.slice(0, 2) + '/' + value.slice(2, 4);
            }
            e.target.value = value;
        }

        function savePayment() {
            const cardName = document.getElementById('cardName').value.trim();
            const cardNumber = document.getElementById('cardNumber').value.trim();
            const expiry = document.getElementById('expiry').value.trim();
            const cvv = document.getElementById('cvv').value.trim();
            const billingAddress = document.getElementById('billingAddress').value.trim();

            if (!cardName || !cardNumber || !expiry || !cvv || !billingAddress) {
                alert('Please fill in all payment details');
                return;
            }

            const maskedCard = '**** **** **** ' + cardNumber.replace(/\s/g, '').slice(-4);
            
            const paymentList = document.getElementById('paymentList');
            
            if (paymentList.querySelector('p')) {
                paymentList.innerHTML = '';
            }

            const paymentCard = document.createElement('div');
            paymentCard.className = 'field-card';
            paymentCard.innerHTML = `
                <div class="field-info">
                    <div class="field-label">Credit Card</div>
                    <div class="field-value">${cardName}</div>
                    <div style="color: #e9d5ff; margin-top: 0.5rem;">${maskedCard}</div>
                    <div style="color: #c4b5fd; font-size: 0.9rem; margin-top: 0.25rem;">Expires: ${expiry}</div>
                    <div style="color: #c4b5fd; font-size: 0.9rem; margin-top: 0.25rem;">${billingAddress}</div>
                </div>
                <button class="edit-btn" onclick="this.parentElement.remove()" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">Remove</button>
            `;

            paymentList.appendChild(paymentCard);
            closePaymentPopup();
            alert('Payment method added successfully!');
        }

        function clearPaymentForm() {
            document.getElementById('cardName').value = '';
            document.getElementById('cardNumber').value = '';
            document.getElementById('expiry').value = '';
            document.getElementById('cvv').value = '';
            document.getElementById('billingAddress').value = '';
        }

        // Skill Modal Functions
        function openSkillModal() {
            document.getElementById('skillModal').classList.remove('hidden');
            renderSkills();
            displaySkills();
        }

        function closeSkillModal() {
            document.getElementById('skillModal').classList.add('hidden');
            clearSkillForm();
        }

        function renderSkills() {
            const skillList = document.getElementById('skillList');
            
            if (skills.length === 0) {
                skillList.innerHTML = '<p style="color: #e9d5ff; text-align: center; padding: 1rem;">No skills added yet.</p>';
                return;
            }

            skillList.innerHTML = skills.map(skill => `
                <div class="skill-item">
                    <div>
                        <div class="skill-name">${skill.name}</div>
                        <div class="skill-level">${skill.level}</div>
                    </div>
                    <div class="skill-actions">
                        <button class="icon-btn" onclick="editSkill(${skill.id})" title="Edit">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                            </svg>
                        </button>
                        <button class="icon-btn delete" onclick="deleteSkill(${skill.id})" title="Delete">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            `).join('');
        }

        // function displaySkills() {
        //     const skillDisplay = document.getElementById('skillDisplay');
            
        //     if (skills.length === 0) {
        //         skillDisplay.innerHTML = '<p style="color: #e9d5ff; text-align: center; padding: 2rem;">No skills added yet.</p>';
        //         return;
        //     }

        //     skillDisplay.innerHTML = skills.map(skill => `
        //         <div class="field-card">
        //             <div class="field-info">
        //                 <div class="field-label">${skill.name}</div>
        //                 <div class="field-value">${skill.level}</div>
        //             </div>
        //         </div>
        //     `).join('');
        // }

        function addSkill() {
            const name = document.getElementById('newSkillName').value.trim();
            const level = document.getElementById('newSkillLevel').value;

            if (!name) {
                alert('Please enter a skill name');
                return;
            }

            if (editingSkillId) {
                const skill = skills.find(s => s.id === editingSkillId);
                if (skill) {
                    skill.name = name;
                    skill.level = level;
                }
                editingSkillId = null;
            } else {
                skills.push({
                    id: nextSkillId++,
                    name: name,
                    level: level
                });
            }

            clearSkillForm();
            renderSkills();
            displaySkills();
        }

        function editSkill(id) {
            const skill = skills.find(s => s.id === id);
            if (skill) {
                document.getElementById('newSkillName').value = skill.name;
                document.getElementById('newSkillLevel').value = skill.level;
                editingSkillId = id;
            }
        }

        function deleteSkill(id) {
            if (confirm('Are you sure you want to delete this skill?')) {
                skills = skills.filter(s => s.id !== id);
                renderSkills();
                displaySkills();
            }
        }

        function clearSkillForm() {
            document.getElementById('newSkillName').value = '';
            document.getElementById('newSkillLevel').value = 'Beginner';
            editingSkillId = null;
        }

        // Event Listeners for Input Formatting
        document.addEventListener('DOMContentLoaded', function() {
            const cardNumberInput = document.getElementById('cardNumber');
            const expiryInput = document.getElementById('expiry');
            const cvvInput = document.getElementById('cvv');

            if (cardNumberInput) {
                cardNumberInput.addEventListener('input', formatCardNumber);
            }
            
            if (expiryInput) {
                expiryInput.addEventListener('input', formatExpiryDate);
            }
            
            if (cvvInput) {
                cvvInput.addEventListener('input', function(e) {
                    e.target.value = e.target.value.replace(/\D/g, '');
                });
            }

            // Display initial skills
            displaySkills();
        });

        // Close modals when clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.querySelector('.mobile-menu-toggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !menuToggle.contains(e.target) &&
                sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        });

        // --- LEAFLET CONFIG ---

        let map;
    let marker;
    let geocoder = L.Control.Geocoder.nominatim(); // Initialize here
    // Initial coordinates (Cambridge, MA)
    let currentCoords = { lat: 42.3736, lng: -71.1097 }; 
    // Stores the human-readable address fetched via reverse geocoding
    let savedLocationText = 'Cambridge, MA'; 
    // ---------------------

    // Section Navigation
    function showSection(section) {
        document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
        // Note: Added null check for robustness
        const menuItem = document.querySelector(`[onclick="showSection('${section}')"]`);
        if (menuItem) {
            menuItem.classList.add('active');
        }

        document.querySelectorAll('.main-section').forEach(s => s.classList.add('hidden'));
        document.getElementById(section + 'Section').classList.remove('hidden');

        // Close mobile menu (if implemented)
        const sidebar = document.getElementById('sidebar');
        if (sidebar && window.innerWidth <= 768) {
                sidebar.classList.remove('active');
        }
    }

// --- LEAFLET MAP FUNCTIONS ---
/**
 * Converts coordinates to a human-readable address using reverse geocoding
 * and updates the UI (locationValue element) and the global savedLocationText.
 */
async function reverseGeocodeAndUpdateUI(lat, lng) {
    const locationValueElement = document.getElementById('locationValue');
    
    locationValueElement.textContent = '... Resolving Address ...';

    try {
        const response = await fetch(
            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`
        );
        
        if (!response.ok) {
            throw new Error('Geocoding failed');
        }
        
        const data = await response.json();
        
        let addressToDisplay = `Lat: ${lat.toFixed(4)}, Lng: ${lng.toFixed(4)}`; 
        
        if (data && data.display_name) {
            addressToDisplay = data.display_name;
            
            if (data.address) {
                const addr = data.address;
                const parts = [
                    addr.city || addr.town || addr.village,
                    addr.state,
                    addr.country
                ].filter(Boolean);
                
                if (parts.length > 0) {
                    addressToDisplay = parts.join(', ');
                }
            }
        }
        
        // Update the visible text in the main profile section immediately
        locationValueElement.textContent = addressToDisplay;
        
        // Store the current address globally for when the SAVE button is clicked
        savedLocationText = addressToDisplay;
        
        console.log('Address resolved:', addressToDisplay);
        console.log('Full data:', data);
        
    } catch (error) {
        console.error('Reverse geocoding error:', error);
        const fallbackAddress = `Lat: ${lat.toFixed(4)}, Lng: ${lng.toFixed(4)}`;
        locationValueElement.textContent = fallbackAddress;
        savedLocationText = fallbackAddress;
    }
}

    // Function to update coordinates after drag or geocoding
    function onDragEnd() {
        const latLng = marker.getLatLng();
        currentCoords.lat = latLng.lat;
        currentCoords.lng = latLng.lng;
        
        console.log(`Marker dragged. New coords: Lat ${currentCoords.lat}, Lng ${currentCoords.lng}`);

    reverseGeocodeAndUpdateUI(currentCoords.lat, currentCoords.lng);
    
    map.panTo(latLng);
}

    /**
     * Saves the globally stored address and coordinates, finalizing the change
     * in the profile display.
     */
    async function saveMapLocation() {
        if (!savedLocationText || savedLocationText.includes('Resolving')) {
            alert('Please wait for the location address to finish resolving before saving.');
            return;
        }
        
        // 1. Update the display in the profile section permanently
        document.getElementById('locationValue').textContent = savedLocationText;
        
        // 2. Prepare data to send to server
        const locationData = {
            address: savedLocationText,
            latitude: currentCoords.lat,
            longitude: currentCoords.lng,
            userId: 'miawalker@yahoo.com' // Replace with actual user ID from session/auth
        };
        
        try {
            // 3. Send AJAX request to server
            const response = await fetch('https://joonh.sgedu.site/skillswap/backend/save-location.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(locationData)
            });
            
            // Check if request was successful
            if (!response.ok) {
                throw new Error(`Server error: ${response.status}`);
            }
            
            const result = await response.json();
            
            // 4. Handle server response
            if (result.success) {
                alert(`✅ Location successfully saved!\n${savedLocationText}\n(Lat: ${currentCoords.lat.toFixed(4)}, Lng: ${currentCoords.lng.toFixed(4)})`);
                console.log('Location saved successfully:', result);
            } else {
                alert(`❌ Failed to save location: ${result.message || 'Unknown error'}`);
                console.error('Save failed:', result);
            }
            
        } catch (error) {
            console.error('Error saving location:', error);
            alert(`❌ Error saving location: ${error.message}\n\nLocation is saved locally but not synced to server.`);
            
            // Still save locally even if server fails
            console.log('Location saved locally:', locationData);
        }
    }

    // Add this version for local testing (no server needed)
    function saveMapLocation() {
        if (!savedLocationText || savedLocationText.includes('Resolving')) {
            alert('Please wait for the location address to finish resolving before saving.');
            return;
        }
        
        document.getElementById('locationValue').textContent = savedLocationText;
        
        // Save to localStorage for local testing
        const locationData = {
            address: savedLocationText,
            latitude: currentCoords.lat,
            longitude: currentCoords.lng,
            timestamp: new Date().toISOString()
        };
        
        localStorage.setItem('userLocation', JSON.stringify(locationData));
        
        alert(`✅ Location saved locally!\n${savedLocationText}\n(Lat: ${currentCoords.lat.toFixed(4)}, Lng: ${currentCoords.lng.toFixed(4)})`);
        console.log('Saved location:', locationData);
    }
    // --- INITIALIZATION ---
    document.addEventListener('DOMContentLoaded', () => {
        
        // Set initial location field text
        document.getElementById('locationValue').textContent = savedLocationText;

        // --- LEAFLET INITIALIZATION ---
        map = L.map('profileMap').setView([currentCoords.lat, currentCoords.lng], 12);

        // 1. Add Tile Layer (OpenStreetMap)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // 2. Add Draggable Marker
        marker = L.marker([currentCoords.lat, currentCoords.lng], {
            draggable: true
        }).addTo(map);

        marker.on('dragend', onDragEnd);

        // 3. Add Geocoder (Search Box) - CORRECTED
        const nominatimGeocoding = L.Control.Geocoder.nominatim();
        geocoder = nominatimGeocoding; 
        
        const geocoderControl = L.Control.geocoder({
            geocoder: nominatimGeocoding,
            position: 'topleft', 
            placeholder: 'Search for your city or address...',
            defaultMarkGeocode: false  // Important: prevents default marker
        }).addTo(map);
        
        geocoderControl.on('markgeocode', function(e) {
            const center = e.geocode.center;
            
            // Update map view
            map.setView(center, 14);
            
            // Move existing marker to new position
            marker.setLatLng(center);
            
            // Update stored coordinates
            currentCoords.lat = center.lat;
            currentCoords.lng = center.lng;
            
            // Update the address display
            reverseGeocodeAndUpdateUI(currentCoords.lat, currentCoords.lng);
        });

        // Initial reverse geocode lookup to populate the field on load
        reverseGeocodeAndUpdateUI(currentCoords.lat, currentCoords.lng); 
    });

    function toggleMenu() {
        var nav = document.getElementById("myTopnav");
        nav.classList.toggle("responsive");
    }
    </script>
</body>
</html>