<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class OrderModel extends CI_Model{

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}



	public function placeOrderModel($data){
		$insert = $this->db->insert('order_master',$data);
		if($insert){
			return $this->db->insert_id();
		}
		else{
			return false;
		}
	}

	public function cancelOrderModel($id,$data){
		$this->db
		->where('order_id',$id)
		->update('order_master',$data);
		if ($this->db->affected_rows() >= 0) {
			return true;
		}else{
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



	public function getAllOrderDatas($data,$pagecount){
		$this->db->select('*');
		$this->db->from('order_master');
		$this->db->join('product_master','order_master.product_id=product_master.product_id');
		$this->db->where('order_master.user_id',$data);
		$this->db->limit(10,$pagecount); 
		$query_result=$this->db->get();
		return $query_result->result_array();
	} 

	public function getAllOrderByDate($data,$from_date,$to_date,$pagecount){
		$this->db->select('*');
		$this->db->from('order_master');
		$this->db->join('product_master','order_master.product_id=product_master.product_id');
		$this->db->where('order_master.order_date >=', $from_date);
		$this->db->where('order_master.order_date <=', $to_date);
		$this->db->where('order_master.user_id',$data);
		$this->db->limit(10,$pagecount); 
		$query_result=$this->db->get();
		return $query_result->result_array();
	} 



}?>