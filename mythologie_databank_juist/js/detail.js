document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');
  
    // Navbar ophalen
    fetch('../php/getNavbar.php')
      .then(res => res.text())
      .then(html => document.getElementById('navbar').innerHTML = html);
  
    // Detail ophalen
    fetch(`../php/getDetail.php?id=${encodeURIComponent(id)}`)
      .then(res => res.text())
      .then(html => document.getElementById('detail-content').innerHTML = html)
      .catch(err => {
        document.getElementById('detail-content').innerHTML = "<p>Fout bij ophalen van detail.</p>";
        console.error(err);
      });
  });
  