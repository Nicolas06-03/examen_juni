<?php
session_start();
include("connection.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo "Ongeldige ID.";
    exit;
}

$id = intval($_GET['id']);

// Ophalen verhaalgegevens
$stmt = $conn->prepare("SELECT * FROM stories WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if (!$row = $result->fetch_assoc()) {
    echo "<p>Verhaal niet gevonden.</p>";
    exit;
}

// Ophalen gekoppelde landen en wezens
$landQuery = $conn->prepare("
    SELECT countries.id, countries.name 
    FROM story_country 
    JOIN countries ON story_country.country_id = countries.id 
    WHERE story_country.story_id = ?
");
$landQuery->bind_param("i", $id);
$landQuery->execute();
$landenResult = $landQuery->get_result();

$wezenQuery = $conn->prepare("
    SELECT creatures.id, creatures.name 
    FROM story_creature 
    JOIN creatures ON story_creature.creature_id = creatures.id 
    WHERE story_creature.story_id = ?
");
$wezenQuery->bind_param("i", $id);
$wezenQuery->execute();
$wezensResult = $wezenQuery->get_result();

// Alle landen en wezens (voor checkboxes)
$allCountries = $conn->query("SELECT id, name FROM countries ORDER BY name");
$allCreatures = $conn->query("SELECT id, name FROM creatures ORDER BY name");

// Is admin?
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

if ($isAdmin):
?>
<h1>Verhaal bewerken</h1>
<form id="edit-story-form">
    <input type="hidden" name="id" value="<?= $row['id'] ?>">
    
    <label>Titel:</label><br>
    <input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>" class="form-control mb-2"><br>
    
    <label>Tekst:</label><br>
    <textarea name="texte" class="form-control mb-2" rows="8"><?= htmlspecialchars($row['texte']) ?></textarea><br>
    
    <label>Synopsis:</label><br>
    <textarea name="synopsis" class="form-control mb-2" rows="4"><?= htmlspecialchars($row['synopsis']) ?></textarea><br>
    
    <label>Periode:</label><br>
    <input type="text" name="periode" value="<?= htmlspecialchars($row['periode']) ?>" class="form-control mb-2"><br>
    
    <label>Landen van herkomst:</label><br>
    <?php
    $gekozenLanden = [];
    while ($land = $landenResult->fetch_assoc()) {
        $gekozenLanden[] = $land['id'];
    }
    while ($c = $allCountries->fetch_assoc()):
        $checked = in_array($c['id'], $gekozenLanden) ? 'checked' : '';
    ?>
        <div><label><input type="checkbox" name="countries[]" value="<?= $c['id'] ?>" <?= $checked ?>> <?= htmlspecialchars($c['name']) ?></label></div>
    <?php endwhile; ?>
    <br>
    
    <label>Mythologische wezens:</label><br>
    <?php
    $gekozenWezens = [];
    while ($wezen = $wezensResult->fetch_assoc()) {
        $gekozenWezens[] = $wezen['id'];
    }
    while ($cr = $allCreatures->fetch_assoc()):
        $checked = in_array($cr['id'], $gekozenWezens) ? 'checked' : '';
    ?>
        <div><label><input type="checkbox" name="creatures[]" value="<?= $cr['id'] ?>" <?= $checked ?>> <?= htmlspecialchars($cr['name']) ?></label></div>
    <?php endwhile; ?>
    <br>
    
    <button type="submit" class="btn btn-primary">Opslaan</button>
</form>

<script>
document.getElementById('edit-story-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    // Zet FormData om naar JSON object
    const data = {};
    formData.forEach((value, key) => {
        if (key.endsWith('[]')) {
            const realKey = key.slice(0, -2);
            if (!data[realKey]) data[realKey] = [];
            data[realKey].push(value);
        } else {
            if (data[key]) { // meerdere waarden zonder []? voeg ook als array
                if (!Array.isArray(data[key])) data[key] = [data[key]];
                data[key].push(value);
            } else {
                data[key] = value;
            }
        }
    });

    // Fix voor checkboxes met []-notatie: browser stuurt name="countries[]" als "countries[]"
    // Pas eventueel aan als jouw form zo is ingesteld:
    // Hier in jouw HTML gebruik je name="countries[]" - dus in formData keys zijn 'countries[]'
    // Oplossing: hernoem in data object:
    ['countries', 'creatures'].forEach(arrName => {
        if (!data[arrName] && data[arrName + '[]']) {
            data[arrName] = data[arrName + '[]'];
            delete data[arrName + '[]'];
        }
    });

    try {
        const resp = await fetch('../php/updateStory.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        });
        const text = await resp.text();
        alert(text);
        if (resp.ok) {
            location.reload();
        }
    } catch(err) {
        alert('Fout bij opslaan: ' + err.message);
    }
});
</script>

<?php else: // Niet admin, leesbare versie ?>
<h1><?= htmlspecialchars($row['title']) ?></h1>
<p><?= nl2br(htmlspecialchars($row['texte'])) ?></p>
<p><strong>Periode:</strong> <?= htmlspecialchars($row['periode']) ?></p>
<p><strong>Synopsis:</strong> <?= htmlspecialchars($row['synopsis']) ?></p>
<img src="../images/<?= $row['id'] ?>.jpg" alt="<?= htmlspecialchars($row['title']) ?>" class="img-fluid mt-3">

<h4>Landen van herkomst:</h4>
<ul>
    <?php
    while ($land = $landenResult->fetch_assoc()) {
        echo '<li><a href="verhalen_per_land.html?land_id=' . $land['id'] . '">' . htmlspecialchars($land['name']) . '</a></li>';
    }
    ?>
</ul>

<h4>Mythologische wezens in dit verhaal:</h4>
<ul>
    <?php
    while ($wezen = $wezensResult->fetch_assoc()) {
        echo '<li><a href="wezen_detail.html?id=' . $wezen['id'] . '">' . htmlspecialchars($wezen['name']) . '</a></li>';
    }
    ?>
</ul>

<?php endif; ?>
