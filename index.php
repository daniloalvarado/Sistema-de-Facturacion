<!-- Si alguien ingresaba a http://localhost/tu-sistema/, 
 lo envia automáticamente a http://localhost/tu-sistema/admin/. -->
<?php 
ob_start();
header("location:admin/");
ob_end_clean();
exit;
?>