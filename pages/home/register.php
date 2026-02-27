<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSRPOS - New Member Registration</title>
    <style>
        :root {
            --primary-color: #0056b3; /* Professional Blue */
            --primary-hover: #004494;
            --bg-color: #f0f2f5;
            --card-bg: #ffffff;
            --text-color: #333333;
            --text-light: #666666;
            --border-color: #dddddd;
            --error-color: #e74c3c;
            --success-color: #2ecc71;
            --input-radius: 6px;
            --btn-radius: 6px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--bg-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* Container for the Registration Card */
        .registration-container {
            background-color: var(--card-bg);
            width: 100%;
            max-width: 600px;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: var(--primary-color);
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }

        .header p {
            color: var(--text-light);
            font-size: 14px;
        }

        /* Form Grid Layout */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        /* Responsive adjustment for smaller screens */
        @media (max-width: 500px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .registration-container {
                padding: 25px;
            }
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 5px; /* Grid handles spacing mostly */
            position: relative;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-color);
        }

        /* Input Styling */
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"],
        select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: var(--input-radius);
            font-size: 14px;
            transition: all 0.3s ease;
            outline: none;
            background-color: #fafafa;
        }

        input:focus, select:focus {
            border-color: var(--primary-color);
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(0, 86, 179, 0.1);
        }

        /* Validation Styling */
        input.invalid, select.invalid {
            border-color: var(--error-color);
            background-color: #fff6f6;
        }

        .error-message {
            color: var(--error-color);
            font-size: 11px;
            margin-top: 4px;
            display: none;
        }

        input.invalid + .error-message, select.invalid + .error-message {
            display: block;
        }

        /* Password Toggle Icon */
        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            background: none;
            border: none;
            padding: 0;
        }

        .toggle-password:hover {
            color: var(--primary-color);
        }

        /* Terms Checkbox */
        .terms-group {
            grid-column: 1 / -1;
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .terms-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            cursor: pointer;
            accent-color: var(--primary-color);
        }

        .terms-group label {
            font-weight: 400;
            margin-bottom: 0;
            cursor: pointer;
            color: var(--text-light);
        }

        .terms-group a {
            color: var(--primary-color);
            text-decoration: none;
        }

        /* Buttons */
        .btn-register {
            grid-column: 1 / -1;
            background-color: var(--primary-color);
            color: white;
            padding: 14px;
            border: none;
            border-radius: var(--btn-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease, transform 0.1s ease;
        }

        .btn-register:hover {
            background-color: var(--primary-hover);
        }

        .btn-register:active {
            transform: scale(0.98);
        }

        .btn-register:disabled {
            background-color: #a0c4e8;
            cursor: not-allowed;
            transform: none;
        }

        /* Footer Link */
        .login-link {
            grid-column: 1 / -1;
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
            color: var(--text-light);
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* Toast Notification */
        #toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .toast {
            display: flex;
            align-items: center;
            min-width: 250px;
            background: white;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            margin-bottom: 10px;
            border-left: 5px solid;
            opacity: 0;
            transform: translateX(50px);
            transition: all 0.3s ease;
        }

        .toast.show {
            opacity: 1;
            transform: translateX(0);
        }

        .toast.success { border-left-color: var(--success-color); }
        .toast.error { border-left-color: var(--error-color); }

        .toast-icon {
            margin-right: 12px;
            font-size: 20px;
        }
        
        .toast.success .toast-icon { color: var(--success-color); }
        .toast.error .toast-icon { color: var(--error-color); }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            margin-right: 8px;
            vertical-align: middle;
            display: none;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

    </style>
</head>
<body>

    <div id="toast-container"></div>

    <div class="registration-container">
        <div class="header">
            <h1>CSRPOS</h1>
            <p>Create a new membership account</p>
        </div>

        <form id="registerForm" novalidate>
            <div class="form-grid">
                
                <!-- ID Number -->
                <div class="form-group">
                    <label for="idNumber">ID Number</label>
                    <input type="text" id="idNumber" name="idNumber" placeholder="Enter ID Number" required>
                    <span class="error-message">ID Number is required</span>
                </div>

                <!-- Full Name -->
                <div class="form-group">
                    <label for="fullName">Full Name</label>
                    <input type="text" id="fullName" name="fullName" placeholder="John Doe" required>
                    <span class="error-message">Full Name is required</span>
                </div>

                <!-- Department/Office -->
                <div class="form-group">
                    <label for="department">Department/Office</label>
                    <input type="text" id="department" name="department" placeholder="e.g. Sales, IT" required>
                    <span class="error-message">Department is required</span>
                </div>

                <!-- Select User Type -->
                <div class="form-group">
                    <label for="userType">Select User Type</label>
                    <select id="userType" name="userType" required>
                        <option value="" disabled selected>Choose User Type</option>
                        <option value="admin">Administrator</option>
                        <option value="manager">Manager</option>
                        <option value="cashier">Cashier</option>
                        <option value="staff">Staff</option>
                    </select>
                    <span class="error-message">Please select a user type</span>
                </div>

                <!-- Username -->
                <div class="form-group full-width">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Choose a username" required>
                    <span class="error-message">Username is required</span>
                </div>

                <!-- Email -->
                <div class="form-group full-width">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="example@company.com" required>
                    <span class="error-message">Please enter a valid email address</span>
                </div>

                <!-- Contact Number -->
                <div class="form-group full-width">
                    <label for="contactNumber">Contact Number</label>
                    <input type="tel" id="contactNumber" name="contactNumber" placeholder="e.g. +1 234 567 8900" required>
                    <span class="error-message">Contact number is required</span>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="Create password" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('password', this)" aria-label="Toggle password visibility">
                            <!-- Eye Icon SVG -->
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
                    </div>
                    <span class="error-message">Password is required</span>
                </div>

                <!-- Retype Password -->
                <div class="form-group">
                    <label for="retypePassword">Retype Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="retypePassword" name="retypePassword" placeholder="Confirm password" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('retypePassword', this)" aria-label="Toggle password visibility">
                            <!-- Eye Icon SVG -->
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
                    </div>
                    <span class="error-message">Passwords do not match</span>
                </div>

                <!-- Terms -->
                <div class="terms-group">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">I agree to the <a href="#">terms</a> and conditions</label>
                </div>
                <!-- Hidden error for checkbox logic handled in JS -->
                <div id="terms-error" class="error-message" style="margin-top: 5px; display: none;">You must agree to the terms.</div>

                <!-- Register Button -->
                <button type="submit" class="btn-register" id="submitBtn">
                    <span class="spinner"></span>
                    <span class="btn-text">Register</span>
                </button>

                <!-- Login Link -->
                <div class="login-link">
                    I already have a membership <a href="#">Login</a>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Toggle Password Visibility
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('svg');
            
            if (input.type === "password") {
                input.type = "text";
                // Slash eye icon
                icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
            } else {
                input.type = "password";
                // Normal eye icon
                icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
            }
        }

        // Toast Notification System
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            const iconSymbol = type === 'success' ? '✔' : '✖';
            
            toast.innerHTML = `
                <div class="toast-icon">${iconSymbol}</div>
                <div class="toast-message">${message}</div>
            `;
            
            container.appendChild(toast);
            
            // Trigger animation
            requestAnimationFrame(() => {
                toast.classList.add('show');
            });
            
            // Remove after 3 seconds
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }

        // Form Validation & Submission
        const form = document.getElementById('registerForm');
        const submitBtn = document.getElementById('submitBtn');
        const spinner = submitBtn.querySelector('.spinner');
        const btnText = submitBtn.querySelector('.btn-text');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            let isValid = true;
            const inputs = form.querySelectorAll('input[required], select[required]');
            
            // Reset previous errors
            inputs.forEach(input => input.classList.remove('invalid'));
            document.getElementById('terms-error').style.display = 'none';

            // 1. Check Required Fields & Email Format
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('invalid');
                    isValid = false;
                } else if (input.type === 'email' && !validateEmail(input.value)) {
                    input.classList.add('invalid');
                    isValid = false;
                }
            });

            // 2. Check Password Match
            const pass = document.getElementById('password');
            const retype = document.getElementById('retypePassword');
            if (pass.value !== retype.value) {
                retype.classList.add('invalid');
                isValid = false;
            }

            // 3. Check Terms Checkbox
            const terms = document.getElementById('terms');
            if (!terms.checked) {
                document.getElementById('terms-error').style.display = 'block';
                isValid = false;
            }

            if (!isValid) {
                showToast('Please correct the errors in the form.', 'error');
                return;
            }

            // Simulate API Call
            setLoading(true);

            setTimeout(() => {
                setLoading(false);
                showToast('Registration successful! Redirecting...', 'success');
                form.reset();
            }, 2000);
        });

        // Helper: Email Regex
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(String(email).toLowerCase());
        }

        // Helper: Loading State
        function setLoading(isLoading) {
            if (isLoading) {
                submitBtn.disabled = true;
                spinner.style.display = 'inline-block';
                btnText.textContent = 'Registering...';
            } else {
                submitBtn.disabled = false;
                spinner.style.display = 'none';
                btnText.textContent = 'Register';
            }
        }

        // Remove invalid class on input
        document.querySelectorAll('input, select').forEach(input => {
            input.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('invalid');
                }
            });
        });
    </script>
</body>
</html>