<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class ProductModel extends CI_Model{

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}


	public function getAllProductDetails($data,$pagecount){
		$this->db->select('*');
		$this->db->from('product_master');
		$this->db->like('product_name',$data, 'after');
		$this->db->limit(10,$pagecount); 
		$query_result=$this->db->get();
		return $query_result->result_array();
	} 


}?>