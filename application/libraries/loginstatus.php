<?php

class LoginStatus{
	protected $_ci;

	public function __construct(){
		$this->_ci =&get_instance();
	}

	public function check_login(){
		if($this->_ci->session->userdata('logged_in')==false){
			echo 'You don\'t permit to access this page.<br/>';
			echo '<a href="'.base_url().'user/login">Click here to login.</a>';
			redirect('user/login');
			die;
		}
	}

	public function get_role(){
		//1 => administrator
		//2 => manager
		//3 => operator
		//4 => member
		if($this->_ci->session->userdata('user_type_id')=='1'){
			return 'administrator';
		}elseif($this->_ci->session->userdata('user_type_id')=='2'){
			return 'manager';
		}elseif($this->_ci->session->userdata('user_type_id')=='3'){
			return 'operator';
		}elseif($this->_ci->session->userdata('user_type_id')=='4'){
			return 'member';
		}else{
			return 'null';
		}
	}
}