<?php
function navbaraanroepen(){
    include ("myth_connection.php")
    ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand">Mythologische verhalen</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="homepage.html">Home</a>
            </li>
            <?php if (isset($_SESSION['gebruiker'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Log Out (<?= htmlspecialchars($_SESSION['gebruiker']) ?>)</a>
                </li>
            <?php else: ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Account
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="login.php">login</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="signup.php">Sign up</a></li>
                </ul>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<?php

}