<div class="row">
	<div class="col-md-12">
		<h1>Upload Bukti Pembayaran Denda</h1>
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
				<label for="rent_property_name" class="col-sm-4 control-label">Nomor Peminjaman</label>
				<div class="col-sm-8">
					<?php echo $rent['rent_no']; ?>
					<input type="hidden" name="rent_id" value="<?php echo $rent['rent_id']; ?>">
				</div>
			</div>
			<div class="form-group">
				<label for="rent_property_name" class="col-sm-4 control-label">Properti</label>
				<div class="col-sm-8">
					<img src="<?php echo base_url().'uploads/images/'.$rent['property_images']; ?>" class="img-thumbnail" width="300"><br/>
					<?php echo $rent['property_name']; ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Nama Peminjam</label>
				<div class="col-sm-8">
					<?php echo $rent['rent_fullname']; ?>
				</div>
			</div>
			<div class="form-group">
				<label for="rent_date" class="col-sm-4 control-label">Tanggal Peminjaman </label>
				<div class="col-sm-8">
					<?php echo $this->tanggal->tanggal_indo($rent['rent_date']); ?>
				</div>
			</div>
			<div class="form-group">
				<label for="rent_date_return" class="col-sm-4 control-label">Tanggal Pengembalian </label>
				<div class="col-sm-8">
					<?php echo $this->tanggal->tanggal_indo($rent['rent_date_return']); ?>
				</div>
			</div>
			<div class="form-group">
				<label for="rent_desc" class="col-sm-4 control-label">Keterangan</label>
				<div class="col-sm-8">
					<?php echo $rent['rent_description']; ?>
				</div>
			</div>
			<div class="form-group">
				<label for="rent_type" class="col-sm-4 control-label">Tipe Peminjaman</label>
				<div class="col-sm-8">
					<?php echo ($rent['rent_type']=='1') ? 'Internal' : 'Eksternal'; ?>
				</div>
			</div>
			<div class="form-group">
				<label for="rent_price" class="col-sm-4 control-label">Total Bayar</label>
				<div class="col-sm-8">
					<?php echo $this->uang->rupiah($rent['rent_price']); ?>
				</div>
			</div>
			<div class="form-group">
				<label for="rent_penalty" class="col-sm-4 control-label">Denda</label>
				<div class="col-sm-8">
					<?php echo $this->uang->rupiah($rent['rent_penalty']); ?>
				</div>
			</div>
			<div class="form-group">
				<label for="rent_penalty_paid" class="col-sm-4 control-label">Pembayaran Denda</label>
				<div class="col-sm-8">
					<?php echo ($rent['rent_penalty_paid']=='0') ? 'Belum Membayar Denda' : 'Denda Sudah Dibayar / Tidak Ada Denda'; ?>
				</div>
			</div>
			<div id="rent_upload_internal" class="form-group">
				<label for="rent_penalty_upload" class="col-sm-4 control-label">Upload Bukti Transfer Denda*</label>
				<div class="col-sm-8">
					<input type="file" name="rent_penalty_upload" id="rent_penalty_upload" class="form-control">
					<span id="helpBlock">Max Size 800kb. Max Resolution (2000 px x 2000px). Allowed Files(.JPG,.PNG,.GIF,.DOC,.PDF,.DOCX)</span>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-4 col-sm-8">
					<input type="submit" name="submit" id="submit" class="btn btn-default" value="Upload">&nbsp;
					<a href="<?php echo base_url().'rent/history'; ?>" class="btn btn-warning">Batal</a>
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-6">&nbsp;</div>
</div>