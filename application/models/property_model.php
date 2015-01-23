<?php

class Property_model extends CI_Model{
	private $table_name = 'property';
	private $table_pk = 'property_id';
	private $table_status = 'property_active';

	public function __construct(){
		parent::__construct();
	}

	public function get_all($paging=true,$start=0,$limit=10,$search=''){
		$this->db->select('property_id,property_param_no,property_code,property.property_category_id,property_name,property_rent_price,property_images,property_desc,property_status,property_category_code,property_category_name');
		$this->db->from($this->table_name);
		$this->db->join('property_category','property_category.property_category_id = '.$this->table_name.'.property_category_id','INNER');
		if($paging==true){
			$this->db->limit($limit,$start);
		}
		if($search!=''){
			$this->db->like('property_code',$search);
			$this->db->or_like('property_name',$search);
		}
		$this->db->where($this->table_status,'1');
		return $this->db->get();
	}

	public function get_by_id($property_id){
		$this->db->select('property_id,property_param_no,property_code,property.property_category_id,property_name,property_rent_price,property_images,property_desc,property_status,property_category_code,property_category_name');
		$this->db->from($this->table_name);
		$this->db->join('property_category','property_category.property_category_id = '.$this->table_name.'.property_category_id','INNER');
		$this->db->where($this->table_pk,$property_id);
		$this->db->where($this->table_status,'1');
		return $this->db->get();
	}

	public function get_last($property_category_id){
		$this->db->select('property_id,property_param_no,property_code');
		$this->db->from($this->table_name);
		$this->db->where('property_category_id',$property_category_id);
		$this->db->order_by('property_id','DESC');
		$this->db->limit('1');
		return $this->db->get();
	}

	public function save($data_property){
		$this->db->insert($this->table_name,$data_property);
	}

	public function update($property_id,$data_property){
		$this->db->where($this->table_pk,$property_id);
		$this->db->update($this->table_name,$data_property);
	}

	public function delete($property_id){
		$this->db->where($this->table_pk,$property_id);
		$this->db->delete($this->table_name);
	}
}