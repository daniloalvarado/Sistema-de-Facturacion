<?php
require('../../config.php');

// --- INICIO SOLUCIÓN IMAGEN PARA MÓVIL ---
// Función para convertir la imagen del logo a Base64
// Esto evita que el PDF salga en blanco en Android por problemas de seguridad (CORS)
function getLogoAsBase64($url) {
    try {
        // Intentamos obtener la imagen
        // Si la URL es relativa (ej: /uploads/...), prepandemos el document root
        $path = parse_url($url, PHP_URL_PATH);
        $absolute_path = $_SERVER['DOCUMENT_ROOT'] . $path;

        if(file_exists($absolute_path)){
            $type = pathinfo($absolute_path, PATHINFO_EXTENSION);
            $data = file_get_contents($absolute_path);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        } 
        // Si no encuentra ruta local, intentamos descargarla (si el server lo permite)
        else {
             $data = @file_get_contents($url);
             if($data){
                 return 'data:image/jpeg;base64,' . base64_encode($data);
             }
        }
    } catch (Exception $e) {
        // Si falla, retornamos la URL original y rezamos para que funcione
    }
    return $url; 
}

// Preparamos el logo
$logo_url_original = validate_image($_settings->info('logo'));
$logo_final = getLogoAsBase64($logo_url_original);
// --- FIN SOLUCIÓN IMAGEN ---
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura - <?php echo isset($invoice_code) ? $invoice_code : ''; ?></title>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        /* TUS ESTILOS ORIGINALES CON PEQUEÑOS AJUSTES PARA PDF */
        @media print {
            @page { margin: 0; size: A4; } /* Margen 0 para que html2pdf controle */
            body { margin: 0; padding: 0; }
            .no-print { display: none; }
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #333;
            background: #fff;
            padding: 20px;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 10px; /* Padding interno para que no corte bordes */
        }
        
        .invoice-header {
            border-bottom: 3px solid #2c5aa0;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        
        .invoice-title {
            text-align: center;
            font-size: 28pt;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .header-info { display: table; width: 100%; }
        
        .header-left, .header-right {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }
        
        .company-logo {
            width: 100px;
            height: 100px;
            object-fit: contain;
            margin-bottom: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 5px;
        }
        
        .info-label { font-weight: bold; color: #555; font-size: 10pt; }
        .info-value { color: #2c5aa0; font-weight: 600; font-size: 11pt; margin-left: 5px; }
        .info-row { margin-bottom: 8px; line-height: 1.6; }
        
        .header-right { text-align: right; padding-left: 20px; }
        
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .invoice-table thead {
            background: #2c5aa0; /* Color sólido mejor para PDF */
            color: white;
        }
        
        .invoice-table th {
            padding: 12px 8px;
            text-align: center;
            font-weight: 600;
            font-size: 10pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .invoice-table td { padding: 10px 8px; border-bottom: 1px solid #e0e0e0; }
        .invoice-table tbody tr:last-child td { border-bottom: 2px solid #2c5aa0; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .item-details { font-size: 9pt; color: #666; }
        .item-details p { margin: 3px 0; }
        .item-name { font-weight: 600; color: #333; font-size: 10pt; }
        
        .category-badge {
            display: inline-block;
            background: #e3f2fd;
            color: #1976d2;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: 600;
        }
        
        .invoice-table tfoot { background: #f5f5f5; }
        .invoice-table tfoot tr { border-top: 1px solid #d0d0d0; }
        .invoice-table tfoot th { padding: 10px 8px; font-size: 10pt; background: transparent; color: #333; }
        
        .total-row { background: #2c5aa0 !important; color: white !important; }
        .total-row th { color: white !important; font-size: 12pt !important; padding: 14px 8px !important; }
        
        .subtotal-row { background: #e3f2fd !important; }
        .tax-row { background: #e3f2fd !important; }
        
        .observations {
            margin-top: 30px;
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #2c5aa0;
            border-radius: 4px;
        }
        .observations-title { font-weight: bold; color: #2c5aa0; font-size: 11pt; margin-bottom: 10px; }
        .observations-content { color: #555; line-height: 1.6; }
        
        .footer-note {
            margin-top: 30px;
            text-align: center;
            font-size: 9pt;
            color: #888;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #2c5aa0;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 10pt;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: all 0.3s;
            z-index: 9999; 
        }
        
        .print-button:hover {
            background: #1e3a6e;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
    </style>
</head>

<body>
    <?php
    $type = isset($_GET['type']) ? $_GET['type'] : 1;
    if (isset($_GET['id']) && $_GET['id'] > 0) {
        $qry = $conn->query("SELECT * from `invoice_list` where id = '{$_GET['id']}' ");
        if ($qry->num_rows > 0) {
            foreach ($qry->fetch_assoc() as $k => $v) {
                $$k = $v;
            }
        }
    }
    $tax_rate = isset($tax_rate) ? $tax_rate : $_settings->info('tax_rate');
    // Aseguramos que haya un código de factura para el nombre del archivo
    $safe_invoice_code = isset($invoice_code) ? $invoice_code : 'DOC';
    ?>
    
    <button class="print-button no-print" id="btn-download">🖨️ Descargar PDF</button>
    
    <div class="invoice-container" id="invoice-content">
        <h1 class="invoice-title">Factura</h1>
        
        <div class="invoice-header">
            <div class="header-info">
                <div class="header-left">
                    <img src="<?php echo $logo_final; ?>" class="company-logo" alt="Logo">
                    
                    <div class="info-row">
                        <span class="info-label">Facturado por:</span>
                        <span class="info-value"><?php echo $_settings->info('name') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Facturado a:</span>
                        <span class="info-value"><?php echo $customer_name ?></span>
                    </div>
                </div>
                <div class="header-right">
                    <div class="info-row">
                        <span class="info-label">Fecha de Factura:</span><br>
                        <span class="info-value"><?php echo date("d/m/Y", strtotime($date_created)) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Código de Factura:</span><br>
                        <span class="info-value"><?php echo $invoice_code ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <table class="invoice-table">
            <thead>
                <tr>
                    <th style="width: 8%;">Cant.</th>
                    <th style="width: 10%;">Unid.</th>
                    <th style="width: 45%;">Producto/Servicio</th>
                    <th style="width: 17%;">Precio Unit.</th>
                    <th style="width: 20%;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($type == 1)
                    $items = $conn->query("SELECT i.*,p.description,p.id as pid,p.product as `name`,p.category_id as cid FROM invoices_items i inner join product_list p on p.id = i.form_id where i.invoice_id = '{$id}' ");
                else
                    $items = $conn->query("SELECT i.*,s.description,s.id as `sid`,s.`service` as `name`,s.category_id as cid FROM invoices_items i inner join service_list s on s.id = i.form_id where i.invoice_id = '{$id}' ");
                while ($row = $items->fetch_assoc()):
                    $category = $conn->query("SELECT * FROM `category_list` where id = {$row['cid']}");
                    $cat_count = $category->num_rows;
                    $res = $cat_count > 0 ? $category->fetch_assoc() : array();
                    $cat_name = $cat_count > 0 ? $res['name'] : "N/A";
                    $description = stripslashes(html_entity_decode($row['description']));
                ?>
                    <tr>
                        <td class="text-center"><strong><?php echo $row['quantity'] ?></strong></td>
                        <td class="text-center"><?php echo $row['unit'] ?></td>
                        <td>
                            <div class="item-details">
                                <span class="category-badge"><?php echo $cat_name ?></span>
                                <p class="item-name"><?php echo $row['name'] ?></p>
                                <?php if (!empty($description)): ?>
                                    <div style="margin-top: 5px; color: #777;">
                                        <?php echo $description ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="text-right"><strong>S/ <?php echo number_format($row['price'], 2) ?></strong></td>
                        <td class="text-right"><strong>S/ <?php echo number_format($row['total'], 2) ?></strong></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr class="subtotal-row">
                    <th class="text-right" colspan="4">Subtotal</th>
                    <th class="text-right">S/ <?php echo number_format($sub_total, 2) ?></th>
                </tr>
                <tr class="tax-row">
                    <th class="text-right" colspan="4">Tasa de Impuestos (<?php echo $tax_rate ?>%)</th>
                    <th class="text-right">S/ <?php echo number_format($sub_total * ($tax_rate / 100), 2) ?></th>
                </tr>
                <tr class="total-row">
                    <th class="text-right" colspan="4">TOTAL GENERAL</th>
                    <th class="text-right">S/ <?php echo number_format($total_amount, 2) ?></th>
                </tr>
            </tfoot>
        </table>
        
        <?php if (!empty($remarks)): ?>
        <div class="observations">
            <div class="observations-title">📋 Observaciones:</div>
            <div class="observations-content">
                <?php echo $remarks ?>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="footer-note">
            Documento generado el <?php echo date("d/m/Y H:i:s") ?>
        </div>
    </div>
    
    <script>
        document.getElementById('btn-download').addEventListener('click', function() {
            // Referencias
            const element = document.getElementById('invoice-content');
            const btn = document.getElementById('btn-download');
            const originalText = btn.innerText;
            
            // Feedback visual
            btn.innerText = 'Generando...';
            btn.disabled = true;

            // Configuración
            var opt = {
                margin:       [0.3, 0.3, 0.3, 0.3], // Márgenes (arriba, izq, abajo, der)
                filename:     'Factura-<?php echo $safe_invoice_code; ?>.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { 
                    scale: 2, // Mejor calidad
                    useCORS: true, // Permitir imágenes externas si la base64 falla
                    scrollY: 0
                },
                jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
            };

            // Ejecutar
            html2pdf().set(opt).from(element).save().then(function(){
                btn.innerText = originalText;
                btn.disabled = false;
            }).catch(function(err){
                console.error(err);
                alert("Hubo un error al generar el PDF: " + err.message);
                btn.innerText = originalText;
                btn.disabled = false;
            });
        });
    </script>
</body>
</html>
