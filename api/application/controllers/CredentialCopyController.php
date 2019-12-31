<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class CredentialController extends CI_Controller{


	public function index()
	{
		$this->load->view('demo');
	}


	public function signup(){
		$this->load->model('CredentialModel');
		$response_array =array();	
		$signup_array =array();
		$user_name = $this->input->post('user_username');
		$email_id = $this->input->post('user_emailid');
		$mobile_number = $this->input->post('user_mobilenumber');
		$password = $this->input->post('user_password');
		$firebasekey = $this->input->post('user_firebasekey');
		$deviceimeno = $this->input->post('user_deviceimeno');

		if(empty($user_name)){
			$response_array=array(
				'status_code'=>"0",
				'status'=>"fails",
				'message'=>"Enter Username",
			);
			echo json_encode($response_array);
		}
		else if(empty($email_id)){
			$response_array=array(
				'status_code'=>"0",
				'status'=>"fails",
				'message'=>"Enter EmailID",
			);
			echo json_encode($response_array);
		}
		else if(empty($mobile_number)){
			$response_array=array(
				'status_code'=>"0",
				'status'=>"fails",
				'message'=>"Enter Mobilenumber",
			);
			echo json_encode($response_array);
		}
		else if(empty($password)){
			$response_array=array(
				'status_code'=>"0",
				'status'=>"fails",
				'message'=>"Enter Password",
			);
			echo json_encode($response_array);
		}
		else{
			$check_duplicate_array=array('user_mobilenumber'=>$mobile_number);
			$query_result_duplicate=$this->CredentialModel->checkduplicate_mobilenumber(
				$check_duplicate_array);
			if($query_result_duplicate==0){
				$signup_array=array('user_username'=>$user_name,
					'user_emailid'=>$email_id,
					'user_mobilenumber'=>$mobile_number,
					'user_password'=>$password,
					'user_firebasekey'=>$firebasekey,
					'user_deviceimeno'=>$deviceimeno,
				);

				$result_query=$this->CredentialModel->signupmodel($signup_array);
				if($result_query)
				{
					$response_array=array(
						'status_code'=>"1",
						'status'=>"success",
						'message'=>"Registartion Successfully",
						'user_details'=>array(
							'user_id'=>$result_query,
							'user_name'=>$user_name,
							'user_mailid'=>$email_id,
							'user_mobile_number'=>$mobile_number,
						),

					);
					echo json_encode($response_array);

					$firebasekey_array=array('flower_firebase_token'=>$firebasekey,
						'flower_firebase_deviceid'=>$deviceimeno,
						'flower_firebase_userid'=>$result_query,
						'flower_firebase_user_status'=>"active",
					);
					$this->CredentialModel->register_firebase_keys($firebasekey_array);

				}
				else{
					$response_array=array(
						'status_code'=>"0",
						'status'=>"fails",
						'message'=>"Registartion Fails",
					);
					echo json_encode($response_array);
				}
			}
			else{
				$response_array=array(
					'status_code'=>"0",
					'status'=>"fails",
					'message'=>"Mobilenumber Already Registered",
				);
				echo json_encode($response_array);
			}
		}

	}


	public function login(){

		$this->load->model('CredentialModel');

		$mobilenumber=$this->input->post('user_mobilenumber');
		//$password=$this->input->post('password');
		if(empty($mobilenumber)){
			$response_array=array(
				'status_code'=>"0",
				'status'=>"fails",
				'message'=>"Enter Mobilenumber",
			);
			echo json_encode($response_array);
		}
		else{	   
			$result_array=array('user_mobilenumber'=>$mobilenumber);
			$query=$this->CredentialModel->checklogin($result_array);
			if($query){
				$response_array=array(
					'status_code'=>"1",
					'status'=>"success",
					'message'=>"Login Successfully",
					'user_details'=>array('user_id'=>$query[0]['user_id'],
						'user_name'=>$query[0]['user_username'],
						'user_mailid'=>$query[0]['user_emailid'],
						'user_mobile_number'=>$query[0]['user_mobilenumber']),

				);
			echo json_encode($response_array);
		}
		else{
			$response_array=array(
				'status_code'=>"0",
				'status'=>"fails",
				'message'=>"Login Fails",
			);
			echo json_encode($response_array);
		}
	}



}


}



?>
