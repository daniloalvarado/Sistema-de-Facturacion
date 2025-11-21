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
            background: #2c5aa0;
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
            background: #2c5aa0 !important;
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
        
        .action-buttons {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 1000;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: all 0.3s;
            text-decoration: none;
            display: block;
            text-align: center;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        
        .btn-download {
            background: #4caf50;
            color: white;
        }
        
        .btn-print {
            background: #2c5aa0;
            color: white;
        }
        
        @media screen and (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .invoice-title {
                font-size: 20pt;
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
            
            .action-buttons {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                flex-direction: row;
                padding: 10px;
                background: white;
                box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            }
            
            .btn {
                flex: 1;
            }
        }
        
        @media print {
            .action-buttons {
                display: none;
            }
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
    
    <div class="action-buttons no-print">
        <a href="descargar_pdf.php?id=<?php echo $_GET['id']; ?>&type=<?php echo $type; ?>" 
           class="btn btn-download">
            📥 Descargar PDF
        </a>
        <button class="btn btn-print" onclick="window.print()">
            🖨️ Imprimir
        </button>
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
</body>
</html>
