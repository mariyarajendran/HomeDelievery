<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class CartModel extends CI_Model{

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}



	public function addToCartModel($data){
		$insert = $this->db->insert('cart_master',$data);
		if($insert){
			return $this->db->insert_id();
		}
		else{
			return false;
		}
	}

	public function deleteToCartModel($id){
		$this->db
		->where('cart_id', $id)
		->delete('cart_master');
		if ($this->db->affected_rows() >= 0) {
			return true;
		}else{
			return false;
		}
	}


	public function getAllCartDatas($data,$pagecount){
		$this->db->select('*');
		$this->db->from('cart_master');
		$this->db->join('product_master','cart_master.product_id=product_master.product_id');
		$this->db->where('cart_master.user_id',$data);
		$this->db->limit(10,$pagecount); 
		$query_result=$this->db->get();
		return $query_result->result_array();
	} 

	public function getAllCartByDate($data,$from_date,$to_date,$pagecount){
		$this->db->select('*');
		$this->db->from('cart_master');
		$this->db->join('product_master','cart_master.product_id=product_master.product_id');
		$this->db->where('cart_master.cart_date >=', $from_date);
		$this->db->where('cart_master.cart_date <=', $to_date);
		$this->db->where('cart_master.user_id',$data);
		$this->db->limit(10,$pagecount); 
		$query_result=$this->db->get();
		return $query_result->result_array();
	} 



}?>