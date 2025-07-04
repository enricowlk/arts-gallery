<?php
/**
 * Navigation bar component for the Art Gallery website.
 * 
 * Checks if the user is logged in and if the user is an administrator.
 * These values are used to conditionally display navigation options.
 */

// Check if a user is logged in
$isLoggedIn = isset($_SESSION['user']);

/**
 * Check if the logged-in user has administrator rights.
 * 
 * Admin users have 'Type' set to 1 in the session.
 */
$isAdmin = isset($_SESSION['user']['Type']) && $_SESSION['user']['Type'] == 1;
?>

<nav class="navbar navbar-expand-lg fixed-top shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="/arts-gallery/index.php">Art Gallery</a>

        <!-- Collapsible navigation content -->
        <div class="navbar-collapse">
            <!-- Left navigation menu -->
            <ul class="navbar-nav">
                <li><a class="nav-link" href="/arts-gallery/index.php">Home</a></li>
                <li><a class="nav-link" href="/arts-gallery/src/views/site_aboutUs.php">About Us</a></li>
                
                <!-- Browse dropdown menu -->
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

            <!-- Center search form -->
            <form class="d-flex mx-auto" action="site_search_results.php" method="GET" style="max-width: 600px; width: 100%;">
                <!-- Search input with regex: min. 3 alphanumeric characters or spaces -->
                <input class="form-control me-2" type="search" name="query" 
                       placeholder="Search" pattern="^[a-zA-Z0-9 ]{3,}$" required>
                <button class="btn btn-secondary" type="submit">Search</button>
            </form>

            <!-- Right-side user menu -->
            <ul class="navbar-nav">
                <?php if ($isLoggedIn) { ?>
                    <!-- Dropdown for logged-in users -->
                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $_SESSION['user']['FirstName']; ?>
                            <?php echo $_SESSION['user']['LastName']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/arts-gallery/src/views/site_myAccount.php">My Account</a></li>
                            <li><a class="dropdown-item" href="/arts-gallery/src/views/site_favorites.php">Favorites</a></li>
                            <?php if ($isAdmin) { ?>
                                <!-- Visible only to admins -->
                                <li><a class="dropdown-item" href="/arts-gallery/src/views/site_manage_users.php">Manage Users</a></li>
                            <?php } ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/arts-gallery/src/controllers/logout_process.php">Logout</a></li>
                        </ul>
                    </li>
                <?php } else { ?>
                    <!-- Links for guests (not logged in) -->
                    <li><a class="nav-link" href="/arts-gallery/src/views/site_login.php">Login</a></li>
                    <li><a class="nav-link" href="/arts-gallery/src/views/site_register.php">Register</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
