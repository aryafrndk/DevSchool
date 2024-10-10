document.addEventListener("DOMContentLoaded", function () {
    function fetchBerita() {
        fetch('php/berita.php')
            .then(response => response.json())
            .then(data => {
                let beritaContainer = document.getElementById('berita-container');
                beritaContainer.innerHTML = '';

                data.slice(0, 3).forEach(berita => { 
                    let card = `
                        <div class="card">
                            <div class="card-header">
                                <span class="category">${berita.nama_kategori}</span>
                                <h3 class="card-title">${berita.judul_berita}</h3>
                            </div>
                            <img src="${berita.foto_berita}" alt="${berita.judul_berita}" class="card-image">
                            <p class="release-date">${new Date(berita.tanggal_publikasi).toLocaleDateString()}</p>
                            <p class="card-content">${berita.isi_berita}</p>
                            <a href="berita-detail.php?id_berita=${berita.id_berita}" class="read-more">Baca Selengkapnya</a>
                        </div>
                    `;
                    beritaContainer.innerHTML += card;
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    fetchBerita();
});
