<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'libraries/API_Controller.php');

class CredentialController extends API_Controller{


	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
	}


	public function index()
	{
		$this->load->view('demo');
		$this->load->library('database');
		$this->load->library('Authorization_Token');
		
	}
	
	
	
	public function do_upload()
	{
		$config['upload_path']          = './uploads/';
		$config['allowed_types']        = 'gif|jpg|png';
		$config['max_size']             = 100;
		$config['max_width']            = 1024;
		$config['max_height']           = 768;

		$this->load->library('upload', $config);
		$count = count($_FILES['userfile']['name']);

		for ($k = 0; $k < $count; $k++) {
			if (!$this->upload->do_upload('userfile',$k)) {
				$error = array('error' => $this->upload->display_errors());
				print_r($error);
			}else{

        $udata[$k] = $this->upload->data(); //gradually build up upload->data()
        //$data = array('upload_data' => $this->upload->data());
                       // print_r($udata[$k]);    
    }


}

                // if ( ! $this->upload->do_upload('userfile'))
                // {
                //         $error = array('error' => $this->upload->display_errors());
                //         print_r($error);

                // }
                // else
                // {
                //         $data = array('upload_data' => $this->upload->data());
                //         print_r($data);

                // }
}



public function signup(){
	$this->load->model('CredentialModel');
	$response_array =array();	
	$signup_array =array();

	$json_request_body = file_get_contents('php://input');
	$data = json_decode($json_request_body, true);

	$user_id = $data['user_id'];
	$user_name = $data['user_username'];
	$email_id = $data['user_emailid'];    //optional
	$mobile_number = $data['user_mobile_number'];
	//$user_lat = $data['user_lat'];  //no need remove
	//$user_lng = $data['user_lng'];  //no need remove
	$user_address=$data['user_address'];
	$user_street=$data['user_street'];
	$user_city=$data['user_city'];
	$user_country=$data['user_country'];

	//add address -- *
	//add street -- *
	//add city -- *
	//add country -- *


	if(empty($user_id)){
		$response_array=array(
			'status_code'=>"0",
			'status'=>"fails",
			'message'=>"User ID Missing",
		);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($response_array));
		
	}
	else if(empty($user_name)){
		$response_array=array(
			'status_code'=>"0",
			'status'=>"fails",
			'message'=>"Enter Username",
		);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($response_array));
	}
	else if(empty($user_address)){
		$response_array=array(
			'status_code'=>"0",
			'status'=>"fails",
			'message'=>"Enter Address",
		);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($response_array));
	}
	else if(empty($user_street)){
		$response_array=array(
			'status_code'=>"0",
			'status'=>"fails",
			'message'=>"Enter Street",
		);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($response_array));
	}
	else if(empty($user_city)){
		$response_array=array(
			'status_code'=>"0",
			'status'=>"fails",
			'message'=>"Enter City",
		);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($response_array));
	}
	else if(empty($user_country)){
		$response_array=array(
			'status_code'=>"0",
			'status'=>"fails",
			'message'=>"Enter Country",
		);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($response_array));
	}

	else{

		$signup_array=array('user_username'=>$user_name,
			'user_emailid'=>$email_id,
			'user_mobilenumber'=>$mobile_number,
			//'user_lat'=>"",
			//'user_lng'=>"",
			'user_register_status'=>"1",
			'user_address'=>$user_address,
			'user_street'=>$user_street,
			'user_city'=>$user_city,
			'user_country'=>$user_country,
		);

		$result_query=$this->CredentialModel->updateUserDatas($user_id,$signup_array);
		if($result_query)
		{

			$response_array=array(
				'status_code'=>"1",
				'status'=>true,
				'message'=>"User Details Updated Successfully",
				'user_details'=>array(
					'user_id'=>$user_id,
					'user_name'=>$user_name,
					'user_mailid'=>$email_id,
					'user_mobile_number'=>$mobile_number,
					//'user_current_lat'=>$user_lat,
					//'user_current_lng'=>$user_lng,
					'user_address'=>$user_address,
					'user_street'=>$user_street,
					'user_city'=>$user_city,
					'user_country'=>$user_country,
				),

			);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response_array));
		}
		else{
			$response_array=array(
				'status_code'=>"0",
				'status'=>false,
				'message'=>"Something Wrong.. user details updation failed",
			);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response_array));
		}


	}

}


public function login(){

	$this->load->model('CredentialModel');
// 		$mobilenumber=$this->input->post('user_mobilenumber',true);
// 		$mobilenumber = stripslashes($mobilenumber);
//         $mobilenumber = json_decode($mobilenumber);

	$json_request_body = file_get_contents('php://input');
	$data = json_decode($json_request_body, true);
	$mobilenumber = $data['user_mobilenumber'];
	$payload = [
		'token_generation' => "Token Generated",
	];
	$this->load->library('Authorization_Token');
	$token = $this->authorization_token->generateToken($payload);


		//$password=$this->input->post('password');
	if(empty($mobilenumber)){
		$response_array=array(
			'status_code'=>"0",
			'status'=>false,
			'message'=>"Enter Mobilenumber",
		);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($response_array));
	}
	else{	
		$check_duplicate_array=array('user_mobilenumber'=>$mobilenumber);
		$query_result_duplicate=$this->CredentialModel->checkduplicate_mobilenumber(
			$check_duplicate_array); 
		$randomOTP = substr(str_shuffle("0123456789"), 0, 4);  

		// $payload = [
		// 	'id' => "Your User's ID",
		// 	'other' => "Some other data"
		// ];

		// $token = $this->authorization_token->generateToken($payload);


///This functionality is for new registration users
		if($query_result_duplicate==0){
			$signup_array=array('user_username'=>"",
				'user_emailid'=>"",
				'user_mobilenumber'=>$mobilenumber,
				'user_password'=>"",
				'user_otp'=>$randomOTP,
				'user_lat'=>"",
				'user_lng'=>"",
				'user_register_status'=>"0",
				'user_firebasekey'=>"",
				'user_deviceimeno'=>"",
				'user_access_token'=>$token
			);

			$result_query=$this->CredentialModel->signupmodel($signup_array);
			if($result_query)
			{
				$response_array=array(
					'status_code'=>"1",
					'status'=>true,
					'message'=>"New User Registered Successfully",
					'user_details'=>array(
						'user_id'=>$result_query,
						'user_register_status'=>"0",
						'user_mobile_number'=>$mobilenumber,
						'user_otp'=>$randomOTP,
						'user_access_token'=>$token
					),

				);
				$this->output
				->set_content_type('application/json')
				->set_output(json_encode($response_array));
			}
			else{
				$response_array=array(
					'status_code'=>"0",
					'status'=>false,
					'message'=>"Something Wrong in Registartion",
				);
				$this->output
				->set_content_type('application/json')
				->set_output(json_encode($response_array));
			}
		}
		else{
///This functionality is for already existing users
			$get_user_details_array=array('user_mobilenumber'=>$mobilenumber);
			$queryResultUserDetails=$this->CredentialModel->getUserDetails(
				$get_user_details_array);

			$userId=$queryResultUserDetails[0]['user_id'];
			$userRegistrationStatus=$queryResultUserDetails[0]['user_register_status'];
			$userAccessToken=$queryResultUserDetails[0]['user_access_token'];

			$payload = [
				'token_generation' => "Token Generated",
			];
			$this->load->library('Authorization_Token');
			$token = $this->authorization_token->generateToken($payload);

			$data=array('user_otp'=>$randomOTP,'user_access_token'=>$token);
			$this->CredentialModel->updateOTP($userId,$data);



			$response_array=array(
				'status_code'=>"1",
				'status'=>true,
				'message'=>"Already Existing User",
				'user_details'=>array(
					'user_id'=>$userId,
					'user_register_status'=>$userRegistrationStatus,
					'user_mobile_number'=>$mobilenumber,
					'user_otp'=>$randomOTP,
					'user_access_token'=>$token
				),
			);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response_array));
		}

	}

}



private function key()
{
        // use database query for get valid key
	return 1452;
}



public function demoapi()
{
	header("Access-Control-Allow-Origin: *");
// API Configuration
	$this->_apiConfig([
/**
* By Default Request Method `GET`
*/
'methods' => ['POST'], // 'GET', 'OPTIONS'
/**
* Number limit, type limit, time limit (last minute)
*/
'limit' => [5, 'ip', 'everyday'],
/**
* type :: ['header', 'get', 'post']
* key  :: ['table : Check Key in Database', 'key']
*/
'key' => ['POST', $this->key() ], // type, {key}|table (by default)
]);

// return data
	$this->api_return(
		[
			'status' => true,
			"result" => "Return API Response",
		],
		200);
}









public function demoapilogin()
{
	header("Access-Control-Allow-Origin: *");
        // API Configuration
	$this->_apiConfig([
		'methods' => ['POST'],
		'requireAuthorization'=> false,
	]);

	$payload = [
		'id' => "Your User's ID",
		'other' => "Some other data"

	];

	$this->load->library('Authorization_Token');

	$token = $this->authorization_token->generateToken($payload);
        // return data
	$this->api_return(
		[
			'status' => true,
			"result" => [
				'token' => $token,
			],

		],
		200);
}
    /**
     * view method
     *
     * @link [api/user/view]
     * @method POST
     * @return Response|void
     */
    public function demoapiview()
    {
    	header("Access-Control-Allow-Origin: *");
        // API Configuration [Return Array: User Token Data]
    	$user_data = $this->_apiConfig([
    		'methods' => ['POST'],
    		'requireAuthorization' => true,
    	]);
        // return data
    	$this->api_return(
    		[
    			'status' => true,
    			"result" => [
    				'user_data' => $user_data['token_data']
    			],
    		],
    		200);
    }




}



?>
