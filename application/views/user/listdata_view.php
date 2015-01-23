<div class="row">
	<div class="col-md-12">
		<h1>Kelola User</h1>
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
					<th>User Name</th>
					<th>Nama Lengkap</th>
					<th>Email</th>
					<th>User Type</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$no = $number;
			foreach ($user as $row) {
				echo '<tr>';
				echo '<td>'.$no.'</td>';
				echo '<td>'.$row['user_name'].'</td>';
				echo '<td>'.$row['user_fullname'].'</td>';
				echo '<td>'.$row['user_email'].'</td>';
				echo '<td>'.$row['user_type_name'].'</td>';
				if($row['user_id']!=$this->session->userdata('user_id')){
					if($row['user_id']!='1'){
						echo '<td><a href="'.base_url().'user/edit/'.$row['user_id'].'">Edit</a>&nbsp;&nbsp;';
						echo '<a href="'.base_url().'user/delete/'.$row['user_id'].'" class="delete-link">Hapus</a>&nbsp;&nbsp;</td>';
					}else{
						echo '<td>&nbsp;</td>';
					}
				}else{
					echo '<td>&nbsp;</td>';
				}
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
		<a href="<?php echo base_url().'user/add'; ?>" class="btn btn-warning btn-lg">Tambah User</a>
	</div>
</div>