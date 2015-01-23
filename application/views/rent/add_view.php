<div class="row">
	<div class="col-md-12">
		<h1>Peminjaman Properti</h1>
		<?php 
			echo validation_errors();
			echo '<br/>';
			if(isset($error_upload)){
				echo $error_upload;
			}
		 ?>
		 <?php
		if($this->session->flashdata('message_alert')){
			echo $this->session->flashdata('message_alert');
		}
		?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<h4>Daftar Peminjaman Properti <?php echo $property_name; ?></h4>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>No</th>
					<th>Nomor Peminjaman</th>
					<th>Tanggal Peminjaman</th>
					<th>Tanggal Pengembalian</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
			<?php
				$no = 1;
				foreach($property as $row){
					$status = '';

					if($row['rent_status']=='0'){
					$status = 'Menunggu Konfirmasi Peminjaman';
					}elseif($row['rent_status']=='1'){
						$status = 'Peminjaman Telah Dikonfirmasi';
					}elseif($row['rent_status']=='2'){
						$status = 'Menunggu Konfirmasi Pengembalian';
					}elseif($row['rent_status']=='3'){
						$status = 'Peminjaman Selesai';
					}

					echo '<tr>';
					echo '<td>'.$no.'</td>';
					echo '<td>'.$row['rent_no'].'</td>';
					echo '<td>'.$this->tanggal->tanggal_indo($row['rent_date']).'</td>';
					echo '<td>'.$this->tanggal->tanggal_indo($row['rent_date_return']).'</td>';
					echo '<td>'.$status.'</td>';
					echo '</tr>';
					$no++;
				}

				if($no==1){
					echo '<tr><td colspan="5"><center>Tidak ada peminjaman</center></td></tr>';
				}
			?>
			</tbody>
		</table>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<form class="form-horizontal" role="form" method="POST" action="" enctype="multipart/form-data">
			<div class="form-group">
				<label for="rent_property_name" class="col-sm-4 control-label">Nama Properti *</label>
				<div class="col-sm-8">
					<?php echo $rent_property_name.$rent_property_id; ?>
				</div>
			</div>
			<div class="form-group">
				<label for="property_rent_price" class="col-sm-4 control-label">Harga Sewa *</label>
				<div class="col-sm-8">
					<?php echo $rent_property_price; ?>
				</div>
			</div>
			<div class="form-group">
				<label for="rent_date" class="col-sm-4 control-label">Tanggal Peminjaman *</label>
				<div class="col-sm-8">
					<input type="text" name="rent_date" class="form-control date" id="rent_date" required="required" placeholder="mm/dd/yyyy">
				</div>
			</div>
			<div class="form-group">
				<label for="rent_days" class="col-sm-4 control-label">Lama Peminjaman *</label>
				<div class="col-sm-8">
					<!-- <input type="text" name="rent_date_return" class="form-control" id="rent_date_return" required="required" placeholder="mm/dd/yyyy"> -->
					<input type="text" name="rent_days" id="rent_days" value="1" class="form-control" required="required">
				</div>
			</div>
			<div class="form-group">
				<label for="rent_date_return" class="col-sm-4  control-label">Tanggal Pengembalian *</label>
				<div class="col-sm-8">
					<input type="text" name="rent_date_return" class="form-control" id="rent_date_return" required="required" placeholder="mm/dd/yyyy" readonly="true">
				</div>
			</div>
			<div class="form-group">
				<label for="rent_desc" class="col-sm-4 control-label">Keterangan</label>
				<div class="col-sm-8">
					<textarea name="rent_desc" id="rent_desc" class="form-control" placeholder="Keterangan"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label for="rent_type" class="col-sm-4 control-label">Tipe Peminjaman *</label>
				<div class="col-sm-8">
					<select name="rent_type" id="rent_type" class="form-control" required="required">
						<option value="">Pilih</option>
						<option value="1">Internal</option>					
						<option value="2">Eksternal</option>
					</select>
				</div>
			</div>
			<div id="rent_upload_internal" class="form-group">
				<label for="rent_upload" class="col-sm-4 control-label">Upload Bukti *</label>
				<div class="col-sm-8">
					<input type="file" name="rent_upload" id="rent_upload" class="form-control">
					<span id="helpBlock">Max Size 800kb. Max Resolution (2000 px x 2000px). Allowed Files(.JPG,.PNG,.GIF,.DOC,.PDF,.DOCX)</span>
				</div>
			</div>
			<div class="form-group">
				<label for="rent_price" class="col-sm-4 control-label">Total Bayar</label>
				<div class="col-sm-8">
					<input type="text" name="rent_price" id="rent_price" class="form-control" required="required" value="0" readonly="readonly">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-4 col-sm-8">
					<input type="submit" name="submit" id="submit" class="btn btn-default" value="Simpan">&nbsp;
					<a href="<?php echo base_url().'rent/gallery'; ?>" class="btn btn-warning">Batal</a>
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-6">&nbsp;</div>
</div>