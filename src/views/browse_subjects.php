<?php
/**
 * Subjects browse Page
 *
 * This script starts a session, sets up the global exception handler, retrieves all subjects from the database,
 * and renders them in a responsive Bootstrap grid layout. Each subject is shown as a card with an image and title.
 * If a subject-specific image does not exist, a placeholder is used instead.
 */

session_start(); // Start the session for session variables

// Include required files: global exception handler and subject repository
require_once __DIR__ . '/../services/global_exception_handler.php'; 
require_once __DIR__ . '/../repositories/subjectRepository.php'; 

/**
 * Instantiate the SubjectRepository with a database connection.
 *
 * @var SubjectRepository $subjectRepo Repository for handling subject-related database operations.
 */
$subjectRepo = new SubjectRepository(new Database()); 

/**
 * Retrieve all subjects from the database.
 *
 * @var Subject[] $subjects Array of Subject objects.
 */
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
    <?php 
    include __DIR__ . '/components/navigation.php'; 
    ?>

    <div class="container">
        <h1 class="text-center">Subjects</h1>  
        <div class="row">
            <?php foreach ($subjects as $subject) {  
                /**
                 * Build the image path for the subject based on its ID.
                 *
                 * @var string $imagePath Path to the subject's image.
                 * @var bool $imageExists Whether the image file exists.
                 * @var string $imageUrl URL to use for the image (subject image or placeholder).
                 */
                $imagePath = "../../images/subjects/square-medium/" . $subject->getSubjectID() . ".jpg";
                $imageExists = file_exists($imagePath);
                $imageUrl = $imageExists ? $imagePath : "../../images/placeholder.png";
                ?>
                
                <div class="col-md-4 mb-4">
                    <!-- Link to the subject detail page using the subject ID -->
                    <a href="site_subject.php?id=<?php echo $subject->getSubjectID(); ?>">
                        <div class="card">
                            <!-- Display subject image -->
                            <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $subject->getSubjectName(); ?>">
                            <div class="card-body">
                                <!-- Display subject name -->
                                <h5 class="card-title"><?php echo $subject->getSubjectName(); ?></h5>
                            </div>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php 
    include __DIR__ . '/components/footer.php'; 
    ?> 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
