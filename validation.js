// validation.js

// Email Validation
function validateEmail(email) {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return regex.test(email);
}

// Password Validation
function validatePassword(password) {
  return (
    password.length >= 8 &&
    /[A-Z]/.test(password) &&
    /[a-z]/.test(password) &&
    /\d/.test(password) &&
    /[@$!%*?&]/.test(password)
  );
}

// Username Validation
function validateUsername(username) {
  const regex = /^[a-zA-Z0-9]+$/;
  return regex.test(username);
}

// Export functions for testing
module.exports = { validateEmail, validatePassword, validateUsername };
