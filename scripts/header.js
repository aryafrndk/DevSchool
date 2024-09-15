// Load the header content
fetch("components/header.html")
  .then((response) => response.text())
  .then((data) => {
    document.getElementById("header").innerHTML = data;

    // Check login status after the header is loaded
    const isLoggedIn = localStorage.getItem('isLoggedIn');

    if (isLoggedIn) {
      document.getElementById('btn-masuk').style.display = 'none';
      document.getElementById('btn-daftar').style.display = 'none';
      document.getElementById('user-icon').style.display = 'block';
    } else {
      document.getElementById('btn-masuk').style.display = 'block';
      document.getElementById('btn-daftar').style.display = 'block';
      document.getElementById('user-icon').style.display = 'none';
    }
  })
  .catch((error) => console.error("Error loading header:", error));
