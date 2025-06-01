<?php
$isLoggedIn = isset($_SESSION['user']);

$isAdmin = isset($_SESSION['user']['Type']) && $_SESSION['user']['Type'] == 1;
?>

<nav class="navbar navbar-expand-lg fixed-top shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="../../index.php">Art Gallery</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Hauptnavigation -->
        <div class="navbar-collapse">
            <!-- Linke Seite der Navbar -->
            <ul class="navbar-nav">
                <li><a class="nav-link" href="../../index.php">Home</a></li>
                <li><a class="nav-link" href="/arts-gallery/src/views/site_aboutUs.php">About Us</a></li>
                <li class="dropdown">
                    <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">Browse</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/arts-gallery/src/views/browse_artists.php">Artists</a></li>
                        <li><a class="dropdown-item" href="/arts-gallery/src/views/browse_artworks.php">Artworks</a></li>
                        <li><a class="dropdown-item" href="/arts-gallery/src/views/browse_genres.php">Genres</a></li>
                        <li><a class="dropdown-item" href="/arts-gallery/src/views/browse_subjects.php">Subjects</a></li>
                    </ul>
                </li>
                <li><a class="nav-link" href="/arts-gallery/src/views/site_advanced_search.php">Advanced Search</a></li>
            </ul>

            <!-- Mitte: Suchleiste -->
            <form class="d-flex mx-auto" action="site_search_results.php" method="GET" style="max-width: 600px; width: 100%;">
                <input class="form-control me-2" type="search" name="query" placeholder="Search" pattern="^[a-zA-Z0-9 ]{3,}$" required>
                <button class="btn btn-secondary" type="submit">Search</button>
            </form>

            <!-- Rechte Seite: Benutzerbezogene Links -->
            <ul class="navbar-nav">
                <?php if ($isLoggedIn){ ?>
                    <!-- Dropdown-Menü für angemeldete Benutzer -->
                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $_SESSION['user']['FirstName']; ?> <?php echo $_SESSION['user']['LastName']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/arts-gallery/src/views/site_myAccount.php">My Account</a></li>
                            <li><a class="dropdown-item" href="/arts-gallery/src/views/site_favorites.php">Favorites</a></li>
                            <?php if ($isAdmin){ ?>
                                <li><a class="dropdown-item" href="/arts-gallery/src/views/site_manage_users.php">Manage Users</a></li>
                            <?php } ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/logout_process.php">Logout</a></li>
                        </ul>
                    </li>
                    <!-- Inhalt für nicht angemeldete Benutzer -->
                <?php }else{ ?>
                    <li><a class="nav-link" href="/arts-gallery/src/views/site_login.php">Login</a></li>
                    <li><a class="nav-link" href="/arts-gallery/src/views/site_register.php">Register</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
