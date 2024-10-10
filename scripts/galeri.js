document.addEventListener("DOMContentLoaded", function () {
  function fetchGaleri() {
    fetch('php/galeri.php') // URL file PHP yang mengambil data dari database
      .then(response => response.json())
      .then(data => {
        let slideshowContainer = document.querySelector('.slideshow-container');
        slideshowContainer.innerHTML = ''; 

        if (data.length === 0) {
          console.error('No images found in the gallery.');
          return; // Exit if there are no images
        }

        data.forEach((foto, index) => {
          let slide = `
            <div class="mySlides fade">
              <img src="${foto.url_foto}" class="slide-image" />
              <figcaption>${foto.deskripsi_foto}</figcaption>
            </div>
          `;
          slideshowContainer.innerHTML += slide;
        });

        // Tambahkan tombol navigasi
        let prevButton = `<a class="prev" onclick="plusSlides(-1)">&#10094;</a>`;
        let nextButton = `<a class="next" onclick="plusSlides(1)">&#10095;</a>`;
        slideshowContainer.innerHTML += prevButton + nextButton;

        showSlides(); // Call showSlides after slides are added to the DOM
      })
      .catch(error => console.error('Error fetching data:', error));
  }

  fetchGaleri(); 
});

let slideIndex = 0;

function showSlides() {
  let slides = document.getElementsByClassName("mySlides");

  // Only proceed if there are slides available
  if (slides.length === 0) {
    console.error('No slides available to show.');
    return; // Exit if no slides exist
  }

  // Hide all slides
  for (let i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }

  slideIndex++;
  
  if (slideIndex > slides.length) {
    slideIndex = 1; // Loop back to the first slide
  }

  slides[slideIndex - 1].style.display = "block"; // Show the current slide

  setTimeout(showSlides, 7000); // Change slide every 7 seconds
}

function plusSlides(n) {
  let slides = document.getElementsByClassName("mySlides");

  if (slides.length === 0) {
    return; // Exit if no slides exist
  }

  slideIndex += n;

  if (slideIndex > slides.length) {
    slideIndex = 1; // Loop back to the first slide
  } else if (slideIndex < 1) {
    slideIndex = slides.length; // Loop to the last slide
  }

  // Hide all slides and show the current slide
  for (let i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }

  slides[slideIndex - 1].style.display = "block"; // Show the current slide
}
