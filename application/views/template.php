<!DOCTYPE html>
<html>
<head>
	<title>Property</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/custom.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/dashboard.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/bootstrap-datetimepicker.css">

	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a href="#" class="navbar-brand">APLIKASI PEMINJAMAN PROPERTI</a>
			</div>
			<nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $this->session->userdata('user_fullname'); ?><span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="<?php echo base_url().'user/change_pwd'; ?>">Ubah Password</a></li>
						</ul>
					</li>
					<li><a href="<?php echo base_url().'user/logout'; ?>" onclick="return confirm('Are you sure want to exit?')">Logout</a></li>
				</ul>
			</nav>
		</div>
	</nav>
	<div class="container-fluid">
		<div class="col-md-2 sidebar">
			<ul class="nav nav-sidebar">
				<li class="<?php echo ($this->uri->segment(1)=='dashboard') ? 'active' : ''; ?>"><a href="<?php echo base_url().'dashboard'; ?>">Home</a></li>
				<?php /*** ADMINISTRATOR **/
					if($this->session->userdata('user_type_id')=='1'){
				?>
				<li class="<?php echo ($this->uri->segment(1)=='user_type') ? 'active' : ''; ?>"><a href="<?php echo base_url().'user_type'; ?>">Kelola User Type</a></li>
				<li class="<?php echo ($this->uri->segment(1)=='user') ? 'active' : ''; ?>"><a href="<?php echo base_url().'user'; ?>">Kelola User</a></li>
				<li class="<?php echo ($this->uri->segment(1)=='property_category') ? 'active' : ''; ?>"><a href="<?php echo base_url().'property_category'; ?>">Kelola Kategori Properti</a></li>
				<li class="<?php echo ($this->uri->segment(1)=='property') ? 'active' : ''; ?>"><a href="<?php echo base_url().'property'; ?>">Kelola Properti</a></li>
				<?php
					}
				?>
				<!-- <li class=""><a href="#">Kelola Peminjaman</a></li> -->
				<?php /**** MEMBER ***/
					if(($this->session->userdata('user_type_id')=='4') || ($this->session->userdata('user_type_id')=='1')){
				?>
				<li class="<?php echo ($this->uri->segment(2)=='gallery') ? 'active' : ''; ?>"><a href="<?php echo base_url().'rent/gallery'; ?>">Galeri</a></li>
				<?php
					}
				?>
				<?php
					if($this->session->userdata('user_type_id')=='4'){
				?>
				<li class="<?php echo ($this->uri->segment(2)=='history') ? 'active' : ''; ?>"><a href="<?php echo base_url().'rent/history'; ?>">Riwayat Peminjaman</a></li>
				<?php
					}
				?>
				<?php /**** OPERATOR ***/
					if(($this->session->userdata('user_type_id')=='3') || ($this->session->userdata('user_type_id')=='1')){
				?>
				<li class="<?php echo ($this->uri->segment(2)=='confirmation') ? 'active' : ''; ?>"><a href="<?php echo base_url().'rent/confirmation'; ?>">Kelola Peminjaman</a></li>
				<li class="<?php echo ($this->uri->segment(2)=='return_confirmation') ? 'active' : ''; ?>"><a href="<?php echo base_url().'rent/return_confirmation'; ?>">Kelola Pengembalian</a></li>
				<?php
					}
				?>
				<?php /**** MANAGER ***/
					if(($this->session->userdata('user_type_id')=='2') || ($this->session->userdata('user_type_id')=='1')){
				?>
				<li class="<?php echo ($this->uri->segment(2)=='rent_all') ? 'active' : ''; ?>"><a href="<?php echo base_url().'report/rent_all'; ?>">Laporan Semua Peminjaman</a></li>
				<li class="<?php echo ($this->uri->segment(2)=='rent_by_user') ? 'active' : ''; ?>"><a href="<?php echo base_url().'report/rent_by_user'; ?>">Laporan Berdasarkan Peminjam</a></li>
				<li class="<?php echo ($this->uri->segment(2)=='rent_type') ? 'active' : ''; ?>"><a href="<?php echo base_url().'report/rent_type'; ?>">Laporan Berdasarkan Tipe Pinjaman</a></li>
				<li class="<?php echo ($this->uri->segment(2)=='rent_status') ? 'active' : ''; ?>"><a href="<?php echo base_url().'report/rent_status'; ?>">Laporan Berdasarkan Status Peminjaman</a></li>
				<li class="<?php echo ($this->uri->segment(2)=='rent_income') ? 'active' : ''; ?>"><a href="<?php echo base_url().'report/rent_income'; ?>">Laporan Pendapatan Eksternal</a></li>
				<?php
					}
				?>
			</ul>
		</div>
		<div class="col-md-10 col-sm-offset-2" id="content">
			<?php echo $_content; ?>
			<br/><br/><br/>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 footer">
				<br/>Copyright &copy; 2015 | Designed by <a href="#">Politeknik Pos Indonesia</a>.
			</div>
		</div>
	</div>
	<script type="text/javascript">
	var base_url = '<?php echo base_url(); ?>';
	var today = '<?php echo date("m/d/Y"); ?>';
	</script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/vendor/moment-develop/moment.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/vendor/bootstrap-datetimepicker.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom.js"></script>
</body>
</html>