<?php

class User_type_model extends CI_Model{
	private $table_name = 'user_type';
	private $table_pk = 'user_type_id';
	private $table_status = 'user_type_active';

	public function __construct(){
		parent::__construct();
	}

	public function get_all($paging=true,$start=0,$limit=10){
		$this->db->select('user_type_id,user_type_name,user_type_active');
		$this->db->from($this->table_name);
		$this->db->where($this->table_status,'1');
		if($paging==true){
			$this->db->limit($limit,$start);
		}
		return $this->db->get();
	}

	public function get_by_id($user_type_id){
		$this->db->select('user_type_id,user_type_name,user_type_active');
		$this->db->from($this->table_name);
		$this->db->where($this->table_pk,$user_type_id);
		$this->db->where($this->table_status,'1');
		return $this->db->get();
	}

	public function save($data_user_type){
		$this->db->insert($this->table_name,$data_user_type);
	}

	public function update($user_type_id,$data_user_type){
		$this->db->where($this->table_pk,$user_type_id);
		$this->db->update($this->table_name,$data_user_type);
	}

	public function delete($user_type_id){
		$this->db->where($this->table_pk,$user_type_id);
		$this->db->delete($this->table_name);
	}
}