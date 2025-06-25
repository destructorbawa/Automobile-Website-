<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include database connection file
include 'db.php';

// Signup Handling
if (isset($_POST['signupSubmit'])) {
    $username = $_POST['signupUsername'];
    $email = $_POST['signupEmail'];
    $password = password_hash($_POST['signupPassword'], PASSWORD_DEFAULT);

    // Check if email already exists
    $checkEmailQuery = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $checkEmailQuery);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email already exists! Please use a different email.');</script>";
    } else {
        // Insert new user into database
        $insertQuery = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if (mysqli_query($conn, $insertQuery)) {
            echo "<script>alert('Signup successful! You can now log in.');</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
    }
}

// Login Handling
if (isset($_POST['loginSubmit'])) {
    $email = $_POST['loginEmail'];
    $password = $_POST['loginPassword'];

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            echo "<script>alert('Login successful!'); window.location.href='index.html';</script>";
        } else {
            echo "<script>alert('Invalid password!');</script>";
        }
    } else {
        echo "<script>alert('Email not found!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Automobile Login/Signup</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Poppins', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #f0f2f5;
      overflow: hidden;
    }

    .container {
      display: flex;
      width: 75%;
      height: 80%;
      background: linear-gradient(135deg, #667eea, #764ba2);
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }

    .form-section {
      width: 35%;
      padding: 50px;
      background-color: #2b2e4a;
      display: flex;
      flex-direction: column;
      justify-content: center;
      color: #ffffff;
      position: relative;
    }

    .brand-logo {
      font-size: 32px;
      font-weight: bold;
      color: #ffffff;
      text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
      position: absolute;
      top: 20px;
      left: 40px;
      font-family: 'Segoe UI', sans-serif;
    }

    input[type="text"], input[type="password"], input[type="email"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #6a6a9f;
      border-radius: 5px;
      background-color: #3d3d5c;
      color: #ffffff;
      font-size: 16px;
    }

    input:focus {
      border-color: #667eea;
      outline: none;
      box-shadow: 0 0 5px #667eea;
    }

    button {
      width: 100%;
      padding: 12px;
      background: #667eea;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background: #575fcf;
    }

    .toggle-link {
      color: #a4b0be;
      cursor: pointer;
      font-size: 14px;
      margin-top: 15px;
      display: block;
      text-align: center;
    }

    .error {
      color: #ff6b6b;
      font-size: 12px;
      margin-top: -8px;
      margin-bottom: 10px;
      text-align: left;
      display: none;
    }

    .success {
      color: #4caf50;
      font-size: 16px;
      margin-top: 10px;
      text-align: center;
    }

    .hidden {
      display: none;
    }

    .image-section {
      width: 65%;
      background-image: url('Images/loginimage.jpg'); 
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Left Form Section -->
    <div class="form-section">
      <div class="brand-logo">Automobile</div>

      <!-- Login Form -->
      <div id="loginFormContainer">
        <form method="POST" action="" onsubmit="return validateLogin()">
          <input type="email" id="loginEmail" name="loginEmail" placeholder="Email" required oninput="validateEmail()">
          <span id="loginEmailError" class="error">Please enter a valid email address.</span>

          <input type="password" id="loginPassword" name="loginPassword" placeholder="Password" required oninput="validatePassword()">
          <span id="loginPasswordError" class="error">Password must be at least 6 characters long.</span>

          <button type="submit" name="loginSubmit" id="loginSubmit" disabled>Log In</button>
        </form>
        <a class="toggle-link" onclick="showSignup()">Sign up</a>
      </div>

      <!-- Signup Form -->
      <div id="signupFormContainer" class="hidden">
        <form method="POST" action="" onsubmit="return validateSignup()">
            <input type="text" id="signupUsername" name="signupUsername" placeholder="Username" required oninput="validateUsername()">
            <span id="signupUsernameError" class="error">Must include both alphabets and numeric characters.</span>

            <input type="email" id="signupEmail" name="signupEmail" placeholder="Email" required oninput="validateSignupEmail()">
            <span id="signupEmailError" class="error">Please enter a valid email address.</span>

            <input type="password" id="signupPassword" name="signupPassword" placeholder="Password" required oninput="validateSignupPassword()">
            <span id="signupPasswordError" class="error">Must include uppercase, lowercase, numeric, and special characters..</span>

            <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required oninput="validateConfirmPassword()">
            <span id="confirmPasswordError" class="error">Passwords do not match.</span>

            <button type="submit" name="signupSubmit" id="signupSubmit" disabled>Sign Up</button>
        </form>
        <a class="toggle-link" onclick="showLogin()">Login</a>
      </div>
    </div>

    <!-- Right Image Section -->
    <div class="image-section"></div>
  </div>

  <script>
    // Email validation for login
    function validateEmail() {
      const email = document.getElementById('loginEmail').value;
      const emailError = document.getElementById('loginEmailError');
      const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

      if (emailPattern.test(email)) {
        emailError.style.display = 'none';
      } else {
        emailError.style.display = 'inline';
      }
      toggleLoginSubmit();
    }

    // Password validation for login
    function validatePassword() {
      const password = document.getElementById('loginPassword').value;
      const passwordError = document.getElementById('loginPasswordError');

      if (password.length >= 6) {
        passwordError.style.display = 'none';
      } else {
        passwordError.style.display = 'inline';
      }
      toggleLoginSubmit();
    }

    // Enable submit button for login
    function toggleLoginSubmit() {
      const emailError = document.getElementById('loginEmailError').style.display === 'none';
      const passwordError = document.getElementById('loginPasswordError').style.display === 'none';

      if (emailError && passwordError) {
        document.getElementById('loginSubmit').disabled = false;
      } else {
        document.getElementById('loginSubmit').disabled = true;
      }
    }

    // Username validation for signup
    function validateUsername() {
      const username = document.getElementById('signupUsername').value;
      const usernameError = document.getElementById('signupUsernameError');

      if (username.length >= 3) {
        usernameError.style.display = 'none';
      } else {
        usernameError.style.display = 'inline';
      }
      toggleSignupSubmit();
    }

    // Email validation for signup
    function validateSignupEmail() {
      const email = document.getElementById('signupEmail').value;
      const emailError = document.getElementById('signupEmailError');
      const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

      if (emailPattern.test(email)) {
        emailError.style.display = 'none';
      } else {
        emailError.style.display = 'inline';
      }
      toggleSignupSubmit();
    }

    // Password validation for signup
    function validateSignupPassword() {
      const password = document.getElementById('signupPassword').value;
      const passwordError = document.getElementById('signupPasswordError');

      if (password.length >= 6) {
        passwordError.style.display = 'none';
      } else {
        passwordError.style.display = 'inline';
      }
      toggleSignupSubmit();
    }

    // Confirm password validation for signup
    function validateConfirmPassword() {
      const password = document.getElementById('signupPassword').value;
      const confirmPassword = document.getElementById('confirmPassword').value;
      const confirmPasswordError = document.getElementById('confirmPasswordError');

      if (password === confirmPassword) {
        confirmPasswordError.style.display = 'none';
      } else {
        confirmPasswordError.style.display = 'inline';
      }
      toggleSignupSubmit();
    }

    // Enable submit button for signup
    function toggleSignupSubmit() {
      const usernameError = document.getElementById('signupUsernameError').style.display === 'none';
      const emailError = document.getElementById('signupEmailError').style.display === 'none';
      const passwordError = document.getElementById('signupPasswordError').style.display === 'none';
      const confirmPasswordError = document.getElementById('confirmPasswordError').style.display === 'none';

      if (usernameError && emailError && passwordError && confirmPasswordError) {
        document.getElementById('signupSubmit').disabled = false;
      } else {
        document.getElementById('signupSubmit').disabled = true;
      }
    }

    // Show the signup form
    function showSignup() {
      document.getElementById("loginFormContainer").classList.add("hidden");
      document.getElementById("signupFormContainer").classList.remove("hidden");
    }

    // Show the login form
    function showLogin() {
      document.getElementById("signupFormContainer").classList.add("hidden");
      document.getElementById("loginFormContainer").classList.remove("hidden");
    }
  </script>
</body>
</html>


