document
  .getElementById("loginForm")
  .addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent form submission

    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const errorMessage = document.getElementById("error-message");

    const existingUsers = JSON.parse(localStorage.getItem("users")) || [];

    const user = existingUsers.find(
      (user) => user.email === email && user.password === password
    );
    if (user) {
      window.location.href = "index.html";
    } else {
      errorMessage.textContent =
        "Email atau kata sandi salah. Silakan coba lagi.";
    }
  });
