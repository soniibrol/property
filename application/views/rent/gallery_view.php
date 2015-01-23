<div class="row">
	<div class="col-md-12">
		<h1>Galeri</h1>
		<?php
		if($this->session->flashdata('message_alert')){
			echo $this->session->flashdata('message_alert');
		}
		?>
	</div>
</div>
<div class="row">
	<div class="col-md-4"></div>
	<div class="col-md-4">
		<form method="POST" action="" class="form-inline">
			<input type="text" name="search_field" id="search_field" class="form-control" placeholder="Cari..">
			<input type="submit" name="search" id="search" class="btn btn-warning" value="Cari">
		</form>
	</div>
	<div class="col-md-4"></div>
</div><br/>
<div class="row">
	<?php
	$no = $number;
	foreach ($property as $row) {
		echo '<div class="col-md-3 gallery-items">';
		echo '<img src="'.base_url().'uploads/images/'.$row['property_images'].'" class="img-thumbnail" width="230">';
		echo '<br/>';
		echo '<h5><a href="#" data-toggle="tooltip" data-placement="top" title="'.$row['property_desc'].'">'.$row['property_code'].' - '.$row['property_name'].'</a></h5>';
		echo '<h6>'.$this->uang->rupiah($row['property_rent_price']).' / day</h6>';
		//echo ($row['property_status']=='1') ? '<span class="teks-hijau">Tersedia</span> <a href="'.base_url().'rent/add/'.$row['property_id'].'">[Sewa]</a>' : '<span class="teks-merah">Dipinjam</span>';
		echo '<a href="'.base_url().'rent/add/'.$row['property_id'].'">[Sewa]</a>';
		echo '</div>';
		++$no;
	}
	?>
</div>
<div class="row">
	<div class="col-md-12">
		<?php echo $paging; ?>
	</div>
</div>