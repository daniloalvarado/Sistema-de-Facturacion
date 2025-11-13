<?php if ($_settings->chk_flashdata('success')): ?>
	<script>
		alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
	</script>
<?php endif; ?>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Facturas</h3>
		<div class="card-tools">
			<a href="./?page=invoice/manage" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> Nueva Factura</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
			<div class="container-fluid">
				<div class="table-responsive">
					<table class="table table-bordered table-stripped">
						<colgroup>
							<col width="5%">
							<col width="15%">
							<col width="15%">
							<col width="20%">
							<col width="30%">
							<col width="15%">
						</colgroup>
						<thead>
							<tr>
								<th>#</th>
								<th>Creación</th>
								<th>Código</th>
								<th>Cliente</th>
								<th>Detalles</th>
								<th>Acción</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i = 1;
							$qry = $conn->query("SELECT * from `invoice_list`  order by date(date_created) desc ");
							while ($row = $qry->fetch_assoc()):
								$row['remarks'] = strip_tags(stripslashes(html_entity_decode($row['remarks'])));
								$items = $conn->query("SELECT * FROM invoices_items where invoice_id = {$row['id']} ")->num_rows;
							?>
								<tr>
									<td class="text-center"><?php echo $i++; ?></td>
									<td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
									<td><?php echo $row['invoice_code'] ?></td>
									<td><?php echo $row['customer_name'] ?></td>
									<td>
										<p class="m-0"><small><b>Tipo de Factura:</b> <?php echo $row['type'] == 1 ? "Producto" : "Servicio" ?></small></p>
										<p class="m-0"><small><b>Cantidad de Items:</b> <?php echo number_format($items) ?></small></p>
										<p class="m-0"><small><b>Cantidad Total:</b> <?php echo number_format($row['total_amount']) ?></small></p>
									</td>
									<td align="center">
										<button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
											Acción
											<span class="sr-only">Toggle Dropdown</span>
										</button>
										<div class="dropdown-menu" role="menu">
											<a class="dropdown-item edit_data" href="./?page=invoice/manage&id=<?php echo md5($row['id']) ?>"><span class="fa fa-edit text-primary"></span> Editar</a>
											<a class="dropdown-item print_invoice" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-code="<?php echo $row['invoice_code'] ?>"><span class="fa fa-download text-success"></span> Descargar</a>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Eliminar</a>
										</div>
									</td>
								</tr>
							<?php endwhile; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		// Funcionalidad para el nuevo botón "Descargar"
		$('.print_invoice').click(function() {
			var id = $(this).attr('data-id');
			var code = $(this).attr('data-code');

			// Abre una nueva ventana/pestaña para la impresión
			var nw = window.open("./invoice/print.php?id=" + id, "_blank", "width=700,height=500");

			if (nw) {
				// Espera a que la ventana emergente se cargue
				nw.onload = function() {
					// Modifica el título de la página para forzar el nombre de archivo de descarga
					// El nombre será: FACTURA_[CODIGO_DE_FACTURA]
					nw.document.title = "FACTURA_" + code;

					// Dispara la impresión
					nw.print();

					// Cierra la ventana después de un breve retraso
					setTimeout(() => {
						nw.close();
					}, 500);
				};
			}
		});
		$('.delete_data').click(function() {
			_conf("¿Estás seguro de eliminar esta factura permanentemente?", "delete_invoice", [$(this).attr('data-id')])
		})
		$('.table').dataTable({
			// AÑADIR ESTA CONFIGURACIÓN DE LENGUAJE
			"language": {
				"lengthMenu": "Mostrar _MENU_ entradas", // Show _MENU_ entries
				"search": "Buscar:", // Search:
				"info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
				"infoEmpty": "Mostrando 0 a 0 de 0 entradas",
				"infoFiltered": "(filtrado de _MAX_ entradas totales)",
				"paginate": {
					"first": "Primero",
					"last": "Último",
					"next": "Siguiente",
					"previous": "Anterior"
				},
				"zeroRecords": "No se encontraron registros coincidentes"
			}
		});
		$('#uni_modal').on('shown.bs.modal', function() {
			$('.select2').select2({
				width: 'resolve'
			})
			$('.summernote').summernote({
				height: 200,
				toolbar: [
					['style', ['style']],
					['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
					['fontname', ['fontname']],
					['fontsize', ['fontsize']],
					['color', ['color']],
					['para', ['ol', 'ul', 'paragraph', 'height']],
					['table', ['table']],
					['view', ['undo', 'redo', 'fullscreen', 'codeview', 'help']]
				]
			})
		})
	})

	function delete_invoice($id) {
		start_loader();
		$.ajax({
			url: _base_url_ + "classes/Master.php?f=delete_invoice",
			method: "POST",
			data: {
				id: $id
			},
			dataType: "json",
			error: err => {
				console.log(err)
				alert_toast("An error occured.", 'error');
				end_loader();
			},
			success: function(resp) {
				if (typeof resp == 'object' && resp.status == 'success') {
					location.reload();
				} else {
					alert_toast("An error occured.", 'error');
					end_loader();
				}
			}
		})
	}
</script>
