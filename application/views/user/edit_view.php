<div class="row">
	<div class="col-md-12">
		<h1>Edit User</h1>
		<?php echo validation_errors(); ?>
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		<form class="form-horizontal" role="form" method="POST" action="">
			<div class="form-group">
				<label for="user_name" class="col-sm-3 control-label">User Name *</label>
				<div class="col-sm-6">
					<input type="text" name="user_name" class="form-control" id="user_name" required="required" placeholder="User Name" value="<?php echo $user['user_name']; ?>" readonly="true" maxlength="50">
				</div>
				<div class="col-sm-3">
					<span class="status_username"></span>
				</div>
			</div>
			<div class="form-group">
				<label for="user_fullname" class="col-sm-3 control-label">Full Name *</label>
				<div class="col-sm-6">
					<input type="text" name="user_fullname" class="form-control" id="user_fullname" required="required" placeholder="Full Name" value="<?php echo $user['user_fullname']; ?>" maxlength="50">
				</div>
				<div class="col-sm-3"></div>
			</div>
			<div class="form-group">
				<label for="user_type_id" class="col-sm-3 control-label">User Type *</label>
				<div class="col-sm-6">
					<?php echo $user_type_id; ?>
				</div>
				<div class="col-sm-3"></div>
			</div>
			<div class="form-group">
				<label for="user_email" class="col-sm-3 control-label">Email *</label>
				<div class="col-sm-6">
					<input type="email" name="user_email" class="form-control" id="user_email" required="required" placeholder="Email" maxlength="50" value="<?php echo $user['user_email']; ?>">
				</div>
				<div class="col-sm-3"></div>
			</div>
			<div class="form-group">
				<label for="user_phone" class="col-sm-3 control-label">Phone *</label>
				<div class="col-sm-6">
					<input type="text" name="user_phone" class="form-control" id="user_phone" required="required" placeholder="08xxxxxxxxxxx" maxlength="13" value="<?php echo $user['user_phone']; ?>">
				</div>
				<div class="col-sm-3"></div>
			</div>
			<div class="form-group">
				<label for="user_address" class="col-sm-3 control-label">Address</label>
				<div class="col-sm-6">
					<textarea name="user_address" class="form-control" id="user_address" required="required" placeholder="Address"><?php echo $user['user_address']; ?></textarea>
				</div>
				<div class="col-sm-3"></div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9">
					<input type="submit" name="submit_user_edit" id="submit_user_edit" class="btn btn-default" value="Edit">&nbsp;
					<a href="<?php echo base_url().'user/listdata'; ?>" class="btn btn-warning">Batal</a>
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-4">&nbsp;</div>
</div>