<?php
// Session starten für Session-Variablen
session_start(); 

// Einbinden benötigter Dateien
require_once __DIR__ . '/../services/global_exception_handler.php'; 
require_once __DIR__ . '/../repositories/subjectRepository.php'; 

// SubjectRepository instanziieren
$subjectRepo = new SubjectRepository(new Database()); 

// Alle Subjects aus der Datenbank abrufen
$subjects = $subjectRepo->getAllSubjects(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subjects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
    <?php include __DIR__ . '/components/navigation.php'; ?>

    <div class="container">
        <h1 class="text-center">Subjects</h1>  
        <div class="row">
            <?php foreach ($subjects as $subject) {  
                // Pfad zum Subject-Bild erstellen
                $imagePath = "../../images/subjects/square-medium/" . $subject->getSubjectID() . ".jpg";
                // Prüfen ob Bild existiert
                $imageExists = file_exists($imagePath);

                // Subject-Bild oder Platzhalter
                $imageUrl = $imageExists ? $imagePath : "../../images/placeholder.png";
                ?>
                
                <div class="col-md-4 mb-4">
                    <!-- Link zur Subject-Detailseite -->
                    <a href="site_subject.php?id=<?php echo $subject->getSubjectID(); ?>">
                    <div class="card">
                        <!-- Subject-Bild -->
                        <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $subject->getSubjectName(); ?>">
                        <div class="card-body">
                            <!-- Subject-Name -->
                            <h5 class="card-title"><?php echo $subject->getSubjectName(); ?></h5>
                        </div>
                    </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include __DIR__ . '/components/footer.php'; ?> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>