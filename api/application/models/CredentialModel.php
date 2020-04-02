<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class CredentialModel extends CI_Model{

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}




	public function checkduplicate_mobilenumber($data){
		$this->db->select('*');
		$this->db->from('user_registration');
		$this->db->where($data);
		$query_result=$this->db->get();
		return $query_result->num_rows();
	} 


	public function getUserDetails($data){
		$this->db->select('*');
		$this->db->from('user_registration');
		$this->db->where($data);
		$query_result=$this->db->get();
		return $query_result->result_array();
	} 


	public function updateOTP($id,$data) {
		$this->db->where('user_id', $id);
		$this->db->update('user_registration', $data);
	}


	public function updateUserDatas($id,$data){
		$this->db
		->where('user_id', $id)
		->update('user_registration', $data);
		if ($this->db->affected_rows() >= 0) {
			return true;
		}else{
			return false;
		}
	}


	public function signupmodel($data){
		$insert = $this->db->insert('user_registration',$data);
		if($insert){
			return $this->db->insert_id();
		}
		else{
			return false;
		}
	}


	public function register_firebase_keys($data){
		$insert = $this->db->insert('flower_firebase_keys',$data);
		if($insert){
			return true;
		}
		else{
			return false;
		}
	}


	public function checklogin($data){
		$this->db->select('*');
		$this->db->from('user_registration');
		$this->db->where($data);
		$query_result=$this->db->get();
		return $query_result->result_array();
	}




}?>