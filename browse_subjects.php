<?php
session_start(); // Startet die Session

require_once 'logging.php';
require_once 'global_exception_handler.php';
require_once 'subjectRepository.php'; // Bindet die SubjectRepository-Klasse ein

$subjectRepo = new SubjectRepository(new Database()); // Erstellt eine Instanz von SubjectRepository mit der Datenbankverbindung

$subjects = $subjectRepo->getAllSubjects(); // Ruft alle Themen (Subjects) aus der Datenbank ab
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subjects</title>
    <!-- Bindet Bootstrap CSS und benutzerdefinierte CSS-Datei ein -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navigation.php'; ?> <!-- Bindet die Navigationsleiste ein -->

    <div class="container mt-3">
        <h1 class="text-center">Subjects</h1> <!-- Überschrift -->
        <div class="row">
            <?php foreach ($subjects as $subject) {  // Schleife durch alle Themen -->
                $imagePath = "images/subjects/square-medium/" . $subject->getSubjectID() . ".jpg";
                $imageExists = file_exists($imagePath);

                if (file_exists($imagePath)) {
                    $imageUrl = $imagePath;
                } else {
                    $imageUrl = "images/placeholder.png";
                }
                ?>
                <div class="col-md-4 mb-4">
                    <!-- Link zur Themen-Detailseite mit Bild -->
                    <a href="site_subject.php?id=<?php echo $subject->getSubjectID(); ?>">
                    <div class="card">
                        <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $subject->getSubjectName(); ?>">
                        <div class="card-body">
                            <!-- Name des Themas -->
                            <h5 class="card-title"><?php echo $subject->getSubjectName(); ?></h5>
                        </div>
                    </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include 'footer.php'; ?> <!-- Bindet die Fußzeile ein -->
    <!-- Bindet Bootstrap JavaScript ein -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>