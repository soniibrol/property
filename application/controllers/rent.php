<?php

class Rent extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->loginstatus->check_login();
		$this->load->library('tanggal');
		$this->load->library('uang');
		$this->load->model('property_model');
		$this->load->model('property_category_model');
		$this->load->model('rent_model');
	}

	public function index(){
		if($this->loginstatus->get_role()=='operator'){
			redirect('rent/confirmation');
		}

		if($this->loginstatus->get_role()=='member'){
			redirect('rent/history');
		}
	}

	public function gallery($start=0,$perpage=8){
		$data = array();

		if($this->loginstatus->get_role()=='operator' || $this->loginstatus->get_role()=='manager'){
			redirect('dashboard');
		}

		$search = '';
		if($_POST){
			$search = $this->input->post('search_field');
		}

		$count = $this->property_model->get_all(false)->num_rows();
		$data['property'] = $this->property_model->get_all(true,$start,$perpage,$search)->result_array();

		$this->load->library('pagination');
		$config['base_url'] = base_url().'rent/gallery/';
		$config['total_rows'] = $count;
		$config['per_page'] = $perpage;

		$this->pagination->initialize($config);

		$data['paging'] = $this->pagination->create_links();
		$data['number'] = $start + 1;

		$this->template->display('rent/gallery_view',$data);
	}

	public function add($property_id){
		if($this->loginstatus->get_role()=='operator' || $this->loginstatus->get_role()=='manager'){
			redirect('dashboard');
		}

		//check status peminjaman properti
		/*if(!$this->check_status_properti($property_id)){
			$this->session->set_flashdata('message_alert','<div class="alert alert-warning">Properti sedang dalam peminjaman.</div>');
			redirect('rent/gallery');
		}*/

		$this->form_validation->set_rules('rent_property_id','Properti','required|numeric');
		$this->form_validation->set_rules('rent_date','Tanggal Peminjaman','required');
		$this->form_validation->set_rules('rent_days','Lama Peminjaman','required');
		$this->form_validation->set_rules('rent_type','Tipe Peminjaman','required|numeric');

		if($this->form_validation->run()==false){
			$data = array();

			$pro = $this->property_model->get_by_id($property_id)->row_array();
			$data['rent_property_name'] = '<input type="text" name="rent_property_name" id="rent_property_name" class="form-control" required value="'.$pro['property_code'].' - '.$pro['property_name'].'" readonly>';
			$data['rent_property_price'] = '<input type="text" name="rent_property_price" id="rent_property_price" class="form-control" required value="'.$pro['property_rent_price'].'" readonly>';
			$data['rent_property_id'] = '<input type="hidden" name="rent_property_id" id="rent_property_id" required value="'.$pro['property_id'].'">';

			$data['property'] = $this->rent_model->get_by_available_property($property_id)->result_array();
			$data['property_name'] = $pro['property_name'];

			$this->template->display('rent/add_view',$data);
		}else{
			$today = strtotime(date("Y-m-d"));
			$rentDate = strtotime($this->tanggal->tanggal_simpan_db($this->input->post('rent_date')));


			if($rentDate<$today){
				$this->session->set_flashdata('message_alert','<div class="alert alert-warning">Anda tidak bisa melakukan peminjaman pada tanggal yg sudah berlalu.</div>');
				redirect('rent/history');
			}

			//ngecheck apakah peminjaman pada waktu tersebut bisa dilakukan atau tidak
			$cek_pro = $this->rent_model->get_by_available_property($property_id)->result_array();
			foreach($cek_pro as $cp){
				$rentDate = strtotime($this->tanggal->tanggal_simpan_db($this->input->post('rent_date')));
				$rentDateReturn = strtotime($this->tanggal->tanggal_simpan_db($this->input->post('rent_date_return')));

				$start = strtotime($cp['rent_date']);
				$end = strtotime($cp['rent_date_return']);

				if(($rentDate >= $start) && ($rentDate <= $end)){
					$this->session->set_flashdata('message_alert','<div class="alert alert-warning">Anda tidak bisa melakukan peminjaman pada tanggal yg Anda inputkan karena properti tersebut sudah dalam peminjaman oleh user lain.</div>');
					redirect('rent/add/'.$property_id);
				}
			}

			//pertama cek dulu tipe peminjaman, 1=internal atau 2=eksternal
			if($this->input->post('rent_type')=='1'){
				//jika tipe internal, harus upload dokumen
				/** Uploading Data **/
				$config['upload_path'] = './uploads/rent/';
				$config['allowed_types'] ='gif|jpg|png|pdf|doc|docx';
				$config['max_size'] = '800';
				$config['max_width'] = '2048';
				$config['max_width'] = '2048';
				$config['encrypt_name'] = true;

				$this->load->library('upload',$config);

				if(!$this->upload->do_upload('rent_upload')){
					$data = array();

					$pro = $this->property_model->get_by_id($property_id)->row_array();
					$data['rent_property_name'] = '<input type="text" name="rent_property_name" id="rent_property_name" class="form-control" required value="'.$pro['property_code'].' - '.$pro['property_name'].'" readonly>';
					$data['rent_property_price'] = '<input type="text" name="rent_property_price" id="rent_property_price" class="form-control" required value="'.$pro['property_rent_price'].'" readonly>';
					$data['rent_property_id'] = '<input type="hidden" name="rent_property_id" id="rent_property_id" required value="'.$pro['property_id'].'">';


					$data['error_upload'] = $this->upload->display_errors();
					$this->template->display('rent/add_view',$data);
				}else{
					$images = $this->upload->data();

					//generate code
					$code = $this->generate_code();

					$data_rent = array(
									'rent_param' => $code['param_no'],
									'rent_no' => $code['code'],
									'rent_user_id' => $this->session->userdata('user_id'),
									'rent_property_id' => $property_id,
									'rent_date' => $this->tanggal->tanggal_simpan_db($this->input->post('rent_date')),
									'rent_date_return' => $this->generate_date_return($this->input->post('rent_date'),$this->input->post('rent_days')),
									'rent_days' => $this->input->post('rent_days'),
									'rent_description' => $this->input->post('rent_desc'),
									'rent_type' => $this->input->post('rent_type'),
									'rent_upload' => $images['file_name'],
									'rent_price' => $this->input->post('rent_price'),
									'rent_status' => '0',
									'rent_active' => '1',
									'created_user' => $this->session->userdata('user_id'),
									'created_time' => date('Y-m-d H:i:s')
								);
					$this->rent_model->save($data_rent);

					//change property status to unavailable
					/*$data_property = array('property_status'=>'0','updated_time'=>date('Y-m-d H:i:s'),'updated_user'=>$this->session->userdata('user_id'));
					$this->property_model->update($property_id,$data_property);*/

					$this->session->set_flashdata('message_alert','<div class="alert alert-success">Peminjaman telah disimpan.<br/></div>');

					redirect('rent/history');
				}
			}else{
				//jika tipe eksternal, tidak perlu upload
				//generate code
				$code = $this->generate_code();

				$data_rent = array(
								'rent_param' => $code['param_no'],
								'rent_no' => $code['code'],
								'rent_user_id' => $this->session->userdata('user_id'),
								'rent_property_id' => $property_id,
								'rent_date' => $this->tanggal->tanggal_simpan_db($this->input->post('rent_date')),
								'rent_date_return' => $this->generate_date_return($this->input->post('rent_date'),$this->input->post('rent_days')),
								'rent_days' => $this->input->post('rent_days'),
								'rent_description' => $this->input->post('rent_desc'),
								'rent_type' => $this->input->post('rent_type'),
								'rent_price' => $this->input->post('rent_price'),
								'rent_status' => '0',
								'rent_active' => '1',
								'created_user' => $this->session->userdata('user_id'),
								'created_time' => date('Y-m-d H:i:s')
							);
				$this->rent_model->save($data_rent);

				//change property status to unavailable
				/*$data_property = array('property_status'=>'0','updated_time'=>date('Y-m-d H:i:s'),'updated_user'=>$this->session->userdata('user_id'));
				$this->property_model->update($property_id,$data_property);*/

				$msg = 'Peminjaman telah disimpan. <br/> No rekening transfer akan dikirimkan ke email Anda. <br/>Harap lakukan pembayaran sehari sebelum tanggal peminjaman, dan upload bukti transfer Anda.';
				$msg .= 'Peminjaman dianggap batal apabila bukti transfer belum diupload sampai 1 hari sebelum tanggal peminjaman.<br/>';
				$msg .= 'Terima kasih.';

				$this->session->set_flashdata('message_alert','<div class="alert alert-success">'.$msg.'</div>');

				redirect('rent/history');
			}
		}
	}

	public function edit_rent($rent_id){
		if($this->loginstatus->get_role()=='member' || $this->loginstatus->get_role()=='manager'){
			redirect('dashboard');
		}

		if($rent_id){
			$this->form_validation->set_rules('rent_property_id','Properti','required|numeric');
			$this->form_validation->set_rules('rent_date','Tanggal Peminjaman','required');
			$this->form_validation->set_rules('rent_days','Lama Peminjaman','required');

			if($this->form_validation->run()==false){
				$data = array();

				$rent = $this->rent_model->get_by_id($rent_id)->row_array();
				$pro = $this->property_model->get_by_id($rent['rent_property_id'])->row_array();

				if($rent['rent_type']=='2'){
					redirect('rent/confirmation');
				}

				$data['rent_property_name'] = '<input type="text" name="rent_property_name" id="rent_property_name" class="form-control" required value="'.$pro['property_code'].' - '.$pro['property_name'].'" readonly>';
				$data['rent_property_price'] = '<input type="text" name="rent_property_price" id="rent_property_price" class="form-control" required value="'.$pro['property_rent_price'].'" readonly>';
				$data['rent_property_id'] = '<input type="hidden" name="rent_property_id" id="rent_property_id" required value="'.$pro['property_id'].'">';

				$data['rent'] = $this->rent_model->get_by_id($rent_id)->row_array();

				$data['property'] = $this->rent_model->get_by_available_property($rent['rent_property_id'])->result_array();

				$this->template->display('rent/edit_rent_view',$data);
			}else{
				$today = strtotime(date("Y-m-d"));
				$rentDate = strtotime($this->tanggal->tanggal_simpan_db($this->input->post('rent_date')));


				if($rentDate<$today){
					$this->session->set_flashdata('message_alert','<div class="alert alert-success">Anda tidak bisa melakukan peminjaman pada tanggal yg sudah berlalu.</div>');
					redirect('rent/confirmation');
				}

				//ngecheck apakah peminjaman pada waktu tersebut bisa dilakukan atau tidak
				$rent = $this->rent_model->get_by_id($rent_id)->row_array();
				$pro = $this->property_model->get_by_id($rent['rent_property_id'])->row_array();

				$cek_pro = $this->rent_model->get_by_available_property($rent['rent_property_id'])->result_array();
				foreach($cek_pro as $cp){
					$rentDate = strtotime($this->tanggal->tanggal_simpan_db($this->input->post('rent_date')));
					$rentDateReturn = strtotime($this->tanggal->tanggal_simpan_db($this->input->post('rent_date_return')));

					$start = strtotime($cp['rent_date']);
					$end = strtotime($cp['rent_date_return']);

					//pertama dicek dulu apakah data looping merupakan data peminjaman dengan id yg sedang diedit atau tidak
					//jika ya, maka pengecekan tanggal peminjaman DIABAIKAN
					//jika tidak, pengecekan DILAKUKAN
					if($cp['rent_id'] != $rent_id){
						if(($rentDate >= $start) && ($rentDate <= $end)){
							$this->session->set_flashdata('message_alert','<div class="alert alert-warning">Anda tidak bisa mengedit peminjaman pada tanggal yg Anda inputkan karena properti tersebut sudah dalam peminjaman oleh user lain.</div>');
							redirect('rent/edit_rent/'.$rent_id);
						}
					}
				}

				//jika tipe internal, harus upload dokumen
				/** Uploading Data **/
				$config['upload_path'] = './uploads/rent/';
				$config['allowed_types'] ='gif|jpg|png|pdf|doc|docx';
				$config['max_size'] = '800';
				$config['max_width'] = '2048';
				$config['max_width'] = '2048';
				$config['encrypt_name'] = true;

				$this->load->library('upload',$config);

				if(!$this->upload->do_upload('rent_upload')){
					$data = array();

					$rent = $this->rent_model->get_by_id($rent_id)->row_array();
					$pro = $this->property_model->get_by_id($rent['rent_property_id'])->row_array();

					if($rent['rent_type']=='2'){
						redirect('rent/confirmation');
					}

					$data['rent_property_name'] = '<input type="text" name="rent_property_name" id="rent_property_name" class="form-control" required value="'.$pro['property_code'].' - '.$pro['property_name'].'" readonly>';
					$data['rent_property_price'] = '<input type="text" name="rent_property_price" id="rent_property_price" class="form-control" required value="'.$pro['property_rent_price'].'" readonly>';
					$data['rent_property_id'] = '<input type="hidden" name="rent_property_id" id="rent_property_id" required value="'.$pro['property_id'].'">';

					$data['rent'] = $this->rent_model->get_by_id($rent_id)->row_array();

					$data['error_upload'] = $this->upload->display_errors();
					$this->template->display('rent/edit_rent_view',$data);
				}else{
					$images = $this->upload->data();

					//generate code
					$code = $this->generate_code();

					$data_rent = array(
									'rent_date' => $this->tanggal->tanggal_simpan_db($this->input->post('rent_date')),
									'rent_date_return' => $this->generate_date_return($this->input->post('rent_date'),$this->input->post('rent_days')),
									'rent_days' => $this->input->post('rent_days'),
									'rent_description' => $this->input->post('rent_desc'),
									'rent_upload' => $images['file_name'],
									'rent_status' => '1',
									'updated_user' => $this->session->userdata('user_id'),
									'updated_time' => date('Y-m-d H:i:s')
								);
					$this->rent_model->update($rent_id,$data_rent);

					/*//change property status to unavailable
					$data_property = array('property_status'=>'0','updated_time'=>date('Y-m-d H:i:s'),'updated_user'=>$this->session->userdata('user_id'));
					$this->property_model->update($property_id,$data_property);*/

					$this->session->set_flashdata('message_alert','<div class="alert alert-success">Data has been updated.</div>');

					redirect('rent/confirmation');
				}
			}
		}else{
			redirect('rent/confirmation');
		}
	}

	public function upload_bukti($rent_id){
		if($rent_id){
			$count = $this->rent_model->get_by_id($rent_id)->num_rows();
			if($count > 0){
				$tmp = $this->rent_model->get_by_id($rent_id)->row_array();

				if($tmp['rent_type']=='1'){
					$this->session->set_flashdata('message_alert','<div class="alert alert-success">Upload bukti pembarayan hanya untuk pembayaran eksternal.</div>');
					redirect('rent/history');
				}

				$this->form_validation->set_rules('rent_id','Rent ID','required|numeric');

				if($this->form_validation->run()==false){
					$data = array();

					$data['rent'] = $this->rent_model->get_by_id($rent_id)->row_array();

					$this->template->display('rent/upload_bukti_view',$data);
				}else{
					//upload bukti pembayaran
					/** Uploading Data **/
					$config['upload_path'] = './uploads/rent/';
					$config['allowed_types'] ='gif|jpg|png|pdf|doc|docx';
					$config['max_size'] = '800';
					$config['max_width'] = '2048';
					$config['max_width'] = '2048';
					$config['encrypt_name'] = true;

					$this->load->library('upload',$config);

					if(!$this->upload->do_upload('rent_upload')){
						$data = array();

						$data['rent'] = $this->rent_model->get_by_id($rent_id)->row_array();

						if($data['rent']['rent_type']=='1'){
							redirect('rent/history');
						}

						$data['error_upload'] = $this->upload->display_errors();
						$this->template->display('rent/upload_bukti_view',$data);
					}else{
						$images = $this->upload->data();

						//generate code
						$code = $this->generate_code();

						$data_rent = array(
										'rent_upload' => $images['file_name'],
										'updated_user' => $this->session->userdata('user_id'),
										'updated_time' => date('Y-m-d H:i:s')
									);
						$this->rent_model->update($rent_id,$data_rent);

						/*//change property status to unavailable
						$data_property = array('property_status'=>'0','updated_time'=>date('Y-m-d H:i:s'),'updated_user'=>$this->session->userdata('user_id'));
						$this->property_model->update($property_id,$data_property);*/

						$this->session->set_flashdata('message_alert','<div class="alert alert-success">Bukti pembayaran telah diupload.</div>');

						redirect('rent/history');
					}	
				}
			}else{
				redirect('rent/history');	
			}
		}else{
			redirect('rent/history');
		}
	}

	public function history($start=0,$perpage=10){
		$data = array();

		if($this->loginstatus->get_role()=='operator' || $this->loginstatus->get_role()=='manager' || $this->loginstatus->get_role()=='administrator'){
			redirect('dashboard');
		}

		$data = array();

		$count = $this->rent_model->get_all_history(false)->num_rows();
		$data['rent'] = $this->rent_model->get_all_history(true,$start,$perpage)->result_array();

		$this->load->library('pagination');
		$config['base_url'] = base_url().'rent/history/';
		$config['total_rows'] = $count;
		$config['per_page'] = $perpage;

		$this->pagination->initialize($config);

		$data['paging'] = $this->pagination->create_links();
		$data['number'] = $start + 1;

		$this->template->display('rent/history_view',$data);
	}

	public function confirmation($start=0,$perpage=10){
		$data = array();

		if($this->loginstatus->get_role()=='member' || $this->loginstatus->get_role()=='manager'){
			redirect('dashboard');
		}

		$data = array();

		$search = '';
		if($_POST){
			$search = $this->input->post('search_field');
		}

		$count = $this->rent_model->get_all_confirmation(false)->num_rows();
		$data['rent'] = $this->rent_model->get_all_confirmation(true,$start,$perpage,$search)->result_array();

		$this->load->library('pagination');
		$config['base_url'] = base_url().'rent/confirmation/';
		$config['total_rows'] = $count;
		$config['per_page'] = $perpage;

		$this->pagination->initialize($config);

		$data['paging'] = $this->pagination->create_links();
		$data['number'] = $start + 1;

		$this->template->display('rent/confirmation_view',$data);
	}

	public function return_confirmation($start=0,$perpage=10){
		$data = array();

		if($this->loginstatus->get_role()=='member' || $this->loginstatus->get_role()=='manager'){
			redirect('dashboard');
		}

		$data = array();

		$search = '';
		if($_POST){
			$search = $this->input->post('search_field');
		}

		$count = $this->rent_model->get_all_return_confirmation(false)->num_rows();
		$data['rent'] = $this->rent_model->get_all_return_confirmation(true,$start,$perpage,$search)->result_array();

		$this->load->library('pagination');
		$config['base_url'] = base_url().'rent/return_confirmation/';
		$config['total_rows'] = $count;
		$config['per_page'] = $perpage;

		$this->pagination->initialize($config);

		$data['paging'] = $this->pagination->create_links();
		$data['number'] = $start + 1;

		$this->template->display('rent/return_confirmation_view',$data);
	}

	public function confirm_rent($rent_id){
		if($this->loginstatus->get_role()=='member' || $this->loginstatus->get_role()=='manager'){
			redirect('dashboard');
		}

		if($rent_id){
			$chk = $this->rent_model->get_by_id($rent_id)->num_rows();
			if($chk > 0){
				$tmp = $this->rent_model->get_by_id($rent_id)->row_array();
				if($tmp['rent_status']=='0'){
					//if data valid, masuk ke bagian ini
					$this->form_validation->set_rules('rent_status','Status','required');
					if($this->form_validation->run()==false){
						$data = array();

						$data['rent'] = $this->rent_model->get_by_id($rent_id)->row_array();

						$this->template->display('rent/confirm_rent_view',$data);
					}else{
						$data_rent = array(
										'rent_status' => '1',
										'rent_user_approved' => $this->session->userdata('user_id'),
										'updated_user' => $this->session->userdata('user_id'),
										'updated_time' => date('Y-m-d H:i:s')
							);

						$this->rent_model->update($rent_id,$data_rent);

						$this->session->set_flashdata('message_alert','<div class="alert alert-success">Konfirmasi Peminjaman Berhasil.</div>');
						redirect('rent/confirmation');
					}
				}else{
					$this->session->set_flashdata('message_alert','<div class="alert alert-danger">The ID you\'ve choosen not allow to be confirmed.</div>');
					redirect('rent/confirmation');
				}
			}else{
				$this->session->set_flashdata('message_alert','<div class="alert alert-danger">The ID you\'ve choosen not registered.</div>');
				redirect('rent/confirmation');
			}
		}else{
			redirect('rent/confirmation');
		}
	}

	public function return_rent($rent_id){
		if($this->loginstatus->get_role()=='operator' || $this->loginstatus->get_role()=='manager'){
			redirect('dashboard');
		}

		if($rent_id){
			$chk = $this->rent_model->get_by_id($rent_id)->num_rows();
			if($chk > 0){
				$tmp = $this->rent_model->get_by_id($rent_id)->row_array();
				if($tmp['rent_status']=='1'){
					//if data valid, masuk ke bagian ini
					$this->form_validation->set_rules('rent_status','Status','required');
					if($this->form_validation->run()==false){
						$data = array();

						$denda = 0;
						$drent = $this->rent_model->get_by_id($rent_id)->row_array();

						if($drent['rent_type']=='2'){
							$tanggal_kembali = strtotime($this->tanggal->tanggal_dmy($drent['rent_date_return']));
							$hari_ini = strtotime(date('Y-m-d'));

							$drent = $this->rent_model->get_by_id($rent_id)->row_array();
							$dpro = $this->property_model->get_by_id($drent['rent_property_id'])->row_array();

							if($hari_ini > $tanggal_kembali){
								$selisih = $this->tanggal->get_selisih($this->tanggal->tanggal_dmy($drent['rent_date_return']),date('Y-m-d'));

								$denda = $selisih * $dpro['property_rent_price'];

							}else{
								$denda = 0;
							}
						}

						$data['denda'] = $denda;
						$data['rent'] = $this->rent_model->get_by_id($rent_id)->row_array();

						$this->template->display('rent/return_rent_view',$data);
					}else{
						$denda = 0;
						$rent_penalty_paid = '1';
						$msg_denda = '';
						$drent = $this->rent_model->get_by_id($rent_id)->row_array();

						if($drent['rent_type']=='2'){
							$tanggal_kembali = strtotime($this->tanggal->tanggal_dmy($drent['rent_date_return']));
							$hari_ini = strtotime(date('Y-m-d'));

							$drent = $this->rent_model->get_by_id($rent_id)->row_array();
							$dpro = $this->property_model->get_by_id($drent['rent_property_id'])->row_array();

							if($hari_ini > $tanggal_kembali){
								$selisih = $this->tanggal->get_selisih($this->tanggal->tanggal_dmy($drent['rent_date_return']),date('Y-m-d'));

								$denda = $selisih * $dpro['property_rent_price'];
								$rent_penalty_paid = '0';

								$msg_denda = 'Harap bayar denda atas keterlambatan pengembalian Anda';
							}
						}

						$data_rent = array(
										'rent_penalty' => $denda,
										'rent_penalty_paid' => $rent_penalty_paid,
										'rent_status' => '2',
										'updated_user' => $this->session->userdata('user_id'),
										'updated_time' => date('Y-m-d H:i:s')
							);

						$this->rent_model->update($rent_id,$data_rent);

						$this->session->set_flashdata('message_alert','<div class="alert alert-danger">Pengembalian Peminjaman akan segera dikonfirmasi oleh Operator. '.$msg_denda.'</div>');
						redirect('rent/history');
					}
				}else{
					$this->session->set_flashdata('message_alert','<div class="alert alert-danger">The ID you\'ve choosen not allow to be confirmed.</div>');
					redirect('rent/history');
				}
			}else{
				$this->session->set_flashdata('message_alert','<div class="alert alert-danger">The ID you\'ve choosen not registered.</div>');
				redirect('rent/history');
			}
		}else{
			redirect('rent/history');
		}
	}

	public function confirm_return($rent_id){
		if($this->loginstatus->get_role()=='member' || $this->loginstatus->get_role()=='manager'){
			redirect('dashboard');
		}

		if($rent_id){
			$chk = $this->rent_model->get_by_id($rent_id)->num_rows();
			if($chk > 0){
				$tmp = $this->rent_model->get_by_id($rent_id)->row_array();
				if($tmp['rent_status']=='2'){
					//if data valid, masuk ke bagian ini
					$this->form_validation->set_rules('rent_status','Status','required');
					if($this->form_validation->run()==false){
						$data = array();

						$data['rent'] = $this->rent_model->get_by_id($rent_id)->row_array();

						$this->template->display('rent/confirm_return_view',$data);
					}else{
						$data_rent = array(
										'rent_status' => '3',
										'rent_user_approved' => $this->session->userdata('user_id'),
										'updated_user' => $this->session->userdata('user_id'),
										'updated_time' => date('Y-m-d H:i:s')
							);

						$this->rent_model->update($rent_id,$data_rent);

						$tmp = $this->rent_model->get_by_id($rent_id)->row_array();

						//change property status to available
						/*$data_property = array('property_status'=>'1','updated_time'=>date('Y-m-d H:i:s'),'updated_user'=>$this->session->userdata('user_id'));
						$this->property_model->update($tmp['rent_property_id'],$data_property);*/

						$this->session->set_flashdata('message_alert','<div class="alert alert-success">Konfirmasi Pengembalian Berhasil.</div>');
						redirect('rent/confirmation');
					}
				}else{
					$this->session->set_flashdata('message_alert','<div class="alert alert-danger">The ID you\'ve choosen not allow to be confirmed.</div>');
					redirect('rent/confirmation');
				}
			}else{
				$this->session->set_flashdata('message_alert','<div class="alert alert-danger">The ID you\'ve choosen not registered.</div>');
				redirect('rent/confirmation');
			}
		}else{
			redirect('rent/confirmation');
		}
	}

	public function pay_penalty($rent_id){
		if($this->loginstatus->get_role()=='manager' || $this->loginstatus->get_role()=='operator'){
			redirect('dashboard');
		}

		if($rent_id){
			$chk = $this->rent_model->get_by_id($rent_id)->num_rows();
			if($chk > 0){
				$tmp = $this->rent_model->get_by_id($rent_id)->row_array();
				if($tmp['rent_penalty_paid']=='0'){
					$this->form_validation->set_rules('rent_id','Rent ID','required|numeric');

					if($this->form_validation->run()==false){
						$data = array();

						$data['rent'] = $this->rent_model->get_by_id($rent_id)->row_array();

						$this->template->display('rent/pay_penalty_view',$data);
					}else{
						//upload bukti pembayaran denda
						/** Uploading Data **/
						$config['upload_path'] = './uploads/rent/';
						$config['allowed_types'] ='gif|jpg|png|pdf|doc|docx';
						$config['max_size'] = '800';
						$config['max_width'] = '2048';
						$config['max_width'] = '2048';
						$config['encrypt_name'] = true;

						$this->load->library('upload',$config);

						if(!$this->upload->do_upload('rent_penalty_upload')){
							$data = array();

							$data['rent'] = $this->rent_model->get_by_id($rent_id)->row_array();

							$data['error_upload'] = $this->upload->display_errors();
							$this->template->display('rent/pay_penalty_view',$data);
						}else{
							$images = $this->upload->data();

							//generate code
							$code = $this->generate_code();

							$data_rent = array(
											'rent_penalty_upload' => $images['file_name'],
											'updated_user' => $this->session->userdata('user_id'),
											'updated_time' => date('Y-m-d H:i:s')
										);
							$this->rent_model->update($rent_id,$data_rent);

							$this->session->set_flashdata('message_alert','<div class="alert alert-success">Bukti pembayaran denda berhasil diupload.</div>');

							redirect('rent/history');
						}
					}
				}else{
					$this->session->set_flashdata('message_alert','<div class="alert alert-danger">The ID you\'ve choosen not allow to be accessed here.</div>');
					redirect('rent/history');
				}
			}else{
				$this->session->set_flashdata('message_alert','<div class="alert alert-danger">The ID you\'ve choosen not registered.</div>');
				redirect('rent/history');
			}
		}else{
			redirect('rent/history');
		}
	}

	public function confirm_penalty($rent_id){
		if($this->loginstatus->get_role()=='manager' || $this->loginstatus->get_role()=='member'){
			redirect('dashboard');
		}

		if($rent_id){
			$chk = $this->rent_model->get_by_id($rent_id)->num_rows();
			if($chk > 0){
				$tmp = $this->rent_model->get_by_id($rent_id)->row_array();
				if($tmp['rent_penalty_paid']=='0'){
					$this->form_validation->set_rules('rent_id','Rent ID','required|numeric');
					$this->form_validation->set_rules('rent_penalty_paid','Rent Penalty Payment','required|numeric');

					if($this->form_validation->run()==false){
						$data = array();

						$data['rent'] = $this->rent_model->get_by_id($rent_id)->row_array();

						$this->template->display('rent/confirm_penalty_view',$data);
					}else{
						$data_rent = array(
										'rent_penalty_paid' => '1',
										'updated_user' => $this->session->userdata('user_id'),
										'updated_time' => date('Y-m-d H:i:s')
							);
						$this->rent_model->update($rent_id,$data_rent);

						$this->session->set_flashdata('message_alert','<div class="alert alert-success">Pembayaran denda berhasil dikonfirmasi.</div>');

						redirect('rent/confirmation');
					}
				}else{
					$this->session->set_flashdata('message_alert','<div class="alert alert-danger">The ID you\'ve choosen not allow to be accessed here.</div>');
					redirect('rent/confirmation');
				}
			}else{
				$this->session->set_flashdata('message_alert','<div class="alert alert-danger">The ID you\'ve choosen not registered.</div>');
				redirect('rent/confirmation');
			}
		}else{
			redirect('rent/confirmation');
		}
	}

	public function delete($id){
		if($this->loginstatus->get_role()=='manager'){
			redirect('dashboard');
		}

		if($id){
			$count = $this->rent_model->get_by_id($id)->num_rows();
			$tmp = $this->rent_model->get_by_id($id)->row_array();
			if($count > 0){
				$data_rent = array(
									'rent_active' => '0',
									'updated_user' => $this->session->userdata('user_id'),
									'updated_time' => date('Y-m-d H:i:s')
				);
				$this->rent_model->update($id,$data_rent);

				//change property status to available
				/*$data_property = array('property_status'=>'1','updated_time'=>date('Y-m-d H:i:s'),'updated_user'=>$this->session->userdata('user_id'));
				$this->property_model->update($tmp['rent_property_id'],$data_property);*/

				$this->session->set_flashdata('message_alert','<div class="alert alert-success">Data has been deleted.</div>');

				if($this->loginstatus->get_role()=='operator'){
					redirect('rent/confirmation');
				}

				redirect('rent/history');
			}else{
				$this->session->set_flashdata('message_alert','<div class="alert alert-danger">The ID you\'ve choosen not registered.</div>');

				if($this->loginstatus->get_role()=='operator'){
					redirect('rent/confirmation');
				}

				redirect('rent/history');
			}
		}else{
			if($this->loginstatus->get_role()=='operator'){
				redirect('rent/confirmation');
			}

			redirect('rent/history');
		}
	}


	private function generate_code(){
		$type = 'RENT';
		$return = array();

		$return['param_no'] = 1;
		$return['code'] = $type.'000001';

		$count = $this->rent_model->get_last()->num_rows();

		if($count > 0){
			$tmp = $this->rent_model->get_last()->row_array();

			$new_param_no = $tmp['rent_param'] + 1;
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

	private function generate_date_return($date,$add){
		$newDate = date('Y-m-d', strtotime('+'.$add.' days', strtotime($date)));
		return $newDate;
	}

	private function check_status_properti($property_id){
		$tmp = $this->property_model->get_by_id($property_id)->row_array();
		if($tmp['property_status']=='0'){
			return false;
		}else{
			return true;
		}
	}

	public function get_date_return(){
		$date = $this->tanggal->tanggal_simpan_db($_POST['start_date']);
		$add = $_POST['add'];

		$data = array();
		$newDate = date('Y-m-d', strtotime('+'.$add.' days', strtotime($date)));
		$data['newDate'] = $newDate;

		echo json_encode($data);
	}
}