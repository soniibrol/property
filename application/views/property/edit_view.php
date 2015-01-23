<div class="row">
	<div class="col-md-12">
		<h1>Edit Properti</h1>
		<?php 
			echo validation_errors();
			echo '<br/>';
			if(isset($error_upload)){
				echo $error_upload;
			}
		 ?>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<form class="form-horizontal" role="form" method="POST" action="" enctype="multipart/form-data">
			<div class="form-group">
				<label for="property_category_id" class="col-sm-4 control-label">Kategori *</label>
				<div class="col-sm-8">
					<?php echo $property_category_id; ?>
				</div>
			</div>
			<div class="form-group">
				<label for="property_name" class="col-sm-4 control-label">Nama Properti *</label>
				<div class="col-sm-8">
					<input type="text" name="property_name" class="form-control" id="property_name" required="required" placeholder="Nama Properti" value="<?php echo $property['property_name']; ?>">
				</div>
			</div>
			<div class="form-group">
				<label for="property_rent_price" class="col-sm-4 control-label">Harga Sewa *</label>
				<div class="col-sm-8">
					<input type="text" name="property_rent_price" class="form-control" id="property_rent_price" required="required" placeholder="000,00" value="<?php echo $property['property_rent_price']; ?>">
				</div>
			</div>
			<div class="form-group">
				<label for="property_desc" class="col-sm-4 control-label">Deskripsi *</label>
				<div class="col-sm-8">
					<textarea name="property_desc" id="property_desc" class="form-control" required="required" placeholder="Deskripsi"><?php echo $property['property_desc']; ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-4 col-sm-8">
					<input type="submit" name="submit" id="submit" class="btn btn-default" value="Edit">&nbsp;
					<a href="<?php echo base_url().'property/listdata'; ?>" class="btn btn-warning">Batal</a>
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-6">&nbsp;</div>
</div>