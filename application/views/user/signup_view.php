<!DOCTYPE html>
<html>
<head>
	<title>Signup User</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/custom.css">

	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-2">&nbsp;</div>
			<div class="col-md-8">
				<center><h1>Daftar User</h1>
				<?php echo validation_errors(); ?>
				<?php
				if($this->session->flashdata('message_alert')){
					echo $this->session->flashdata('message_alert');
				}
				?>
				</center>
			</div>
			<div class="col-md-2">&nbsp;</div>
		</div>
		<div class="row">
			<div class="col-md-2">&nbsp;</div>
			<div class="col-md-8">
				<form class="form-horizontal" role="form" method="POST" action="">
					<div class="form-group">
						<label for="user_name" class="col-sm-3 control-label">User Name *</label>
						<div class="col-sm-6">
							<input type="text" name="user_name" class="form-control" id="user_name" required="required" placeholder="User Name" maxlength="50">
						</div>
						<div class="col-sm-3">
							<span class="status_username"></span>
						</div>
					</div>
					<div class="form-group">
						<label for="user_password" class="col-sm-3 control-label">User Password *</label>
						<div class="col-sm-6">
							<input type="password" name="user_password" class="form-control" id="user_password" required="required" placeholder="******" maxlength="50">
						</div>
						<div class="col-sm-3">
						</div>
					</div>
					<div class="form-group">
						<label for="user_fullname" class="col-sm-3 control-label">Full Name *</label>
						<div class="col-sm-6">
							<input type="text" name="user_fullname" class="form-control" id="user_fullname" required="required" placeholder="Full Name" maxlength="50">
						</div>
						<div class="col-sm-3"></div>
					</div>
					<!-- <div class="form-group">
						<label for="user_type_id" class="col-sm-3 control-label">User Type *</label>
						<div class="col-sm-6">
							<?php echo $user_type_id; ?>
						</div>
						<div class="col-sm-3"></div>
					</div> -->
					<div class="form-group">
						<label for="user_email" class="col-sm-3 control-label">Email *</label>
						<div class="col-sm-6">
							<input type="email" name="user_email" class="form-control" id="user_email" required="required" placeholder="Email" maxlength="50">
						</div>
						<div class="col-sm-3"></div>
					</div>
					<div class="form-group">
						<label for="user_phone" class="col-sm-3 control-label">Phone *</label>
						<div class="col-sm-6">
							<input type="text" name="user_phone" class="form-control" id="user_phone" required="required" placeholder="08xxxxxxxxxxx" maxlength="13">
						</div>
						<div class="col-sm-3"></div>
					</div>
					<div class="form-group">
						<label for="user_address" class="col-sm-3 control-label">Address</label>
						<div class="col-sm-6">
							<textarea name="user_address" class="form-control" id="user_address" required="required" placeholder="Address"></textarea>
						</div>
						<div class="col-sm-3"></div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-9">
							<input type="submit" name="submit_user_add" id="submit_user_add" class="btn btn-default" value="Daftar">&nbsp;
							<a href="<?php echo base_url().'user/login'; ?>" class="btn btn-warning">Batal</a>
						</div>
					</div>
				</form>
			</div>
			<div class="col-md-2">&nbsp;</div>
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
	</script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom.js"></script>
</body>
</html>