<?php
session_start(); 

require_once __DIR__ . '/../services/global_exception_handler.php';
require_once __DIR__ . 'subjectRepository.php';

$subjectRepo = new SubjectRepository(new Database()); 

$subjects = $subjectRepo->getAllSubjects(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subjects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include __DIR__ . '/components/navigation.php'; ?>

    <div class="container">
        <h1 class="text-center">Subjects</h1> 
        <div class="row">
            <?php foreach ($subjects as $subject) {  
                $imagePath = "images/subjects/square-medium/" . $subject->getSubjectID() . ".jpg";
                $imageExists = file_exists($imagePath);

                if ($imageExists) {
                    $imageUrl = $imagePath;
                } else {
                    $imageUrl = "images/placeholder.png";
                }
                ?>
                <div class="col-md-4 mb-4">
                    <a href="site_subject.php?id=<?php echo $subject->getSubjectID(); ?>">
                    <div class="card">
                        <img src="<?php echo $imageUrl; ?>" class="card-img-top" alt="<?php echo $subject->getSubjectName(); ?>">
                        <div class="card-body">
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