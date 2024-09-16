document.getElementById('upload-btn').addEventListener('click', function () {
    document.getElementById('profile-pic-input').click();
  });
  
  document.getElementById('profile-pic-input').addEventListener('change', function (event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById('profile-pic-preview').src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  });
  
  document.querySelector('form').addEventListener('submit', function (event) {
    event.preventDefault();
    alert('Perubahan berhasil disimpan!');
  });
  
  document.addEventListener('DOMContentLoaded', function() {
  const saveButton = document.getElementById('save-btn');
  
  saveButton.addEventListener('click', function() {
    const fullName = document.getElementById('full-name').value;
    const gender = document.getElementById('gender').value;
    const birthPlace = document.getElementById('birth-place').value;
    const birthDate = document.getElementById('birth-date').value;
    const address = document.getElementById('address').value;
    const phoneNumber = document.getElementById('phone-number').value;
    const email = document.getElementById('email').value;
    const hobbies = document.getElementById('hobbies').value;

    if (!fullName || !gender || !birthPlace || !birthDate || !address || !phoneNumber || !email|| !hobbies) {
      alert('Semua bidang wajib diisi.');
    } else {
      alert('Data berhasil disimpan.');
    }
  });
});
