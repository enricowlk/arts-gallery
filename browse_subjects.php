<?php
session_start();

require_once 'SubjectRepository.php';

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
    <?php include 'navigation.php'; ?>

    <div class="container mt-3">
        <h1 class="text-center">Subjects</h1>
        <div class="row">
            <?php foreach ($subjects as $subject) { ?>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <a href="site_subject.php?id=<?php echo $subject->getSubjectID(); ?>">
                            <img src="images/subjects/square-medium/<?php echo $subject->getSubjectID(); ?>.jpg" class="card-img-top" alt="<?php echo $subject->getSubjectName(); ?>">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $subject->getSubjectName(); ?></h5>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>