document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');

    fetch('../php/getNavbar.php')
        .then(res => res.text())
        .then(html => {
            document.getElementById('navbar').innerHTML = html;
    });

    fetch(`../php/getWezendetail.php?id=${encodeURIComponent(id)}`)
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById("wezen-detail");

            if(data.error) {
                container.innerHTML = `<p>${data.error}</p>`;
                return;
            }

            // Maak links van categorieën (indien je een filterpagina wilt)
            const categoriesHtml = data.categories.length
                ? `<h5>Categorieën:</h5><ul>` +
                  data.categories.map(cat => 
                    `<li>${cat.name}</li>`).join('') +
                  `</ul>`
                : `<p>Geen categorieën gevonden.</p>`;

            const imagePath = data.image_url ? `../images/${data.image_url}` : '../images/default.jpg';

            container.innerHTML = `
                <h2>${data.name}</h2>
                <img src="${imagePath}" class="img-fluid mb-3" alt="${data.name}">
                <p>${data.description}</p>
                ${categoriesHtml}
            `;
        });
});
