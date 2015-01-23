<div class="row">
	<div class="col-md-12">
		<h1>Konfirmasi Pengembalian Properti</h1>
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
					<th>Kode Peminjaman</th>
					<th>Nama Peminjam</th>
					<th>Nama Properti</th>
					<th>Tipe Peminjaman</th>
					<th>Status Peminjaman</th>
					<th><center>Action</center></th>
				</tr>
			</thead>
			<tbody>
			<?php
			$no = $number;
			foreach ($rent as $row) {
				$status = 'null';
				if($row['rent_status']=='0'){
					$status = 'Menunggu Konfirmasi Peminjaman';
				}elseif($row['rent_status']=='1'){
					$status = 'Peminjaman Telah Dikonfirmasi';
				}elseif($row['rent_status']=='2'){
					if($row['rent_penalty_paid']=='0'){
						$status = 'Menunggu Konfirmasi Pengembalian - Denda '.$this->uang->rupiah($row['rent_penalty']);
					}else{
						$status = 'Menunggu Konfirmasi Pengembalian';
					}
				}elseif($row['rent_status']=='3'){
					if($row['rent_penalty_paid']=='0'){
						$status = 'Peminjaman Selesai - Denda '.$this->uang->rupiah($row['rent_penalty']);
					}else{
						$status = 'Peminjaman Selesai';
					}
				}

				echo '<tr>';
				echo '<td>'.$no.'</td>';
				echo '<td>'.$row['rent_no'].'</td>';
				echo '<td>'.$row['rent_fullname'].'</td>';
				echo '<td>'.$row['property_name'].'</td>';
				echo '<td>'.(($row['rent_type']=='1') ? 'Internal' : 'Eksternal').'</td>';
				echo '<td>'.$status.'</td>';
				echo '<td><center>';
				echo (($row['rent_status']=='0') ? '<a href="'.base_url().'rent/delete/'.$row['rent_id'].'" onclick="return confirm(\'Apakah peminjaman ini akan dibatalkan?\')">Batalkan Peminjaman</a><br/><a href="'.base_url().'rent/confirm_rent/'.$row['rent_id'].'">Konfirmasi Peminjaman</a>' : '');
				if($row['rent_status']=='1'){
					if($row['rent_type']=='1'){
						echo (($row['rent_status']=='1') ? '<a href="'.base_url().'rent/edit_rent/'.$row['rent_id'].'">Edit Peminjaman</a>' : '');
					}
				}
				echo (($row['rent_status']=='2') ? '<a href="'.base_url().'rent/confirm_return/'.$row['rent_id'].'">Konfirmasi Pengembalian</a>' : '');
				if(($row['rent_status']=='2') || ($row['rent_status']=='3')){
					if($row['rent_penalty_paid']=='0'){
						echo '<br/><a href="'.base_url().'rent/confirm_penalty/'.$row['rent_id'].'">Konfirmasi Denda</a>';
					}
				}
				echo '</center></td>';
				echo '</tr>';
				++$no;
			}
			?>
			</tbody>
		</table>
		<?php echo $paging; ?>
	</div>
</div>