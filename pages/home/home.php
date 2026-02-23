<?php 
include('../../config/config.php');

// Initialize variables 
 $success_message = ''; 
 $error_message = ''; 
 $edit_student = null; 

// Handle form submissions 
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    if (isset($_POST['action'])) { 
        switch ($_POST['action']) { 
            case 'add_student': 
                try { 
                    // Hash the password before storing
                    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                    $stmt = $pdo->prepare("INSERT INTO students (firstname, lastname, middlename, age, year_level, course, section, username, password, contactno, account_type) 
                                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"); 
                    
                    // Execute with correct order
                    $stmt->execute([ 
                        $_POST['firstname'], 
                        $_POST['lastname'], 
                        $_POST['middlename'] ?? null, 
                        $_POST['age'], 
                        $_POST['year_level'], 
                        $_POST['course'], 
                        $_POST['section'],
                        $_POST['username'],
                        $hashed_password, // Use hashed password
                        $_POST['contactno'],
                        $_POST['account_type']
                    ]); 
                    $success_message = "Student added successfully!"; 
                } catch(PDOException $e) { 
                    $error_message = "Error adding student: " . $e->getMessage(); 
                } 
                break; 
                        
            case 'edit_student': 
                try { 
                    // Hash the password if it's changed (or keep old one if blank - logic depends on requirements)
                    // For now, we assume password is always updated in this form
                    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                    $stmt = $pdo->prepare("UPDATE students SET firstname=?, lastname=?, middlename=?, age=?, year_level=?, course=?, section=?, username=?, password=?, contactno=?, account_type=? 
                                           WHERE id=?"); 
                    
                    // FIX: The ID must be the LAST item in the array because the WHERE clause is last
                    $stmt->execute([ 
                        $_POST['firstname'], 
                        $_POST['lastname'], 
                        $_POST['middlename'] ?? null, 
                        $_POST['age'], 
                        $_POST['year_level'], 
                        $_POST['course'], 
                        $_POST['section'], 
                        $_POST['username'],
                        $hashed_password, // Use hashed password
                        $_POST['contactno'],
                        $_POST['account_type'],
                        $_POST['id'] // <--- MOVED TO END
                    ]); 
                    $success_message = "Student updated successfully!"; 
                } catch(PDOException $e) { 
                    $error_message = "Error updating student: " . $e->getMessage(); 
                } 
                break; 
                        
            case 'delete_student': 
                try { 
                    $stmt = $pdo->prepare("DELETE FROM students WHERE id=?"); 
                    $stmt->execute([$_POST['id']]); 
                    $success_message = "Student deleted successfully!"; 
                } catch(PDOException $e) { 
                    $error_message = "Error deleting student: " . $e->getMessage(); 
                } 
                break; 
        } 
    } 
} 

// Handle edit request 
if (isset($_GET['edit_id'])) { 
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id=?"); 
    $stmt->execute([$_GET['edit_id']]); 
    $edit_student = $stmt->fetch(PDO::FETCH_ASSOC); 
} 

// Fetch all students 
 $stmt = $pdo->query("SELECT * FROM students ORDER BY id DESC"); 
 $students = $stmt->fetchAll(PDO::FETCH_ASSOC); 
?>  
<!DOCTYPE html> 
<html lang="en"> 

<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Management System</title> 

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="../../dist/css/font.css">
    <!-- BS Stepper -->
    <link rel="stylesheet" href="../../plugins/bs-stepper/css/bs-stepper.min.css"> 

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap4.css"> 

    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="../../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../dist/css/user_defined.css">
    <link rel="stylesheet" href="../../plugins/dropzone/min/dropzone.min.css" type="text/css" />
    <link rel="icon" type="image/png" sizes="40x16" href="../../dist/img/splogo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../../plugins/ekko-lightbox/ekko-lightbox.css"> 

    <!-- new -->
    <link rel="stylesheet" href="https://unpkg.com/intro.js/minified/introjs.min.css"> 

    <!-- Toastr -->
    <style>
        /* Center text in DataTables */ 

        /* form */ 

        /* submit button */ 
        .custom-submit-from-button {
            font-family: inherit;
            font-size: 26px;
            background: #4169e100;
            padding: 0.7em 1.2em;
            padding-left: 1.2em;
            padding-left: 0.9em;
            display: flex;
            font-weight: bold;
            align-items: center;
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.2s;
            cursor: pointer;
            background: #f00;
            color: white;
            height: 60px;
        } 

        .swal2-image {
            animation: fly-1 2s ease-in-out infinite alternate;
            /* Bouncing effect */
            width: 100px;
            margin-top: 40px;
            margin-bottom: -32px;
            /* Adjust the size as needed */
            height: 100px;
        } 

        .animate-fly {
            animation: fly-1 2s ease-in-out infinite alternate;
        } 

        @keyframes fly-1 {
            from {
                transform: translate(1em, 4px) rotate(-3deg);
            } 

            to {
                transform: translate(1em, 3px) rotate(3deg);
            }
        } 

        .datatable-header {
            min-width: 100%;
        } 

        .datatable-body {
            min-width: 100%;
        } 

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.9);
            z-index: 9999;
            /* Above Bootstrap's modal overlay */
        } 

        .overlay-content {
            position: absolute;
            top: 50%;
            left: 60%;
            transform: translate(-50%, -50%);
        } 

        /* Ensure .btn-close is visible on the dark background */
        .imageSpinner {
            filter: invert(1);
            mix-blend-mode: multiply;
            width: 30%;
        } 

        /* animate */
        .overlay {
            /* Other styles */
            display: none;
            /* Hidden by default */
            opacity: 0;
            transition: opacity .3s ease-in-out;
        } 

        .overlay.active {
            display: block;
            /* Show overlay */
            opacity: 1;
        } 

        #unit-masterlist-table-view tr td .view-modal {
            background-color: transparent;
        } 

        .nav-icon {
            margin-bottom: 2px;
        } 

        .text {
            font-size: 14px !important;
            color: #fff;
        } 

        .dropdown .nav-item .nav-link {
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
        } 

        .portrait {
            height: 100px !important;
        } 

        .portrait-sidebar {
            height: 32px !important;
        } 

        #table-body-unit {
            font-size: 15px;
            /* font-weight: bold; */
            text-align: center;
        } 

        .rounded-circle {
            border-radius: 30px !important;
        } 

        .table td {
            vertical-align: middle !important;
        } 

        .row-chassis {
            font-size: 12px;
        } 

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        } 

        input[type="number"] {
            -moz-appearance: textfield;
            appearance: textfield;
        } 

        .name {
            display: inline-block;
            width: 180px;
            white-space: nowrap;
            overflow: hidden !important;
            text-overflow: ellipsis;
        } 

        .ribbon-wrapper {
            height: 57px !important;
            right: -1px !important;
        } 

        .dz-upload {
            background-color: green;
            display: block;
            height: 10px;
            width: 0%;
        } 

        #container-image {
            display: inline-block;
            overflow: hidden;
            /* clip the excess when child gets bigger than parent */
        } 

        #container-image img {
            display: block;
            transition: transform .4s;
            /* smoother zoom */
        } 

        #container-image:hover img {
            transform: scale(1.3);
            transform-origin: 50% 50%;
        } 

        .nav-tabs .nav-item .nav-link {
            color: black
        } 

        .user-panel .info {
            display: inline-block;
            padding: 8px 5px 10px 10px;
        } 

        .user-panel img {
            height: 33px;
        } 

        .portrait-sidebar {
            height: 32px !important;
            width: 33px;
        } 

        #upload-adtl-file {
            width: 240px
        } 

        #table_masterlist.dataTable thead th,
        #table_pending_franchise.dataTable thead th,
        #table_expired_franchise.dataTable thead th,
        #table_about_expire_franchise.dataTable thead th,
        #table_active_franchise.dataTable thead th,
        #table_history_franchise.dataTable thead th,
        #table_data_entry_franchise.dataTable thead th {
            background-color: #343a40;
            border-color: #4b545c;
            /* Change this to your desired header color */
            color: white;
            text-align: center;
            /* Optional: change text color */
        } 

        #table_masterlist.dataTable tbody td,
        #table_pending_franchise.dataTable tbody td,
        #table_expired_franchise.dataTable tbody td,
        #table_about_expire_franchise.dataTable tbody td,
        #table_history_franchise.dataTable tbody td,
        #unit-masterlist-table-view.dataTable tbody td,
        #unit-masterlist-table-view.dataTable thead th { 
            text-align: center;
            /* Optional: change text color */
        } 

        .introjs-skipbutton {
            color: black;
            border-radius: 5px;
            border: none;
            cursor: pointer; 

            font-size: 17px;
            font-weight: lighter;
        } 

        /* Position the tooltip below the target element */
        .introjs-tooltip {
            position: absolute;
            top: 104% !important;
            /* Position below the target element */
            left: 10% !important;
            transform: translateX(-50%);
            /* Center the tooltip horizontally */
            max-width: 90vw;
            /* Ensure the tooltip doesn't overflow the viewport */
            width: auto;
            /* Adjust width to content */
            height: auto;
            /* Adjust height to content */
            padding: 20px;
            /* Add padding for content */
            box-sizing: border-box;
            /* Include padding in size calculations */
            overflow: hidden;
            /* Hide overflow content */
        } 

        /* Ensure tooltip text wraps properly */
        .introjs-tooltip .introjs-tooltiptext {
            word-wrap: break-word;
            white-space: normal;
        } 

        /* Position buttons within the tooltip */
        .introjs-tooltipbuttons {
            text-align: right;
            /* Align buttons to the right */
            margin-top: 10px;
            /* Add space between content and buttons */
        } 

        /* Ensure the tooltip is positioned correctly on small screens */
        @media (max-width: 768px) {
            .introjs-tooltip {
                padding: 15px;
                /* Reduce padding */
                font-size: 14px;
                /* Adjust font size */
                max-width: 80vw;
                /* Adjust max width on small screens */
            }
        } 

        #edit_application .nav .active {
            background-color: #6c757d;
            font-weight: bold;
            color: white;
            border-bottom: 0;
        } 

        #openViewModalBody .nav .active {
            background-color: #6c757d;
            font-weight: bold;
            color: white;
            margin-left: -1px;
            border-bottom: 0;
        } 

        /* Student Table Styles */
        #studentTable.dataTable thead th {
            background-color: #343a40;
            border-color: #4b545c;
            color: white;
            text-align: center;
        } 

        #studentTable.dataTable tbody td {
            text-align: center;
            vertical-align: middle !important;
        } 

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 5px;
        } 

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        /* Card heights for consistency */
        .card-body {
            max-height: 600px;
            overflow-y: auto;
        }
    </style> 

</head> 
<!-- oncontextmenu="return false" --> 

<body class="sidebar-mini layout-fixed" style="height: auto"> 

    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="" src="../../dist/img/itcsologo.webp" alt="AdminLTELogo" height="60" width="60">
        </div> 

        <!-- Navbar -->
        <nav class="main-header navbar sticky-top navbar-expand navbar-dark navbar-dark">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <!-- Right navbar links -->
            <div class="collapse navbar-collapse justify-content-end text-sm" id="navbarSupportedContent">
                <ul class="navbar-nav navbar-sidebar justify-content-end">
                    <!-- Notifications Dropdown Menu -->
                    <li class="nav-item">
                        <a class="nav-link text-sm" data-widget="fullscreen" href="#" role="button">
                            <i class="fas fa-expand-arrows-alt text-white"></i>
                        </a>
                    </li> 

                    <li class="nav-item dropdown">
                        <a class="nav-link text-sm pt-0 pb-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button">
                            <div class="image pt-0 pb-0">
                                <img src="../../dist/img/default.jfif" class="img-circle portrait-sidebar elevation-2" alt="User Image">
                            </div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" style="background-color: #495057 !important">
                            <div class="user-panel d-flex">
                                <div class="image">
                                    <img src="../../dist/img/default.jfif" class="img-circle elevation-2" alt="User Image">
                                </div>
                                <div class="info">
                                    <a href="#" class="d-block text-white text-sm">ADMIN USER</a>
                                </div>
                            </div>
                            <hr class="mt-1 mb-1">
                            <a class="nav-link text-sm sidebar-franchise-user-panel" style="padding-left: 13px;" role="button">
                                <i class="fa-solid fa-user-pen" style="background-color: rgb(16 16 16 / 42%); border-radius: 22px;padding: 7px 5px 5px 9px !important; height: 31px;"></i> &nbsp Edit Profile
                            </a>
                            <a class="nav-link text-sm" style="padding-left: 13px;" onclick="logout()" role="button">
                                <i class="fa-solid p-1 fa-right-from-bracket" style="background-color: rgb(16 16 16 / 42%); border-radius: 22px ; padding: 9px !important;"></i> &nbsp Logout
                            </a>
                        </div>
                    </li> 

                </ul>
            </div>
        </nav>
        <!-- /.navbar --> 

        <?php include '../../pages/sidebar/sidebar.php' ?> 

        <!-- body content --> 

        <div id="body_wrapper" class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Student Management System</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Students</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div> 

            <!-- PUT THE CONTENTS HERE -->
            <div class="content">
                <!-- Display messages -->
                <?php if ($success_message): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-check"></i> Success!</h5>
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?> 
                
                <?php if ($error_message): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?> 

                <div class="row">
                    <!-- Add Student Card -->
                    <div class="col-lg-6">
                        <div class="card card-success card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Add Students</h3>
                            </div>
                            <form id="addStudentForm" method="post" action="">
                                <input type="hidden" name="action" value="add_student">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="firstname">First Name</label>
                                                <input type="text" class="form-control" id="firstname" name="firstname" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="lastname">Last Name</label>
                                                <input type="text" class="form-control" id="lastname" name="lastname" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="middlename">Middle Name</label>
                                                <input type="text" class="form-control" id="middlename" name="middlename">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="age">Age</label>
                                                <input type="number" class="form-control" id="age" name="age" min="1" max="100" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="year_level">Year Level</label>
                                                <select class="form-control" id="year_level" name="year_level" required>
                                                    <option value="">Select Year Level</option>
                                                    <option value="1st Year">1st Year</option>
                                                    <option value="2nd Year">2nd Year</option>
                                                    <option value="3rd Year">3rd Year</option>
                                                    <option value="4th Year">4th Year</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="course">Course</label>
                                                <input type="text" class="form-control" id="course" name="course" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="section">Section</label>
                                                <input type="text" class="form-control" id="section" name="section" required>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- New Fields -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="username">Username</label>
                                                <input type="text" class="form-control" id="username" name="username" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="password" class="form-control" id="password" name="password" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="contactno">Contact No.</label>
                                                <input type="text" class="form-control" id="contactno" name="contactno" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="account_type">Account Type</label>
                                                <select class="form-control" id="account_type" name="account_type" required>
                                                    <option value="">Select Type</option>
                                                    <option value="Student">Student</option>
                                                    <option value="Teacher">Teacher</option>
                                                    <option value="Admin">Admin</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Add Student
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div> 

                    <!-- Student List Card -->
                    <div class="col-lg-6">
                        <div class="card card-danger card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Student Lists</h3>
                            </div>
                            <div class="card-body">
                                <table id="studentTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Full Name</th>
                                            <th>Year</th>
                                            <th>Section</th>
                                            <th>Account Type</th>
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($students)): ?>
                                            <?php foreach ($students as $student): ?>
                                                <tr>
                                                    <td><?php echo $student['id']; ?></td>
                                                    <td><?php echo $student['firstname'] . ' ' . $student['lastname']; ?></td>
                                                    <td><?php echo $student['year_level']; ?></td>
                                                    <td><?php echo $student['section']; ?></td>
                                                    <td><?php echo $student['account_type']; ?></td>
                                                    <td>
                                                        <div class="action-buttons">
                                                            <a href="?edit_id=<?php echo $student['id']; ?>" class="btn btn-warning btn-sm">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $student['id']; ?>)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center">No students found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> 

                    <!-- Edit Student Card -->
                    <div class="col-12">
                        <div class="card card-secondary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Edit Students</h3>
                            </div>
                            <?php if ($edit_student): ?>
                                <form id="editStudentForm" method="post" action="">
                                    <input type="hidden" name="action" value="edit_student">
                                    <input type="hidden" name="id" value="<?php echo $edit_student['id']; ?>">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="edit_firstname">First Name</label>
                                                    <input type="text" class="form-control" id="edit_firstname" name="firstname" 
                                                            value="<?php echo $edit_student['firstname']; ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="edit_lastname">Last Name</label>
                                                    <input type="text" class="form-control" id="edit_lastname" name="lastname" 
                                                            value="<?php echo $edit_student['lastname']; ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="edit_middlename">Middle Name</label>
                                                    <input type="text" class="form-control" id="edit_middlename" name="middlename" 
                                                            value="<?php echo $edit_student['middlename']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="edit_age">Age</label>
                                                    <input type="number" class="form-control" id="edit_age" name="age" 
                                                            value="<?php echo $edit_student['age']; ?>" min="1" max="100" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="edit_year_level">Year Level</label>
                                                    <select class="form-control" id="edit_year_level" name="year_level" required>
                                                        <option value="">Select Year Level</option>
                                                        <option value="1st Year" <?php echo $edit_student['year_level'] == '1st Year' ? 'selected' : ''; ?>>1st Year</option>
                                                        <option value="2nd Year" <?php echo $edit_student['year_level'] == '2nd Year' ? 'selected' : ''; ?>>2nd Year</option>
                                                        <option value="3rd Year" <?php echo $edit_student['year_level'] == '3rd Year' ? 'selected' : ''; ?>>3rd Year</option>
                                                        <option value="4th Year" <?php echo $edit_student['year_level'] == '4th Year' ? 'selected' : ''; ?>>4th Year</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="edit_course">Course</label>
                                                    <input type="text" class="form-control" id="edit_course" name="course" 
                                                            value="<?php echo $edit_student['course']; ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="edit_section">Section</label>
                                                    <input type="text" class="form-control" id="edit_section" name="section" 
                                                            value="<?php echo $edit_student['section']; ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- New Edit Fields -->
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="edit_username">Username</label>
                                                    <input type="text" class="form-control" id="edit_username" name="username" 
                                                            value="<?php echo $edit_student['username']; ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="edit_password">Password</label>
                                                    <input type="password" class="form-control" id="edit_password" name="password" 
                                                            value="<?php echo $edit_student['password']; ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="edit_contactno">Contact No.</label>
                                                    <input type="text" class="form-control" id="edit_contactno" name="contactno" 
                                                            value="<?php echo $edit_student['contactno']; ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="edit_account_type">Account Type</label>
                                                    <select class="form-control" id="edit_account_type" name="account_type" required>
                                                        <option value="">Select Type</option>
                                                        <option value="Student" <?php echo $edit_student['account_type'] == 'Student' ? 'selected' : ''; ?>>Student</option>
                                                        <option value="Teacher" <?php echo $edit_student['account_type'] == 'Teacher' ? 'selected' : ''; ?>>Teacher</option>
                                                        <option value="Admin" <?php echo $edit_student['account_type'] == 'Admin' ? 'selected' : ''; ?>>Admin</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-right">
                                        <a href="" class="btn btn-default">Cancel</a>
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-save"></i> Update Student
                                        </button>
                                    </div>
                                </form>
                            <?php else: ?>
                                <div class="card-body">
                                    <p class="text-muted">Select a student from the list to edit</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div> 

    </div> 

    <div class="overlay" id="myOverlay">
        <div class="overlay-content">
            <img src="../../dist/img/load.gif" class="imageSpinner" alt="" srcset="">
            <!-- Your content here -->
        </div>
    </div> 

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
        <div class="p-3">
            <h5>Title</h5>
            <p>Sidebar content</p>
        </div>
    </aside>
    <!-- /.control-sidebar --> 

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="float-right d-none d-sm-inline">
            All rights reserved
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; 2024 Student Management System.</strong>
    </footer>
    </div>
    <!-- ./wrapper --> 

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this student?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="post" action="" style="display: inline;">
                        <input type="hidden" name="action" value="delete_student">
                        <input type="hidden" id="delete_id" name="id">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div> 

    <!-- REQUIRED SCRIPTS --> 

    <!-- jQuery -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- BS-Stepper -->
    <script src="../../plugins/bs-stepper/js/bs-stepper.min.js"></script> 

    <!-- AdminLTE App -->
    <script src="../../dist/js/adminlte.min.js"></script> 

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script> 

    <!-- <script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script> -->
    <script src="../../plugins/pdfmake/vfs_fonts.js"></script>
    <script src="../../plugins/dropzone/min/dropzone.min.js"></script>
    <script src="../../plugins/validate.js-master/validate.min.js"></script>
    <script src="../../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <script src="../../plugins/fontawesomekit/a757e6f388.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>
    <script src="../../plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script> 

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 

    <!-- new  -->
    <script src="https://unpkg.com/intro.js/minified/intro.min.js"></script> 

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#studentTable').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "ordering": true,
                "info": true,
                "paging": true,
                "pageLength": 5
            }); 

            // Show success/error messages with SweetAlert
            <?php if ($success_message): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '<?php echo $success_message; ?>',
                    timer: 3000,
                    showConfirmButton: false
                });
            <?php endif; ?> 
            
            <?php if ($error_message): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '<?php echo $error_message; ?>',
                    timer: 3000,
                    showConfirmButton: false
                });
            <?php endif; ?>
        }); 

        function confirmDelete(id) {
            $('#delete_id').val(id);
            $('#deleteModal').modal('show');
        } 

        function logout() {
            Swal.fire({
                title: 'Logout',
                text: 'Are you sure you want to logout?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, logout!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'logout.php';
                }
            });
        } 

        // Show overlay when form is submitted
        $('form').on('submit', function() {
            $('#myOverlay').addClass('active');
        });
    </script> 

</body> 

</html>