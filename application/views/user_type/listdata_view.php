<div class="row">
	<div class="col-md-12">
		<h1>Kelola User Type</h1>
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
					<th>Nama</th>
					<!-- <th>Action</th> -->
				</tr>
			</thead>
			<tbody>
			<?php
			$no = $number;
			foreach ($user_type as $row) {
				echo '<tr>';
				echo '<td>'.$no.'</td>';
				echo '<td>'.$row['user_type_name'].'</td>';
				//echo '<td><a href="'.base_url().'user_type/edit/'.$row['user_type_id'].'">Edit</a>&nbsp;&nbsp;';
				//echo '<a href="'.base_url().'user_type/delete/'.$row['user_type_id'].'" class="delete-link">Hapus</a>&nbsp;&nbsp;</td>';
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
		<a href="<?php echo base_url().'user_type/add'; ?>" class="btn btn-warning btn-lg">Tambah User Type</a>
	</div>
</div>