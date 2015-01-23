<div class="row">
	<div class="col-md-12">
		<fieldset>
			<legend>Ubah Password</legend>
			<?php
			if($this->session->flashdata('change_password_callback')){
				echo $this->session->flashdata('change_password_callback');
			}
			?>
			<form class="form-horizontal" method="POST" action="">
				<div class="form-group">
					<label class="col-sm-2 control-label" for="old_password">Password Lama</label>
					<div class="col-sm-4">
						<input type="password" name="old_password" id="old_password" class="form-control" required="true">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="new_password">Password Baru</label>
					<div class="col-sm-4">
						<input type="password" name="new_password" id="new_password" class="form-control" required="true">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="confirm_password">Konfirmasi Password</label>
					<div class="col-sm-4">
						<input type="password" name="confirm_password" id="confirm_password" class="form-control" required="true">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-4">
						<input type="submit" name="submit" id="submit" value="Change Password" class="btn btn-warning">
					</div> 
				</div>
			</form>
		</fieldset>
	</div>
</div>