<div class="row">
	<div class="col-md-12">
		<h1>Kelola Properti</h1>
		<?php
		if($this->session->flashdata('message_alert')){
			echo $this->session->flashdata('message_alert');
		}
		?>
	</div>
</div>
<div class="row">
	<div class="col-md-8"></div>
	<div class="col-md-4">
		<form method="POST" action="" class="form-inline">
			<input type="text" name="search_field" id="search_field" class="form-control" placeholder="Cari..">
			<input type="submit" name="search" id="search" class="btn btn-warning" value="Cari">
		</form>
	</div>
</div><br/>
<div class="row">
	<div class="col-md-12">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>No</th>
					<th>Kode Properti</th>
					<th>Nama Properti</th>
					<th>Harga Sewa</th>
					<th>Status</th>
					<th><center>Action</center></th>
				</tr>
			</thead>
			<tbody>
			<?php
			$no = $number;
			foreach ($property as $row) {
				echo '<tr>';
				echo '<td>'.$no.'</td>';
				echo '<td>'.$row['property_code'].'</td>';
				echo '<td>'.$row['property_name'].'</td>';
				echo '<td>'.$this->uang->rupiah($row['property_rent_price']).'</td>';
				echo '<td>'.(($row['property_status']=='1') ? 'Available' : 'Not Available').'</td>';
				echo '<td><center><a href="'.base_url().'property/edit/'.$row['property_id'].'">Edit Data</a>&nbsp;&nbsp;';
				echo '<a href="'.base_url().'property/change_pic/'.$row['property_id'].'">Ganti Gambar</a>&nbsp;&nbsp;';
				echo '<a href="'.base_url().'property/delete/'.$row['property_id'].'" class="delete-link">Hapus</a>&nbsp;&nbsp;</center></td>';
				echo '</tr>';
				++$no;
			}
			?>
			</tbody>
		</table>
		<?php echo $paging; ?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<a href="<?php echo base_url().'property/add'; ?>" class="btn btn-warning btn-lg">Tambah Properti</a>
	</div>
</div>