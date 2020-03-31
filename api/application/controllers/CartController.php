<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'libraries/API_Controller.php');

class CartController extends API_Controller{


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


	public function insertCart(){
		$this->load->model('CartModel');
		$json_request_body = file_get_contents('php://input');
		$data = json_decode($json_request_body, true);

		if(isset($data['user_id']) && isset($data['product_id'])){

			$user_id=$data['user_id'];
			$product_id=$data['product_id'];

			if(empty($user_id)){
				$response_array = array(
					'status_code' => "0",
					'status' => "fails",
					'message' => "User id missing",
				);
				$this->output
				->set_content_type('application/json')
				->set_output(json_encode($response_array));
			}
			else if(empty($product_id)){
				$response_array = array(
					'status_code' => "0",
					'status' => "fails",
					'message' => "Product id missing",
				);
				$this->output
				->set_content_type('application/json')
				->set_output(json_encode($response_array));
			}else{
				$cart_array = array(
					'user_id' => $user_id,
					'product_id' => $product_id
				);

				$result_query = $this->CartModel->addToCartModel($cart_array);
				if($result_query)
				{
					$response_array = array(
						'status_code' => "1",
						'status' => true,
						'message' => "Add to cart Successfully"
					);
					$this->output
					->set_content_type('application/json')
					->set_output(json_encode($response_array));
				}
				else{
					$response_array = array(
						'status_code' => "0",
						'status' => false,
						'message' => "Failed to add cart."
					);
					$this->output
					->set_content_type('application/json')
					->set_output(json_encode($response_array));
				}
			}


		}
		else{
			$response_array = array(
				'status_code' => "0",
				'status' => false,
				'message' => "Please give all request params",
				'user_details' => array(
					'user_id' => "",
					'user_register_status' => "",
					'user_mobile_number' => "",
					'user_otp' => "",
					'user_access_token' => ""
				),
			);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response_array));
		}
	}



	public function deleteCart(){
		$this->load->model('CartModel');
		$json_request_body = file_get_contents('php://input');
		$data = json_decode($json_request_body, true);

		if(isset($data['cart_id'])){

			$cart_id=$data['cart_id'];

			if(empty($cart_id)){
				$response_array = array(
					'status_code' => "0",
					'status' => "fails",
					'message' => "Cart id missing",
				);
				$this->output
				->set_content_type('application/json')
				->set_output(json_encode($response_array));
			}
			else{
				$result_query = $this->CartModel->deleteToCartModel($cart_id);
				if($result_query)
				{
					$response_array = array(
						'status_code' => "1",
						'status' => true,
						'message' => "Delete to cart Successfully"
					);
					$this->output
					->set_content_type('application/json')
					->set_output(json_encode($response_array));
				}
				else{
					$response_array = array(
						'status_code' => "0",
						'status' => false,
						'message' => "Failed to delete cart."
					);
					$this->output
					->set_content_type('application/json')
					->set_output(json_encode($response_array));
				}
			}


		}
		else{
			$response_array = array(
				'status_code' => "0",
				'status' => false,
				'message' => "Please give all request params",
				'user_details' => array(
					'user_id' => "",
					'user_register_status' => "",
					'user_mobile_number' => "",
					'user_otp' => "",
					'user_access_token' => ""
				),
			);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response_array));
		}
	}



	public function getAllCartDetails(){
		$this->load->model('CartModel');
		$json_request_body = file_get_contents('php://input');
		$data = json_decode($json_request_body, true);

		if(isset($data['user_id']) && isset($data['from_date']) && isset($data['to_date'])){
			$user_id = $data['user_id'];
			$from_date = $data['from_date'];
			$to_date = $data['to_date'];

			if(empty($from_date) && empty($to_date)){
				$result_query = $this->CartModel->getAllCartDatas($user_id);
			}else{
				$result_query = $this->CartModel->getAllCartByDate($user_id,$from_date,$to_date);
			}
			//print_r($result_query);
			$resultSet = Array();
			if($result_query)
			{
				foreach ($result_query as $product_result) 
				{ 
					$resultSet[] = array(
						"product_id" =>  $product_result['product_id'],
						"user_id" =>  $product_result['user_id'],
						"cart_id" =>  $product_result['cart_id'],
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
					'message' => "Cart Details Received Successfully",
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
					'message' => "Cart Details result not found.",
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
				'message' => "Please give all request params",
				'user_details' => array(
					'user_id' => "",
					'user_register_status' => "",
					'user_mobile_number' => "",
					'user_otp' => "",
					'user_access_token' => ""
				),
			);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response_array));
		}

	}




}



?>
