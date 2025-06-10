document.getElementById("login-form").addEventListener("submit", function(e) {
    const data = {
      gebruikersnaam: document.getElementById("gebruikersnaam").value,
      wachtwoord: document.getElementById("wachtwoord").value
    };
  
    fetch("../php/login.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data)
    })
    .then(res => res.text())
    .then(msg => {
      document.getElementById("login-feedback").innerHTML = msg;
      if (msg.includes("Succes")) {
        window.location.href = "index.html";
      }
    })
    .catch(err => console.error("Fout:", err));
  });
fetch('../php/getNavbar.php')
  .then(res => res.text())
  .then(html => document.getElementById('navbar').innerHTML = html);

  