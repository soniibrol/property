<?php

class User_model extends CI_Model{
	private $table_name = 'user';
	private $table_pk = 'user_id';
	private $table_status = 'user_active';
	private $table_uc = 'user_type';
	private $table_uc_pk = 'user_type_id';

	public function __construct(){
		parent::__construct();
	}

	public function get_by_id($user_id){
		$this->db->select('user_id,user_name,user_password,user_fullname,user_email,user_phone,user_address,user_active,user_type_name,user.user_type_id');
		$this->db->from($this->table_name);
		$this->db->join($this->table_uc,$this->table_name.'.user_type_id = '.$this->table_uc.'.'.$this->table_uc_pk,'INNER');
		$this->db->where($this->table_pk,$user_id);
		$this->db->where($this->table_status,'1');
		return $this->db->get();
	}

	public function get_by_type($user_type_id){
		$this->db->select('user_id,user_name,user_password,user_fullname,user_email,user_phone,user_address,user_active,user_type_name');
		$this->db->from($this->table_name);
		$this->db->join($this->table_uc,$this->table_name.'.user_type_id = '.$this->table_uc.'.'.$this->table_uc_pk,'INNER');
		$this->db->where('user.user_type_id',$user_type_id);
		$this->db->where($this->table_status,'1');
		return $this->db->get();	
	}

	public function get_by_username($user_name){
		$this->db->select('user_id,user_name,user_password,user_fullname,user_email,user_phone,user_address,user_active,user_type_name,user.user_type_id');
		$this->db->from($this->table_name);
		$this->db->join($this->table_uc,$this->table_name.'.user_type_id = '.$this->table_uc.'.'.$this->table_uc_pk,'INNER');
		$this->db->where('user_name',$user_name);
		$this->db->where($this->table_status,'1');
		return $this->db->get();	
	}

	public function get_by_email($email){
		$this->db->select('user_id');
		$this->db->from($this->table_name);
		$this->db->join($this->table_uc,$this->table_name.'.user_type_id = '.$this->table_uc.'.'.$this->table_uc_pk,'INNER');
		$this->db->where('user_email',$email);
		//$this->db->where($this->table_status,'1');
		return $this->db->get();
	}

	public function get_all($paging=true,$start=0,$limit=10,$search=''){
		$this->db->select('user_id,user_name,user_password,user_fullname,user_email,user_phone,user_address,user_active,user_type_name');
		$this->db->from($this->table_name);
		$this->db->join($this->table_uc,$this->table_name.'.user_type_id = '.$this->table_uc.'.'.$this->table_uc_pk,'INNER');
		if($paging==true){
			$this->db->limit($limit,$start);
		}
		if($search!=''){
			$this->db->like('user_name',$search);
			$this->db->or_like('user_fullname',$search);
			$this->db->or_like('user_email',$search);
		}
		$this->db->where($this->table_status,'1');
		return $this->db->get();	
	}

	public function save($data_user){
		$this->db->insert($this->table_name,$data_user);
	}

	public function update($user_id,$data_user){
		$this->db->where($this->table_pk,$user_id);
		$this->db->update($this->table_name,$data_user);
	}

	public function delete($user_id){
		$this->db->where($this->table_pk,$user_id);
		$this->db->delete($this->table_name);
	}

	public function check_login(){
		$username = $this->input->post('username');
		$password = sha1($this->input->post('password'));


		//first checking username
		if($this->get_by_username($username)->num_rows() > 0){
			//username exist
			$data_user = $this->get_by_username($username)->row_array();
			if(($data_user['user_password'] == $password) && ($data_user['user_active'] == '1')){
				//login success
				return true;
			}else{
				//login failed, password is not match
				return false;
			}
		}else{
			//login failed, username is not registered
			return false;
		}
	}

	public function change_password(){
		$old = $this->input->post('old_password');
		$new = $this->input->post('new_password');

		$userdata = $this->get_by_id($this->session->userdata('user_id'))->row_array();

		if($userdata['user_password']==sha1($old)){
			//update data
			$data_user = array('user_password' => sha1($new), 'updated_time' => date('Y-m-d H:i:s'), 'updated_user' => $this->session->userdata('user_id'));
			$this->update($this->session->userdata('user_id'),$data_user);

			return 1;
		}else{
			//update failed
			return 0;
		}
	}
}