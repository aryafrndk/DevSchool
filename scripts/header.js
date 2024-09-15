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

    const userIcon = document.getElementById('user-icon');
    const dropdownMenu = document.getElementById('dropdown-menu');
    
    userIcon.addEventListener('click', function (event) {
      event.preventDefault();
      userIcon.classList.toggle('active');
      dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
    });

    // Close the dropdown if clicked outside
    document.addEventListener('click', function (event) {
      if (!userIcon.contains(event.target)) {
        dropdownMenu.style.display = 'none';
        userIcon.classList.remove('active');
      }
    });

    // Example of logout functionality
    document.getElementById('logout').addEventListener('click', function() {
      localStorage.removeItem('isLoggedIn'); 
      window.location.href = 'masuk.html'; 
    });
  })
  .catch((error) => console.error("Error loading header:", error));
