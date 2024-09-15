document.getElementById("loginForm").addEventListener("submit", function (event) {
  event.preventDefault();

  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;
  const errorMessage = document.getElementById("error-message");

  // Dummy login validation (for demonstration)
  if (email === "test@example.com" && password === "123") {
    // Store login state in localStorage
    localStorage.setItem("isLoggedIn", true);

    // Redirect to home page or reload the current page
    window.location.href = "index.html"; // Change the URL if needed
  } else {
    // Show error message
    errorMessage.textContent = "Email atau kata sandi salah.";
  }
});
