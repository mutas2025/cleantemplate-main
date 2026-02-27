<?php 
include('../../config/config.php');

// Initialize variables 
 $success_message = ''; 
 $error_message = ''; 
 $edit_member = null; 

// Handle form submissions 
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    if (isset($_POST['action'])) { 
        switch ($_POST['action']) { 
            case 'add_member': 
                try { 
                    // Hash the password before storing
                    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    
                    // Handle Terms Checkbox (1 if checked, 0 if not)
                    $terms_agreed = isset($_POST['terms']) ? 1 : 0;

                    $stmt = $pdo->prepare("INSERT INTO tbl_users (id_number, full_name, department, user_type, username, email, contact_number, password_hash, terms_agreed) 
                                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"); 
                    
                    $stmt->execute([ 
                        $_POST['id_number'], 
                        $_POST['full_name'],
                        $_POST['department'], 
                        $_POST['user_type'],
                        $_POST['username'],
                        $_POST['email'],
                        $_POST['contact_number'],
                        $hashed_password, 
                        $terms_agreed
                    ]); 
                    $success_message = "Member added successfully!"; 
                } catch(PDOException $e) { 
                    $error_message = "Error adding member: " . $e->getMessage(); 
                } 
                break; 
                        
            case 'edit_member': 
                try { 
                    // Hash password if changed, else keep old (For this example, we assume update)
                    // In a real app, check if password field is empty to keep old hash.
                    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    
                    $terms_agreed = isset($_POST['terms']) ? 1 : 0;

                    $stmt = $pdo->prepare("UPDATE tbl_users SET id_number=?, full_name=?, department=?, user_type=?, username=?, email=?, contact_number=?, password_hash=?, terms_agreed=? 
                                           WHERE user_id=?"); 
                    
                    // Note: user_id is the primary key in the new schema
                    $stmt->execute([ 
                        $_POST['id_number'], 
                        $_POST['full_name'],
                        $_POST['department'], 
                        $_POST['user_type'],
                        $_POST['username'],
                        $_POST['email'],
                        $_POST['contact_number'],
                        $hashed_password,
                        $terms_agreed,
                        $_POST['user_id'] // The ID to update
                    ]); 
                    $success_message = "Member updated successfully!"; 
                } catch(PDOException $e) { 
                    $error_message = "Error updating member: " . $e->getMessage(); 
                } 
                break; 
                        
            case 'delete_member': 
                try { 
                    $stmt = $pdo->prepare("DELETE FROM tbl_users WHERE user_id=?"); 
                    $stmt->execute([$_POST['user_id']]); 
                    $success_message = "Member deleted successfully!"; 
                } catch(PDOException $e) { 
                    $error_message = "Error deleting member: " . $e->getMessage(); 
                } 
                break; 
        } 
    } 
} 

// Handle edit request 
if (isset($_GET['edit_id'])) { 
    $stmt = $pdo->prepare("SELECT * FROM tbl_users WHERE user_id=?"); 
    $stmt->execute([$_GET['edit_id']]); 
    $edit_member = $stmt->fetch(PDO::FETCH_ASSOC); 
} 

// Fetch all members 
 $stmt = $pdo->query("SELECT * FROM tbl_users ORDER BY user_id DESC"); 
 $members = $stmt->fetchAll(PDO::FETCH_ASSOC); 
?>  
<!DOCTYPE html> 
<html lang="en"> 

<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CSRPOS - Member Management</title> 

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

    <style>
        /* Existing styles from your file */
        .swal2-image {
            animation: fly-1 2s ease-in-out infinite alternate;
            width: 100px;
            margin-top: 40px;
            margin-bottom: -32px;
            height: 100px;
        } 

        .animate-fly {
            animation: fly-1 2s ease-in-out infinite alternate;
        } 

        @keyframes fly-1 {
            from { transform: translate(1em, 4px) rotate(-3deg); } 
            to { transform: translate(1em, 3px) rotate(3deg); }
        } 

        .datatable-header { min-width: 100%; } 
        .datatable-body { min-width: 100%; } 

        .overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(0, 0, 0, 0.9);
            z-index: 9999;
            display: none;
            opacity: 0;
            transition: opacity .3s ease-in-out;
        } 

        .overlay.active { display: block; opacity: 1; } 

        .imageSpinner {
            filter: invert(1);
            mix-blend-mode: multiply;
            width: 30%;
        } 

        .nav-icon { margin-bottom: 2px; } 
        .text { font-size: 14px !important; color: #fff; } 

        .portrait { height: 100px !important; } 
        .portrait-sidebar { height: 32px !important; } 

        #memberTable.dataTable thead th {
            background-color: #343a40;
            border-color: #4b545c;
            color: white;
            text-align: center;
        } 

        #memberTable.dataTable tbody td {
            text-align: center;
            vertical-align: middle !important;
        } 

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 5px;
        } 

        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; }
        
        .card-body {
            max-height: 600px;
            overflow-y: auto;
        }
    </style> 

</head> 
<body class="sidebar-mini layout-fixed" style="height: auto"> 

    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="" src="../../dist/img/itcsologo.webp" alt="AdminLTELogo" height="60" width="60">
        </div> 

        <!-- Navbar -->
        <nav class="main-header navbar sticky-top navbar-expand navbar-dark navbar-dark">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <div class="collapse navbar-collapse justify-content-end text-sm" id="navbarSupportedContent">
                <ul class="navbar-nav navbar-sidebar justify-content-end">
                    <li class="nav-item">
                        <a class="nav-link text-sm" data-widget="fullscreen" href="#" role="button">
                            <i class="fas fa-expand-arrows-alt text-white"></i>
                        </a>
                    </li> 
                    <li class="nav-item dropdown">
                        <a class="nav-link text-sm pt-0 pb-0" data-toggle="dropdown" role="button">
                            <div class="image pt-0 pb-0">
                                <img src="../../dist/img/default.jfif" class="img-circle portrait-sidebar elevation-2" alt="User Image">
                            </div>
                        </a>
                        <div class="dropdown-menu" style="background-color: #495057 !important">
                            <div class="user-panel d-flex">
                                <div class="image">
                                    <img src="../../dist/img/default.jfif" class="img-circle elevation-2" alt="User Image">
                                </div>
                                <div class="info">
                                    <a href="#" class="d-block text-white text-sm">ADMIN USER</a>
                                </div>
                            </div>
                            <hr class="mt-1 mb-1">
                            <a class="nav-link text-sm" style="padding-left: 13px;" onclick="logout()" role="button">
                                <i class="fa-solid p-1 fa-right-from-bracket" style="background-color: rgb(16 16 16 / 42%); border-radius: 22px ; padding: 9px !important;"></i> &nbsp Logout
                            </a>
                        </div>
                    </li> 
                </ul>
            </div>
        </nav>

        <?php include '../../pages/sidebar/sidebar.php' ?> 

        <div id="body_wrapper" class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">CSRPOS Member Management</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Members</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div> 

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
                    <!-- Add Member Card -->
                    <div class="col-lg-6">
                        <div class="card card-success card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Add New Member</h3>
                            </div>
                            <form id="addMemberForm" method="post" action="">
                                <input type="hidden" name="action" value="add_member">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="id_number">ID Number</label>
                                                <input type="text" class="form-control" id="id_number" name="id_number" placeholder="e.g. EMP-001" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="full_name">Full Name</label>
                                                <input type="text" class="form-control" id="full_name" name="full_name" placeholder="John Doe" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="department">Department/Office</label>
                                                <input type="text" class="form-control" id="department" name="department" placeholder="e.g. IT, Sales" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="user_type">User Type</label>
                                                <select class="form-control" id="user_type" name="user_type" required>
                                                    <option value="">Select User Type</option>
                                                    <option value="admin">Administrator</option>
                                                    <option value="manager">Manager</option>
                                                    <option value="cashier">Cashier</option>
                                                    <option value="staff">Staff</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

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
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" id="email" name="email" placeholder="example@company.com" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="contact_number">Contact Number</label>
                                                <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="+1 234 567 8900" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="terms" name="terms" value="1">
                                            <label for="terms">
                                                I agree to the <a href="#">terms</a>
                                            </label>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Register Member
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div> 

                    <!-- Member List Card -->
                    <div class="col-lg-6">
                        <div class="card card-danger card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Member List</h3>
                            </div>
                            <div class="card-body">
                                <table id="memberTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>ID No.</th>
                                            <th>Name</th>
                                            <th>Role</th>
                                            <th>Dept</th>
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($members)): ?>
                                            <?php foreach ($members as $member): ?>
                                                <tr>
                                                    <td><?php echo $member['user_id']; ?></td>
                                                    <td><?php echo htmlspecialchars($member['id_number']); ?></td>
                                                    <td><?php echo htmlspecialchars($member['full_name']); ?></td>
                                                    <td>
                                                        <span class="badge badge-info">
                                                            <?php echo ucfirst($member['user_type']); ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($member['department']); ?></td>
                                                    <td>
                                                        <div class="action-buttons">
                                                            <a href="?edit_id=<?php echo $member['user_id']; ?>" class="btn btn-warning btn-sm">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $member['user_id']; ?>)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center">No members found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> 

                    <!-- Edit Member Card -->
                    <div class="col-12">
                        <div class="card card-secondary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Edit Member Details</h3>
                            </div>
                            <?php if ($edit_member): ?>
                                <form id="editMemberForm" method="post" action="">
                                    <input type="hidden" name="action" value="edit_member">
                                    <input type="hidden" name="user_id" value="<?php echo $edit_member['user_id']; ?>">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="edit_id_number">ID Number</label>
                                                    <input type="text" class="form-control" id="edit_id_number" name="id_number" 
                                                            value="<?php echo htmlspecialchars($edit_member['id_number']); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="edit_full_name">Full Name</label>
                                                    <input type="text" class="form-control" id="edit_full_name" name="full_name" 
                                                            value="<?php echo htmlspecialchars($edit_member['full_name']); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="edit_department">Department</label>
                                                    <input type="text" class="form-control" id="edit_department" name="department" 
                                                            value="<?php echo htmlspecialchars($edit_member['department']); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="edit_user_type">User Type</label>
                                                    <select class="form-control" id="edit_user_type" name="user_type" required>
                                                        <option value="">Select User Type</option>
                                                        <option value="admin" <?php echo $edit_member['user_type'] == 'admin' ? 'selected' : ''; ?>>Administrator</option>
                                                        <option value="manager" <?php echo $edit_member['user_type'] == 'manager' ? 'selected' : ''; ?>>Manager</option>
                                                        <option value="cashier" <?php echo $edit_member['user_type'] == 'cashier' ? 'selected' : ''; ?>>Cashier</option>
                                                        <option value="staff" <?php echo $edit_member['user_type'] == 'staff' ? 'selected' : ''; ?>>Staff</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="edit_username">Username</label>
                                                    <input type="text" class="form-control" id="edit_username" name="username" 
                                                            value="<?php echo htmlspecialchars($edit_member['username']); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="edit_password">New Password</label>
                                                    <input type="password" class="form-control" id="edit_password" name="password" 
                                                            placeholder="Leave blank to keep current" value="">
                                                    <small class="text-muted">Enter new password to change</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="edit_email">Email</label>
                                                    <input type="email" class="form-control" id="edit_email" name="email" 
                                                            value="<?php echo htmlspecialchars($edit_member['email']); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="edit_contact_number">Contact Number</label>
                                                    <input type="text" class="form-control" id="edit_contact_number" name="contact_number" 
                                                            value="<?php echo htmlspecialchars($edit_member['contact_number']); ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="edit_terms" name="terms" value="1" <?php echo $edit_member['terms_agreed'] ? 'checked' : ''; ?>>
                                                <label for="edit_terms">
                                                    Terms Agreed
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-right">
                                        <a href="" class="btn btn-default">Cancel</a>
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-save"></i> Update Member
                                        </button>
                                    </div>
                                </form>
                            <?php else: ?>
                                <div class="card-body">
                                    <p class="text-muted">Select a member from the list to edit their details</p>
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
        </div>
    </div> 

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <div class="p-3">
            <h5>Title</h5>
            <p>Sidebar content</p>
        </div>
    </aside>

    <!-- Main Footer -->
    <footer class="main-footer">
        <div class="float-right d-none d-sm-inline">
            All rights reserved
        </div>
        <strong>Copyright &copy; 2024 CSRPOS System.</strong>
    </footer>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this member? This cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="post" action="" style="display: inline;">
                        <input type="hidden" name="action" value="delete_member">
                        <input type="hidden" id="delete_id" name="user_id">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div> 

    <!-- REQUIRED SCRIPTS --> 
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../dist/js/adminlte.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script> 

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#memberTable').DataTable({
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