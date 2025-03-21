<?php
session_start(); // Startet die Session

require_once 'ArtistRepository.php'; // Bindet die ArtistRepository-Klasse ein

$artistRepo = new ArtistRepository(new Database()); // Erstellt eine Instanz von ArtistRepository mit der Datenbankverbindung

$artists = $artistRepo->getAllArtists(); // Ruft alle Künstler aus der Datenbank ab
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Artists</title>
    <!-- Bindet Bootstrap CSS und benutzerdefinierte CSS-Datei ein -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navigation.php'; ?> <!-- Bindet die Navigationsleiste ein -->

    <div class="container mt-3">
        <h1 class="text-center">Artists</h1> <!-- Überschrift -->
        <div class="row">
            <?php foreach ($artists as $artist) { ?> <!-- Schleife durch alle Künstler -->
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <!-- Link zur Künstlerseite mit Bild -->
                        <a href="site_artist.php?id=<?php echo $artist->getArtistID(); ?>">
                            <img src="images/artists/medium/<?php echo $artist->getArtistID(); ?>.jpg" class="card-img-top" alt="<?php echo $artist->getLastName(); ?>">
                        </a>
                        <div class="card-body">
                            <!-- Künstlername -->
                            <h5 class="card-title"><?php echo $artist->getLastName(); ?>, <?php echo $artist->getFirstName(); ?></h5>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include 'footer.php'; ?> <!-- Bindet die Fußzeile ein -->
    <!-- Bindet Bootstrap JavaScript ein -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>