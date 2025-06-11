document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);
    const landId = params.get('land_id');

    fetch('../php/getNavbar.php')
        .then(res => res.text())
        .then(html => {
            document.getElementById('navbar').innerHTML = html;
    });
    fetch(`../php/getVerhalenPerLand.php?land_id=${encodeURIComponent(landId)}`)
        .then(res => res.json())
        .then(verhalen => {
            const container = document.getElementById("verhalen-lijst");
            verhalen.forEach(v => {
                const card = document.createElement("div");
                card.className = "col-md-4 mb-3";
                card.innerHTML = `
                    <div class="card">
                        <img src="../images/${v.id}.jpg" class="card-img-top" alt="${v.title}">
                        <div class="card-body">
                            <h5 class="card-title">${v.title}</h5>
                            <p class="card-text">${v.synopsis}</p>
                            <a href="detail.html?id=${v.id}" class="btn btn-danger">Lees meer</a>
                        </div>
                    </div>`;
                container.appendChild(card);
            });
        });
});
