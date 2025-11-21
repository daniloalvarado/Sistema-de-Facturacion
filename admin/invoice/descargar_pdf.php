<?php
require('../../config.php');



use Dompdf\Dompdf;
use Dompdf\Options;

$type = isset($_GET['type']) ? $_GET['type'] : 1;
$id = isset($_GET['id']) ? $_GET['id'] : 0;

if ($id > 0) {
    $qry = $conn->query("SELECT * from `invoice_list` where id = '{$id}' ");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = $v;
        }
    }
}

$tax_rate = isset($tax_rate) ? $tax_rate : $_settings->info('tax_rate');

// Construir el HTML
ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #2c5aa0;
            border-bottom: 3px solid #2c5aa0;
            padding-bottom: 10px;
        }
        .header-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .header-table td {
            vertical-align: top;
            padding: 5px;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        .info-value {
            color: #2c5aa0;
            font-weight: bold;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table.items th {
            background: #2c5aa0;
            color: white;
            padding: 10px;
            text-align: left;
        }
        table.items td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals {
            margin-top: 20px;
            width: 100%;
        }
        .totals td {
            padding: 8px;
        }
        .total-row {
            background: #2c5aa0;
            color: white;
            font-weight: bold;
            font-size: 14pt;
        }
        .subtotal-row {
            background: #f5f5f5;
        }
        .observations {
            margin-top: 20px;
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #2c5aa0;
        }
    </style>
</head>
<body>
    <h1>FACTURA</h1>
    
    <table class="header-table">
        <tr>
            <td width="50%">
                <p><span class="info-label">Facturado por:</span><br>
                <span class="info-value"><?php echo $_settings->info('name'); ?></span></p>
                <p><span class="info-label">Facturado a:</span><br>
                <span class="info-value"><?php echo $customer_name; ?></span></p>
            </td>
            <td width="50%" style="text-align: right;">
                <p><span class="info-label">Fecha:</span><br>
                <span class="info-value"><?php echo date("d/m/Y", strtotime($date_created)); ?></span></p>
                <p><span class="info-label">Código:</span><br>
                <span class="info-value"><?php echo $invoice_code; ?></span></p>
            </td>
        </tr>
    </table>
    
    <table class="items">
        <thead>
            <tr>
                <th width="10%">Cant.</th>
                <th width="10%">Unid.</th>
                <th width="45%">Producto/Servicio</th>
                <th width="17%">P. Unit.</th>
                <th width="18%">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($type == 1)
                $items = $conn->query("SELECT i.*,p.description,p.product as `name`,p.category_id as cid FROM invoices_items i inner join product_list p on p.id = i.form_id where i.invoice_id = '{$id}' ");
            else
                $items = $conn->query("SELECT i.*,s.description,s.`service` as `name`,s.category_id as cid FROM invoices_items i inner join service_list s on s.id = i.form_id where i.invoice_id = '{$id}' ");
            
            while ($row = $items->fetch_assoc()):
                $category = $conn->query("SELECT * FROM `category_list` where id = {$row['cid']}");
                $cat_count = $category->num_rows;
                $res = $cat_count > 0 ? $category->fetch_assoc() : array();
                $cat_name = $cat_count > 0 ? $res['name'] : "N/A";
                $description = stripslashes(html_entity_decode($row['description']));
            ?>
                <tr>
                    <td class="text-center"><?php echo $row['quantity']; ?></td>
                    <td class="text-center"><?php echo $row['unit']; ?></td>
                    <td>
                        <strong>[<?php echo $cat_name; ?>]</strong> <?php echo $row['name']; ?>
                        <?php if (!empty($description)): ?>
                            <br><small style="color: #666;"><?php echo $description; ?></small>
                        <?php endif; ?>
                    </td>
                    <td class="text-right">S/ <?php echo number_format($row['price'], 2); ?></td>
                    <td class="text-right"><strong>S/ <?php echo number_format($row['total'], 2); ?></strong></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    
    <table class="totals">
        <tr class="subtotal-row">
            <td width="70%" class="text-right"><strong>Subtotal</strong></td>
            <td width="30%" class="text-right"><strong>S/ <?php echo number_format($sub_total, 2); ?></strong></td>
        </tr>
        <tr class="subtotal-row">
            <td class="text-right"><strong>Impuestos (<?php echo $tax_rate; ?>%)</strong></td>
            <td class="text-right"><strong>S/ <?php echo number_format($sub_total * ($tax_rate / 100), 2); ?></strong></td>
        </tr>
        <tr class="total-row">
            <td class="text-right"><strong>TOTAL</strong></td>
            <td class="text-right"><strong>S/ <?php echo number_format($total_amount, 2); ?></strong></td>
        </tr>
    </table>
    
    <?php if (!empty($remarks)): ?>
    <div class="observations">
        <p><strong>Observaciones:</strong></p>
        <p><?php echo $remarks; ?></p>
    </div>
    <?php endif; ?>
    
    <p style="text-align: center; color: #888; margin-top: 30px;">
        Documento generado el <?php echo date("d/m/Y H:i:s"); ?>
    </p>
</body>
</html>
<?php
$html = ob_get_clean();

// Configurar DomPDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Descargar el PDF
$filename = 'Factura_' . $invoice_code . '.pdf';
$dompdf->stream($filename, array("Attach" => true));
?>
