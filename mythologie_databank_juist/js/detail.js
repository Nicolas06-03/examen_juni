document.addEventListener('DOMContentLoaded', () => {
  const params = new URLSearchParams(window.location.search);
  const id = params.get('id');

  fetch('../php/getNavbar.php')
    .then(res => res.text())
    .then(html => {
      document.getElementById('navbar').innerHTML = html;
    });

  fetch(`../php/getDetail.php?id=${encodeURIComponent(id)}`)
    .then(res => res.text())
    .then(html => {
      document.getElementById('detail-content').innerHTML = html;

      const form = document.getElementById('edit-form');
      if (form) {
        form.addEventListener('submit', e => {
          e.preventDefault();

          const title = document.getElementById('title').value;
          const texte = document.getElementById('texte').value;
          const imageFile = document.getElementById('image').files[0];

          const formData = new FormData();
          formData.append('id', id);
          formData.append('title', title);
          formData.append('texte', texte);
          if (imageFile) formData.append('image', imageFile);

          fetch('../php/updateDetail.php', {
            method: 'POST',
            body: formData
          })
            .then(res => res.text())
            .then(response => {
              alert(response);
              return fetch(`../php/getDetail.php?id=${encodeURIComponent(id)}`);
            })
            .then(res => res.text())
            .then(updatedHtml => {
              document.getElementById('detail-content').innerHTML = updatedHtml;
            });
        });
      }
    })
    .catch(err => {
      document.getElementById('detail-content').innerHTML = "<p>Fout bij laden.</p>";
      console.error(err);
    });
    
    document.addEventListener('submit', function (e) {
      if (e.target && e.target.id === 'edit-form') {
        e.preventDefault();
        const form = e.target;
        const id = form.dataset.id;
    
        const data = {
          id: id,
          title: form.title.value,
          text: form.text.value
        };
    
        fetch('../php/updateDetail.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(data)
        })
          .then(res => res.text())
          .then(msg => {
            alert(msg);
            location.reload();
          })
          .catch(err => console.error(err));
      }
    });
    
});
