<?php
function globalExceptionhandler($exception){
    header("Location: http://localhost/arts-gallery/error.php");
    die();
}
?>