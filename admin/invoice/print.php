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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
            background: #f5f5f5;
            padding: 10px;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            margin-bottom: 100px;
        }
        
        .invoice-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 20px;
            border-bottom: 3px solid #2c5aa0;
            padding-bottom: 10px;
        }
        
        .header-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .header-col {
            flex: 1;
        }
        
        .info-item {
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
            font-size: 12px;
        }
        
        .info-value {
            color: #2c5aa0;
            font-weight: bold;
            display: block;
            margin-top: 2px;
        }
        
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .invoice-table thead {
            background: #2c5aa0;
            color: white;
        }
        
        .invoice-table th {
            padding: 10px 5px;
            text-align: left;
            font-size: 12px;
            font-weight: bold;
        }
        
        .invoice-table td {
            padding: 10px 5px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 13px;
        }
        
        .invoice-table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .item-name {
            font-weight: bold;
            color: #333;
            margin-bottom: 3px;
        }
        
        .item-category {
            background: #e3f2fd;
            color: #1976d2;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            display: inline-block;
            margin-bottom: 3px;
        }
        
        .item-description {
            font-size: 11px;
            color: #666;
            margin-top: 3px;
        }
        
        .totals-section {
            margin-top: 20px;
            border-top: 2px solid #2c5aa0;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 10px;
            font-size: 14px;
        }
        
        .total-row.subtotal {
            background: #f5f5f5;
        }
        
        .total-row.tax {
            background: #e3f2fd;
        }
        
        .total-row.final {
            background: #2c5aa0;
            color: white;
            font-weight: bold;
            font-size: 16px;
            padding: 12px 10px;
        }
        
        .observations {
            margin-top: 20px;
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #2c5aa0;
        }
        
        .observations-title {
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 8px;
        }
        
        .footer-note {
            margin-top: 20px;
            text-align: center;
            font-size: 11px;
            color: #888;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
        }
        
        /* Botones de acción FIJOS */
        .action-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 10px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.2);
            display: flex;
            flex-direction: column;
            gap: 8px;
            z-index: 1000;
        }
        
        .btn {
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-screenshot {
            background: #4caf50;
            color: white;
        }
        
        .btn-whatsapp {
            background: #25D366;
            color: white;
        }
        
        .btn-email {
            background: #2196F3;
            color: white;
        }
        
        .btn-share {
            background: #ff9800;
            color: white;
        }
        
        /* Alerta de instrucciones */
        .alert-box {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            z-index: 3000;
            max-width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .alert-box.active {
            display: block;
        }
        
        .alert-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            z-index: 2500;
        }
        
        .alert-overlay.active {
            display: block;
        }
        
        .alert-title {
            font-size: 20px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .alert-content {
            font-size: 14px;
            line-height: 1.8;
            color: #333;
        }
        
        .alert-step {
            background: #f5f5f5;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border-left: 4px solid #4caf50;
        }
        
        .alert-step strong {
            color: #2c5aa0;
            display: block;
            margin-bottom: 5px;
        }
        
        .close-alert {
            margin-top: 20px;
            padding: 12px;
            background: #2c5aa0;
            color: white;
            border: none;
            border-radius: 8px;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        
        /* Responsive */
        @media screen and (max-width: 600px) {
            body {
                padding: 5px;
            }
            
            .invoice-container {
                padding: 15px;
            }
            
            .header-row {
                flex-direction: column;
            }
            
            .invoice-table {
                font-size: 11px;
            }
            
            .invoice-table th,
            .invoice-table td {
                padding: 6px 3px;
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
    
    <div class="invoice-container" id="factura">
        <h1 class="invoice-title">FACTURA</h1>
        
        <div class="header-row">
            <div class="header-col">
                <div class="info-item">
                    <span class="info-label">Facturado por:</span>
                    <span class="info-value"><?php echo $_settings->info('name') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Facturado a:</span>
                    <span class="info-value"><?php echo $customer_name ?></span>
                </div>
            </div>
            <div class="header-col" style="text-align: right;">
                <div class="info-item">
                    <span class="info-label">Fecha:</span>
                    <span class="info-value"><?php echo date("d/m/Y", strtotime($date_created)) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Código:</span>
                    <span class="info-value"><?php echo $invoice_code ?></span>
                </div>
            </div>
        </div>
        
        <table class="invoice-table">
            <thead>
                <tr>
                    <th style="width: 8%;">Cant.</th>
                    <th style="width: 10%;">Unid.</th>
                    <th style="width: 42%;">Producto/Servicio</th>
                    <th style="width: 20%;" class="text-right">P. Unit.</th>
                    <th style="width: 20%;" class="text-right">Total</th>
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
                        <td class="text-center"><?php echo $row['quantity'] ?></td>
                        <td class="text-center"><?php echo $row['unit'] ?></td>
                        <td>
                            <span class="item-category"><?php echo $cat_name ?></span>
                            <div class="item-name"><?php echo $row['name'] ?></div>
                            <?php if (!empty($description)): ?>
                                <div class="item-description"><?php echo $description ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="text-right">S/ <?php echo number_format($row['price'], 2) ?></td>
                        <td class="text-right"><strong>S/ <?php echo number_format($row['total'], 2) ?></strong></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <div class="totals-section">
            <div class="total-row subtotal">
                <span>Subtotal</span>
                <span>S/ <?php echo number_format($sub_total, 2) ?></span>
            </div>
            <div class="total-row tax">
                <span>Impuestos (<?php echo $tax_rate ?>%)</span>
                <span>S/ <?php echo number_format($sub_total * ($tax_rate / 100), 2) ?></span>
            </div>
            <div class="total-row final">
                <span>TOTAL</span>
                <span>S/ <?php echo number_format($total_amount, 2) ?></span>
            </div>
        </div>
        
        <?php if (!empty($remarks)): ?>
        <div class="observations">
            <div class="observations-title">📋 Observaciones:</div>
            <div><?php echo $remarks ?></div>
        </div>
        <?php endif; ?>
        
        <div class="footer-note">
            Documento generado el <?php echo date("d/m/Y H:i:s") ?>
        </div>
    </div>
    
    <!-- Barra de acciones -->
    <div class="action-bar">
        <button class="btn btn-screenshot" onclick="mostrarInstrucciones()">
            📸 Guardar como Imagen
        </button>
        <button class="btn btn-whatsapp" onclick="enviarWhatsApp()">
            💬 Enviar por WhatsApp
        </button>
        <button class="btn btn-email" onclick="enviarEmail()">
            📧 Enviar por Email
        </button>
        <button class="btn btn-share" onclick="compartirLink()">
            🔗 Copiar Link
        </button>
    </div>
    
    <!-- Overlay -->
    <div class="alert-overlay" id="overlay" onclick="cerrarAlerta()"></div>
    
    <!-- Alerta de instrucciones -->
    <div class="alert-box" id="alertBox">
        <div class="alert-title">📸 Cómo Guardar la Factura</div>
        <div class="alert-content">
            <div class="alert-step">
                <strong>📱 Para Android:</strong>
                1. Presiona: Botón de BAJAR VOLUMEN + ENCENDIDO al mismo tiempo<br>
                2. Se guardará en tu Galería/Fotos<br>
                3. Opcional: Desplázate hacia abajo para capturar todo
            </div>
            
            <div class="alert-step">
                <strong>🍎 Para iPhone:</strong>
                1. Presiona: Botón LATERAL + SUBIR VOLUMEN al mismo tiempo<br>
                2. Se guardará en tu app de Fotos<br>
                3. Opcional: Toca la miniatura y usa "Página completa"
            </div>
            
            <div class="alert-step">
                <strong>💡 Consejo:</strong>
                • Puedes hacer varias capturas si la factura es larga<br>
                • Después puedes editar o recortar la imagen<br>
                • También puedes compartirla directamente desde tu galería
            </div>
        </div>
        <button class="close-alert" onclick="cerrarAlerta()">Entendido ✓</button>
    </div>
    
    <script>
        function mostrarInstrucciones() {
            document.getElementById('overlay').classList.add('active');
            document.getElementById('alertBox').classList.add('active');
        }
        
        function cerrarAlerta() {
            document.getElementById('overlay').classList.remove('active');
            document.getElementById('alertBox').classList.remove('active');
        }
        
        function enviarWhatsApp() {
            const texto = encodeURIComponent(
                '📄 *Factura <?php echo $invoice_code; ?>*\n\n' +
                '👤 Cliente: <?php echo $customer_name; ?>\n' +
                '💰 Total: S/ <?php echo number_format($total_amount, 2); ?>\n\n' +
                '🔗 Ver factura completa:\n' + window.location.href
            );
            window.open('https://wa.me/?text=' + texto, '_blank');
        }
        
        function enviarEmail() {
            const asunto = encodeURIComponent('Factura <?php echo $invoice_code; ?> - <?php echo $customer_name; ?>');
            const cuerpo = encodeURIComponent(
                'Estimado/a,\n\n' +
                'Le envío la factura con los siguientes detalles:\n\n' +
                'Código de Factura: <?php echo $invoice_code; ?>\n' +
                'Cliente: <?php echo $customer_name; ?>\n' +
                'Total: S/ <?php echo number_format($total_amount, 2); ?>\n\n' +
                'Puede ver la factura completa en el siguiente enlace:\n' +
                window.location.href + '\n\n' +
                'Saludos cordiales.'
            );
            window.location.href = 'mailto:?subject=' + asunto + '&body=' + cuerpo;
        }
        
        function compartirLink() {
            const url = window.location.href;
            
            // Intentar usar la API de compartir nativa
            if (navigator.share) {
                navigator.share({
                    title: 'Factura <?php echo $invoice_code; ?>',
                    text: 'Cliente: <?php echo $customer_name; ?> | Total: S/ <?php echo number_format($total_amount, 2); ?>',
                    url: url
                }).then(() => {
                    console.log('Compartido exitosamente');
                }).catch(err => {
                    copiarAlPortapapeles(url);
                });
            } else {
                copiarAlPortapapeles(url);
            }
        }
        
        function copiarAlPortapapeles(texto) {
            // Crear elemento temporal
            const el = document.createElement('textarea');
            el.value = texto;
            el.style.position = 'fixed';
            el.style.left = '-9999px';
            document.body.appendChild(el);
            
            // Seleccionar y copiar
            el.select();
            el.setSelectionRange(0, 99999);
            
            try {
                document.execCommand('copy');
                alert('✅ Link copiado al portapapeles!\n\nPuedes pegarlo en WhatsApp, Email, etc.');
            } catch (err) {
                // Si falla, mostrar el link
                prompt('Copia este link:', texto);
            }
            
            document.body.removeChild(el);
        }
        
        // Prevenir que se cierre el menú al tocar dentro del alert
        document.getElementById('alertBox').addEventListener('click', function(e) {
            e.stopPropagation();
        });
    </script>
</body>
</html>
