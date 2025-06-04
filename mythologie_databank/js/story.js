// Ophalen van het ID via het HTML data-attribuut
const storyId = document.documentElement.dataset.storyId;

// Verhaal ophalen via PHP
fetch(`../php/get_story.php?id=${storyId}`)
  .then(response => response.json())
  .then(data => {
    document.getElementById('story-title').textContent = data.title;
    document.getElementById('story-period').textContent = data.period;
    document.getElementById('story-synopsis').textContent = data.synopsis;
    document.getElementById('story-text').innerHTML = data.text;

    // Landen
    const countriesContainer = document.getElementById('story-countries');
    data.countries.forEach(country => {
      const badge = document.createElement('span');
      badge.className = 'badge bg-secondary me-1';
      badge.textContent = country;
      countriesContainer.appendChild(badge);
    });

    // Mythische wezens
    const creatureList = document.getElementById('story-creatures');
    data.creatures.forEach(creature => {
      const li = document.createElement('li');
      const a = document.createElement('a');
      a.href = `creature_detail.html?id=${creature.id}`;
      a.textContent = creature.name;
      li.appendChild(a);
      creatureList.appendChild(li);
    });
  })
  .catch(error => {
    console.error('Fout bij ophalen van verhaal:', error);
  });
