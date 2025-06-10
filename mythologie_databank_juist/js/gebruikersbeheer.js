window.addEventListener("DOMContentLoaded", () => {
    fetchGebruikers();
});

fetch('../php/getNavbar.php')
    .then(res => res.text())
    .then(html => document.getElementById('navbar').innerHTML = html);


function fetchGebruikers() {
    fetch("../php/getUsers.php")
        .then(res => res.json())
        .then(data => {
            const goedgekeurdEl = document.getElementById("goedgekeurde-gebruikers");
            const pendingEl = document.getElementById("pending-gebruikers");

            goedgekeurdEl.innerHTML = "";
            pendingEl.innerHTML = "";

            data.gekeurd.forEach(user => {
                const li = document.createElement("li");
                li.innerHTML = `${user.username} <button onclick="verwijderGebruiker(${user.id})">Verwijder</button>`;
                goedgekeurdEl.appendChild(li);
            });

            data.pending.forEach(user => {
                const li = document.createElement("li");
                li.innerHTML = `${user.username} <button onclick="bevestigGebruiker(${user.id})">Bevestig</button>`;
                pendingEl.appendChild(li);
            });
        });
}

function bevestigGebruiker(id) {
    fetch("../php/confirmUser.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id })
    }).then(fetchGebruikers);
}

function verwijderGebruiker(id) {
    fetch("../php/deleteUsers.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id })
    }).then(fetchGebruikers);
}
