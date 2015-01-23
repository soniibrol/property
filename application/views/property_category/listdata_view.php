<div class="row">
	<div class="col-md-12">
		<h1>Kelola Kategori Properti</h1>
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
					<th>Kode Kategori Properti</th>
					<th>Nama Kategori Properti</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$no = $number;
			foreach ($property_category as $row) {
				echo '<tr>';
				echo '<td>'.$no.'</td>';
				echo '<td>'.$row['property_category_code'].'</td>';
				echo '<td>'.$row['property_category_name'].'</td>';
				echo '<td><a href="'.base_url().'property_category/edit/'.$row['property_category_id'].'">Edit</a>&nbsp;&nbsp;';
				echo '<a href="'.base_url().'property_category/delete/'.$row['property_category_id'].'" class="delete-link">Hapus</a>&nbsp;&nbsp;</td>';
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
		<a href="<?php echo base_url().'property_category/add'; ?>" class="btn btn-warning btn-lg">Tambah Kategori Properti</a>
	</div>
</div>