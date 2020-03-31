<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'libraries/API_Controller.php');

class ProductController extends API_Controller{


	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));

		
		$this->_APIConfig([
			'methods'                              => ['POST','GET'],
			'requireAuthorization'                 => true,
			'limit' => [100, 'ip', 'everyday'] ,
			'data' => [ 'status_code' => '404' ],
		]);
	}


	public function index()
	{
		$this->load->view('demo');
		$this->load->library('database');
	}



	public function getAllProducts(){
		$this->load->model('ProductModel');
		$json_request_body = file_get_contents('php://input');
		$data = json_decode($json_request_body, true);

		if(isset($data['search_keyword'])){
			$search_keyword = $data['search_keyword'];
			$result_query = $this->ProductModel->getAllProductDetails($search_keyword);
			//print_r($result_query);
			$resultSet = Array();
			if($result_query)
			{
				foreach ($result_query as $product_result) 
				{ 
					$resultSet[] = array(
						"product_id" =>  $product_result['product_id'],
						"product_name" =>  $product_result['product_name'],
						"product_cost" =>  $product_result['product_cost'],
						"product_image" =>  $product_result['product_image'],
						"product_short_descr" =>  $product_result['product_short_descr'],
						"product_long_descr" =>  $product_result['product_long_descr'],
						"product_offers" =>  $product_result['product_offers'],
						"product_date" =>  $product_result['product_date'],
					);
				} 

				$response_array = array(
					'status_code' => "1",
					'status' => true,
					'message' => "Product Details Received Successfully",
					'product_details' => $resultSet
				);
				$this->output
				->set_content_type('application/json')
				->set_output(json_encode($response_array));
			}
			else{
				$response_array = array(
					'status_code' => "0",
					'status' => false,
					'message' => "Searched product result not found.",
					'product_details' => $resultSet
				);
				$this->output
				->set_content_type('application/json')
				->set_output(json_encode($response_array));
			}


		}
		else{
			$response_array = array(
				'status_code' => "0",
				'status' => false,
				'message' => "Please give all request params"
			);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response_array));
		}

	}






}



?>
