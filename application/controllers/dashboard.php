<?php

class Dashboard extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->loginstatus->check_login();
	}

	public function index(){
		$this->template->display('dashboard');
	}
}