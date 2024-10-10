document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const prog = params.get('prog');
  
    if (prog === 'selesai') {
      document.getElementById('aktif-programs').style.display = 'none';
      document.getElementById('selesai-programs').style.display = 'block';
      document.querySelector('.program-nav .active').classList.remove('active');
      document.querySelector('.program-nav a[href="?prog=selesai"]').classList.add('active');
    } else {
      document.getElementById('aktif-programs').style.display = 'block';
      document.getElementById('selesai-programs').style.display = 'none';
      document.querySelector('.program-nav .active').classList.remove('active');
      document.querySelector('.program-nav a[href="?prog=aktif"]').classList.add('active');
    }
  });
  