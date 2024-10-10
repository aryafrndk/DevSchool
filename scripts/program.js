fetch("php/get-programs.php")
  .then((response) => response.json())
  .then((data) => {
    const programs = data.programs;
    const programCardsContainer = document.getElementById("programCards");

    programs.forEach((program) => {
      const card = document.createElement("div");
      card.classList.add("card");

      card.innerHTML = `
              <img src="${program.image_url}" class="card-img-top" alt="${program.altText}" />
              <div class="card-body">
                <h5 class="card-title">${program.nama_program}</h5>
                <p class="card-text">${program.deskripsi}</p>
                <a href="program-detail.php?id_program=${program.id_program}" class="btn btn-primary">${program.button_text}</a>
              </div>
            `;

      programCardsContainer.appendChild(card);
    });
  })
  .catch((error) => console.error("Error fetching the program data:", error));
