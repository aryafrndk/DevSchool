document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get("id");

    fetch("data/programs.json")
      .then((response) => response.json())
      .then((data) => {
        const program = data.programs.find((p) => p.id == id);
        if (program) {
          const container = document.getElementById("programDetail");
          const pelatihList = program.details.pelatih
            .map(
              (pelatih) => `
            <li>
              <strong>${pelatih.nama}</strong> - ${pelatih.jabatan}, ${pelatih.pengalaman}
            </li>
          `
            )
            .join("");

          const kurikulumList = program.details.kurikulum
            .map((kurikulum) => `<li>${kurikulum}</li>`)
            .join("");

          container.innerHTML = `
          <div class="border-box">
            <h2>${program.title}</h2>
            <img src="${program.imageUrl}" alt="${program.altText}" class="program-detail-img" />
          </div>

          <div class="border-box">
            <h3>Description</h3>
            <p>${program.description}</p>
          </div>

          <div class="border-box">
            <h3>Kurikulum</h3>
            <ul>${kurikulumList}</ul>
          </div>

          <div class="border-box">
            <h3>Jadwal</h3>
            <p>${program.details.jadwal}</p>
          </div>

          <div class="border-box">
            <h3>Biaya</h3>
            <p>${program.details.biaya}</p>
          </div>

          <div class="border-box">
            <h3>Pelatih</h3>
            <ul>${pelatihList}</ul>
          </div>

          <div class="border-box">
            <h3>Syarat</h3>
            <p>${program.details.syarat}</p>
          </div>

          <a href="" class="btn-primary">Daftar Program</a>
        `;
        } else {
          document.getElementById(
            "programDetail"
          ).innerHTML = `<p>Program not found.</p>`;
        }
      })
      .catch((error) =>
        console.error("Error fetching the JSON data:", error)
      );
  });