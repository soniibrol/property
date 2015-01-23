<div class="row">
	<div class="col-md-12">
		<h1>Edit Kategori Properti</h1>
		<?php echo validation_errors(); ?>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<form class="form-horizontal" role="form" method="POST" action="">
			<div class="form-group">
				<label for="property_category_code" class="col-sm-4 control-label">Kode Kategori Properti *</label>
				<div class="col-sm-8">
					<input type="text" name="property_category_code" class="form-control" id="property_category_code" required="required" placeholder="Nama Kategori Properti" maxlength="5" value="<?php echo $property_category['property_category_code']; ?>" readonly>
				</div>
			</div>
			<div class="form-group">
				<label for="property_category_name" class="col-sm-4 control-label">Nama Kategori Properti *</label>
				<div class="col-sm-8">
					<input type="text" name="property_category_name" class="form-control" id="property_category_name" required="required" placeholder="Nama Kategori Properti" value="<?php echo $property_category['property_category_name']; ?>">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-4 col-sm-8">
					<input type="submit" name="submit" id="submit" class="btn btn-default" value="Simpan">&nbsp;
					<a href="<?php echo base_url().'property_category/listdata'; ?>" class="btn btn-warning">Batal</a>
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-6">&nbsp;</div>
</div>