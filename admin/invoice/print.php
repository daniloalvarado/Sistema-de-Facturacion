<?php
require('../../config.php');
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
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura <?php echo $invoice_code ?></title>
    <style>
        /* BLOQUEAR función de imprimir para evitar que se cuelgue */
        @media print {
            body {
                display: none !important;
            }
            body:before {
                content: "Para guardar esta factura, usa los botones en la parte inferior de la página o toma una captura de pantalla.";
                display: block !important;
                padding: 20px;
                font-size: 18px;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            background: white;
        }
        
        .factura {
            max-width: 800px;
            margin: 0 auto 80px;
        }
        
        h1 {
            text-align: center;
            color: #2c5aa0;
            border-bottom: 2px solid #2c5aa0;
            padding-bottom: 10px;
        }
        
        .info {
            margin: 20px 0;
            padding: 15px;
            background: #f5f5f5;
        }
        
        .info p {
            margin: 5px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th {
            background: #2c5aa0;
            color: white;
            padding: 10px 5px;
            text-align: left;
        }
        
        td {
            padding: 8px 5px;
            border-bottom: 1px solid #ddd;
        }
        
        .total {
            background: #2c5aa0;
            color: white;
            font-weight: bold;
            padding: 10px;
            text-align: right;
            margin-top: 10px;
        }
        
        .botones {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 10px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        }
        
        .btn {
            display: block;
            width: 100%;
            padding: 15px;
            margin: 5px 0;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .btn-whatsapp {
            background: #25D366;
            color: white;
        }
        
        .btn-captura {
            background: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>

<script>
// BLOQUEAR completamente la función de imprimir
window.print = function() {
    alert('⚠️ La función de imprimir está deshabilitada.\n\n📸 Por favor usa el botón "¿Cómo guardar?" para tomar una captura de pantalla.\n\n💬 O envía la factura por WhatsApp.');
    return false;
};

// Evitar atajos de teclado para imprimir
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
        e.preventDefault();
        alert('⚠️ La función de imprimir está deshabilitada.\n\n📸 Usa el botón "¿Cómo guardar?" abajo.');
        return false;
    }
});
</script>

<div class="factura">
    <div style="background: #fff3cd; border: 2px solid #ffc107; padding: 10px; margin-bottom: 15px; border-radius: 5px; text-align: center;">
        <strong>⚠️ IMPORTANTE:</strong> NO uses el botón de descarga de tu navegador (arriba).<br>
        📱 Usa los botones amarillos/verdes ABAJO para guardar.
    </div>
    
    <h1>FACTURA</h1>
    
    <div class="info">
        <p><strong>De:</strong> <?php echo $_settings->info('name') ?></p>
        <p><strong>Para:</strong> <?php echo $customer_name ?></p>
        <p><strong>Fecha:</strong> <?php echo date("d/m/Y", strtotime($date_created)) ?></p>
        <p><strong>Código:</strong> <?php echo $invoice_code ?></p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Cant.</th>
                <th>Producto</th>
                <th>Precio</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($type == 1)
                $items = $conn->query("SELECT i.*,p.product as `name` FROM invoices_items i inner join product_list p on p.id = i.form_id where i.invoice_id = '{$id}' ");
            else
                $items = $conn->query("SELECT i.*,s.`service` as `name` FROM invoices_items i inner join service_list s on s.id = i.form_id where i.invoice_id = '{$id}' ");
            
            while ($row = $items->fetch_assoc()):
            ?>
                <tr>
                    <td><?php echo $row['quantity'] ?></td>
                    <td><?php echo $row['name'] ?></td>
                    <td>S/ <?php echo number_format($row['price'], 2) ?></td>
                    <td>S/ <?php echo number_format($row['total'], 2) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    
    <div class="total">
        TOTAL: S/ <?php echo number_format($total_amount, 2) ?>
    </div>
    
    <?php if (!empty($remarks)): ?>
    <div class="info">
        <p><strong>Observaciones:</strong></p>
        <p><?php echo $remarks ?></p>
    </div>
    <?php endif; ?>
</div>

<div class="botones">
    <button class="btn btn-captura" onclick="alert('📸 Toma captura de pantalla:\n\nAndroid: Volumen abajo + Encendido\niPhone: Botón lateral + Volumen arriba')">
        📸 ¿Cómo guardar?
    </button>
    <button class="btn btn-whatsapp" onclick="window.open('https://wa.me/?text=Factura <?php echo $invoice_code ?>: ' + encodeURIComponent(window.location.href))">
        💬 Enviar por WhatsApp
    </button>
</div>

</body>
</html>
