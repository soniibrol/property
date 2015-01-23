<div class="row">
	<div class="col-md-12">
		<h1>Ganti Gambar Properti</h1>
		<?php 
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
				<div class="col-sm-12">
					<img src="<?php echo base_url().'uploads/images/'.$property['property_images']; ?>" width="300" class="img-thumbnail">
				</div>
			</div>
			<div class="form-group">
				<label for="property_name" class="col-sm-4 control-label">Nama Properti *</label>
				<div class="col-sm-8">
					<?php echo $property['property_name']; ?>
				</div>
			</div>
			<div class="form-group">
				<label for="property_rent_price" class="col-sm-4 control-label">Harga Sewa *</label>
				<div class="col-sm-8">
					<?php echo $this->uang->rupiah($property['property_rent_price']); ?>
				</div>
			</div>
			<div class="form-group">
				<label for="property_desc" class="col-sm-4 control-label">Deskripsi *</label>
				<div class="col-sm-8">
					<?php echo $property['property_desc']; ?>
				</div>
			</div>
			<div class="form-group">
				<label for="property_images" class="col-sm-4 control-label">Upload Gambar *</label>
				<div class="col-sm-8">
					<input type="file" name="property_images" id="property_images" class="form-control">
					<span id="helpBlock">Max Size 800kb. Max Resolution (2000 px x 2000px). Allowed Files(.JPG,.PNG,.GIF)</span>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-4 col-sm-8">
					<input type="submit" name="submit" id="submit" class="btn btn-default" value="Ganti Gambar">&nbsp;
					<a href="<?php echo base_url().'property/listdata'; ?>" class="btn btn-warning">Batal</a>
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-6">&nbsp;</div>
</div>