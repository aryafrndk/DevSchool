let slideIndex = 0;
      showSlides();

      function showSlides() {
        let i;
        let slides = document.getElementsByClassName("mySlides");
        for (i = 0; i < slides.length; i++) {
          slides[i].style.display = "none";
        }
        slideIndex++;
        if (slideIndex > slides.length) {
          slideIndex = 1;
        }
        slides[slideIndex - 1].style.display = "block";
        setTimeout(showSlides, 7000);
      }

      function plusSlides(n) {
        showSlides((slideIndex += n));
      }

      let currentSlide = 0;

      function moveSlide(step) {
        const slides = document.querySelectorAll(".carousel-slide");
        const totalSlides = slides.length;
        const slidesToShow = 3;
        const maxSlides = totalSlides - slidesToShow;

        currentSlide =
          (currentSlide + step + (maxSlides + 1)) % (maxSlides + 1);
        const offset = -currentSlide * (100 / slidesToShow);
        document.querySelector(
          ".carousel"
        ).style.transform = `translateX(${offset}%)`;
      }

      document.addEventListener("DOMContentLoaded", () => {
        moveSlide(0);
      });