<?php

class User_type extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->loginstatus->check_login();
		if($this->loginstatus->get_role()!='administrator'){
			redirect('dashboard');
		}
		$this->load->library('template');
		$this->load->model('user_type_model');
	}

	public function index(){
		redirect('user_type/listdata');
	}

	public function listdata($start=0,$perpage=10){
		$data = array();

		$count = $this->user_type_model->get_all(false)->num_rows();
		$data['user_type'] = $this->user_type_model->get_all(true,$start,$perpage)->result_array();

		$this->load->library('pagination');
		$config['base_url'] = base_url().'user_category/listdata/';
		$config['total_rows'] = $count;
		$config['per_page'] = $perpage;

		$this->pagination->initialize($config);

		$data['paging'] = $this->pagination->create_links();
		$data['number'] = $start + 1;

		$this->template->display('user_type/listdata_view',$data);
	}

	public function add(){
		$this->form_validation->set_rules('user_type_name','User Type Name','required');

		if($this->form_validation->run()==FALSE){
			$this->template->display('user_type/add_view');
		}else{
			$data_user_type = array(
									'user_type_name' => $this->input->post('user_type_name'),
									'user_type_active' => '1',
									'created_user' => $this->session->userdata('user_id'),
									'created_time' => date('Y-m-d H:i:s')
				);
			$this->user_type_model->save($data_user_type);

			$this->session->set_flashdata('message_alert','<div class="alert alert-success">Data has been saved.</div>');

			redirect('user_type/listdata');
		}
	}

	public function edit($id){
		if($id){
			$this->form_validation->set_rules('user_type_name','User Type Name','required');

			if($this->form_validation->run()==FALSE){
				$data = array();

				$count = $this->user_type_model->get_by_id($id)->num_rows();
				if($count > 0){
					$data['user_type'] = $this->user_type_model->get_by_id($id)->row_array();

					$this->template->display('user_type/edit_view',$data);
				}else{
					redirect('user_type/listdata');
				}
			}else{
				$data_user_type = array(
										'user_type_name' => $this->input->post('user_type_name'),
										'updated_user' => $this->session->userdata('user_id'),
										'updated_time' => date('Y-m-d H:i:s')
					);
				$this->user_type_model->update($id,$data_user_type);

				$this->session->set_flashdata('message_alert','<div class="alert alert-success">Data has been updated.</div>');

				redirect('user_type/listdata');
			}
		}else{
			redirect('user_type/listdata');
		}
	}

	public function delete($id){
		if($id){
			$count = $this->user_type_model->get_by_id($id)->num_rows();
			if($count > 0){
				$data_user_type = array(
									'user_type_active' => '0',
									'updated_user' => $this->session->userdata('user_id'),
									'updated_time' => date('Y-m-d H:i:s')
				);
				$this->user_type_model->update($id,$data_user_type);
				$this->session->set_flashdata('message_alert','<div class="alert alert-success">Data has been deleted.</div>');

				redirect('user_type/listdata');
			}else{
				$this->session->set_flashdata('message_alert','<div class="alert alert-danger">The ID you\'ve choosen not registered.</div>');
				redirect('user_type/listdata');
			}
		}else{
			redirect('user_type/listdata');
		}
	}
}