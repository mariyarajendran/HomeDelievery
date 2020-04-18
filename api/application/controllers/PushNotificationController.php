<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'libraries/API_Controller.php');

class PushNotificationController extends API_Controller{


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




  public function pushNotification(){

    $this->load->model('CredentialModel');
    //user_status
    $json_request_body = file_get_contents('php://input');
    $data = json_decode($json_request_body, true);



    if(isset($data['user_status']) && isset($data['title']) && isset($data['body'])){
     $user_status = $data['user_status'];
     $title = $data['title'];
     $body = $data['body'];
     if(empty($user_status)){
       $response_array = array(
        'status_code' => "0",
        'status' => HTTP_400,
        'message' => "User Status Missing.please check",
      );
       $this->output
       ->set_content_type('application/json')
       ->set_status_header(HTTP_400)
       ->set_output(json_encode($response_array));
     }else if(empty($title)){
       $response_array = array(
        'status_code' => "0",
        'status' => HTTP_400,
        'message' => "Title Missing.please check",
      );
       $this->output
       ->set_content_type('application/json')
       ->set_status_header(HTTP_400)
       ->set_output(json_encode($response_array));
     }else if(empty($body)){
       $response_array = array(
        'status_code' => "0",
        'status' => HTTP_400,
        'message' => "Body Missing.please check",
      );
       $this->output
       ->set_content_type('application/json')
       ->set_status_header(HTTP_400)
       ->set_output(json_encode($response_array));
     }else{
      $status_array = array('user_status' => $user_status);
      $result_query = $this->CredentialModel->getUserDetails($status_array);

      $tokenarray=Array();
      if($result_query)
      {
        foreach ($result_query as $product_result) 
        { 
          $tokenarray[] = $product_result['user_firebasekey'];
        } 

        $this->sendPushNotifications($tokenarray,$title,$body);

        $response_array = array(
          'status_code' => "1",
          'status' => HTTP_200,
          'message' => "Push Notification successfully send to the selected users",
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
          'message' => "user status data not found"
        );
        $this->output
        ->set_content_type('application/json')
        ->set_status_header(HTTP_400)
        ->set_output(json_encode($response_array));
      }
    }
   //print_r($result)

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


public function sendPushNotifications($tokens,$title,$body){

  $data=array('title' => "hi",
    'body' => "body");

  $notification=array('title' => $title,
    'body' => $body);

  $url = 'https://fcm.googleapis.com/fcm/send';
  $fields = array(
   /*'to'             => $tokens,*/
   'registration_ids' => $tokens,
   'priority'     => "high",
   'notification' => $notification,
   'data'         => $data
 );

  $headers = array(
    'Authorization:key = AAAAXsS66YE:APA91bHB0OcfmI0NYbPyl2bA4YilTbJP__7iV75pC-soZ_AjvWI9wsMdBsuRlVi0N-vXmQv5fqgccVaYkS_4nIsXKxKbUjv6s3yUWCh5_EluqOWl35ZVFboAJxiDcqavq27MnDV_yrMr',
    'Content-Type: application/json'
  );

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
  $result = curl_exec($ch);           
  if ($result === FALSE) {
   die('Curl failed: ' . curl_error($ch));
 }
 curl_close($ch);
}




}



?>
