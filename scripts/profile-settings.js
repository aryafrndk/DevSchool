document.getElementById('profile-pic-input').addEventListener('change', function(event) {
  const file = event.target.files[0];
  const img = document.getElementById('profile-pic'); 

  if (file) {
    const reader = new FileReader();

    reader.onload = function(e) {
      img.src = e.target.result;
    }

    reader.readAsDataURL(file);
  }
});

function openModal() {
  document.getElementById("registrationModal").style.display = "block";
}

function closeModal() {
  document.getElementById("registrationModal").style.display = "none";
}

document.getElementById('profileForm').addEventListener('submit', function(event) {
  event.preventDefault(); 

  const formData = new FormData(this);
  console.log('Form Data:', Array.from(formData.entries()));

  fetch('php/process.php', {
    method: 'POST',
    body: formData
  })
  .then(response => {
    console.log('Status:', response.status);
    return response.json();
  })
  .then(data => {
    console.log('Data dari server:', data);
    if (!data.success) {
      Swal.fire({
        title: 'Data Berhasil Disimpan!',
        text: data.message,
        icon: 'success',
        confirmButtonText: 'OK'
      }).then(() => {
        const fullName = document.getElementById('full-name').value;
        const gender = document.getElementById('gender').value;
        const birthPlace = document.getElementById('birth-place').value;
        const birthDate = document.getElementById('birth-date').value;
        const address = document.getElementById('address').value;
        const phoneNumber = document.getElementById('phone-number').value;
        const email = document.getElementById('email').value;
        const hobbies = document.getElementById('hobbies').value;
        const aboutMe = document.getElementById('about-me').value;
    
        // Update tampilan
        document.getElementById('name-display').innerText = fullName || 'Nama Tidak Ada';
        document.getElementById('gender-display').innerText = gender ? (gender === 'pria' ? 'Pria' : 'Wanita') : 'Tidak ada';
        document.getElementById('birth-place-display').innerText = birthPlace || 'Tidak ada';
        document.getElementById('birth-date-display').innerText = birthDate || 'Tidak ada';
        document.getElementById('address-display').innerText = address || 'Tidak ada';
        document.getElementById('phone-number-display').innerText = phoneNumber || 'Tidak ada';
        document.getElementById('email-display').innerText = email || 'Tidak ada';
        document.getElementById('hobbies-display').innerText = hobbies || 'Tidak ada';
        document.getElementById('about-me-display').innerText = aboutMe || 'Tidak ada';
    
        const modalContent = document.querySelector('.modal-content');
        modalContent.style.backgroundColor = data.backgroundColor;
        modalContent.style.color = data.fontColor;
        console.log('Warna diterapkan:', data.backgroundColor, data.fontColor); 
        });
    } else {
      Swal.fire({
        title: 'Data Gagal Disimpan!',
        text: data.message,
        icon: 'error',
        confirmButtonText: 'OK'
      });
    }
  })
  .catch(error => {
    Swal.fire({
      title: 'Error!',
      text: 'Terjadi kesalahan saat mengirim data ke server.',
      icon: 'error',
      confirmButtonText: 'OK'
    });
    console.error('Error:', error);
  });
});

window.onclick = function(event) {
  const modal = document.getElementById("registrationModal");
  if (event.target === modal) {
      closeModal();
  }
}
