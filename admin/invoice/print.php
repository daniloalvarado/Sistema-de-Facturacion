<?php
require('../../config.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura - <?php echo isset($invoice_code) ? $invoice_code : ''; ?></title>
    <style>
        @media print {
            @page {
                margin: 1cm;
                size: A4;
            }
            
            body {
                margin: 0;
                padding: 0;
            }
            
            .no-print {
                display: none;
            }
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
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
        
        .header-info {
            display: table;
            width: 100%;
        }
        
        .header-left,
        .header-right {
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
        
        .info-label {
            font-weight: bold;
            color: #555;
            font-size: 10pt;
        }
        
        .info-value {
            color: #2c5aa0;
            font-weight: 600;
            font-size: 11pt;
            margin-left: 5px;
        }
        
        .info-row {
            margin-bottom: 8px;
            line-height: 1.6;
        }
        
        .header-right {
            text-align: right;
            padding-left: 20px;
        }
        
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .invoice-table thead {
            background: linear-gradient(135deg, #2c5aa0 0%, #4a7dc9 100%);
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
        
        .invoice-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .invoice-table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .invoice-table tbody tr:last-child td {
            border-bottom: 2px solid #2c5aa0;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .item-details {
            font-size: 9pt;
            color: #666;
        }
        
        .item-details p {
            margin: 3px 0;
        }
        
        .item-name {
            font-weight: 600;
            color: #333;
            font-size: 10pt;
        }
        
        .category-badge {
            display: inline-block;
            background: #e3f2fd;
            color: #1976d2;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: 600;
        }
        
        .invoice-table tfoot {
            background: #f5f5f5;
        }
        
        .invoice-table tfoot tr {
            border-top: 1px solid #d0d0d0;
        }
        
        .invoice-table tfoot th {
            padding: 10px 8px;
            font-size: 10pt;
            background: transparent;
            color: #333;
        }
        
        .total-row {
            background: linear-gradient(135deg, #2c5aa0 0%, #4a7dc9 100%) !important;
            color: white !important;
        }
        
        .total-row th {
            color: white !important;
            font-size: 12pt !important;
            padding: 14px 8px !important;
        }
        
        .subtotal-row {
            background: #e3f2fd !important;
        }
        
        .tax-row {
            background: #e3f2fd !important;
        }
        
        .observations {
            margin-top: 30px;
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #2c5aa0;
            border-radius: 4px;
        }
        
        .observations-title {
            font-weight: bold;
            color: #2c5aa0;
            font-size: 11pt;
            margin-bottom: 10px;
        }
        
        .observations-content {
            color: #555;
            line-height: 1.6;
        }
        
        .footer-note {
            margin-top: 30px;
            text-align: center;
            font-size: 9pt;
            color: #888;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        
        /* Botones de acción mejorados para móvil */
        .action-buttons {
            position: fixed;
            top: 10px;
            right: 10px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 1000;
        }
        
        .action-button {
            padding: 12px 20px;
            background: #2c5aa0;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 10pt;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: all 0.3s;
            text-align: center;
            text-decoration: none;
            display: block;
        }
        
        .action-button:hover {
            background: #1e3a6e;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        
        .action-button.secondary {
            background: #4caf50;
        }
        
        .action-button.secondary:hover {
            background: #388e3c;
        }
        
        /* Estilos responsivos para móvil */
        @media screen and (max-width: 768px) {
            body {
                padding: 10px;
                font-size: 10pt;
            }
            
            .invoice-title {
                font-size: 20pt;
                margin-bottom: 15px;
            }
            
            .header-info {
                display: block;
            }
            
            .header-left,
            .header-right {
                display: block;
                width: 100%;
                text-align: left;
            }
            
            .header-right {
                margin-top: 15px;
                padding-left: 0;
            }
            
            .company-logo {
                width: 80px;
                height: 80px;
            }
            
            .invoice-table {
                font-size: 9pt;
            }
            
            .invoice-table th,
            .invoice-table td {
                padding: 8px 4px;
            }
            
            .action-buttons {
                position: fixed;
                bottom: 10px;
                top: auto;
                right: 10px;
                left: 10px;
                flex-direction: row;
            }
            
            .action-button {
                flex: 1;
                font-size: 9pt;
                padding: 10px;
            }
        }
        
        @media print {
            .action-buttons {
                display: none;
            }
        }
        
        /* Loader para indicar que está procesando */
        .loader {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 20px;
            border-radius: 10px;
            z-index: 9999;
        }
        
        .loader.active {
            display: block;
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
    ?>
    
    <div class="loader" id="loader">
        <div>Generando PDF...</div>
    </div>
    
    <div class="action-buttons no-print">
        <button class="action-button" onclick="printInvoice()">🖨️ Imprimir</button>
        <button class="action-button secondary" onclick="shareInvoice()">📤 Compartir</button>
    </div>
    
    <div class="invoice-container">
        <h1 class="invoice-title">Factura</h1>
        
        <div class="invoice-header">
            <div class="header-info">
                <div class="header-left">
                    <img src="<?php echo validate_image($_settings->info('logo')) ?>" class="company-logo" alt="Logo">
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
        function printInvoice() {
            window.print();
        }
        
        // Detectar si es móvil para mostrar mensaje adicional
        function isMobile() {
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        }
        
        // Si es móvil y intenta imprimir, sugerir descargar PDF
        if (isMobile()) {
            const printBtn = document.querySelector('.action-buttons .action-button');
            printBtn.addEventListener('click', function(e) {
                if (!confirm('En dispositivos móviles, es mejor usar el botón "Descargar PDF". ¿Desea continuar con imprimir?')) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            });
        }
    </script>
</body>
</html>
