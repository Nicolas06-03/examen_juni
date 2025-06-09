document.addEventListener('DOMContentLoaded', () => {
    // Navbar ophalen
    fetch('../php/getNavbar.php')
      .then(res => res.text())
      .then(html => document.getElementById('navbar').innerHTML = html);
  
    // Dieren ophalen
    fetch('../php/getDieren.php')
      .then(res => res.text())
      .then(html => document.getElementById('dieren').innerHTML = html);
  });
  