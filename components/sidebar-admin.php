<div class="sidebar">
    <ul>
        <li><a href="manage-user.php">Data Pengguna</a></li>
        <li><a href="manage-trainers.php">Data Tenaga Pelatih</a></li>
        <li><a href="manage-programs.php">Data Program Pelatihan</a></li>
        <li><a href="manage-user_program.php">Data Program Pengguna</a></li>
        <li><a href="manage-news.php">Data Berita</a></li>
        <li><a href="manage-testimonials.php">Data Testimonial</a></li>
    </ul>
</div>

<script>
    const sidebarLinks = document.querySelectorAll('.sidebar ul li a');

    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            sidebarLinks.forEach(item => item.classList.remove('active'));

            this.classList.add('active');
        });
    });

    const currentPage = window.location.pathname;

    sidebarLinks.forEach(link => {
        if (link.href.includes(currentPage)) {
            link.classList.add('active');
        }
    });
</script>
