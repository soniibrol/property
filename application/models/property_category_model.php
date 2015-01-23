<?php

class Property_category_model extends CI_Model{
	private $table_name = 'property_category';
	private $table_pk = 'property_category_id';
	private $table_status = 'property_category_active';

	public function __construct(){
		parent::__construct();
	}

	public function get_all($paging=true,$start=0,$limit=10){
		$this->db->select('property_category_id,property_category_code,property_category_name,property_category_active');
		$this->db->from($this->table_name);
		$this->db->where($this->table_status,'1');
		if($paging==true){
			$this->db->limit($limit,$start);
		}
		return $this->db->get();
	}

	public function get_by_id($property_category_id){
		$this->db->select('property_category_id,property_category_code,property_category_name,property_category_active');
		$this->db->from($this->table_name);
		$this->db->where($this->table_pk,$property_category_id);
		$this->db->where($this->table_status,'1');
		return $this->db->get();
	}

	public function get_by_code($property_category_code){
		$this->db->select('property_category_id,property_category_code,property_category_name,property_category_active');
		$this->db->from($this->table_name);
		$this->db->where('property_category_code',$property_category_code);
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