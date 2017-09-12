<div class="modal fade" tabindex="-1" role="dialog" id="mdlProcesar">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Procesar Carrito</h4>
			</div>
			<form method="post">
				<input type="hidden" id="hdnNumeCarr">
				<div class="modal-body">
					<div class="form-group">
						<label for="NumeFact" class="control-label">Nro de Factura</label>
						<input type="text" class="form-control" id="NumeFact" required>
					</div>
					<div class="form-group">
						<label for="ObseCarr" class="control-label">Observaciones</label>
						<textarea class="form-control" id="ObseCarr" required></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="submit" class="btn btn-primary" onclick="procesarCarrito();">Aceptar</button>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->