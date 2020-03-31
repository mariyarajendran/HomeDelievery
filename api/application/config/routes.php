<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['demo'] = 'DemoController';
///credential Area
$route['json/userLogin'] = 'CredentialController/userLogin';
$route['json/userSignup'] = 'CredentialController/userSignup';
$route['json/logout'] = 'CredentialController/logout';
///credential Area

///profile Area
$route['json/getAllUserDetails'] = 'ProfileController/getAllUserDetails';
$route['json/updateUserDetails'] = 'ProfileController/updateAllUserProfileDetails';
///profile Area

//product Area
$route['json/getAllProducts'] = 'ProductController/getAllProducts';
//product Area

//cart Area
$route['json/addToCart'] = 'CartController/insertCart';
$route['json/deleteToCart'] = 'CartController/deleteCart';
$route['json/getAllCartDatas'] = 'CartController/getAllCartDetails';
//cart Area

//order Area
$route['json/placeOrder'] = 'OrderController/placeOrder';
$route['json/cancelOrder'] = 'OrderController/cancelOrder';
$route['json/orderHistory'] = 'OrderController/orderHistoryDetails';
//order Area

//admin add product
$route['json/addNewProduct'] = 'AdminAddProductController/adminAddProductDatas';
$route['json/updateProduct'] = 'AdminAddProductController/updateProductDetails';
$route['json/deleteproduct'] = 'AdminAddProductController/adminDeleteProduct';
$route['json/adminUpdateProductStatus'] = 'AdminAddProductController/confirmAndCloseOrder';
//admin add product

//pushNotification Area
$route['json/pushNotification'] = 'PushNotificationController/pushNotification';
//pushNotification Area

$route['json/demoapi'] = 'CredentialController/demoapi';
$route['json/demoapilogin'] = 'CredentialController/demoapilogin';
$route['json/demoapiview'] = 'CredentialController/demoapiview';
$route['json/upload'] = 'CredentialController/do_upload';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
