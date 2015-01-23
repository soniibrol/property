<?php

class Rent_model extends CI_Model{
	private $table_name = 'rent';
	private $table_pk = 'rent_id';
	private $table_status = 'rent_active';

	public function __construct(){
		parent::__construct();
	}

	public function get_all($paging=true,$start=0,$limit=10){
		$this->db->select("
						rent_id,
						rent_param,
						rent_no,
						a.rent_user_id,
						a.rent_property_id,
						b.property_code,
						b.property_name,
						c.user_name as 'rent_username',
						c.user_fullname as 'rent_fullname',
						rent_date,
						rent_date_return,
						rent_days,
						rent_description,
						rent_type,
						rent_upload,
						rent_price,
						rent_penalty,
						rent_penalty_paid,
						rent_penalty_upload,
						a.rent_user_approved,
						d.user_name as 'rent_username_approved',
						d.user_fullname as 'rent_fullname_approved',
						rent_status
						");
		$this->db->from($this->table_name.' a');
		$this->db->join('property b','b.property_id = a.rent_property_id','INNER');
		$this->db->join('user c','c.user_id = a.rent_user_id','INNER');
		$this->db->join('user d','d.user_id = a.rent_user_approved','INNER');
		$this->db->where($this->table_status,'1');
		if($paging==true){
			$this->db->limit($limit,$start);
		}
		$this->db->order_by($this->table_pk,'DESC');
		return $this->db->get();
	}

	public function get_by_available_property($property_id){
		$this->db->select("
						rent_id,
						rent_param,
						rent_no,
						a.rent_user_id,
						a.rent_property_id,
						b.property_code,
						b.property_name,
						rent_date,
						rent_date_return,
						rent_days,
						rent_description,
						rent_type,
						rent_upload,
						rent_price,
						rent_penalty,
						rent_penalty_paid,
						rent_penalty_upload,
						a.rent_user_approved,
						rent_status
						");
		$this->db->from($this->table_name.' a');
		$this->db->join('property b','b.property_id = a.rent_property_id','INNER');
		$this->db->where($this->table_status,'1');
		$this->db->where('rent_property_id',$property_id);
		$this->db->where("(rent_status = '0' OR rent_status = '1' OR rent_status = '2')");
		$this->db->order_by($this->table_pk,'DESC');
		return $this->db->get();
	}

	public function get_all_history($paging=true,$start=0,$limit=10){
		$this->db->select("
						rent_id,
						rent_param,
						rent_no,
						a.rent_user_id,
						a.rent_property_id,
						b.property_code,
						b.property_name,
						c.user_name as 'rent_username',
						c.user_fullname as 'rent_fullname',
						rent_date,
						rent_date_return,
						rent_days,
						rent_description,
						rent_type,
						rent_upload,
						rent_price,
						rent_penalty,
						rent_penalty_paid,
						rent_penalty_upload,
						a.rent_user_approved,
						d.user_name as 'rent_username_approved',
						d.user_fullname as 'rent_fullname_approved',
						rent_status
						");
		$this->db->from($this->table_name.' a');
		$this->db->join('property b','b.property_id = a.rent_property_id','INNER');
		$this->db->join('user c','c.user_id = a.rent_user_id','INNER');
		$this->db->join('user d','d.user_id = a.rent_user_approved','LEFT');
		$this->db->where($this->table_status,'1');
		$this->db->where('a.rent_user_id',$this->session->userdata('user_id'));
		if($paging==true){
			$this->db->limit($limit,$start);
		}
		$this->db->order_by($this->table_pk,'DESC');
		return $this->db->get();
	}

	public function get_all_confirmation($paging=true,$start=0,$limit=10,$search=''){
		$this->db->select("
						rent_id,
						rent_param,
						rent_no,
						a.rent_user_id,
						a.rent_property_id,
						b.property_code,
						b.property_name,
						c.user_name as 'rent_username',
						c.user_fullname as 'rent_fullname',
						rent_date,
						rent_date_return,
						rent_days,
						rent_description,
						rent_type,
						rent_upload,
						rent_price,
						rent_penalty,
						rent_penalty_paid,
						rent_penalty_upload,
						a.rent_user_approved,
						d.user_name as 'rent_username_approved',
						d.user_fullname as 'rent_fullname_approved',
						rent_status
						");
		$this->db->from($this->table_name.' a');
		$this->db->join('property b','b.property_id = a.rent_property_id','INNER');
		$this->db->join('user c','c.user_id = a.rent_user_id','INNER');
		$this->db->join('user d','d.user_id = a.rent_user_approved','LEFT');
		$this->db->where($this->table_status,'1');
		$this->db->where('a.rent_status','0');
		$this->db->or_where('a.rent_status','1');
		if($paging==true){
			$this->db->limit($limit,$start);
		}
		if($search!=''){
			$this->db->like('rent_no',$search);
		}
		$this->db->order_by('rent_status','ASC');
		$this->db->order_by($this->table_pk,'DESC');
		return $this->db->get();
	}

	public function get_all_return_confirmation($paging=true,$start=0,$limit=10,$search=''){
		$this->db->select("
						rent_id,
						rent_param,
						rent_no,
						a.rent_user_id,
						a.rent_property_id,
						b.property_code,
						b.property_name,
						c.user_name as 'rent_username',
						c.user_fullname as 'rent_fullname',
						rent_date,
						rent_date_return,
						rent_days,
						rent_description,
						rent_type,
						rent_upload,
						rent_price,
						rent_penalty,
						rent_penalty_paid,
						rent_penalty_upload,
						a.rent_user_approved,
						d.user_name as 'rent_username_approved',
						d.user_fullname as 'rent_fullname_approved',
						rent_status
						");
		$this->db->from($this->table_name.' a');
		$this->db->join('property b','b.property_id = a.rent_property_id','INNER');
		$this->db->join('user c','c.user_id = a.rent_user_id','INNER');
		$this->db->join('user d','d.user_id = a.rent_user_approved','LEFT');
		$this->db->where($this->table_status,'1');
		$this->db->where('a.rent_status','2');
		$this->db->or_where('a.rent_status','3');
		if($paging==true){
			$this->db->limit($limit,$start);
		}
		if($search!=''){
			$this->db->like('rent_no',$search);
		}
		$this->db->order_by('rent_status','ASC');
		$this->db->order_by($this->table_pk,'DESC');
		return $this->db->get();
	}

	public function get_by_id($rent_id){
		$this->db->select("
						rent_id,
						rent_param,
						rent_no,
						a.rent_user_id,
						a.rent_property_id,
						b.property_code,
						b.property_name,
						b.property_images,
						c.user_name as 'rent_username',
						c.user_fullname as 'rent_fullname',
						rent_date,
						rent_date_return,
						rent_days,
						rent_description,
						rent_type,
						rent_upload,
						rent_price,
						rent_penalty,
						rent_penalty_paid,
						rent_penalty_upload,
						a.rent_user_approved,
						d.user_name as 'rent_username_approved',
						d.user_fullname as 'rent_fullname_approved',
						rent_status
						");
		$this->db->from($this->table_name.' a');
		$this->db->join('property b','b.property_id = a.rent_property_id','INNER');
		$this->db->join('user c','c.user_id = a.rent_user_id','INNER');
		$this->db->join('user d','d.user_id = a.rent_user_approved','LEFT');
		$this->db->where($this->table_pk,$rent_id);
		$this->db->where($this->table_status,'1');
		return $this->db->get();
	}

	public function get_last(){
		$this->db->select('rent_id,rent_param,rent_no');
		$this->db->from($this->table_name);
		$this->db->order_by('rent_id','DESC');
		$this->db->limit('1');
		return $this->db->get();
	}

	public function save($data_rent){
		$this->db->insert($this->table_name,$data_rent);
	}

	public function update($rent_id,$data_rent){
		$this->db->where($this->table_pk,$rent_id);
		$this->db->update($this->table_name,$data_rent);
	}

	public function delete($rent_id){
		$this->db->where($this->table_pk,$rent_id);
		$this->db->delete($this->table_name);
	}
}