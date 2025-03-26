<?php
// Überprüft, ob der Benutzer angemeldet ist
$isLoggedIn = isset($_SESSION['user']);

// Überprüft, ob der Benutzer ein Admin ist
$isAdmin = isset($_SESSION['user']['Type']) && $_SESSION['user']['Type'] == 1;
?>

<!-- Navigationsleiste -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <!-- Brand/Logo -->
        <a class="navbar-brand" href="index.php">Art Gallery</a>

        <!-- Toggle-Button für mobile Ansicht -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Hauptnavigation -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Linke Seite der Navbar -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="site_aboutUs.php">About Us</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Browse</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="browse_artists.php">Artists</a></li>
                        <li><a class="dropdown-item" href="browse_artworks.php">Artworks</a></li>
                        <li><a class="dropdown-item" href="browse_genres.php">Genres</a></li>
                        <li><a class="dropdown-item" href="browse_subjects.php">Subjects</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="site_advanced_search.php">Advanced Search</a></li>
            </ul>

            <!-- Suchformular -->
            <form class="d-flex" action="site_search_results.php" method="GET">
                <input class="form-control me-2" type="search" name="query" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-light" type="submit">Search</button>
            </form>

            <!-- Rechte Seite der Navbar (Benutzerbezogene Links) -->
            <ul class="navbar-nav ms-auto">
                <?php if ($isLoggedIn){ ?>
                    <!-- Dropdown-Menü für angemeldete Benutzer -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $_SESSION['user']['FirstName']; ?> <?php echo $_SESSION['user']['LastName']; ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="site_myAccount.php">My Account</a></li>
                            <li><a class="dropdown-item" href="site_favorites.php">Favorites</a></li>
                            <?php if ($isAdmin){ ?>
                                <!-- Customer Management (nur für Administratoren sichtbar) -->
                                <li><a class="dropdown-item" href="site_manage_users.php">Manage Users</a></li>
                            <?php } ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout_process.php">Logout</a></li>
                        </ul>
                    </li>
                <?php }else{ ?>
                    <!-- Login- und Register-Links für nicht angemeldete Benutzer -->
                    <li class="nav-item"><a class="nav-link" href="site_login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="site_register.php">Register</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>