<div class="row">
	<div class="col-md-12">
		<h1>Riwayat Peminjaman Properti</h1>
		<?php
		if($this->session->flashdata('message_alert')){
			echo $this->session->flashdata('message_alert');
		}
		?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>No</th>
					<th>Kode Peminjaman</th>
					<th>Tanggal Peminjaman</th>
					<th>Tanggal Pengembalian</th>
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
				echo '<td>'.$this->tanggal->tanggal_indo($row['rent_date']).'</td>';
				echo '<td>'.$this->tanggal->tanggal_indo($row['rent_date_return']).'</td>';
				echo '<td>'.$row['property_name'].'</td>';
				echo '<td>'.(($row['rent_type']=='1') ? 'Internal' : 'Eksternal').'</td>';
				echo '<td>'.$status.'</td>';
				echo '<td><center>';
				if($row['rent_status']=='0'){
					if($row['rent_type']=='2'){
						echo '<a href="'.base_url().'rent/upload_bukti/'.$row['rent_id'].'">Upload Bukti Transfer</a><br/>';
					}
				}
				echo (($row['rent_status']=='0') ? '<a href="'.base_url().'rent/delete/'.$row['rent_id'].'" class="delete-link">Batalkan Peminjaman</a>' : '');
				echo (($row['rent_status']=='1') ? '<a href="'.base_url().'rent/return_rent/'.$row['rent_id'].'" onclick="return confirm(\'Apakah peminjaman ini akan dikembalikan?\')">Kembalikan Peminjaman</a>' : '');
				if($row['rent_penalty_paid']=='0'){
					echo '<a href="'.base_url().'rent/pay_penalty/'.$row['rent_id'].'">Upload Bukti Pembayaran Denda</a>';
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