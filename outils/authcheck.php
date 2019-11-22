<?php
if (!isset($_SESSION['auth']) || ($_SESSION['auth']==FALSE)){
    // Tentative de fraude
    header('Location:/~ra803006/ext/trombinouc/index.php?msg=noauth');
    exit();
}
?>