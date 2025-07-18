<?php 
ob_start();
header("Location: /admin/?page=home");  // Ruta absoluta desde la raíz
ob_end_clean();
exit;
?>

