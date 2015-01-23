<div class="row">
	<div class="col-md-12">
		<h1>Laporan Peminjaman Berdasarkan Peminjam</h1>
		<?php echo validation_errors(); ?>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<form class="form-horizontal" role="form">
			<div class="form-group">
				<label for="start_date" class="col-sm-4 control-label">Peminjam *</label>
				<div class="col-sm-8">
					<?php echo $user; ?>
				</div>
			</div>
			<div class="form-group">
				<label for="start_date" class="col-sm-4 control-label">Dari *</label>
				<div class="col-sm-8">
					<input type="text" name="start_date" class="form-control" id="start_date" required="required" placeholder="mm/dd/yyyy" maxlength="10" value="<?php echo date('m/d/Y'); ?>">
				</div>
			</div>
			<div class="form-group">
				<label for="end_date" class="col-sm-4 control-label">Sampai *</label>
				<div class="col-sm-8">
					<input type="text" name="end_date" class="form-control" id="end_date" required="required" placeholder="mm/dd/yyyy" maxlength="10" value="<?php echo date('m/d/Y'); ?>">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-4 col-sm-8">
					<input type="submit" name="submit" id="submit_report_by_user" class="btn btn-warning" value="Generate Report">&nbsp;
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-6">&nbsp;</div>
</div>