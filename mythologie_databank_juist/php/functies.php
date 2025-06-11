<?php
function navbaraanroepen(){
    include ("connection.php")
    ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
    <a class="navbar-brand">Mythologische verhalen</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="index.html">Home</a>
            </li>
            <?php if (isset($_SESSION['gebruiker_id'])): ?>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="gebruikersbeheer.html">Gebruikersbeheer</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="../php/logout.php">Log Out (<?= htmlspecialchars($_SESSION['gebruiker']) ?>)</a>
                </li>
            <?php else: ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Account
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="login.html">login</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="signup.html">Sign up</a></li>
                </ul>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<?php

}


function startpagina($conn){

    $sql = "SELECT stories.id, stories.title, stories.periode, stories.synopsis FROM stories";
            $result = $conn->query($sql);
        
            if (!$result) {
                die("Fout bij het uitvoeren van de query: " . $conn->error);
            }
        
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row["id"];
                    $imagePath = "../images/{$id}.jpg";  
                    $synopsis = $row["synopsis"]; 
        
                    
        
                    echo '<div class="col-md-4 mb-4">';
                    echo '  <div class="card h-100">';
                    echo '      <a href="detail.html?id=' . $id . '"><img src="' . $imagePath . '" class="card-img-top" alt="' . $row["title"] . '"></a>';
                    echo '      <div class="card-body">';
                    echo '          <h5 class="card-title">' . $row["title"] . '</h5>';
                    echo '          <p class="card-text"><strong>periode:</strong> ' . $row["periode"] . '</p>';
                    echo '          <p class="card-text"><strong>synopsis:</strong> ' . $synopsis . '</p>';
                    echo '      </div>';
                    echo '  </div>';
                    echo '</div>';
                }
            } else {
                echo '<div class="col-12 text-center"><p>Geen dieren in de database gevonden</p></div>';
            }

}

function updategegevens($conn,$id){

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nieuweNaam = $_POST['naam'];
        $nieuweGeboortedatum = $_POST['geboortedatum'];
        $nieuweSoort = $_POST['idsoort'];
    
        $update_dier_sql = "UPDATE dieren SET naam = $nieuweNaam, geboortedatum = $nieuweGeboortedatum, idsoort = $nieuweSoort WHERE id = $id";
        $result = $conn->query($update_dier_sql);

        echo "<div class='alert alert-success'>Gegevens succesvol bijgewerkt.</div>";
    }


}


function login($conn){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $gebruikersnaam = $_POST['gebruikersnaam'];
        $wachtwoord = $_POST['wachtwoord'];

        if (!empty($gebruikersnaam) && !empty($wachtwoord)) {
            $query = "SELECT id, wachtwoord_hash FROM aanloggegevens WHERE gebruikersnaam = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $gebruikersnaam);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($gebruiker_id, $wachtwoord_hash);
                $stmt->fetch();

                if (hash('sha256', $wachtwoord) == $wachtwoord_hash) {
                    $_SESSION['logged_in'] = true;
                    $_SESSION['gebruiker'] = $gebruikersnaam;
                    $_SESSION['gebruiker_id'] = $gebruiker_id;  // ✅ DIT IS CRUCIAAL

                    header("Location: ../html/index.html");
                    exit;
                } else {
                    echo "Ongeldig wachtwoord.";
                }
            } else {
                echo "Gebruikersnaam bestaat niet.";
            }
            $stmt->close();
        } else {
            echo "Gebruikersnaam en wachtwoord mogen niet leeg zijn.";
        }
    }
}
