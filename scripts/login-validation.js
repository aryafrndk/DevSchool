document.getElementById("loginForm").addEventListener("submit", function (event) {
  event.preventDefault();

  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;
  const errorMessage = document.getElementById("error-message");

  if (email === "test@example.com" && password === "123") {
    localStorage.setItem("isLoggedIn", true);

    window.location.href = "index.html"; 
  } else {
    errorMessage.textContent = "Email atau kata sandi salah.";
  }
});
