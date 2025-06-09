document.getElementById("signup-form").addEventListener("submit", function(e) {
    e.preventDefault();
  
    const data = {
      gebruikersnaam: document.getElementById("gebruikersnaam").value,
      wachtwoord: document.getElementById("wachtwoord").value
    };
  
    fetch("../php/signup.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data)
    })
    .then(res => res.text())
    .then(msg => document.getElementById("signup-feedback").innerHTML = msg)
    .catch(err => console.error("Fout:", err));
  });
    // Navbar ophalen
fetch('../php/getNavbar.php')
  .then(res => res.text())
  .then(html => document.getElementById('navbar').innerHTML = html);
    