<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'libraries/API_Controller.php');

class AdminAddProductController extends API_Controller{


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


  public function adminAddProductDatas(){
   $this->load->model('AdminAddProductModel');
   $json_request_body = file_get_contents('php://input');
   $data = json_decode($json_request_body, true);

   if(isset($data['product_name']) && isset($data['product_cost']) && isset($data['product_image']) 
    && isset($data['product_short_descr']) 
    && isset($data['product_long_descr']) 
    && isset($data['product_offers'])){

     $product_name = $data['product_name'];
   $product_cost = $data['product_cost'];    
   $product_image = $data['product_image'];
   $product_short_descr = $data['product_short_descr'];
   $product_long_descr = $data['product_long_descr'];
   $product_offers = $data['product_offers'];

   if(empty($product_name)){
    $response_array = array(
     'status_code' => "0",
     'status' => "fails",
     'message' => "Enter Productname",
   );
    $this->output
    ->set_content_type('application/json')
    ->set_output(json_encode($response_array));
  }
  else if(empty($product_cost)){
    $response_array = array(
     'status_code' => "0",
     'status' => "fails",
     'message' => "Enter Product Cost",
   );
    $this->output
    ->set_content_type('application/json')
    ->set_output(json_encode($response_array));
  }
  else if(empty($product_image)){
    $response_array = array(
     'status_code' => "0",
     'status' => "fails",
     'message' => "Enter Product Image",
   );
    $this->output
    ->set_content_type('application/json')
    ->set_output(json_encode($response_array));
  }

  else if(empty($product_short_descr)){
    $response_array = array(
      'status_code' => "0",
      'status' => "fails",
      'message' => "Enter Product Short Descriptions",
    );
    $this->output
    ->set_content_type('application/json')
    ->set_output(json_encode($response_array));
  }
  else if(empty($product_long_descr)){
    $response_array = array(
      'status_code' => "0",
      'status' => "fails",
      'message' => "Enter Product Long Descriptions",
    );
    $this->output
    ->set_content_type('application/json')
    ->set_output(json_encode($response_array));
  }
  else if(empty($product_offers)){
    $response_array = array(
      'status_code' => "0",
      'status' => "fails",
      'message' => "Enter Product offers",
    );
    $this->output
    ->set_content_type('application/json')
    ->set_output(json_encode($response_array));
  }
  else{
    $product_array = array(
      'product_name' => $product_name,
      'product_cost' => $product_cost,
      'product_image' => $product_image,
      'product_short_descr' => $product_short_descr,
      'product_long_descr' => $product_long_descr,
      'product_offers' => $product_offers
    );

    $result_query = $this->AdminAddProductModel->addProductModel($product_array);
    if($result_query)
    {
      $response_array = array(
       'status_code' => "1",
       'status' => true,
       'message' => "New Product Added Successfully"
     );
      $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($response_array));
    }
    else{
      $response_array = array(
       'status_code' => "0",
       'status' => false,
       'message' => "Something Wrong in Add Product",
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
  );
  $this->output
  ->set_content_type('application/json')
  ->set_output(json_encode($response_array));
}
}

public function updateProductDetails(){
 $this->load->model('AdminAddProductModel');
 $json_request_body = file_get_contents('php://input');
 $data = json_decode($json_request_body, true);

 if(isset($data['product_name']) && isset($data['product_cost']) && isset($data['product_image']) 
  && isset($data['product_short_descr']) 
  && isset($data['product_long_descr']) 
  && isset($data['product_offers'])){

   $product_id = $data['product_id'];
 $product_name = $data['product_name'];
 $product_cost = $data['product_cost'];
 $product_image = $data['product_image'];
 $product_short_descr = $data['product_short_descr'];
 $product_long_descr = $data['product_long_descr'];
 $product_offers = $data['product_offers'];

 if(empty($product_id)){
  $response_array = array(
   'status_code' => "0",
   'status' => "fails",
   'message' => "Product Id Missing.Unable to update product datas",
 );
  $this->output
  ->set_content_type('application/json')
  ->set_output(json_encode($response_array));
}else{
  $product_array = array('product_id' => $product_id);
  $result_query = $this->AdminAddProductModel->getProductDetails($product_array);
  $db_product_name = $result_query[0]['product_name'];
  $db_product_cost = $result_query[0]['product_cost'];
  $db_product_image = $result_query[0]['product_image'];
  $db_product_short_descr = $result_query[0]['product_short_descr'];
  $db_product_long_descr = $result_query[0]['product_long_descr'];
  $db_product_offers = $result_query[0]['product_offers'];

  if(empty($product_name)){
    $product_name = $db_product_name;
  } if(empty($product_cost)){
    $product_cost=$db_product_cost;
  } if(empty($product_image)){
    $product_image=$db_product_image;
  } if(empty($product_short_descr)){
    $product_short_descr=$db_product_short_descr;
  } if(empty($product_long_descr)){
    $product_long_descr=$db_product_long_descr;
  } if(empty($product_offers)){
    $product_offers=$db_product_offers;
  }
  $product_data = array(
    'product_name' => $product_name,
    'product_cost' => $product_cost,
    'product_image' => $product_image,
    'product_short_descr' => $product_short_descr,
    'product_long_descr' => $product_long_descr,
    'product_offers' => $product_offers
  );
  $result_query = $this->AdminAddProductModel->updateProductDatas($product_id,$product_data);
  if($result_query)
  {
    $response_array = array(
      'status_code' => "1",
      'status' => true,
      'message' => "Product Details Updated Successfully",
    );
    $this->output
    ->set_content_type('application/json')
    ->set_output(json_encode($response_array));
  }
  else{
    $response_array = array(
      'status_code' => "0",
      'status' => false,
      'message' => "Something Wrong, while updating Datas",
    );
    $this->output
    ->set_content_type('application/json')
    ->set_output(json_encode($response_array));
  }
}
}else{
  $response_array = array(
    'status_code' => "0",
    'status' => false,
    'message' => "Please give all request params",
  );
  $this->output
  ->set_content_type('application/json')
  ->set_output(json_encode($response_array));
}

}



public function adminDeleteProduct(){
  $this->load->model('AdminAddProductModel');
  $json_request_body = file_get_contents('php://input');
  $data = json_decode($json_request_body, true);

  if(isset($data['product_id'])){

    $product_id=$data['product_id'];

    if(empty($product_id)){
      $response_array = array(
        'status_code' => "0",
        'status' => "fails",
        'message' => "Product id missing",
      );
      $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($response_array));
    }
    else{
      $result_query = $this->AdminAddProductModel->deleteProductModel($product_id);
      if($result_query)
      {
        $response_array = array(
          'status_code' => "1",
          'status' => true,
          'message' => "Product Deleted Successfully"
        );
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response_array));
      }
      else{
        $response_array = array(
          'status_code' => "0",
          'status' => false,
          'message' => "Failed to delete product."
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


public function confirmAndCloseOrder(){
  $this->load->model('AdminAddProductModel');
  $json_request_body = file_get_contents('php://input');
  $data = json_decode($json_request_body, true);

  if(isset($data['order_id']) && isset($data['order_status'])){
    $order_id = $data['order_id'];
    $order_status = $data['order_status'];
    if(empty($order_id)){
      $response_array = array(
        'status_code' => "0",
        'status' => "fails",
        'message' => "Order Id Missing.Unable to update user datas",
      );
      $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($response_array));
    }
    else if(empty($order_status)){
      $response_array = array(
        'status_code' => "0",
        'status' => "fails",
        'message' => "Order Status Missing.Unable to update user datas",
      );
      $this->output
      ->set_content_type('application/json')
      ->set_output(json_encode($response_array));
    }else{
      $order_data = array(
        'order_status' => $order_status
      );
      $result_query = $this->AdminAddProductModel->adminUpdateOrderStatus($order_id,$order_data);
      if($result_query)
      {
        $response_array = array(
          'status_code' => "1",
          'status' => true,
          'message' => "Order Status Updated Successfully",
        );
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response_array));
      }
      else{
        $response_array = array(
          'status_code' => "0",
          'status' => false,
          'message' => "Something Wrong, while update Order Status",
        );
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response_array));
      }


    }
  }else{
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
