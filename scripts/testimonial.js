fetch('php/testimonial.php')
  .then(response => response.json())
  .then(data => {
    const carouselContainer = document.querySelector('.carousel');
    carouselContainer.innerHTML = ''; // Clear existing content
    
    data.forEach(item => {
      const slide = document.createElement('div');
      slide.classList.add('carousel-slide');
      
      slide.innerHTML = `
        <img src="${item.photo}" alt="Foto ${item.name}" class="carousel-image" />
        <blockquote>
          <p>"${item.testimonial}"</p>
          <p id="nama">- ${item.name}, ${item.role}</p>
        </blockquote>
      `;
      
      carouselContainer.appendChild(slide);
    });
  });

let currentSlide = 0;

function moveSlide(step) {
  const slides = document.querySelectorAll(".carousel-slide");
  const totalSlides = slides.length;
  const slidesToShow = 3;
  const maxSlides = totalSlides - slidesToShow;

  currentSlide = (currentSlide + step + (maxSlides + 1)) % (maxSlides + 1);
  const offset = -currentSlide * (100 / slidesToShow);
  document.querySelector(
    ".carousel"
  ).style.transform = `translateX(${offset}%)`;
}

document.addEventListener("DOMContentLoaded", () => {
  moveSlide(0);
});