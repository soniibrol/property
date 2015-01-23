<?php

class User extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('user_type_model');
	}

	public function index(){
		$this->loginstatus->check_login();
		if($this->loginstatus->get_role()!='administrator'){
			redirect('dashboard');
		}
		redirect('user/listdata');
	}

	public function listdata($start=0,$perpage=10){
		$this->loginstatus->check_login();
		if($this->loginstatus->get_role()!='administrator'){
			redirect('dashboard');
		}
		$data = array();

		$search = '';
		if($_POST){
			$search = $this->input->post('search_field');
		}

		$count = $this->user_model->get_all(false)->num_rows();
		$data['user'] = $this->user_model->get_all(true,$start,$perpage,$search)->result_array();

		$this->load->library('pagination');
		$config['base_url'] = base_url().'user/listdata/';
		$config['total_rows'] = $count;
		$config['per_page'] = $perpage;

		$this->pagination->initialize($config);

		$data['paging'] = $this->pagination->create_links();
		$data['number'] = $start + 1;

		$this->template->display('user/listdata_view',$data);
	}

	public function add(){
		$this->loginstatus->check_login();
		if($this->loginstatus->get_role()!='administrator'){
			redirect('dashboard');
		}
		$this->form_validation->set_rules('user_name','User Name','required|alpha_dash');
		$this->form_validation->set_rules('user_fullname','Full Name','required');
		$this->form_validation->set_rules('user_type_id','User Type','required|numeric');
		$this->form_validation->set_rules('user_email','Email','required|email');
		$this->form_validation->set_rules('user_phone','Phone','required');
		if($this->form_validation->run()==FALSE){
			$data = array();

			$cat = $this->user_type_model->get_all('all')->result_array();
			$data['user_type_id'] = '<select name="user_type_id" id="user_type_id" class="form-control" required="required">';
			foreach ($cat as $c) {
				$data['user_type_id'] .= '<option value="'.$c['user_type_id'].'">'.$c['user_type_name'].'</option>';
			}
			$data['user_type_id'] .= '</select>';
			$this->template->display('user/add_view',$data);
		}else{
			if($this->check_username_availabilities($this->input->post('user_name'))==false){
				$this->session->set_flashdata('message_alert','<div class="alert alert-warning">Username exists. Please replace by another username.</div>');
				redirect('user/add');
			}

			if($this->check_email_availabilities($this->input->post('user_email'))==false){
				$this->session->set_flashdata('message_alert','<div class="alert alert-warning">Email exists. Please replace by another email.</div>');
				redirect('user/add');
			}

			$data_user = array(
							'user_type_id' => $this->input->post('user_type_id'),
							'user_name' => $this->input->post('user_name'),
							'user_password' => sha1('password'),
							'user_fullname' => $this->input->post('user_fullname'),
							'user_address' => $this->input->post('user_address'),
							'user_email' => $this->input->post('user_email'),
							'user_phone' => $this->input->post('user_phone'),
							'user_active' => '1',
							'created_user' => $this->session->userdata('user_id'),
							'created_time' => date('Y-m-d H:i:s')
						);
			$this->user_model->save($data_user);

			$this->session->set_flashdata('message_alert','<div class="alert alert-success">Data has been saved.</div>');

			redirect('user/listdata');
		}
	}

	public function edit($id){
		$this->loginstatus->check_login();
		if($this->loginstatus->get_role()!='administrator'){
			redirect('dashboard');
		}
		if($id){
			$this->form_validation->set_rules('user_fullname','Full Name','required');
			$this->form_validation->set_rules('user_type_id','User Type','required|numeric');
			$this->form_validation->set_rules('user_email','User Email','required|email');
			$this->form_validation->set_rules('user_phone','User Phone','required');
			if($this->form_validation->run()==FALSE){
				$data = array();

				$count = $this->user_model->get_by_id($id)->num_rows();
				if($count > 0){
					$data['user'] = $this->user_model->get_by_id($id)->row_array();

					$cat = $this->user_type_model->get_all('all')->result_array();
					$data['user_type_id'] = '<select name="user_type_id" id="user_type_id" class="form-control" required="required">';
					foreach ($cat as $c) {
						$selected = '';
						if($data['user']['user_type_id']==$c['user_type_id']){
							$selected = 'selected';
						}
						$data['user_type_id'] .= '<option value="'.$c['user_type_id'].'" '.$selected.'>'.$c['user_type_name'].'</option>';
					}
					$data['user_type_id'] .= '</select>';

					$this->template->display('user/edit_view',$data);
				}else{
					$this->session->set_flashdata('message_alert','<div class="alert alert-danger">The ID you\'ve choosen not registered.</div>');
					redirect('user/listdata');
				}	
			}else{
				$data_user = array(
								'user_type_id' => $this->input->post('user_type_id'),
								'user_fullname' => $this->input->post('user_fullname'),
								'user_email' => $this->input->post('user_email'),
								'user_phone' => $this->input->post('user_phone'),
								'user_address' => $this->input->post('user_address'),
								'updated_user' => $this->session->userdata('user_id'),
								'updated_time' => date('Y-m-d H:i:s')
							);
				$this->user_model->update($id,$data_user);

				$this->session->set_flashdata('message_alert','<div class="alert alert-success">Data has been updated.</div>');

				redirect('user/listdata');
			}
		}else{
			redirect('user/listdata');
		}
	}

	public function delete($id){
		$this->loginstatus->check_login();
		if($this->loginstatus->get_role()!='administrator'){
			redirect('dashboard');
		}
		if($id){
			$count = $this->user_model->get_by_id($id)->num_rows();
			if($count > 0){
				$data_user = array(
									'user_active' => '0',
									'updated_user' => $this->session->userdata('user_id'),
									'updated_time' => date('Y-m-d H:i:s')
				);
				$this->user_model->update($id,$data_user);
				$this->session->set_flashdata('message_alert','<div class="alert alert-success">Data has been deleted.</div>');

				redirect('user/listdata');
			}else{
				$this->session->set_flashdata('message_alert','<div class="alert alert-danger">The ID you\'ve choosen not registered.</div>');
				redirect('user/listdata');
			}
		}else{
			redirect('user/listdata');
		}
	}

	public function login(){
		$this->form_validation->set_rules('username','Username','required');
		$this->form_validation->set_rules('password','Password','required');

		if($this->form_validation->run()==FALSE){
			$this->load->view('user/login_view');
		}else{
			if($this->user_model->check_login()){
				//login success, save to session
				$data_user = $this->user_model->get_by_username($this->input->post('username'))->row_array();
				$user_sess = array(
								'user_id' => $data_user['user_id'],
								'user_name' => $data_user['user_name'],
								'user_fullname' => $data_user['user_fullname'],
								'user_type_id' => $data_user['user_type_id'],
								'logged_in' => true
							);
				$this->session->set_userdata($user_sess);

				redirect('dashboard');
			}else{
				$this->session->set_flashdata('login_failed','<div class="alert alert-danger">Username or password is not registered.</div>');
				redirect('user/login');
			}
		}
	}

	public function logout(){
		$this->loginstatus->check_login();
		$this->session->sess_destroy();
		redirect('user/login');
	}

	public function signup(){
		$this->form_validation->set_rules('user_name','User Name','required|alpha_dash');
		$this->form_validation->set_rules('user_password','Password','required|min_length[6]');
		$this->form_validation->set_rules('user_fullname','Full Name','required');
		/*$this->form_validation->set_rules('user_type_id','User Type','required|numeric');*/
		$this->form_validation->set_rules('user_email','Email','required|email');
		$this->form_validation->set_rules('user_phone','Phone','required');
		if($this->form_validation->run()==FALSE){
			$data = array();

			$cat = $this->user_type_model->get_all('all')->result_array();
			$data['user_type_id'] = '<select name="user_type_id" id="user_type_id" class="form-control" required="required">';
			foreach ($cat as $c) {
				$data['user_type_id'] .= '<option value="'.$c['user_type_id'].'">'.$c['user_type_name'].'</option>';
			}
			$data['user_type_id'] .= '</select>';
			$this->load->view('user/signup_view',$data);
		}else{
			if($this->check_username_availabilities($this->input->post('user_name'))==false){
				$this->session->set_flashdata('message_alert','<div class="alert alert-warning">Username exists. Please replace by another username.</div>');
				redirect('user/signup');
			}

			if($this->check_email_availabilities($this->input->post('user_email'))==false){
				$this->session->set_flashdata('message_alert','<div class="alert alert-warning">Email exists. Please replace by another email.</div>');
				redirect('user/signup');
			}

			$data_user = array(
							'user_type_id' => '4',
							'user_name' => $this->input->post('user_name'),
							'user_password' => sha1($this->input->post('user_password')),
							'user_fullname' => $this->input->post('user_fullname'),
							'user_address' => $this->input->post('user_address'),
							'user_email' => $this->input->post('user_email'),
							'user_phone' => $this->input->post('user_phone'),
							'user_active' => '1',
							'created_user' => $this->session->userdata('user_id'),
							'created_time' => date('Y-m-d H:i:s')
						);
			$this->user_model->save($data_user);

			$this->session->set_flashdata('message_alert','<div class="alert alert-success">User has been registered. Please login to continue.</div>');

			redirect('user/login');
		}
	}

	public function change_pwd(){
		$this->loginstatus->check_login();
		$this->form_validation->set_rules('new_password','Password baru','required|min_length[6]');
		$this->form_validation->set_rules('confirm_password','Password konfirmasi','required');
		$this->form_validation->set_rules('old_password','Password lama','required');

		if($this->form_validation->run()!=false){
			if($this->input->post('new_password')==$this->input->post('confirm_password')){
				if($this->user_model->change_password()==1){
					$alert = '<div class="alert alert-success alert-dismissible" role="alert">
							  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							  Password has been changed.
							</div>';
				}else{
					$alert = '<div class="alert alert-danger alert-dismissible" role="alert">
							  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							  Current Password is incorrect!
							</div>';
				}
			}else{
				$alert = '<div class="alert alert-danger alert-dismissible" role="alert">
							  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							  Confirmation Password must be correct!
							</div>';
			}
			$this->session->set_flashdata('change_password_callback',$alert);
			redirect('user/change_pwd');
		}else{
			$this->template->display('user/change_pwd');
		}
	}

	public function check_username_availabilities($username){
		$count = $this->user_model->get_by_username($username)->num_rows();

		if($count > 0){
			$status = false;
		}else{
			$status = true;
		}

		return $status;
	}

	public function check_email_availabilities($email){
		$count = $this->user_model->get_by_email($email)->num_rows();

		if($count > 0){
			$status = false;
		}else{
			$status = true;
		}

		return $status;
	}
}