<?php

class Property_category extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->loginstatus->check_login();
		if($this->loginstatus->get_role()!='administrator'){
			redirect('dashboard');
		}
		$this->load->model('property_category_model');
	}

	public function index(){
		redirect('property_category/listdata');
	}

	public function listdata($start=0,$perpage=10){
		$data = array();

		$count = $this->property_category_model->get_all(false)->num_rows();
		$data['property_category'] = $this->property_category_model->get_all(true,$start,$perpage)->result_array();

		$this->load->library('pagination');
		$config['base_url'] = base_url().'property_category/listdata/';
		$config['total_rows'] = $count;
		$config['per_page'] = $perpage;

		$this->pagination->initialize($config);

		$data['paging'] = $this->pagination->create_links();
		$data['number'] = $start + 1;

		$this->template->display('property_category/listdata_view',$data);
	}

	public function add(){
		$this->form_validation->set_rules('property_category_code','Code','required');
		$this->form_validation->set_rules('property_category_name','Category Name','required');

		if($this->form_validation->run()==FALSE){
			$this->template->display('property_category/add_view');
		}else{
			if($this->check_code($this->input->post('property_category_code'))==false){
				$this->session->set_flashdata('message_alert','<div class="alert alert-warning">Code is exists. Please change to another one.</div>');
				redirect('property_category/add');
			}

			$data_property_category = array(
									'property_category_code' => $this->input->post('property_category_code'),
									'property_category_name' => $this->input->post('property_category_name'),
									'property_category_active' => '1',
									'created_user' => $this->session->userdata('user_id'),
									'created_time' => date('Y-m-d H:i:s')
				);
			$this->property_category_model->save($data_property_category);

			$this->session->set_flashdata('message_alert','<div class="alert alert-success">Data has been saved.</div>');

			redirect('property_category/listdata');
		}
	}

	public function edit($id){
		if($id){
			$this->form_validation->set_rules('property_category_name','Category Name','required');

			if($this->form_validation->run()==FALSE){
				$data = array();

				$count = $this->property_category_model->get_by_id($id)->num_rows();
				if($count > 0){
					$data['property_category'] = $this->property_category_model->get_by_id($id)->row_array();

					$this->template->display('property_category/edit_view',$data);
				}else{
					redirect('property_category/listdata');
				}
			}else{
				$data_property_category = array(
										'property_category_name' => $this->input->post('property_category_name'),
										'updated_user' => $this->session->userdata('user_id'),
										'updated_time' => date('Y-m-d H:i:s')
					);
				$this->property_category_model->update($id,$data_property_category);

				$this->session->set_flashdata('message_alert','<div class="alert alert-success">Data has been updated.</div>');

				redirect('property_category/listdata');
			}
		}else{
			redirect('property_category/listdata');
		}
	}

	public function delete($id){
		if($id){
			$count = $this->property_category_model->get_by_id($id)->num_rows();
			if($count > 0){
				$data_property_category = array(
									'property_category_active' => '0',
									'updated_user' => $this->session->userdata('user_id'),
									'updated_time' => date('Y-m-d H:i:s')
				);
				$this->property_category_model->update($id,$data_property_category);
				$this->session->set_flashdata('message_alert','<div class="alert alert-success">Data has been deleted.</div>');

				redirect('property_category/listdata');
			}else{
				$this->session->set_flashdata('message_alert','<div class="alert alert-danger">The ID you\'ve choosen not registered.</div>');
				redirect('property_category/listdata');
			}
		}else{
			redirect('property_category/listdata');
		}
	}

	public function check_code($property_category_code){
		$count = $this->property_category_model->get_by_code($property_category_code)->num_rows();

		if($count > 0){
			return false; //code sudah dipakai
		}else{
			return true; //code tersedia
		}
	}
}