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
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
        
        /* Botones de acción */
        .action-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 10px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            display: flex;
            gap: 10px;
            z-index: 1000;
        }
        
        .btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
        }
        
        .btn-primary {
            background: #2c5aa0;
            color: white;
        }
        
        .btn-success {
            background: #4caf50;
            color: white;
        }
        
        .btn-warning {
            background: #ff9800;
            color: white;
        }
        
        /* Modal de opciones */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }
        
        .modal.active {
            display: flex;
        }
        
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .modal-title {
            font-size: 18px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 15px;
        }
        
        .modal-option {
            padding: 15px;
            margin: 10px 0;
            background: #f5f5f5;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .modal-option:active {
            background: #e0e0e0;
        }
        
        .modal-option-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .modal-option-desc {
            font-size: 12px;
            color: #666;
        }
        
        .close-modal {
            margin-top: 15px;
            padding: 10px;
            background: #ccc;
            border: none;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
        }
        
        /* Espaciado inferior para botones */
        .invoice-container {
            margin-bottom: 80px;
        }
        
        /* Responsive */
        @media screen and (max-width: 600px) {
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
        
        /* Estilos para impresión */
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .action-bar,
            .modal {
                display: none !important;
            }
            
            .invoice-container {
                box-shadow: none;
                margin-bottom: 0;
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
    
    <div class="invoice-container">
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
        <button class="btn btn-success" onclick="compartir()">📤 Compartir</button>
        <button class="btn btn-warning" onclick="mostrarOpciones()">📥 Guardar</button>
        <button class="btn btn-primary" onclick="imprimirRapido()">🖨️ Imprimir</button>
    </div>
    
    <!-- Modal de opciones -->
    <div class="modal" id="modalOpciones">
        <div class="modal-content">
            <div class="modal-title">¿Cómo quieres guardar?</div>
            
            <div class="modal-option" onclick="capturarPantalla()">
                <div class="modal-option-title">📸 Captura de Pantalla</div>
                <div class="modal-option-desc">Toma una captura y guárdala en tu galería (Recomendado para móvil)</div>
            </div>
            
            <div class="modal-option" onclick="imprimirAPDF()">
                <div class="modal-option-title">📄 Guardar como PDF</div>
                <div class="modal-option-desc">Usa la función de imprimir del navegador</div>
            </div>
            
            <div class="modal-option" onclick="enviarWhatsApp()">
                <div class="modal-option-title">💬 Enviar por WhatsApp</div>
                <div class="modal-option-desc">Comparte este link por WhatsApp</div>
            </div>
            
            <div class="modal-option" onclick="enviarEmail()">
                <div class="modal-option-title">📧 Enviar por Email</div>
                <div class="modal-option-desc">Abre tu app de correo para enviar</div>
            </div>
            
            <button class="close-modal" onclick="cerrarModal()">Cancelar</button>
        </div>
    </div>
    
    <script>
        function mostrarOpciones() {
            document.getElementById('modalOpciones').classList.add('active');
        }
        
        function cerrarModal() {
            document.getElementById('modalOpciones').classList.remove('active');
        }
        
        function imprimirRapido() {
            window.print();
        }
        
        function imprimirAPDF() {
            cerrarModal();
            alert('📱 INSTRUCCIONES:\n\n' +
                  '1. Se abrirá la ventana de imprimir\n' +
                  '2. Selecciona "Guardar como PDF"\n' +
                  '3. Elige dónde guardar\n\n' +
                  '✅ En iPhone: Pellizca la vista previa para crear PDF');
            setTimeout(() => window.print(), 500);
        }
        
        function compartir() {
            const titulo = 'Factura <?php echo $invoice_code; ?>';
            const texto = 'Cliente: <?php echo $customer_name; ?>\nTotal: S/ <?php echo number_format($total_amount, 2); ?>';
            
            if (navigator.share) {
                navigator.share({
                    title: titulo,
                    text: texto,
                    url: window.location.href
                }).catch(err => console.log('Error al compartir:', err));
            } else {
                alert('Link copiado para compartir:\n' + window.location.href);
                copiarTexto(window.location.href);
            }
        }
        
        function capturarPantalla() {
            cerrarModal();
            alert('📸 CÓMO TOMAR CAPTURA:\n\n' +
                  '📱 Android: Botón bajar volumen + encendido\n' +
                  '🍎 iPhone: Botón lateral + subir volumen\n\n' +
                  '💡 TIP: Desplázate hacia abajo para capturar todo');
        }
        
        function enviarWhatsApp() {
            const texto = encodeURIComponent('Factura <?php echo $invoice_code; ?>\n' + window.location.href);
            window.open('https://wa.me/?text=' + texto, '_blank');
            cerrarModal();
        }
        
        function enviarEmail() {
            const asunto = encodeURIComponent('Factura <?php echo $invoice_code; ?>');
            const cuerpo = encodeURIComponent('Hola,\n\nAdjunto el enlace a la factura:\n' + window.location.href + '\n\nSaludos.');
            window.location.href = 'mailto:?subject=' + asunto + '&body=' + cuerpo;
            cerrarModal();
        }
        
        function copiarTexto(texto) {
            const el = document.createElement('textarea');
            el.value = texto;
            el.style.position = 'fixed';
            el.style.opacity = '0';
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
        }
    </script>
</body>
</html>
