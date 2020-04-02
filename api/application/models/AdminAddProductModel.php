<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class AdminAddProductModel extends CI_Model{

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}





	public function getProductDetails($data){
		$this->db->select('*');
		$this->db->from('product_master');
		$this->db->where($data);
		$query_result=$this->db->get();
		return $query_result->result_array();
	} 

	public function getProductCount(){
		$this->db->select('*');
		$this->db->from('product_master');
		$this->db->limit(1);
		$this->db->order_by('product_id',"DESC");
		$query_result=$this->db->get();
		return $query_result->result_array();
	} 


	public function deleteProductModel($id){
		$this->db
		->where('product_id', $id)
		->delete('product_master');
		if ($this->db->affected_rows() >= 0) {
			return true;
		}else{
			return false;
		}
	}


	public function updateProductDatas($id,$data){
		$this->db
		->where('product_id', $id)
		->update('product_master', $data);
		if ($this->db->affected_rows() >= 0) {
			return true;
		}else{
			return false;
		}
	}

	public function adminUpdateOrderStatus($id,$data){
		$this->db
		->where('order_id',$id)
		->update('order_master',$data);
		if ($this->db->affected_rows() >= 0) {
			return true;
		}else{
			return false;
		}
	}


	public function addProductModel($data){
		$insert = $this->db->insert('product_master',$data);
		if($insert){
			return $this->db->insert_id();
		}
		else{
			return false;
		}
	}




}?>