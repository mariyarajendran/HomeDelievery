<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DemoController extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('demo');
	}
	

	public function display(){
		$this->load->model('User');
		echo json_encode($this->User->testapi());

	}
	

	
	public function data_submitted() {
		$data = array(
			'user_name' => $this->input->post('u_name'),
			'user_email_id' => $this->input->post('u_email')
		);
		echo json_encode($data);
	}

	public function data_submitted_single() {

		$response=array();
		$single = $this->input->post('u_name');
		if(empty($single)){
			$response=array(
				'statuscode' => "0",
				'status' => "Fails",
				'message' => "Please Enter Fields");
			echo json_encode($response);
		}
		else{
			$response=array(
				'statuscode' => "1",
				'status' => "Success",
				'message' => $single);
			echo json_encode($response);

		}

	}

}
