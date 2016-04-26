<?php
header('Content-type: application/json');
include("proddy_services.php");
$json = file_get_contents('php://input');
$action="";
if(isset($_GET['action']) && $json=='')
{
	$action=$_GET['action'];
}
$jsonKey=null;
$posted_data = json_decode($json, true);
if(!isset($_GET['action']) && $json!='')
{
if($posted_data!=null || $posted_data !='' )
{
	$encode_data=json_encode($json,true);
	//print_r($encode_data);itemByThisUser
	$rows = '';
	foreach($posted_data as $key=>$value)
	{
		$jsonKey= $key; 
	}
}
$action=$jsonKey;
}


$api_class=new Api_function();


if($action!='')
{
	switch ($action)
	{
		case "":
			break;

		case "getItems";
                        if($_GET['subCatId']!='')
                        {
                             $user_id=$_GET['userId'];
                             $id=$_GET['subCatId'];
                             $action=$_GET['action'];
                        }
                        else
                        {
                             $id='';
                             $user_id='';
                             $action='';
                        }
			$data = $api_class->dbGetAllItems($id, $user_id, $action);
			break;
                
                case "getCatSubCat":                        
			$data = $api_class->dbGetCatSubCat();
			break;

                case "getItemDetails":
                        if($_GET['id']!='')
                        {
                              $item_id=$_GET['id'];
                              $user_id=$_GET['userId'];
                        }
                        else
                        {
                              $item_id='';
                              $user_id='';
                        }
                     	$data = $api_class->dbItemDetails($item_id, $user_id);
			break;
                
                case "getProddies":
		        $data= $api_class->dbGetProddiesList();
                        break;

		case "getFestsList":
                        if($_GET['userId']!='')
                        {
                             $user_id=$_GET['userId'];
                             $fest_type_id='';
                        }
                        else
                        {
                             $user_id='';
                             $fest_type_id='';
                        }
			$data = $api_class->dbGetFestDetails($user_id, $fest_type_id);
		        break;

                case "getExperts":
			$data = $api_class->dbGetExpertsList();
		        break;

		case "getFestsInfo":
			$id=$_GET['festId'];
			$data= $api_class->dbGetFestInfo($id);
			break;

                case "signin":
                        if($json!='')
                        {			
			       $data = $api_class->signin($posted_data, $jsonKey);							
                        }
                        else
                        {
                              $data=(object)array("status"=>"0");
                        }
		        break;
                
               case "favItem":
                        if($json!='')
                        {			       									
       			     $data = $api_class->dbSetItemAsFavourite($posted_data, $jsonKey);
                        }
                        else
                        {
                             $data=(object)array("status"=>"0");
                        }			
	         	break;

               case "unFavItem":
                        if($json!='')
                        {			       									
       			     $data = $api_class->dbSetItemAsUnFavourite($posted_data, $jsonKey);
                        }
                        else
                        {
                             $date=(object)array("status"=>"0");
                        }			
	         	break;

	        case "getUsrFavItems":
			$id=$_GET['userId'];
			$data= $api_class->dbGetUserFavouriteItems($id);
			break;

	        case "getMyaddaList":
			$user_id=$_GET['userId'];
			$action=$_GET['action'];	
			$data= $api_class->dbGetAllMyaddaPostList($user_id, $action);		
			break;

	        case "getUinfoList":			
			$data= $api_class->dbGetAllUinfoPostsList();
			break;

	        case "getVendors":			
			$data= $api_class->dbGetAllVendorsAndCats();
			break;

	        case "getVenById":			
                        if($_GET['cid']!='')
                        {
                              $cat_id=$_GET['cid'];
                        }
                        else
                        {
                              $cat_id='';
                        }
			$data= $api_class->dbGetVendorsByCat($cat_id);
			break;


	        case "getPostDetails":			
                        if($_GET['postId']!='')
                        {
                              $post_id=$_GET['postId'];
                              $user_id=$_GET['userId'];
                        }
                        else
                        {
                              $post_id='';
                              $user_id='';
                        }
			$data= $api_class->dbGetMyaddaPostDetailsByPostId($post_id, $user_id);
			break;

               case "setComment":
                        if($json!='')
                        {			       									
       			     $data = $api_class->dbAddCmtToPost($posted_data, $jsonKey);
                        }
                        else
                        {
                             $date=(object)array("status"=>"0");
                        }			
	         	break;

               case "likePost":
                        if($json!='')
                        {			       									
       			     $data = $api_class->dbLikePost($posted_data, $jsonKey);
                        }
                        else
                        {
                             $date=(object)array("status"=>"0");
                        }			
	         	break;

               case "unLikePost":
                        if($json!='')
                        {			       									
       			     $data = $api_class->dbUnLikePost($posted_data, $jsonKey);
                        }
                        else
                        {
                             $date=(object)array("status"=>"0");
                        }			
	         	break;

               case "favPost":
                        if($json!='')
                        {			       									
       			     $data = $api_class->dbFavPost($posted_data, $jsonKey);
                        }
                        else
                        {
                             $date=(object)array("status"=>"fail");
                        }			
	         	break;

               case "unFavPost":
                        if($json!='')
                        {			       									
       			     $data = $api_class->dbUnFavPost($posted_data, $jsonKey);
                        }
                        else
                        {
                             $date=(object)array("status"=>"fail");
                        }			
	         	break;

               case "itemByThisUser":
                        if($_GET['user_id']!='')
                        {
                              $user_id=$_GET['user_id'];
                        }
                        else
                        {
                              $user_id='';
                        }
			$data= $api_class->dbGetItemByThisUser($user_id);
			break;

               case "festsByThisUser":
                        if($_GET['user_id']!='')
                        {
                              $user_id=$_GET['user_id'];
                        }
                        else
                        {
                              $user_id='';
                        }
			$data= $api_class->dbGetFestsByThisUser($user_id);
			break;
              case "postsByThisUser":
                        if($_GET['userId']!='')
                        {
         			$user_id=$_GET['userId'];
         			$action=$_GET['action'];			
                        }
                        else
                        {
         			$user_id='';	
         			$action='';
                        }
			$data= $api_class->dbGetAllMyaddaPostList($user_id, $action);
			break;

              case "usrFavPosts":
                        if($_GET['userId']!='')
                        {
         			$user_id=$_GET['userId'];
         			$action=$_GET['action'];			
                        }
                        else
                        {
         			$user_id='';	
         			$action='';
                        }
			$data= $api_class->dbGetAllMyaddaPostList($user_id, $action);
			break;			

               case "favFest":
                        if($json!='')
                        {			       									
       			     $data = $api_class->dbSetFestAsFavourite($posted_data, $jsonKey);
                        }
                        else
                        {
                             $data=(object)array("status"=>"0");
                        }			
	         	break;

               case "unFavFest":
                        if($json!='')
                        {			       									
       			     $data = $api_class->dbSetFestAsUnFavourite($posted_data, $jsonKey);
                        }
                        else
                        {
                             $data=(object)array("status"=>"0");
                        }			
	         	break;

	        case "getUsrFavFests":
			$id=$_GET['userId'];
			$data= $api_class->dbGetUserFavouriteFests($id);
			break;

                case "itemMasterData":                        
			$data = $api_class->dbGetItemMasterData();
			break;

                case "festMasterData":                        
			$data = $api_class->dbGetFestMasterData();
			break;

                case "addItem":
		        if($json!='')
			{
				$data = $api_class->dbAddItems($posted_data,$jsonKey);
			}
			else
			{
				$data=(object)array("status"=>"fail");
			}
			break;

		case "addFest":
			if($json!='')
			{
				$data = $api_class->dbAddFests($posted_data,$jsonKey);
			}
			else
			{
				$data=(object)array("status"=>"fail");
			}
			break;

		case "addPost":
			if($json!='')
			{
				$data = $api_class->dbAddMyaddaPost($posted_data,$jsonKey);
			}
			else
			{
				$data=(object)array("status"=>"fail");
			}
			break;

		case "getColDetails":			
			$data = $api_class->dbGetColDetails();			
			break;

		case "PostQuery":
			if($json!='')
			{
				$data = $api_class->dbAddStudentQuery($posted_data,$jsonKey);
			}
			else
			{
				$data=(object)array("status"=>"fail");
			}
			break;

		case "RegUser":
			if($json!='')
			{
				$data = $api_class->dbRegUser($posted_data,$jsonKey);
			}
			else
			{
				$data=(object)array("status"=>"fail");
			}
			break;

		case "ExptList":			
			$data = $api_class->dbGetExptListToPostStuQuery();			
			break;

		case "getAlertMData":			
			$data = $api_class->dbGetAlertMasterData();			
			break;

		case "getUserProfile":			
                        if($_GET['userId']!='')
                        {
         			$user_id=$_GET['userId'];         		
                        }
                        else
                        {
         			$user_id='';	
                        }
			$data = $api_class->dbGetUserProfile($user_id);			
			break;

		case "saveFestSettings":
			if($json!='')
			{
				$data = $api_class->dbSaveFestSettings($posted_data,$jsonKey);
			}
			else
			{
				$data=(object)array("status"=>"fail");
			}
			break;

		case "saveBuySellSettings":
			if($json!='')
			{
				$data = $api_class->dbSaveBuySellSettings($posted_data,$jsonKey);
			}
			else
			{
				$data=(object)array("status"=>"fail");
			}
			break;

		case "getItemsAll":
                        if($_GET['userId']!='')
                        {

                             $user_id=$_GET['userId'];
                             $action=$_GET['action'];
                             $id='';
                        }
                        else
                        {

                             $user_id='';
                             $action='';
                             $id='';

                        }
			$data = $api_class->dbGetAllItems($id, $user_id, $action);
			break;

                case "itemEditData":
                        if($_GET['itemId']!='')
                        {
                              $item_id=$_GET['itemId'];
                              $user_id='';
                        }
                        else
                        {
                              $item_id='';
                              $user_id='';
                        }
                     	$data = $api_class->dbGetItemDetailsToEdit($item_id, $user_id);
			break;


                case "editUserProfile":
                        if($json!='')
			{
				$data = $api_class->dbEditUserProfile($posted_data,$jsonKey);
			}
			else
			{
				$data=(object)array("status"=>"fail");
			}
			break;

                case "updateItem":
                        if($json!='')
			{
				$data = $api_class->dbEditUserProfile($posted_data,$jsonKey);
			}
			else
			{
				$data=(object)array("status"=>"fail");
			}
			break;

	        case "getMyaddaTrendingList":
			$user_id=$_GET['userId'];
			$action=$_GET['action'];	
			$data= $api_class->dbGetAllMyaddaPostList($user_id, $action);		
			break;

		case "EditFestData":
			$fest_id=$_GET['festId'];
			$data= $api_class->dbGetFestdetailsToEdit($fest_id);
			break;

                case "Updatefest":
                      if($json!='')
                      {
                           $data = $api_class->dbEditFests($posted_data,$jsonKey);
                      }
                      else
                      {
                            $data=(object)array("status"=>"fail");
                      }
                      break;
       
               case "EditItem":
                     if($json!='')
                     {
                           $data = $api_class->dbEditItems($posted_data,$jsonKey);
                     }
                     else
                     {
                           $data=(object)array("status"=>"fail");
                     }
                     break;

              case "DeleteFest":
                    if($json!='')
                    {
                         $data = $api_class->dbDeleteFest($posted_data,$jsonKey);
                   }
                   else
                   {
                       $data=(object)array("status"=>"fail");
                   }
                   break;

              case "DeleteItem":
                    if($json!='')
                    {
                         $data = $api_class->dbDeleteItem($posted_data,$jsonKey);
                   }
                   else
                   {
                       $data=(object)array("status"=>"fail");
                   }
                   break;

              case "DeletePost":
                    if($json!='')
                    {
                         $data = $api_class->dbDeletePost($posted_data,$jsonKey);
                   }
                   else
                   {
                       $data=(object)array("status"=>"fail");
                   }
                   break;       

              case "getPostEditData":
                        if($_GET['post_id']!='')
                        {
                              $post_id=$_GET['post_id'];
                        }
                        else
                        {
                              $post_id='';
                        }
                     	$data = $api_class->dbGetPostEditData($post_id);
			break;

              case "EditPost":
                  if($json!='')
                  {
                      $data = $api_class->dbEditMyaddaPost($posted_data,$jsonKey);
                  }
                  else
                  {
                      $data=(object)array("status"=>"fail");
                  }
                  break;

              case "GetFestTypes":
                     	$data = $api_class->dbGetMasterFestTypes();
                        break;

		case "getFestsListByType":
                        if($_GET['userId']!='')
                        {
                             $fest_type_id=$_GET['festType'];
                             $user_id=$_GET['userId'];
                        }
                        else
                        {
                             $fest_type_id='';
                             $user_id='';
                        }
			$data = $api_class->dbGetFestDetails($user_id, $fest_type_id);
		        break;
                default:
			break;
	}
	$json_response=json_encode($data);
	echo $json_response;
}

?>