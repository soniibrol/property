<?php

class Report_model extends CI_Model{
	public function get_report_rent($start_date,$end_date,$tipe=0,$status=4,$user_id=0){
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
						a.rent_user_approved,
						d.user_name as 'rent_username_approved',
						d.user_fullname as 'rent_fullname_approved',
						rent_status
						");
		$this->db->from('rent a');
		$this->db->join('property b','b.property_id = a.rent_property_id','INNER');
		$this->db->join('user c','c.user_id = a.rent_user_id','INNER');
		$this->db->join('user d','d.user_id = a.rent_user_approved','LEFT');
		$this->db->where('rent_active','1');
		$this->db->where("(rent_date BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59')");
		if($tipe!=0){
			$this->db->where('rent_type',$tipe);
		}
		if($status!=4){
			$this->db->where('rent_status',$status);
		}
		if($user_id!=0){
			$this->db->where('a.rent_user_id',$user_id);
		}
		$this->db->order_by('rent_id','DESC');
		return $this->db->get();
	}

	/** $type(all || accepted || not)***/
	public function get_report_income($type='all',$start_date,$end_date){
		$this->db->select('rent_id,rent_no,user_fullname,rent_date,rent_days,rent_date_return,rent_price,rent_penalty,rent_status,rent_penalty_paid');
		$this->db->from('rent');
		$this->db->join('user','user.user_id = rent.rent_user_id','INNER');
		$this->db->where('rent_active','1');
		$this->db->where("(rent_date BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59')");
		if($type=='accepted'){ //sudah dibayar
			$this->db->where("(rent_status = '1' OR rent_status = '2' OR rent_status = '3')");
		}elseif($type=='not'){
			$this->db->where('rent_status','0');
		}
		$this->db->order_by('rent_id','DESC');
		return $this->db->get();
	}

	/** $type(all || accepted || not)***/
	public function get_report_penalty($type='all',$start_date,$end_date){
		$this->db->select('rent_id,rent_no,user_fullname,rent_date,rent_days,rent_date_return,rent_price,rent_penalty,rent_status,rent_penalty_paid');
		$this->db->from('rent');
		$this->db->join('user','user.user_id = rent.rent_user_id','INNER');
		$this->db->where('rent_active','1');
		$this->db->where("(rent_date BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59')");
		$this->db->where('rent_penalty > 0');
		if($type=='accepted'){ //sudah dibayar
			$this->db->where('rent_penalty_paid','1');
		}elseif($type=='not'){
			$this->db->where('rent_penalty_paid','0');
		}
		$this->db->order_by('rent_id','DESC');
		return $this->db->get();
	}
}