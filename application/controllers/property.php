<?php

class Property extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->loginstatus->check_login();
		if($this->loginstatus->get_role()!='administrator'){
			redirect('dashboard');
		}
		$this->load->model('property_category_model');
		$this->load->model('property_model');
		$this->load->library('uang');
	}

	public function index(){
		redirect('property/listdata');
	}

	public function listdata($start=0,$perpage=10){
		$data = array();

		$search = '';
		if($_POST){
			$search = $this->input->post('search_field');
		}

		$count = $this->property_model->get_all(false)->num_rows();
		$data['property'] = $this->property_model->get_all(true,$start,$perpage,$search)->result_array();

		$this->load->library('pagination');
		$config['base_url'] = base_url().'property/listdata/';
		$config['total_rows'] = $count;
		$config['per_page'] = $perpage;

		$this->pagination->initialize($config);

		$data['paging'] = $this->pagination->create_links();
		$data['number'] = $start + 1;

		$this->template->display('property/listdata_view',$data);
	}

	public function add(){
		$this->form_validation->set_rules('property_category_id','Kategori Properti','required|numeric');
		$this->form_validation->set_rules('property_name','Nama Properti','required');
		$this->form_validation->set_rules('property_rent_price','Harga Sewa','required|numeric');
		$this->form_validation->set_rules('property_desc','Deskripsi','required');

		if($this->form_validation->run()==false){
			$data = array();

			$pc = $this->property_category_model->get_all(false)->result_array();
			$data['property_category_id'] = '<select name="property_category_id" id="property_category_id" class="form-control" required>';
			foreach ($pc as $row) {
				$data['property_category_id'] .= '<option value="'.$row['property_category_id'].'">'.$row['property_category_code'].' - '.$row['property_category_name'].'</option>';
			}
			$data['property_category_id'] .= '</select>';

			$this->template->display('property/add_view',$data);
		}else{
			/** Uploading Data **/
			$config['upload_path'] = './uploads/images/';
			$config['allowed_types'] ='gif|jpg|png';
			$config['max_size'] = '800';
			$config['max_width'] = '2048';
			$config['max_width'] = '2048';
			$config['encrypt_name'] = true;

			$this->load->library('upload',$config);

			if(!$this->upload->do_upload('property_images')){
				$data = array();

				$pc = $this->property_category_model->get_all(false)->result_array();
				$data['property_category_id'] = '<select name="property_category_id" id="property_category_id" class="form-control" required>';
				foreach ($pc as $row) {
					$data['property_category_id'] .= '<option value="'.$row['property_category_id'].'">'.$row['property_category_code'].' - '.$row['property_category_name'].'</option>';
				}
				$data['property_category_id'] .= '</select>';

				$data['error_upload'] = $this->upload->display_errors();
				$this->template->display('property/add_view',$data);
			}else{
				$images = $this->upload->data();

				$code = $this->generate_code($this->input->post('property_category_id'));

				$data_property = array(
									'property_param_no' => $code['param_no'],
									'property_code' => $code['code'],
									'property_category_id' => $this->input->post('property_category_id'),
									'property_name' => $this->input->post('property_name'),
									'property_rent_price' => $this->input->post('property_rent_price'),
									'property_images' => $images['file_name'],
									'property_desc' => $this->input->post('property_desc'),
									'property_status' => '1',
									'property_active' => '1',
									'created_user' => $this->session->userdata('user_id'),
									'created_time' => date('Y-m-d H:i:s')
								);
				$this->property_model->save($data_property);

				$this->session->set_flashdata('message_alert','<div class="alert alert-success">Data has been saved.</div>');

				redirect('property/listdata');
			}
		}
	}

	public function edit($property_id){
		if($property_id){
			$this->form_validation->set_rules('property_name','Nama Properti','required');
			$this->form_validation->set_rules('property_rent_price','Harga Sewa','required|numeric');
			$this->form_validation->set_rules('property_desc','Deskripsi','required');

			if($this->form_validation->run()==false){
				$data = array();
				$data['property'] = $this->property_model->get_by_id($property_id)->row_array();

				$pc = $this->property_category_model->get_all(false)->result_array();
				$data['property_category_id'] = '<select name="property_category_id" id="property_category_id" class="form-control" required readonly>';
				foreach ($pc as $row) {
					$selected = '';
					if($row['property_category_id']==$data['property']['property_category_id']){
						$selected = 'selected';
					}
					$data['property_category_id'] .= '<option value="'.$row['property_category_id'].'" '.$selected.'>'.$row['property_category_code'].' - '.$row['property_category_name'].'</option>';
				}
				$data['property_category_id'] .= '</select>';

				$this->template->display('property/edit_view',$data);
			}else{
				$data_property = array(
									'property_name' => $this->input->post('property_name'),
									'property_rent_price' => $this->input->post('property_rent_price'),
									'property_desc' => $this->input->post('property_desc'),
									'updated_user' => $this->session->userdata('user_id'),
									'updated_time' => date('Y-m-d H:i:s')
								);
				$this->property_model->update($property_id,$data_property);

				$this->session->set_flashdata('message_alert','<div class="alert alert-success">Data has been updated.</div>');

				redirect('property/listdata');
			}
		}else{
			redirect('dashboard');
		}
	}

	public function delete($id){
		if($id){
			$count = $this->property_model->get_by_id($id)->num_rows();
			if($count > 0){
				$data_property = array(
									'property_active' => '0',
									'updated_user' => $this->session->userdata('user_id'),
									'updated_time' => date('Y-m-d H:i:s')
				);
				$this->property_model->update($id,$data_property);
				$this->session->set_flashdata('message_alert','<div class="alert alert-success">Data has been deleted.</div>');

				redirect('property/listdata');
			}else{
				$this->session->set_flashdata('message_alert','<div class="alert alert-danger">The ID you\'ve choosen not registered.</div>');
				redirect('property/listdata');
			}
		}else{
			redirect('property/listdata');
		}
	}

	public function change_pic($property_id){
		if($property_id){
			if($_POST){
				/** Uploading Data **/
				$config['upload_path'] = './uploads/images/';
				$config['allowed_types'] ='gif|jpg|png';
				$config['max_size'] = '800';
				$config['max_width'] = '2048';
				$config['max_width'] = '2048';
				$config['encrypt_name'] = true;

				$this->load->library('upload',$config);

				if(!$this->upload->do_upload('property_images')){
					$data = array();
					$data['property'] = $this->property_model->get_by_id($property_id)->row_array();

					$data['error_upload'] = $this->upload->display_errors();
					$this->template->display('property/change_pic_view',$data);
				}else{
					$images = $this->upload->data();

					$data_property = array(
										'property_images' => $images['file_name'],
										'updated_user' => $this->session->userdata('user_id'),
										'updated_time' => date('Y-m-d H:i:s')
									);
					$this->property_model->update($property_id,$data_property);

					$this->session->set_flashdata('message_alert','<div class="alert alert-success">Pictures has been changed.</div>');

					redirect('property/listdata');
				}
			}else{
				$data = array();
				$data['property'] = $this->property_model->get_by_id($property_id)->row_array();
				$this->template->display('property/change_pic_view',$data);
			}
		}else{
			redirect('property/listdata');
		}
	}

	public function generate_code($property_category_id){
		$temporary = $this->property_category_model->get_by_id($property_category_id)->row_array();
		$type = $temporary['property_category_code'];

		$return = array();

		$return['param_no'] = 1;
		$return['code'] = $type.'000001';

		$count = $this->property_model->get_last($property_category_id)->num_rows();

		if($count > 0){
			$tmp = $this->property_model->get_last($property_category_id)->row_array();

			$new_param_no = $tmp['property_param_no'] + 1;
			$return['param_no'] = $new_param_no;
			
			if($new_param_no > 100000){
				$return['code'] = $type.$new_param_no;
			}elseif($new_param_no > 10000){
				$return['code'] = $type.'0'.$new_param_no;
			}elseif($new_param_no > 1000){
				$return['code'] = $type.'00'.$new_param_no;
			}elseif($new_param_no > 100){
				$return['code'] = $type.'000'.$new_param_no;
			}elseif($new_param_no > 10){
				$return['code'] = $type.'0000'.$new_param_no;
			}else{
				$return['code'] = $type.'00000'.$new_param_no;
			}
		}

		return $return;
	}
}