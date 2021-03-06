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
		$config['upload_path']                      = './uploads/';
		$config['allowed_types']                    = 'gif|jpg|png';
		$config['max_size']                         = 100;
		$config['max_width']                        = 1024;
		$config['max_height']                       = 768;

		$this->load->library('upload', $config);
		$count = count($_FILES['userfile']['name']);

		for ($k = 0; $k < $count; $k++) {
			if (!$this->upload->do_upload('userfile',$k)) {
				$error = array('error' => $this->upload->display_errors());
				print_r($error);
			}else{
        $udata[$k] = $this->upload->data(); 
      }
    }
  }



  public function logout(){
    $this->load->model('CredentialModel');

    $json_request_body = file_get_contents('php://input');
    $data = json_decode($json_request_body, true);

    if(isset($data['user_id'])){
      $user_id = $data['user_id'];

      $payload = [
        'token_generation' => "Token Generated",
      ];
      $this->load->library('Authorization_Token');
      $token = $this->authorization_token->generateToken($payload);

      if(empty($user_id)){
        $response_array = array(
         'status_code' => "0",
         'status' => "fails",
         'message' => "User ID Missing.please check",
       );
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response_array));
      }
      else{
       $updateUserToken = array(
        'user_access_token' => $token,
        'user_status' => "InActive");
       
       $result_query = $this->CredentialModel->updateUserDatas($user_id,$updateUserToken);
       if($result_query)
       {
        $response_array = array(
          'status_code' => "1",
          'status' => true,
          'message' => "Logout Successfully"
        );
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response_array));
      }
      else{
        $response_array = array(
          'status_code' => "0",
          'status' => false,
          'message' => "Something Wrong in Registartion",
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
      'message' => "Please give all request params"
    );
    $this->output
    ->set_content_type('application/json')
    ->set_output(json_encode($response_array));
  }
}


public function userLogin(){
  $this->load->model('CredentialModel');
  $response_array = array();
  $signup_array   = array();

  $json_request_body = file_get_contents('php://input');
  $data = json_decode($json_request_body, true);

  if(isset($data['user_mobile_number']) && isset($data['user_password'])){
    $mobile_number = $data['user_mobile_number'];
    $user_password = $data['user_password'];

    $payload = [
      'token_generation' => "Token Generated",
    ];
    $this->load->library('Authorization_Token');
    $token = $this->authorization_token->generateToken($payload);

    if(empty($mobile_number)){
      $response_array = array(
       'status_code' => "0",
       'status' => "fails",
       'message' => "Enter Mobile Number",
     );
      $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($response_array));
    }
    else if(empty($user_password)){
      $response_array = array(
        'status_code' => "0",
        'status' => "fails",
        'message' => "Enter Password",
      );
      $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($response_array));
    }
    else{
      $check_duplicate_array = array('user_mobilenumber' => $mobile_number);
      $query_result_duplicate = $this->CredentialModel->checkduplicate_mobilenumber($check_duplicate_array);

      if($query_result_duplicate != 0){
        $login_array = array(
         'user_mobilenumber' => $mobile_number,
         'user_password' => $user_password,
       );
        $result_query = $this->CredentialModel->checklogin($login_array);
        if($result_query)
        {
          $user_id=$result_query[0]['user_id'];
          $updateUserToken = array(
            'user_access_token' => $token,
            'user_status' => "Active");
          $this->CredentialModel->updateUserDatas($user_id,$updateUserToken);

          $response_array = array(
            'status_code' => "1",
            'status' => true,
            'message' => "Login Successfully",
            'user_details' => array('user_id' => $result_query[0]['user_id'],
              'user_name' => $result_query[0]['user_username'],
              'user_mailid' => $result_query[0]['user_emailid'],
              'user_mobile_number' => $result_query[0]['user_mobilenumber'],
              'user_access_token' => $token,
              'role_id' => $result_query[0]['role_id']),


          );
          $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode($response_array));
        }
        else{
          $response_array = array(
            'status_code' => "0",
            'status' => false,
            'message' => "Something Wrong in Registartion",
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
          'message' => "Account not exist. please signup first",
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


public function userSignup(){
 $this->load->model('CredentialModel');
 $json_request_body = file_get_contents('php://input');
 $data = json_decode($json_request_body, true);
 $user_name = $data['user_username'];
      $email_id = $data['user_emailid'];    //optional
      $mobile_number = $data['user_mobile_number'];
      $user_address = $data['user_address'];
      $user_password = $data['user_password'];
      $payload = [
        'token_generation' => "Token Generated",
      ];
      $this->load->library('Authorization_Token');
      $token = $this->authorization_token->generateToken($payload);

      if(empty($user_name)){
        $response_array = array(
         'status_code' => "0",
         'status' => "fails",
         'message' => "Enter Username",
       );
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response_array));
      }
      else if(empty($email_id)){
        $response_array = array(
         'status_code' => "0",
         'status' => "fails",
         'message' => "Enter Emaild Id",
       );
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response_array));
      }
      else if(empty($mobile_number)){
        $response_array = array(
         'status_code' => "0",
         'status' => "fails",
         'message' => "Enter Mobile Number",
       );
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response_array));
      }

      else if(empty($user_password)){
        $response_array = array(
          'status_code' => "0",
          'status' => "fails",
          'message' => "Enter Password",
        );
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response_array));
      }
      else{
        $check_duplicate_array = array('user_mobilenumber' => $mobile_number);
        $query_result_duplicate = $this->CredentialModel->checkduplicate_mobilenumber($check_duplicate_array);
        $randomOTP = substr(str_shuffle("0123456789"), 0, 4);

        if($query_result_duplicate == 0){
          $signup_array = array(
            'user_username' => $user_name,
            'user_emailid' => $email_id,
            'user_mobilenumber' => $mobile_number,
            'user_address' => $user_address,
            'user_password' => $user_password,
            'user_otp' => $randomOTP,
            'user_register_status' => "0",
            'user_firebasekey' => "",
            'user_deviceimeno' => "",
            'user_access_token' => $token,
            'user_status' => "Active",
            'role_id' => "0"
          );

          $result_query = $this->CredentialModel->signupmodel($signup_array);
          if($result_query)
          {
            $response_array = array(
             'status_code' => "1",
             'status' => true,
             'message' => "New User Registered Successfully",
             'user_details' => array(
              'user_id' => $result_query,
              'user_register_status' => "0",
              'user_mobile_number' => $mobile_number,
              'user_otp' => $randomOTP,
              'user_access_token' => $token
            ),

           );
            $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response_array));
          }
          else{
            $response_array = array(
             'status_code' => "0",
             'status' => false,
             'message' => "Something Wrong in Registartion",
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
            'message' => "User Already Exist",
            'user_details' => array(
             'user_id' => null,
             'user_register_status' => null,
             'user_mobile_number' => null,
             'user_otp' => null,
             'user_access_token' => null
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
'methods'                                    => ['POST'], // 'GET', 'OPTIONS'
/**
* Number limit, type limit, time limit (last minute)
*/
'limit'                                      => [5, 'ip', 'everyday'],
/**
* type :: ['header', 'get', 'post']
* key  :: ['table : Check Key in Database', 'key']
*/
'key'                                        => ['POST', $this->key() ], // type, {key}|table (by default)
]);

// return data
     $this->api_return(
      [
       'status'                                  => true,
       "result"                                  => "Return API Response",
     ],
     200);
   }









   public function demoapilogin()
   {
     header("Access-Control-Allow-Origin: *");
        // API Configuration
     $this->_apiConfig([
      'methods'                                  => ['POST'],
      'requireAuthorization'                     => false,
    ]);

     $payload                                     = [
      'id'                                       => "Your User's ID",
      'other'                                    => "Some other data"

    ];

    $this->load->library('Authorization_Token');

    $token                                       = $this->authorization_token->generateToken($payload);
        // return data
    $this->api_return(
      [
       'status'                                  => true,
       "result"                                  => [
        'token'                                  => $token,
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
        // API Configuration [Return Array:     User Token Data]
    	$user_data                               = $this->_apiConfig([
    		'methods'                              => ['POST'],
    		'requireAuthorization'                 => true,
    	]);
        // return data
    	$this->api_return(
    		[
    			'status'                              => true,
    			"result"                              => [
    				'user_data'                          => $user_data['token_data']
    			],
    		],
    		200);
    }




  }



  ?>
