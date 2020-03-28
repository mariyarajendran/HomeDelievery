<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class ProfileModel extends CI_Model{

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}


	public function getUserDetails($data){
		$this->db->select('*');
		$this->db->from('user_registration');
		$this->db->where($data);
		$query_result=$this->db->get();
		return $query_result->result_array();
	} 


	public function updateUserDatas($id,$data){
		$this->db
		->where('user_id',$id)
		->update('user_registration',$data);
		if ($this->db->affected_rows() >= 0) {
			return true;
		}else{
			return false;
		}
	}


}?>