<?php
// Session starten, um Benutzerdaten zu speichern
session_start();

// Einbinden der benötigten Dateien für Datenbankzugriff und Repository-Klassen
require_once 'database.php';
require_once 'subjectRepository.php';
require_once 'artworkRepository.php';

// Überprüfen, ob eine gültige Subject-ID als GET-Parameter übergeben wurde
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: error.php?message=Invalid or missing subject ID");
    exit();
}

$subjectId = (int)$_GET['id']; // Subject-ID aus GET-Parameter auslesen und validieren

// Instanzen der Repository-Klassen erstellen, um auf die Datenbank zuzugreifen
$subjectRepo = new SubjectRepository(new Database());
$artworkRepo = new ArtworkRepository(new Database());

// Subject-Daten anhand der ID aus der Datenbank abrufen
$subject = $subjectRepo->getSubjectById($subjectId);

// Überprüfen, ob das Subject existiert
if ($subject === null) {
    header("Location: error.php?message=Subject not found");
    exit();
}

// Alle Kunstwerke, die mit dem Subject verknüpft sind, aus der Datenbank abrufen
$artworks = $artworkRepo->getAllArtworksForOneSubjectBySubjectId($subjectId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Dynamischer Seitentitel basierend auf dem Subject-Namen -->
    <title><?php echo $subject->getSubjectName(); ?> - Art Gallery</title>
    <!-- Einbinden von Bootstrap und benutzerdefinierten CSS-Dateien -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Stil für das Subject-Bild */
        .subject-image {
            width: 322px; /* Breite des Bildes */
            height: 300px; /* Höhe des Bildes */
            object-fit: cover; /* Bild wird zugeschnitten, um den Container zu füllen */
            border-radius: 10px; /* Abgerundete Ecken */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Schatten hinzufügen */
        }
    </style>
</head>
<body>
    <!-- Einbinden der Navigationsleiste -->
    <?php include 'navigation.php'; ?>

    <div class="container mt-5">
        <!-- Subject-Informationen -->
        <div class="row">
            <div class="col-md-4">
                <!-- Subject-Bild anzeigen -->
                <?php
                $imagePath = "images/subjects/square-medium/" . $subject->getSubjectID() . ".jpg";
                $imageExists = file_exists($imagePath);

                if (file_exists($imagePath)) {
                    $imageUrl = $imagePath;
                } else {
                    $imageUrl = "images/placeholder.png";
                }
                ?>
                <img src="<?php echo $imageUrl; ?>" class="subject-image" alt="<?php echo $subject->getSubjectName(); ?>">
            </div>
            <div class="col-md-8">
                <div class="info">
                    <!-- Subject-Namen anzeigen -->
                    <h1><?php echo $subject->getSubjectName(); ?></h1>
                </div>
            </div>
        </div>

        <!-- Kunstwerke in diesem Subject -->
        <h2 class="mt-5 mb-4">Artworks in <?php echo $subject->getSubjectName(); ?></h2>
        <?php if (empty($artworks)) { ?>
            <!-- Falls keine Kunstwerke gefunden wurden, eine Meldung anzeigen -->
            <p class="text-center">No artworks found in this subject.</p>
        <?php } else { ?>
            <div class="row">
                <?php foreach ($artworks as $artwork) { 
                    // Durchschnittliche Bewertung für jedes Kunstwerk abrufen
                    $averageRating = $artworkRepo->getAverageRatingForArtwork($artwork->getArtWorkID());
                    $imagePath = "images/works/medium/" . $artwork->getImageFileName() . ".jpg";
                    $imageExists = file_exists($imagePath);

                    if (file_exists($imagePath)) {
                        $imageUrl = $imagePath;
                    } else {
                        $imageUrl = "images/placeholder.png";
                    }
                ?>
                    <div class="col-md-4 mb-4">
                        <!-- Link zur Detailseite -->
                        <a href="site_artwork.php?id=<?php echo $artwork->getArtWorkID(); ?>">
                        <div class="card artwork-card">
                            <!-- Kunstwerkbild -->
                            <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $artwork->getTitle(); ?>">
                            <div class="card-body">
                                <!-- Titel des Kunstwerks anzeigen -->
                                <h5 class="card-title"><?php echo $artwork->getTitle(); ?></h5>
                                <!-- Jahr des Kunstwerks anzeigen -->
                                <p class="card-text">Year of Work: <?php echo $artwork->getYearOfWork(); ?></p>
                                <!-- Durchschnittliche Bewertung anzeigen -->
                                <p class="card-text">Rating: <?php echo number_format((float)$averageRating, 1); ?> 
                                    <img src="images/icon_gelberStern.png" alt="Star" style="position: relative; top: -2px;">
                                </p>
                            </div>
                        </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <!-- Einbinden des Footers -->
    <?php include 'footer.php'; ?>
    <!-- Einbinden von Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>