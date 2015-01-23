<div class="row">
	<div class="col-md-12">
		<h1>Tambah User Type</h1>
		<?php echo validation_errors(); ?>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<form class="form-horizontal" role="form" method="POST" action="">
			<div class="form-group">
				<label for="user_type_name" class="col-sm-4 control-label">Nama User Type</label>
				<div class="col-sm-8">
					<input type="text" name="user_type_name" class="form-control" id="user_type_name" required="required" placeholder="User Type Name">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-4 col-sm-8">
					<input type="submit" name="submit" id="submit" class="btn btn-default" value="Simpan">&nbsp;
					<a href="<?php echo base_url().'user_type/listdata'; ?>" class="btn btn-warning">Batal</a>
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-6">&nbsp;</div>
</div>