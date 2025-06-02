<?php
// Prüft ob ein Benutzer eingeloggt ist
$isLoggedIn = isset($_SESSION['user']);

// Prüft ob der eingelogte Benutzer Admin-Rechte hat (Type = 1)
$isAdmin = isset($_SESSION['user']['Type']) && $_SESSION['user']['Type'] == 1;
?>

<nav class="navbar navbar-expand-lg fixed-top shadow-sm">
    <div class="container-fluid">
        <!-- Brand/Logo -->
        <a class="navbar-brand" href="/arts-gallery/index.php">Art Gallery</a>

        <!-- Hauptnavigationsbereich -->
        <div class="navbar-collapse">
            <!-- Linke Navigationsleiste -->
            <ul class="navbar-nav">
                <li><a class="nav-link" href="/arts-gallery/index.php">Home</a></li>
                <li><a class="nav-link" href="/arts-gallery/src/views/site_aboutUs.php">About Us</a></li>
                
                <!-- Dropdown-Menü für Browse-Optionen -->
                <li class="dropdown">
                    <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Browse
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/arts-gallery/src/views/browse_artists.php">Artists</a></li>
                        <li><a class="dropdown-item" href="/arts-gallery/src/views/browse_artworks.php">Artworks</a></li>
                        <li><a class="dropdown-item" href="/arts-gallery/src/views/browse_genres.php">Genres</a></li>
                        <li><a class="dropdown-item" href="/arts-gallery/src/views/browse_subjects.php">Subjects</a></li>
                    </ul>
                </li>
                
                <li><a class="nav-link" href="/arts-gallery/src/views/site_advanced_search.php">Advanced Search</a></li>
            </ul>

            <!-- Zentrale Suchleiste -->
            <form class="d-flex mx-auto" action="site_search_results.php" method="GET" style="max-width: 600px; width: 100%;">
                <input class="form-control me-2" type="search" name="query" 
                       placeholder="Search" pattern="^[a-zA-Z0-9 ]{3,}$" required> <!-- Regex überprüfung auf mindestens 3 Zeichen und nur a-z,A-Z,0-9 und Leerzeichen -->
                <button class="btn btn-secondary" type="submit">Search</button>
            </form>

            <!-- Rechte Navigationsleiste (Benutzerbereich) -->
            <ul class="navbar-nav">
                <?php if ($isLoggedIn){ ?>
                    <!-- Benutzer-Dropdown für eingeloggte User -->
                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $_SESSION['user']['FirstName']; ?> <?php echo $_SESSION['user']['LastName']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/arts-gallery/src/views/site_myAccount.php">My Account</a></li>
                            <li><a class="dropdown-item" href="/arts-gallery/src/views/site_favorites.php">Favorites</a></li>
                            <?php if ($isAdmin){ ?>
                                <!-- Nur für Admins sichtbar -->
                                <li><a class="dropdown-item" href="/arts-gallery/src/views/site_manage_users.php">Manage Users</a></li>
                            <?php } ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/arts-gallery/src/controllers/logout_process.php">Logout</a></li>
                        </ul>
                    </li>
                <?php }else{ ?>
                    <!-- Login/Register Links für nicht eingeloggte User -->
                    <li><a class="nav-link" href="/arts-gallery/src/views/site_login.php">Login</a></li>
                    <li><a class="nav-link" href="/arts-gallery/src/views/site_register.php">Register</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>