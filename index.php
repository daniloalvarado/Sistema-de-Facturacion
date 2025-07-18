<!-- Si alguien ingresaba a http://localhost/tu-sistema/, 
 lo envia automáticamente a http://localhost/tu-sistema/admin/. -->
<?php 
ob_start();
header("Location: admin/?page=home");
ob_end_clean();
exit;
?>
