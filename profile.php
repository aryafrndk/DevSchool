<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile Settings - DevSchool</title>
    <link rel="stylesheet" href="styles/header.css" />
    <link rel="stylesheet" href="styles/footer.css" />
    <link rel="stylesheet" href="styles/profile-settings.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap"
      rel="stylesheet"
    />
    <style>
      .registration-card {
        padding: 20px;
        border: 1px solid #ccc;
        margin-top: 20px;
      }
    </style>
  </head>
  <body>
    <!-- Header Section -->
    <?php include 'components/header.php'; ?>

    <div class="settings-container">
      <!-- Sidebar Menu -->
      <nav class="sidebar">
        <ul>
          <li><a href="#">Profil</a></li>
        </ul>
      </nav>

      <!-- Profile Settings Section -->
      <section class="profile-settings">
        <h2>Profil Pengguna</h2>
        <form
          id="profileForm"
          action="php/process.php"
          method="POST"
          enctype="multipart/form-data"
        >
          <!-- Upload Foto Diri -->
          <div class="profile-pic">
            <img src="assets/user.svg" alt="Profile Picture" id="profile-pic" />
            <input type="file" id="profile-pic-input" name="profile-pic" />

            <p>
              Gambar profile Anda sebaiknya memiliki rasio 1:1 dan berukuran
              tidak lebih dari 2MB.
            </p>
          </div>

          <!-- Nama Lengkap -->
          <div class="form-group">
            <label for="full-name">Nama Lengkap *</label>
            <input
              type="text"
              id="full-name"
              name="full-name"
              autocomplete="name"
              required
            />
          </div>

          <!-- Jenis Kelamin -->
          <div class="form-group">
            <label for="gender">Jenis Kelamin *</label>
            <select id="gender" name="gender" required>
              <option value="pria">Pria</option>
              <option value="wanita">Wanita</option>
            </select>
          </div>

          <!-- Tempat Lahir -->
          <div class="form-group">
            <label for="birth-place">Tempat Lahir *</label>
            <input type="text" id="birth-place" name="birth-place" required />
          </div>

          <!-- Tanggal Lahir -->
          <div class="form-group">
            <label for="birth-date">Tanggal Lahir *</label>
            <input type="date" id="birth-date" name="birth-date" required />
          </div>

          <!-- Alamat -->
          <div class="form-group">
            <label for="address">Alamat *</label>
            <textarea id="address" name="address" required></textarea>
          </div>

          <!-- No HP -->
          <div class="form-group">
            <label for="phone-number">No HP *</label>
            <input type="tel" id="phone-number" name="phone-number" required />
          </div>

          <!-- Email -->
          <div class="form-group">
            <label for="email">Email *</label>
            <input
              type="email"
              id="email"
              name="email"
              autocomplete="email"
              required
            />
          </div>

          <!-- Hobi -->
          <div class="form-group">
            <label for="hobbies">Hobi</label>
            <input
              type="text"
              id="hobbies"
              name="hobbies"
              placeholder="Contoh: Memasak, Olahraga"
            />
          </div>

          <!-- Tentang Saya -->
          <div class="form-group">
            <label for="about-me">Tentang Saya</label>
            <textarea
              id="about-me"
              name="about-me"
              placeholder="Tulis cerita singkat tentang diri Anda"
            ></textarea>
          </div>

          <!-- Save Button -->
          <button type="submit" name="save" class="save-btn">
            Simpan Perubahan
          </button>
          <button type="button" class="view-card" onclick="openModal();">
            Lihat Kartu
          </button>
        </form>
      </section>
    </div>

    <div id="registrationModal" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <img
          id="profile-pic-display"
          src="assets/user.svg"
          alt="Foto Profil"
          class="profile-pic"
        />
        <h2 id="name-display">Nama Tidak Ada</h2>
        <p>
          <strong>Jenis Kelamin:</strong>
          <span id="gender-display">Tidak ada</span>
        </p>
        <p>
          <strong>Tempat Lahir:</strong>
          <span id="birth-place-display">Tidak ada</span>
        </p>
        <p>
          <strong>Tanggal Lahir:</strong>
          <span id="birth-date-display">Tidak ada</span>
        </p>
        <p>
          <strong>Alamat:</strong> <span id="address-display">Tidak ada</span>
        </p>
        <p>
          <strong>Nomor Telepon:</strong>
          <span id="phone-number-display">Tidak ada</span>
        </p>
        <p><strong>Email:</strong> <span id="email-display">Tidak ada</span></p>
        <p>
          <strong>Hobi:</strong> <span id="hobbies-display">Tidak ada</span>
        </p>
        <p>
          <strong>Tentang Saya:</strong>
          <span id="about-me-display">Tidak ada</span>
        </p>
      </div>
    </div>

    <!-- Footer -->
    <?php include 'components/footer.php'; ?>

    <script src="scripts/profile-settings.js"></script>
    <script src="scripts/header.js"></script>
    <script src="scripts/footer.js"></script>
  </body>
</html>
