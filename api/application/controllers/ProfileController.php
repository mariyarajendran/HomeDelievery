<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'libraries/API_Controller.php');

class ProfileController extends API_Controller{


	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));

    $this->_APIConfig([
      'methods'                              => ['POST','GET'],
      'requireAuthorization'                 => true,
      'limit' => [100, 'ip', 'everyday'] ,
      'data' => [ 'status_code' => HTTP_401 ],
    ]);
  }


  public function index()
  {
    $this->load->view('demo');
    $this->load->library('database');
  }




  public function getAllUserDetails(){
    header("Access-Control-Allow-Origin: *");

    $this->load->model('ProfileModel');
    $json_request_body = file_get_contents('php://input');
    $data = json_decode($json_request_body, true);

    if(isset($data['user_id'])){
      $user_id = $data['user_id'];

      if(empty($user_id)){
        $response_array = array(
         'status_code' => "0",
         'status' => HTTP_400,
         'message' => "User Id Missing.Unable to read user datas",
       );
        $this->output
        ->set_content_type('application/json')
        ->set_status_header(HTTP_400)
        ->set_output(json_encode($response_array));
      }else{
        $user_array = array('user_id' => $user_id);
        $result_query = $this->ProfileModel->getUserDetails($user_array);
        if($result_query)
        {
          $response_array = array(
            'status_code' => "1",
            'status' => HTTP_200,
            'message' => "User Details Received Successfully",
            'user_details' => array('user_id' => $result_query[0]['user_id'],
              'user_name' => $result_query[0]['user_username'],
              'user_mailid' => $result_query[0]['user_emailid'],
              'user_mobile_number' => $result_query[0]['user_mobilenumber'],
              'user_address' => $result_query[0]['user_address'],
              'user_profile_img' => $result_query[0]['user_profile_img'])
          );
          $this->output
          ->set_content_type('application/json')
          ->set_status_header(HTTP_200)
          ->set_output(json_encode($response_array));

        //$this->api_return(data, status_code);
        }
        else{
          $response_array = array(
            'status_code' => "0",
            'status' => HTTP_400,
            'message' => "Something Wrong, while receiving Datas",
          );
          $this->output
          ->set_content_type('application/json')
          ->set_status_header(HTTP_400)
          ->set_output(json_encode($response_array));
        }

      }
    }
    else{
      $response_array = array(
        'status_code' => "0",
        'status' => HTTP_400,
        'message' => "Please give all request params"
      );
      $this->output
      ->set_content_type('application/json')
      ->set_status_header(HTTP_400)
      ->set_output(json_encode($response_array));
    }

  }


  public function updateAllUserProfileDetails(){
   $this->load->model('ProfileModel');
   $json_request_body = file_get_contents('php://input');
   $data = json_decode($json_request_body, true);


   if(isset($data['user_id']) && isset($data['user_name']) 
    && isset($data['user_mailid']) && 
    isset($data['user_mobile_number']) && 
    isset($data['user_address']) 
    && isset($data['user_profile_img'])){

     $user_id = $data['user_id'];
   $user_name = $data['user_name'];
   $user_mailid = $data['user_mailid'];
   $user_mobile_number = $data['user_mobile_number'];
   $user_address = $data['user_address'];
   $user_profile_img = $data['user_profile_img'];

   if(empty($user_id)){
    $response_array = array(
     'status_code' => "0",
     'status' => HTTP_400,
     'message' => "User Id Missing.Unable to update user datas",
   );
    $this->output
    ->set_content_type('application/json')
    ->set_status_header(HTTP_400)
    ->set_output(json_encode($response_array));
  }else{
    $user_array = array('user_id' => $user_id);
    $result_query = $this->ProfileModel->getUserDetails($user_array);
    $db_user_name = $result_query[0]['user_username'];
    $db_user_mailid = $result_query[0]['user_emailid'];
    $db_user_mobile_number = $result_query[0]['user_mobilenumber'];
    $db_user_address = $result_query[0]['user_address'];
    $db_user_profile_img = $result_query[0]['user_profile_img'];

    if(empty($user_name)){
      $user_name = $db_user_name;
    } if(empty($user_mailid)){
      $user_mailid=$db_user_mailid;
    } if(empty($user_mobile_number)){
      $user_mobile_number=$db_user_mobile_number;
    } if(empty($user_address)){
      $user_address=$db_user_address;
    }

    $image_url_path = "uploads/profile/".$user_mobile_number.".png";

    $user_data = array(
      'user_username' => $user_name,
      'user_emailid' => $user_mailid,
      'user_mobilenumber' => $user_mobile_number,
      'user_address' => $user_address,
      'user_profile_img' => $image_url_path
    );
    $result_query = $this->ProfileModel->updateUserDatas($user_id,$user_data);
    if($result_query)
    {
      if(!empty($user_profile_img)){
        $path = "uploads/profile/".$user_mobile_number.".png";
        $user_profile_img = preg_replace('#data:image/[^;]+;base64,#', '', $user_profile_img);
        $status = file_put_contents($path,base64_decode($user_profile_img));
      }
      $response_array = array(
        'status_code' => "1",
        'status' => HTTP_200,
        'message' => "User Details Updated Successfully",
      );
      $this->output
      ->set_content_type('application/json')
      ->set_status_header(HTTP_200)
      ->set_output(json_encode($response_array));
    }
    else{
      $response_array = array(
        'status_code' => "0",
        'status' => HTTP_400,
        'message' => "Something Wrong, while updating Datas",
      );
      $this->output
      ->set_content_type('application/json')
      ->set_status_header(HTTP_400)
      ->set_output(json_encode($response_array));
    }
  }
}
else{
  $response_array = array(
    'status_code' => "0",
    'status' => HTTP_400,
    'message' => "Please give all request params"
  );
  $this->output
  ->set_content_type('application/json')
  ->set_status_header(HTTP_400)
  ->set_output(json_encode($response_array));
}

}











}



?>
