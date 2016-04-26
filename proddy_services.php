<?php
include("dc.php");
//test by teja
class Api_function
{
	public function __construct()
 	{ 		
		$json = file_get_contents('php://input');	
 	}

	//start by teja to get items on 11-3-2016
 	public function dbGetAllItems($id, $user_id, $action)
 	{	
            if($action!='')
            {
 		$status="fail";
		$message="no data found";
if($action=='getItems')
{
 $query="select mc.college_name, i.id, i.itemname, i.price, i.created_on, i.homearea, i.image_path, i.college_map_id, i.type, m_city.id as city_id, m_city.city_name, md.district_name, md.id as district_id FROM `items` i join master_college_map mc join master_city m_city join master_district md where i.sub_cat_id='".$id."' and i.college_map_id=mc.id and i.address_id=m_city.id and m_city.district_id=md.id and i.status_id='Approved' order by i.id";
}
else if($action=='getItemsAll')
{
$query="select mc.college_name, i.id, i.itemname, i.price, i.created_on, i.homearea, i.image_path, i.college_map_id, i.type, m_city.id as city_id, m_city.city_name, md.district_name, md.id as district_id FROM `items` i join master_college_map mc join master_city m_city join master_district md where i.college_map_id=mc.id and i.address_id=m_city.id and m_city.district_id=md.id and i.status_id='Approved' order by i.id";
}
				 		 		
		$objSql2 = new SqlClass();
		$result=$objSql2->executesql($query);		
		if($result == 'no rows')
		{
			return $msg = (object)array("status"=>"fail");
                        //return $obj =(object)array("Server_data"=>$msg);
		}
		else
		{
		        $json_response=array();
		        foreach($result as $data)
			{  	                                
                                $item_id = $data['id'];
                                $userId = $user_id;
                  		$query_check="SELECT * FROM `express_intrest` WHERE user_id='".$userId."' and item_id='".$item_id."'"; 				 		 		
                  		$objSql2 = new SqlClass();
		                $result_check=$objSql2->executesql($query_check);	
				if($result_check!='no rows' && $result_check!='')
                                {
                                     $row_array['intrest'] = "1";
                                }
                                else
                                {
                                     $row_array['intrest'] = "0";
                                }
				$row_array['type'] = $data['type'];
				$image = $data['image_path'];
				$row_array['id'] = $data['id'];
				$row_array['name'] = $data['itemname'];			
				$row_array['price'] = $data['price'];
				$row_array['college_id'] = $data['college_map_id'];
				$row_array['college'] = $data['college_name'];
				$row_array['city_id'] = $data['city_id'];
				$row_array['city_name'] = $data['city_name'];			
				$row_array['district_id'] = $data['district_id'];
				$row_array['district_name'] = $data['district_name'];
				$row_array['area'] = $data['homearea'];
				$row_array['date'] = $data['created_on'];
				$row_array['imageurl'] = "http://alabs.in/proddy_final/admin/images/".$image;
		                array_push($json_response,$row_array);   
			}
			$obj = (object)array("Server_data"=>$json_response);
			return $obj;
		}	
}
else
{
return $msg = (object)array("status"=>"fail");
//return $obj = (object)array("item_details"=>"No data");
}
 	}
 	//end by teja to get items on 11-3-2016

    //start by dinesh to get cat and subcats on 14-3-2016
    public function dbGetCatSubCat()
    {
        $query="select * from master_item_category order by id desc";
        $objSql2 = new SqlClass();
        $result=$objSql2->executesql($query);
        if($result == 'no rows')
        {
                        return $msg = (object)array("status"=>"fail");
                       // return $obj = (object)array("categoryList"=>$msg);
        }
        else
        {
            $json_response=array();

            foreach($result as $data)
            {
                $cat_id=$data['id'];
                $row_array['id'] = $data['id'];
                $querysubcat="select * from master_item_subcat where cat_id='".$cat_id."' order by id desc";
                $objSql2 = new SqlClass();
                $resultsubcat=$objSql2->executesql($querysubcat);
                $json_subcat=array();
                    if($resultsubcat!='no rows')
                    {
                    foreach($resultsubcat as $subcatdata)
                        {
                        $subcat_array['id'] = $subcatdata['id'];
                        $subcat_array['name'] = $subcatdata['subcat_name'];
                        array_push($json_subcat,$subcat_array);
                        }
                    }
                $row_array['name'] = $data['cat_name'];
                $image = $data['image_path'];
                $row_array['imageurl']="http://alabs.in/proddy/images/1.jpg";
                $row_array['subcat'] = $json_subcat;
                array_push($json_response,$row_array);
            }
            
			$obj = (object)array("categoryList"=>$json_response);
            return $obj;
        }
    }
    //end by dinesh to get cat and subcats on 14-3-2016

    //start by teja to get item details on 14-3-2016
public function dbItemDetails($id, $user_id)
 	{	
if($id!='')
{
 		$status="fail";
		$message="No data found";
 		$query="select i.*, ud.id as ud_id, ud.fname, ud.lname, mc_map.id as col_id, mc_map.college_name from items i join users u join user_details ud join master_college_map mc_map where i.id='".$id."' and i.college_map_id=mc_map.id and i.user_id=u.id and u.user_details_id=ud.id and i.status_id='Approved'"; 				 		 		 				 		 		
		$objSql2 = new SqlClass();
		$result=$objSql2->executesql($query);		
		if($result == 'no rows')
		{
			return $msg = (object)array("status"=>"fail");
                        //return $obj =(object)array("item_details"=>$msg);
		}
		else
		{
		    $json_response=array();
		    foreach($result as $data)
			{  	
                                $item_id = $data['id'];
                                $userId = $user_id;
                  		$query_check="SELECT * FROM `express_intrest` WHERE user_id='".$userId."' and item_id='".$item_id."'"; 				 		 		
                  		$objSql2 = new SqlClass();
		                $result_check=$objSql2->executesql($query_check);	
				if($result_check!='no rows' && $result_check!='')
                                {
                                     $row_array['intrest'] = "1";
                                }
                                else
                                {
                                     $row_array['intrest'] = "0";
                                }
				$image = $data['image_path'];
				$row_array['id'] = $data['id'];
				$row_array['name'] = $data['fname'].' '.$data['lname'];
				$row_array['mobile'] = $data['contact_no'];
				$row_array['price'] = $data['price'];
				$row_array['condition'] = $data['type'];
				$row_array['item_name'] = $data['itemname'];
$nagotiable=$data['nagotiable'];						
if($nagotiable=='yes' && $nagotiable!='')
{
      $row_array['price_status'] = "Negotiable";						
}
else if($nagotiable=='no' && $nagotiable!='')
{
       $row_array['price_status'] = "Non Negotiable";	
}				
				$row_array['desc'] = $data['description'];
				$row_array['college'] = $data['college_name'];
				$row_array['area'] = $data['homearea'];
				$row_array['date'] = $data['created_on'];
                                //$row_array['imageurl']="http://alabs.in/proddy/images/1.jpg";								
				$row_array['imageurl'] = "http://alabs.in/proddy_final/admin/images/".$image;
		        array_push($json_response,$row_array);   
			}	
			$obj = (object)array("item_details"=>$json_response);
			return $obj;
		}	
}
else
{
return $msg = (object)array("status"=>"fail");
//return $obj =(object)array("item_details"=>$msg);
}
 	}
    //end by teja to get item details on 14-3-2016

    //start by dinesh to get proddies on 14-3-2016
        public function dbGetProddiesList()
	{
		$queryProddies="SELECT ca.id,ud.fname,ud.lname,mcm.college_name,ca.dept,ca.email,ca.yof_study,ca.contact_no,u.image_path from campus_ambassidor ca join users u,user_details ud,master_college_map mcm where ca.user_id=u.id and u.user_details_id=ud.id and ud.college_map_id=mcm.id order by id";
		$objSql2 = new SqlClass();
		$resultProddies=$objSql2->executesql($queryProddies);
		$status="fail";
		$message="no data found";
		if($resultProddies== 'no rows')
		{
$msg = (object)array("status"=>"fail");
return $obj =(object)array("proddies_list"=>$msg);
		}
		else
		{
			$json_Proddies=array();
			foreach ($resultProddies as $proddy)
			{
				// print_r($proddy);
				$row_array['id']=$proddy['id'];
				$row_array['name']=$proddy['fname'] .$proddy['lname'];
				$row_array['college']=$proddy['college_name'];
				$row_array['department']=$proddy['dept'];
				$row_array['year']=$proddy['yof_study'];
                                $row_array['imageurl']="http://alabs.in/proddy/images/p1.jpg";								
//				$row_array['image']="http://alabs.in/proddy_final/admin/images/".$proddy['image_path'];
				array_push($json_Proddies,$row_array);  
			}
			$obj = (object)array("proddies_list"=>$json_Proddies);
			return $obj;
		 }
	 }
   //end by dinesh to get proddies on 14-3-2016

   //start by teja to get Fest Details on 14-3-2016

        public function dbGetFestDetails($userId, $fest_type_id)
	{  
			$queryFestDetails="";
		if($_GET['action']=='getFestsListByType')
		{
			$ftype_id=$fest_type_id;
			$queryFestDetails="select fd.id as fid, fd.*, mc.city_name, mc_map.id as col_id, mc_map.college_name from fest_details fd join master_college_map mc_map join master_city mc join festtype_fest_map ftype_map on ftype_map.fests_type_id='".$ftype_id."' and ftype_map.fest_id=fd.id and fd.college_map_id=mc_map.id and mc_map.city_id=mc.id and fd.status_id='Approved'";		
		}
		else
		{
           		$queryFestDetails="select fd.id as fid, fd.*, mc.city_name, mc_map.id as col_id, mc_map.college_name from fest_details fd join master_college_map mc_map join master_city mc on fd.college_map_id=mc_map.id and mc_map.city_id=mc.id and fd.status_id='Approved'";
		}
		$objSql2 = new SqlClass();
		$result=$objSql2->executesql($queryFestDetails);
		if($result == 'no rows')
		{
                    return $msg = (object)array("status"=>"fail");
                     //return $obj =(object)array("fest_list"=>$msg);
		}
		else
		{
			$json_res=array();
			$festType=array();
			foreach($result as $data)
			{

				$fest_id=$data['fid'];       

                  		$query_check="SELECT * FROM `favourite_fests` WHERE user_id='".$userId."' and fest_id='".$fest_id."'"; 				 		 		
                  		$objSql2 = new SqlClass();
		                $result_check=$objSql2->executesql($query_check);	
				if($result_check!='no rows' && $result_check!='')
                                {
                                     $row_array['intrest'] = "1";
                                }
                                else
                                {
                                     $row_array['intrest'] = "0";
                                }


				$qryFtypes="select ft.fest_type from festtype_fest_map ft_map join master_fest_type ft where fest_id='".$fest_id."' and ft_map.fests_type_id=ft.id";
				$objSql2 = new SqlClass();
				$resFtype=$objSql2->executesql($qryFtypes);	
				$json_festType=array();       
				if($resFtype!='no rows')
				{
					foreach($resFtype as $ftypes)
					{
						$festType[]=$ftypes['fest_type'];          	
						
					}         
$fType=implode(",", $festType);
				}
				$row_array['id']      = $data['fid'];
				$row_array['name']    = $data['name'];
				$row_array['college'] = $data['college_name'];
				$row_array['area']    = $data['city_name'];
				$row_array['date']    = $data['start_date'];
				$row_array['festtypes']   = $fType;
				$festType='';
				array_push($json_res,$row_array);
			}
			$obj = (object)array("fest_list"=>$json_res);
			return $obj;
		}
	}

//start by teja to get Experts List on 14-3-2016
public function dbGetExpertsList()
{  
    $queryExFields="select * FROM `master_expert_fields`";
    $objSql2 = new SqlClass();
    $resultFields=$objSql2->executesql($queryExFields);
    if($resultFields == 'no rows')
    {
                     return $msg = (object)array("status"=>"fail");
                     //return $obj =(object)array("careerlist"=>$msg);
    }
    else
    {
        $json_res=array();
        $exp_array=array();
        foreach($resultFields as $data)
        {
    		$field_id=$data['id'];
    		if($field_id!='')
    		{
    			$qryExperts="select * from master_expert where field_id=".$field_id."";
    			$objSql2 = new SqlClass();
    			$resExperts=$objSql2->executesql($qryExperts);		
    		}
                $json_exp=array(); 
        	if($resExperts!='no rows' && $resExperts!='')
        	{        		
        		$json_exp=array();        		
        		foreach ($resExperts as $experts)
        		{
        			$exp_array['id']      = $experts['id'];	
        			$exp_array['name']      = $experts['name'];	
        			$exp_array['qual']      = $experts['qualification'];	
        			$exp_array['job']      = $experts['occupation'];	
        			$exp_array['credits']   = $experts['signi_successes'];	
        			$exp_array['imageurl'] = "http://alabs.in/proddy/images/p1.jpg";
        			array_push($json_exp,$exp_array);
        		}    		
        	}   		       	
           $row_array['id']      = $data['id'];
           $row_array['name']    = $data['name'];
           $row_array['imageurl']="http://alabs.in/proddy/images/1.jpg";
    	   $row_array['subcat']    = $json_exp;                   
                  array_push($json_res,$row_array);
        }
        $obj = (object)array("careerlist"=>$json_res);
        return $obj;
    }
}
//end by teja to get Experts List on 14-3-2016

//start by dinesh to get Fest details List on 15-3-2016
	public function dbGetFestInfo($festId)
	{
		$queryfestinfo="SELECT * FROM fest_details where id='".$festId."'";
		$objSql2 = new SqlClass();
		$resultFestInfo=$objSql2->executesql($queryfestinfo);		
		if($resultFestInfo== 'no rows')
		{
                     return $msg = (object)array("status"=>"fail");
                     //return $obj =(object)array("festdetails"=>$msg);
		}
	 	else
	 	{
			$json_festinfo=array();
			foreach ($resultFestInfo as $festinfo) {
			$queryCollege = "SELECT mcm.college_name,mc.city_name from master_college_map mcm join fest_details fd,master_city mc where fd.id='".$festId."' and fd.college_map_id=mcm.id and mc.id=mcm.city_id";
				$objSql2 = new SqlClass();
				$resultColName=$objSql2->executesql($queryCollege);
				$festcol_array=array();
		      	if($resultColName!='no rows')
		      	{
					foreach ($resultColName as $colname) 
					{
	      				        $festcol_array['colname'] = $colname['college_name'];
						$festcol_array['colarea'] = $colname['city_name'];
					}
				}
				$queryfesttype="SELECT mft.fest_type from festtype_fest_map ffm join fest_details fd ,master_fest_type mft where fd.id='".$festId."' and ffm.fest_id=fd.id and mft.id=ffm.fests_type_id";
				$objSql2 = new SqlClass();
				$resultFestType=$objSql2->executesql($queryfesttype);
				$festType_array=array();
				if($resultFestType!='no rows')
				{
					foreach ($resultFestType as $festtype)
					{
						$festType_array[] = $festtype['fest_type'];
					}
				}
				$queryEvents = "select event_name from master_events me join events_fest_map map WHere map.fest_id='".$festId."' and map.event_id=me.id";
				$objSql2 = new SqlClass();
				$resultEvents=$objSql2->executesql($queryEvents);
				$json_events=array();
				if($resultEvents!='no rows')
				{
					foreach ($resultEvents as $events)
					{
						$events_array = $events['event_name'];
						array_push($json_events,$events_array);
					}
				}
				$queryPostuser="SELECT fd.user_Id,ud.fname,ud.lname FROM `fest_details` fd join users u,user_details ud WHERE fd.id='".$festId."' and fd.user_Id = u.id and u.user_details_id =ud.id";
				$objSql2 = new SqlClass();
				$resultPostName=$objSql2->executesql($queryPostuser);
				$post_array=array();
				if($resultPostName!='no rows')
				{
					foreach ($resultPostName as $postname)
					{
						$post_array['fname'] = $postname['fname'];
						$post_array['lname'] = $postname['lname'];
					}
				}
				$festtype=implode("/",$festType_array);
				$postuser=implode(" ",$post_array);
				$event= implode(",",$json_events);
				$festinfo_array['id']        =  $festinfo['id'];
				$festinfo_array['colname']   =  $festcol_array['colname'];
				$festinfo_array['colarea']   =  $festcol_array['colarea'];
				$festinfo_array['festname']  =  $festinfo['name'];
				$festinfo_array['festtypes'] =  $festtype;
				$festinfo_array['Sdate']     =  $festinfo['start_date'];
				$festinfo_array['Edate']     =  $festinfo['end_date'];
				$festinfo_array['mevents']   =  $event;
				$festinfo_array['pmoney']    =  $festinfo['highlight'];
				$festinfo_array['web']       =  $festinfo['website'];
				$festinfo_array['orgname']   =  $festinfo['contact_name'];
				$festinfo_array['orgno']     =  $festinfo['contact_no'];
				$festinfo_array['mail']      =  $festinfo['email_id'];
				$festinfo_array['postUser']  =  $postuser;
				$image     =  $festinfo['image_path'];

        			//$festinfo_array['image'] = "http://alabs.in/proddy/images/".$image;
				$festinfo_array['image'] = "http://alabs.in/proddy_final/admin/images/".$image;

				$festinfo_array['fb']        =  $festinfo['fb_id'];
				$festinfo_array['twitter']   =  $festinfo['twitter_id'];
				$festinfo_array['youtube']   =  $festinfo['youtube_link'];
				array_push($json_festinfo,$festinfo_array);
			}
			if(count($json_festinfo)>0){
				$status="success";
				$message="";
			}
		return	$obj= (object)array("festdetails"=>$json_festinfo);
		}
	}

//end by dinesh to get Fest details List on 15-3-2016

//start by teja to Login on 15-3-2016 
        public function signin($data, $key)
 	{
                foreach($data[$key] as $login_data)
                {
                      $uname=$login_data['uname'];
                      $password=$login_data['password'];
                }
$query="select u.id as user_id, u.email, u.phno, u.password, u.image_path, ud.fname, ud.lname, mcm.college_name, mcm.id as college_id, m_city.city_name, m_city.id as city_id, md.district_name, md.id as district_id from users u join user_details ud join master_college_map mcm join master_city m_city join master_district md where email='".$uname."' and password='".$password."' and u.status_id='Approved' and u.user_details_id=ud.id and ud.college_map_id=mcm.id and mcm.city_id=m_city.id and mcm.district_id=md.id";

		$objSql2 = new SqlClass();
		$result=$objSql2->executesql($query);
                if($result == 'no rows')
		{
			return $obj = (object)array("status"=>"0");
		}
		else
		{
$json_response=array();
			foreach ($result as $users)
			{
				$image = $users['image_path'];
	      		        $user_details['id'] = $users['user_id'];
	      		        $user_details['name'] = $users['fname']." ".$users['fname'];
				$user_details['imageurl'] =  "http://alabs.in/proddy_final/admin/images/".$image;
				$user_details['phno'] = $users['phno'];
				$user_details['email'] = $users['email'];
	      		        $user_details['college'] = $users['college_name'];
	      		        $user_details['college_id'] = $users['college_id'];
				$user_details['city_id'] = $users['city_id'];
				$user_details['city_name'] = $users['city_name'];
	      		        $user_details['district_id'] = $users['district_id'];
	      		        $user_details['district_name'] = $users['district_name'];
        		        array_push($json_response,$user_details);
			}
			$obj = (object)array("status"=>"1", "userdetails"=>$json_response);
			return $obj;			
		}
 	}
//end by teja to Login List on 15-3-2016 



        public function dbSetItemAsFavourite($data, $key)   //set item favorite by teja on 16-03-2016 
 	{
                foreach($data[$key] as $details)
                {
                      $userId=$details['user_id'];
                      $itemId=$details['item_id'];
                }
                $querychk="select * from `express_intrest`where item_id='".$itemId."' and user_id='".$userId."'";
		$objSql2 = new SqlClass();
		$resultchk=$objSql2->executesql($querychk);
                if($resultchk=='no rows')
		{
		         $query="INSERT INTO `express_intrest`(`item_id`, `user_id`) VALUES ('".$itemId."','".$userId."')";
                 	 $objSql2 = new SqlClass();
		         $result=$objSql2->executesql($query);
                         if($result)
		         {
			        return $obj = (object)array("status"=>"1");
		         }
		         else
		         {
			        return $obj = (object)array("status"=>"0");		
		         }
		}
		else
		{
			return $obj = (object)array("status"=>"0");		
		} 	  	                
 	}
        public function dbSetItemAsUnFavourite($data, $key)
 	{
                foreach($data[$key] as $details)
                {
                      $userId=$details['user_id'];
                      $itemId=$details['item_id'];
                }
 	  	$query="DELETE FROM `express_intrest` WHERE item_id='".$itemId."' and user_id='".$userId."'";
		$objSql2 = new SqlClass();
		$result=$objSql2->executesql($query);
                if($result)
		{
			return $obj = (object)array("status"=>"1");
		}
		else
		{
			return $obj = (object)array("status"=>"0");		
		}
 	} 

//start by teja to Get Favourite Items based on User Id on 16-3-2016
 	public function dbGetUserFavouriteItems($user_id)
 	{	
            if($user_id!='')
            { 		
 		$query="select mc.college_name, i.id, i.itemname, i.price, i.created_on, i.homearea, i.image_path FROM `items` i join master_college_map mc join express_intrest ei where ei.user_id='".$user_id."' and i.college_map_id=mc.id and ei.item_id=i.id order by i.id"; 				 		 		
		$objSql2 = new SqlClass();
		$result=$objSql2->executesql($query);		
		if($result == 'no rows')
		{
                     return $msg = (object)array("status"=>"fail");
                     //return $obj =(object)array("Server_data"=>$msg);
		}
		else
		{
		    $json_response=array();
		    foreach($result as $data)
			{  	

				$image = $data['image_path'];
                                $row_array['intrest'] = "1";
				$row_array['id'] = $data['id'];
				$row_array['name'] = $data['itemname'];			
				$row_array['price'] = $data['price'];
				$row_array['college'] = $data['college_name'];
				$row_array['area'] = $data['homearea'];
				$row_array['date'] = $data['created_on'];
                                //$row_array['imageurl']="http://alabs.in/proddy/images/1.jpg";				
				$row_array['imageurl'] = "http://alabs.in/proddy_final/admin/images/".$image;
		        array_push($json_response,$row_array);   
			}
			if(count($json_response) > 0){
					$status="success";
					$message="Thank you";
				}	
			$obj = (object)array("Server_data"=>$json_response);
			return $obj;
		}	
}
else
{
return $obj = (object)array("item_details"=>"No data");
}
 	}

        public function dbGetAllMyaddaPostList($user_id, $action)
	{
if($action=='postsByThisUser')
{
$query="select mp.id as post_id, mp.post, mp.description, mp.created_on, mp.path, ud.fname, ud.lname, u.image_path from myadda_post mp join users u join user_details ud on mp.type='MYFORUM' and mp.user_id='".$user_id."' and mp.user_id=u.id and u.user_details_id=ud.id and mp.status_id='Approved' order by mp.id";
}
else if($action=='getMyaddaList')
{
$query="select mp.id as post_id, mp.post, mp.description, mp.created_on, mp.path, ud.fname, ud.lname, u.image_path from myadda_post mp join users u join user_details ud on mp.type='MYFORUM' and mp.user_id=u.id and u.user_details_id=ud.id and mp.status_id='Approved' order by mp.id";
}
else if($action=='usrFavPosts')
{
$query="select mp.id as post_id, mp.post, mp.description, mp.created_on, mp.path, ud.fname, ud.lname, u.image_path from myadda_post mp join users u join user_details ud join myadda_follow mf on mp.type='MYFORUM' and mf.user_id='".$user_id."' and mf.post_id=mp.id and mp.user_id=u.id and u.user_details_id=ud.id and mp.status_id='Approved' order by mp.id";
}
else if($action=='getMyaddaTrendingList')
{
$query="select mp.id as post_id, mp.post, mp.description, mp.created_on, mp.path, ud.fname, ud.lname, u.image_path from myadda_post mp join users u join user_details ud on mp.type='MYFORUM' and mp.user_id=u.id and u.user_details_id=ud.id and mp.status_id='Approved' order by mp.trend_score";
}

		
		$objSql2 = new SqlClass();
		$result=$objSql2->executesql($query);
		if($resultProddies== 'no rows')
		{
                     return $msg = (object)array("status"=>"fail");
                     //return $obj =(object)array("myaddalist"=>$msg);
		}
		else
		{
			$json_posts=array();
if($result!='no rows')
{
			foreach ($result as $data)
			{		
				$post_id=$data['post_id'];

		$queryLikeStatus = "SELECT count(*) as like_status FROM `myadda_likes` where user_id='".$user_id."' and post_id='".$post_id."'";
		$objSql2 = new SqlClass();
		$resultLikeStatus=$objSql2->executesql($queryLikeStatus);
		$Likestatus=$resultLikeStatus[0]['like_status'];

		$queryFavStatus = "SELECT count(*) as fav_status FROM `myadda_follow` where user_id='".$user_id."' and post_id='".$post_id."'";
		$objSql2 = new SqlClass();
		$resultFavStatus=$objSql2->executesql($queryFavStatus);
		$Favstatus=$resultFavStatus[0]['fav_status'];

		$queryLikeCnt = "SELECT count(*) as like_count FROM myadda_likes where post_id='".$post_id."'";
		$objSql2 = new SqlClass();
		$resultLikeCnt=$objSql2->executesql($queryLikeCnt);
		$LikeCnt=$resultLikeCnt[0]['like_count'];

		$queryCmtCnt = "SELECT count(*) as cmt_count FROM myadda_comments where post_id='".$post_id."'";
		$objSql2 = new SqlClass();
		$resultCmtCnt=$objSql2->executesql($queryCmtCnt);
		$CmtCnt=$resultCmtCnt[0]['cmt_count'];

		$queryFavCnt = "SELECT count(*) as fav_count FROM myadda_follow where post_id='".$post_id."'";
		$objSql2 = new SqlClass();
		$resultFavCnt=$objSql2->executesql($queryFavCnt);
		$FavCnt=$resultFavCnt[0]['fav_count'];

				

				$row_array['id']=$data['post_id'];
				$row_array['uname']=$data['fname'] .$proddy['lname'];
				$user_propic=$data['image_path'];
                                //$row_array['uimageurl']="http://alabs.in/proddy/images/p1.jpg";	
$row_array['uimageurl']="http://alabs.in/proddy_final/admin/images/".$user_propic;							
				$row_array['title']=$data['post'];
				$image=$data['path'];
                                //$row_array['titleimage']="http://alabs.in/proddy/images/1.jpg";
$row_array['titleimage']="http://alabs.in/proddy_final/admin/images/".$image;								
                                $row_array['titledesc']=$data['description'];
                      		if($Likestatus!='0')
                                {
                                         $row_array['likestatus']="1";
                                }
                                else
                                {
                                         $row_array['likestatus']="0";
                                }
                                $row_array['likecount']=$LikeCnt;

                      		if($Favstatus!='0')
                                {
                                         $row_array['favstatus']="1";
                                }
                                else
                                {
                                         $row_array['favstatus']="0";
                                }
                                $row_array['favcount']=$FavCnt;
                                $row_array['cmtcount']=$CmtCnt;
		
                                $row_array['date']=$data['created_on'];
                                //$row_array['imageurl']="http://alabs.in/proddy/images/p1.jpg";								
				//$row_array['image']="http://alabs.in/proddy_final/admin/images/".$proddy['image_path'];
				array_push($json_posts,$row_array);  
			}
			$obj = (object)array("myaddalist"=>$json_posts);
			return $obj;
}
else
{
    return $msg = (object)array("status"=>"fail");
     //return $obj =(object)array("uinfolist"=>$msg);
}
		 }
	 }

        public function dbGetAllUinfoPostsList()
	{
		$query="select mp.id as post_id, mp.post, mp.description, mp.created_on, mp.path, ud.fname, ud.lname, u.image_path from myadda_post mp join users u join user_details ud on mp.type='UINFO' and mp.user_id=u.id and u.user_details_id=ud.id and mp.status_id='Approved'";
		$objSql2 = new SqlClass();
		$result=$objSql2->executesql($query);
		if($resultProddies== 'no rows')
		{
                     return $msg = (object)array("status"=>"fail");
                     //return $obj =(object)array("uinfolist"=>$msg);
		}
		else
		{
			$json_posts=array();
if($result!='no rows')
{
			foreach ($result as $data)
			{				
				$row_array['id']=$data['post_id'];
				$row_array['uname']=$data['fname'] .$proddy['lname'];
				$user_propic=$data['image_path '];
$row_array['uimageurl']="http://alabs.in/proddy/images/p1.jpg";
                                //$row_array['uimageurl']="http://alabs.in/proddy_final/admin/images/".$user_propic;								
				$row_array['title']=$data['post'];
				//$row_array['titleimage']=$data['path'];
                                $row_array['titleimage']="http://alabs.in/proddy/images/1.jpg";								
                                $row_array['titledesc']=$data['description'];
                                $row_array['date']=$data['created_on'];
                                //$row_array['imageurl']="http://alabs.in/proddy/images/p1.jpg";								
				//$row_array['image']="http://alabs.in/proddy_final/admin/images/".$proddy['image_path'];
				array_push($json_posts,$row_array);  
			}

			$obj = (object)array("uinfolist"=>$json_posts);
			return $obj;
}
else
{
     return $msg = (object)array("status"=>"fail");
     //return $obj =(object)array("uinfolist"=>$msg);
}
		 }
	 }

        public function dbGetAllVendorsAndCats()
	{
		$query="SELECT mv.*, mv.id as vendor_id, mvc.id as category_id, mvc.name as cat_name FROM `master_vendors` mv join master_vendor_cat mvc where mv.category=mvc.id";
		$objSql2 = new SqlClass();
		$result=$objSql2->executesql($query);
		if($result== 'no rows')
		{
                    return $msg = (object)array("status"=>"fail");
                     //return $obj =(object)array("vendorslist"=>$msg);
		}
		else
		{
			$json_vendors=array();
if($result!='no rows')
{
			foreach ($result as $data)
			{				
				$row_array['id']=$data['vendor_id'];
				$row_array['name']=$data['name'];                               							
				$row_array['category']=$data['cat_name'];
				$row_array['mobile']=$data['phno'];
                                $row_array['email']=$data['email'];								
                                $row_array['details']=$data['details'];
                                $row_array['date']=$data['created_on'];                                
				array_push($json_vendors,$row_array);  
			}

                $json_cats=array();
                $querycat="select mvc.* from master_vendor_cat mvc join master_vendors mv where mv.category=mvc.id group by mvc.id";
		$objSql2 = new SqlClass();
		$resultcat=$objSql2->executesql($querycat);
                if($resultcat!= 'no rows')
		{
			foreach ($resultcat as $datacat)
			{				
				$cat_array['id']=$datacat['id'];
				$cat_array['cname']=$datacat['name'];                               							
				array_push($json_cats,$cat_array);  
			}
                }
			$vendors_obj = (object)array("vendorslist"=>$json_vendors, "categorylist"=>$json_cats);
			return $vendors_obj ;
}
else
{
     return $msg = (object)array("status"=>"fail");
     //return $obj =(object)array("vendorslist"=>$msg);
}
		 }
	 }



        public function dbGetVendorsByCat($cat_id)
	{
		$query="SELECT mv.*, mv.id as vendor_id, mvc.id as category_id, mvc.name as cat_name FROM `master_vendors` mv join master_vendor_cat mvc where mvc.id='".$cat_id."' and mv.category=mvc.id";
		$objSql2 = new SqlClass();
		$result=$objSql2->executesql($query);
		if($result== 'no rows')
		{
                     return $msg = (object)array("status"=>"fail");
                     //return $obj =(object)array("vendorslist"=>$msg);
		}
                else
		{
			$json_vendors=array();
                        if($result!='no rows')
                        {
			      foreach ($result as $data)
			      {				
				$row_array['id']=$data['vendor_id'];
				$row_array['name']=$data['name'];                               							
				$row_array['category']=$data['cat_name'];
				$row_array['mobile']=$data['phno'];
                                $row_array['email']=$data['email'];								
                                $row_array['details']=$data['details'];
                                $row_array['date']=$data['created_on'];                                
				array_push($json_vendors,$row_array);  
			      }
			      $vendors_obj = (object)array("vendorslist"=>$json_vendors);
			      return $vendors_obj ;
                        }
                        else
                        {
                               return $msg = (object)array("status"=>"fail");
                               //return $obj =(object)array("vendorslist"=>$msg);
                        }
		 }		
	 }
        public function dbGetMyaddaPostDetailsByPostId($post_id, $user_id)
	{


		$query="select mp.id as post_id, mp.post, mp.description, mp.created_on, mp.path, ud.fname, ud.lname, u.image_path from myadda_post mp join users u join user_details ud on mp.id='".$post_id."' and mp.type='MYFORUM' and mp.user_id=u.id and u.user_details_id=ud.id and mp.status_id='Approved'";
		$objSql2 = new SqlClass();
		$result=$objSql2->executesql($query);
		if($result== 'no rows')
		{
                     return $msg = (object)array("status"=>"fail");
                     //return $obj =(object)array("myaddadetails"=>$msg);
		}
		else
		{
			$json_posts=array();
if($result!='no rows')
{
			foreach ($result as $data)
			{		
				$post_id=$data['post_id'];
		
        		$queryCmts = "select mc.*, mc.id as comment_id, u.id as uid, u.image_path, ud.fname, ud.lname from myadda_comments mc join users u join user_details ud where post_id='".$post_id."' group by mc.id";
	         	$objSql2 = new SqlClass();
		        $resultCmts=$objSql2->executesql($queryCmts);
			$json_comments=array();
                        if($resultCmts!='no rows' && $resultCmts!='')
                        {
			      foreach ($resultCmts as $comments)
			      {				
				$cmts_array['id']=$comments['comment_id'];
				$cmts_array['name']=$comments['fname']." ".$comments['fname'];                               							
				$cmts_array['comment']=$comments['comment'];
				$user_propic=$comments['image_path'];
                                //$cmts_array['uimageurl']="http://alabs.in/proddy/images/p1.jpg";
$cmts_array['uimageurl']="http://alabs.in/proddy_final/admin/images/".$user_propic;								
				$cmts_array['date']=$comments['created_on'];
				array_push($json_comments,$cmts_array);  
			      }			      
                        }
		
                $queryLikeStatus = "SELECT count(*) as like_status FROM `myadda_likes` where user_id='".$user_id."' and post_id='".$post_id."'";
		$objSql2 = new SqlClass();
		$resultLikeStatus=$objSql2->executesql($queryLikeStatus);
		$Likestatus=$resultLikeStatus[0]['like_status'];

		$queryFavStatus = "SELECT count(*) as fav_status FROM `myadda_follow` where user_id='".$user_id."' and post_id='".$post_id."'";
		$objSql2 = new SqlClass();
		$resultFavStatus=$objSql2->executesql($queryFavStatus);
		$Favstatus=$resultFavStatus[0]['fav_status'];

		$queryLikeCnt = "SELECT count(*) as like_count FROM myadda_likes where post_id='".$post_id."'";
		$objSql2 = new SqlClass();
		$resultLikeCnt=$objSql2->executesql($queryLikeCnt);
		$LikeCnt=$resultLikeCnt[0]['like_count'];

		$queryCmtCnt = "SELECT count(*) as cmt_count FROM myadda_comments where post_id='".$post_id."'";
		$objSql2 = new SqlClass();
		$resultCmtCnt=$objSql2->executesql($queryCmtCnt);
		$CmtCnt=$resultCmtCnt[0]['cmt_count'];

		$queryFavCnt = "SELECT count(*) as fav_count FROM myadda_follow where post_id='".$post_id."'";
		$objSql2 = new SqlClass();
		$resultFavCnt=$objSql2->executesql($queryFavCnt);
		$FavCnt=$resultFavCnt[0]['fav_count'];
			
				$row_array['id']=$data['post_id'];
				$row_array['name']=$data['fname'] .$proddy['lname'];
				//$row_array['uimageurl']=$data['image_path '];
                                $row_array['imageurl']="http://alabs.in/proddy/images/p1.jpg";								
				$row_array['title']=$data['post'];
				//$row_array['titleimage']=$data['path'];
                                //$row_array['titleimage']="http://alabs.in/proddy/images/1.jpg";								
                                $row_array['titledesc']=$data['description'];
                      		if($Likestatus!='0')
                                {
                                         $row_array['likestatus']="1";
                                }
                                else
                                {
                                         $row_array['likestatus']="0";
                                }
                                $row_array['likecount']=$LikeCnt;

                      		if($Favstatus!='0')
                                {
                                         $row_array['favstatus']="1";
                                }
                                else
                                {
                                         $row_array['favstatus']="0";
                                }
                                $row_array['favcount']=$FavCnt;
                                $row_array['cmtcount']=$CmtCnt;
                                $row_array['comments']=$json_comments;

		
                                //$row_array['date']=$data['created_on'];
                                //$row_array['imageurl']="http://alabs.in/proddy/images/p1.jpg";								
				//$row_array['image']="http://alabs.in/proddy_final/admin/images/".$proddy['image_path'];
				array_push($json_posts,$row_array);  
			}
			$obj = (object)array("myaddadetails"=>$json_posts);
			return $obj;
}
else
{
     return $msg = (object)array("status"=>"fail");
     //return $obj =(object)array("myaddadetails"=>$msg);
}
		 }
	 }


        public function dbAddCmtToPost($data, $key)   //Add comment to post by teja on 17-03-2016
 	{
                foreach($data[$key] as $details)
                {
                      $user_id=$details['userId'];
                      $post_id=$details['postId'];
                      $comment=$details['comment'];
                }
		$query="INSERT INTO `myadda_comments`(`user_id`, `post_id`, `comment`, `created_by`,`updated_by`) VALUES ('".$user_id."','".$post_id."','".$comment."','".$user_id."','".$user_id."')";
                $objSql2 = new SqlClass();
		$result=$objSql2->executesql($query);

		$queryCmtCount="select count(*) as count from myadda_comments where `post_id`='".$post_id."'";
                $objSql2 = new SqlClass();
		$resultCmtCount=$objSql2->executesql($queryCmtCount);
		$cmtCount=$resultCmtCount[0]['count'];

         	$queryCount="SELECT count(*) as count FROM `myadda_likes` where post_id='".$post_id."'";
        	$objSql2 = new SqlClass();
         	$resultCount=$objSql2->executesql($queryCount);
        	$likecount=$resultCount[0]['count'];

                $newTrendScore=$likecount/4+$cmtCount;

                if($result)
		{
                      $queryUpdateTrendScore="UPDATE `myadda_post` SET `trend_score`='".$newTrendScore."' WHERE id='".$post_id."'";
	              $objSql2 = new SqlClass();
	              $resultUpdate=$objSql2->executesql($queryUpdateTrendScore);
		      return $obj = (object)array("status"=>"1", "count"=>$cmtCount);
		}
		else
		{
		       return $obj = (object)array("status"=>"0");		
		}		 	  	                
 	}


        public function dbLikePost($data, $key)  
 	{
                foreach($data[$key] as $details)
                {
                      $userId=$details['user_id'];
                      $postId=$details['post_id'];
                }
                $querychk="SELECT * FROM `myadda_likes` where post_id='".$postId."' and user_id='".$userId."'";
		$objSql2 = new SqlClass();
		$resultchk=$objSql2->executesql($querychk);
                if($resultchk=='no rows')
		{                                                  
		         $query="INSERT INTO `myadda_likes`(`post_id`, `user_id`) VALUES ('".$postId."','".$userId."')";
                 	 $objSql2 = new SqlClass();
		         $result=$objSql2->executesql($query);

                         $queryCmtCount="select count(*) as count from myadda_comments where `post_id`='".$postId."'";
                         $objSql2 = new SqlClass();
	                 $resultCmtCount=$objSql2->executesql($queryCmtCount);
	                 $cmtCount=$resultCmtCount[0]['count'];

                         if($result)
		         {
                               $queryCount="SELECT count(*) as count FROM `myadda_likes` where post_id='".$postId."'";
	           	       $objSql2 = new SqlClass();
		               $resultCount=$objSql2->executesql($queryCount);
                               $likecount=$resultCount[0]['count'];

	                       $newTrendScore=$likecount/4+$cmtCount;

                               $queryUpdateTrendScore="UPDATE `myadda_post` SET `trend_score`='".$newTrendScore."' WHERE id='".$postId."'";
	                       $objSql2 = new SqlClass();
	                       $resultUpdate=$objSql2->executesql($queryUpdateTrendScore);

			        return $obj = (object)array("status"=>"success", "count"=>$likecount);
		         }
		         else
		         {
			        return $obj = (object)array("status"=>"fail");		
		         }
		}
		else
		{
			return $obj = (object)array("status"=>"fail");		
		} 	  	                
 	}
        public function dbUnLikePost($data, $key)
 	{
                foreach($data[$key] as $details)
                {
                      $userId=$details['user_id'];
                      $postId=$details['post_id'];
                }
 	  	$query="DELETE FROM `myadda_likes` where post_id='".$postId."' and user_id='".$userId."'";
		$objSql2 = new SqlClass();
		$result=$objSql2->executesql($query);

                         $queryCmtCount="select count(*) as count from myadda_comments where `post_id`='".$postId."'";
                         $objSql2 = new SqlClass();
	                 $resultCmtCount=$objSql2->executesql($queryCmtCount);
	                 $cmtCount=$resultCmtCount[0]['count'];

                if($result)
		{


                               $queryCount="SELECT count(*) as count FROM `myadda_likes` where post_id='".$postId."'";
	           	       $objSql2 = new SqlClass();
		               $resultCount=$objSql2->executesql($queryCount);
                               $likecount=$resultCount[0]['count'];

	                       $newTrendScore=$likecount/4+$cmtCount;

                               $queryUpdateTrendScore="UPDATE `myadda_post` SET `trend_score`='".$newTrendScore."' WHERE id='".$postId."'";
	                       $objSql2 = new SqlClass();
	                       $resultUpdate=$objSql2->executesql($queryUpdateTrendScore);


          		       return $obj = (object)array("status"=>"success", "count"=>$likecount);
		}
		else
		{
			return $obj = (object)array("status"=>"fail");		
		}
 	} 

        public function dbFavPost($data, $key)  
 	{
                foreach($data[$key] as $details)
                {
                      $userId=$details['user_id'];
                      $postId=$details['post_id'];
                }
                $querychk="SELECT * FROM `myadda_follow` where post_id='".$postId."' and user_id='".$userId."'";
		$objSql2 = new SqlClass();
		$resultchk=$objSql2->executesql($querychk);
                if($resultchk=='no rows')
		{                                                  
		         $query="INSERT INTO `myadda_follow`(`post_id`, `user_id`) VALUES ('".$postId."','".$userId."')";
                 	 $objSql2 = new SqlClass();
		         $result=$objSql2->executesql($query);
                         if($result)
		         {
                               $queryCount="SELECT count(*) as count FROM `myadda_follow` where post_id='".$postId."'";
	           	       $objSql2 = new SqlClass();
		               $resultCount=$objSql2->executesql($queryCount);
                               $favcount=$resultCount[0]['count'];
			        return $obj = (object)array("status"=>"success", "count"=>$favcount);
		         }
		         else
		         {
			        return $obj = (object)array("status"=>"fail");		
		         }
		}
		else
		{
			return $obj = (object)array("status"=>"fail");		
		} 	  	                
 	}
        public function dbUnFavPost($data, $key)
 	{
                foreach($data[$key] as $details)
                {
                      $userId=$details['user_id'];
                      $postId=$details['post_id'];
                }
 	  	$query="DELETE FROM `myadda_follow` where post_id='".$postId."' and user_id='".$userId."'";
		$objSql2 = new SqlClass();
		$result=$objSql2->executesql($query);
                if($result)
		{
                               $queryCount="SELECT count(*) as count FROM `myadda_follow` where post_id='".$postId."'";
	           	       $objSql2 = new SqlClass();
		               $resultCount=$objSql2->executesql($queryCount);
                               $favcount=$resultCount[0]['count'];
          		       return $obj = (object)array("status"=>"success", "count"=>$favcount);
		}
		else
		{
			return $obj = (object)array("status"=>"fail");		
		}
 	} 

 	public function dbGetItemByThisUser($user_id)
 	{	
        if($user_id!='')
        { 		
	 		$query="select i.*, ud.id as ud_id, ud.fname, ud.lname, mc_map.id as col_id, mc_map.college_name from items i join users u join user_details ud join master_college_map mc_map where i.user_id='".$user_id."' and i.college_map_id=mc_map.id and i.user_id=u.id and u.user_details_id=ud.id"; 				 		 		
			$objSql2 = new SqlClass();
			$result=$objSql2->executesql($query);		
			if($result == 'no rows')
			{
                              return $msg = (object)array("status"=>"fail");
                              //return $obj =(object)array("Server_data"=>$msg);
			}
			else
			{
			    $json_response=array();
			    foreach($result as $data)
			    {  						
	                        $image = $data['image_path'];
				$row_array['id'] = $data['id'];
				$row_array['name'] = $data['itemname'];
				$row_array['user_name'] = $data['fname'].' '.$data['lname'];
				$row_array['price'] = $data['price'];
				$row_array['college'] = $data['college_name'];
				$row_array['area'] = $data['homearea'];
				$created_date = $data['created_on'];
				$date = new \DateTime($created_date);
				$date->modify('+15 day');
				$exp_date=$date->format('Y-m-d');
				$posted_date = strtotime($created_date);
				$row_array['date'] = $data['created_on'];
				$row_array['imageurl'] = "http://alabs.in/proddy_final/admin/images/".$image;
				if(strtotime($exp_date)==strtotime(date("Y-m-d")))
				{
					$row_array['expstatus'] = "renew";
					$row_array['expdate'] =  "renew";		
				}
				else
				{
					$row_array['expstatus'] = $data['contact_status'];
					$row_array['expdate'] =  $exp_date;	
				}					                                                           
			        array_push($json_response,$row_array);   
			    }
				$obj = (object)array("Server_data"=>$json_response);
				return $obj;
			}	
		}
		else
		{
			return $obj = (object)array("Server_data"=>"No data");
		}
 	}
        public function dbGetFestsByThisUser($user_id)
	{
		$queryfestinfo="SELECT * FROM fest_details where user_id='".$user_id."'";
		$objSql2 = new SqlClass();
		$resultFestInfo=$objSql2->executesql($queryfestinfo);		
		if($resultFestInfo== 'no rows')
		{
                     return $msg = (object)array("status"=>"fail");
                     //return $obj =(object)array("fest_list"=>$msg);
		}
	 	else
	 	{
			$json_festinfo=array();
			foreach ($resultFestInfo as $festinfo) {
			$queryCollege = "SELECT mcm.college_name,mc.city_name from master_college_map mcm join fest_details fd,master_city mc where fd.user_id='".$user_id."' and fd.college_map_id=mcm.id and mc.id=mcm.city_id";
				$objSql2 = new SqlClass();
				$resultColName=$objSql2->executesql($queryCollege);
				$festcol_array=array();
		      	if($resultColName!='no rows')
		      	{
					foreach ($resultColName as $colname) 
					{
	      				        $festcol_array['colname'] = $colname['college_name'];
						$festcol_array['colarea'] = $colname['city_name'];
					}
				}
				$queryfesttype="SELECT mft.fest_type from festtype_fest_map ffm join fest_details fd ,master_fest_type mft where fd.user_id='".$user_id."' and ffm.fest_id=fd.id and mft.id=ffm.fests_type_id";
				$objSql2 = new SqlClass();
				$resultFestType=$objSql2->executesql($queryfesttype);
				$festType_array=array();
				if($resultFestType!='no rows')
				{
					foreach ($resultFestType as $festtype)
					{
						$festType_array[] = $festtype['fest_type'];
					}
				}
				$queryEvents = "select event_name from master_events me join events_fest_map map join fest_details fd Where fd.user_id='".$user_id."' and  fd.id=map.id";
				$objSql2 = new SqlClass();
				$resultEvents=$objSql2->executesql($queryEvents);
				$json_events=array();
				if($resultEvents!='no rows')
				{
					foreach ($resultEvents as $events)
					{
						$events_array = $events['event_name'];
						array_push($json_events,$events_array);
					}
				}
				$queryPostuser="SELECT fd.user_Id,ud.fname,ud.lname FROM `fest_details` fd join users u,user_details ud WHERE fd.user_id='".$user_id."' and fd.user_Id = u.id and u.user_details_id =ud.id";
				$objSql2 = new SqlClass();
				$resultPostName=$objSql2->executesql($queryPostuser);
				$post_array=array();
				if($resultPostName!='no rows')
				{
					foreach ($resultPostName as $postname)
					{
						$post_array['fname'] = $postname['fname'];
						$post_array['lname'] = $postname['lname'];
					}
				}
				$festtype=implode(",",$festType_array);
				$postuser=implode(" ",$post_array);
				$event= implode(",",$json_events);
				$festinfo_array['id']        =  $festinfo['id'];
				$festinfo_array['college']   =  $festcol_array['colname'];
				$festinfo_array['area']   =  $festcol_array['colarea'];
				$festinfo_array['name']  =  $festinfo['name'];
				$festinfo_array['festtypes'] =  $festtype;
				$festinfo_array['date']     =  $festinfo['start_date'];
				$festinfo_array['Edate']     =  $festinfo['end_date'];
				$festinfo_array['mevents']   =  $event;
				$festinfo_array['pmoney']    =  $festinfo['highlight'];
				$festinfo_array['web']       =  $festinfo['website'];
				$festinfo_array['orgname']   =  $festinfo['contact_name'];
				$festinfo_array['orgno']     =  $festinfo['contact_no'];
				$festinfo_array['mail']      =  $festinfo['email_id'];
				$festinfo_array['postUser']  =  $postuser;
//				$festinfo_array['image']     =  $festinfo['image_path'];

        			$festinfo_array['image'] = "http://alabs.in/proddy/images/p1.jpg";

				$festinfo_array['fb']        =  $festinfo['fb_id'];
				$festinfo_array['twitter']   =  $festinfo['twitter_id'];
				$festinfo_array['youtube']   =  $festinfo['youtube_link'];
				array_push($json_festinfo,$festinfo_array);
			}
			if(count($json_festinfo)>0){
				$status="success";
				$message="";
			}
		return	$obj= (object)array("fest_list"=>$json_festinfo);
		}
	}
        public function dbSetFestAsFavourite($data, $key)   //set fest as favorite by teja on 22-03-2016 
 	{
                foreach($data[$key] as $details)
                {
                      $userId=$details['user_id'];
                      $festId=$details['fest_id'];
                }
               $querychk="select * from `favourite_fests` where fest_id='".$festId."' and user_id='".$userId."'";
		$objSql2 = new SqlClass();
		$resultchk=$objSql2->executesql($querychk);
                if($resultchk=='no rows')
		{
		         $query="INSERT INTO `favourite_fests`(`fest_id`, `user_id`) VALUES ('".$festId."','".$userId."')";
                 	 $objSql2 = new SqlClass();
		         $result=$objSql2->executesql($query);
                         if($result)
		         {
			        return $obj = (object)array("status"=>"1");
		         }
		         else
		         {
			        return $obj = (object)array("status"=>"0");		
		         }
		}
		else
		{
			return $obj = (object)array("status"=>"0");		
		} 	  	                
 	}
        public function dbSetFestAsUnFavourite($data, $key)
 	{
                foreach($data[$key] as $details)
                {
                      $userId=$details['user_id'];
                      $festId=$details['fest_id'];
                }
 	  	$query="DELETE FROM `favourite_fests` where fest_id='".$festId."' and user_id='".$userId."'";
		$objSql2 = new SqlClass();
		$result=$objSql2->executesql($query);
                if($result)
		{
			return $obj = (object)array("status"=>"1");
		}
		else
		{
			return $obj = (object)array("status"=>"0");		
		}
 	}


        public function dbGetUserFavouriteFests($userId)
	{  
		$queryFestDetails="select fd.id as fid, fd.*, mc.city_name, mc_map.id as col_id, mc_map.college_name from fest_details fd join master_college_map mc_map join master_city mc join favourite_fests f_fest on f_fest.user_id='".$userId."' and f_fest.fest_id=fd.id and fd.college_map_id=mc_map.id and mc_map.city_id=mc.id and fd.status_id='Approved'";
		$objSql2 = new SqlClass();
		$result=$objSql2->executesql($queryFestDetails);
		if($result == 'no rows')
		{
                    return $msg = (object)array("status"=>"fail");
                     //return $obj =(object)array("fest_list"=>$msg);
		}
		else
		{
			$json_res=array();
			$festType=array();
			foreach($result as $data)
			{

				$fest_id=$data['fid'];       

                  		$query_check="SELECT * FROM `favourite_fests` WHERE user_id='".$userId."' and fest_id='".$fest_id."'"; 				 		 		
                  		$objSql2 = new SqlClass();
		                $result_check=$objSql2->executesql($query_check);	
				if($result_check!='no rows' && $result_check!='')
                                {
                                     $row_array['intrest'] = "1";
                                }
                                else
                                {
                                     $row_array['intrest'] = "0";
                                }


				$qryFtypes="select ft.fest_type from festtype_fest_map ft_map join master_fest_type ft where fest_id='".$fest_id."' and ft_map.fests_type_id=ft.id";
				$objSql2 = new SqlClass();
				$resFtype=$objSql2->executesql($qryFtypes);	
				$json_festType=array();       
				if($resFtype!='no rows')
				{
					foreach($resFtype as $ftypes)
					{
						$festType[]=$ftypes['fest_type'];          	
						
					}         
$fType=implode(",", $festType);
				}
				$row_array['id']      = $data['fid'];
				$row_array['name']    = $data['name'];
				$row_array['college'] = $data['college_name'];
				$row_array['area']    = $data['city_name'];
				$row_array['date']    = $data['start_date'];
				$row_array['festtypes']   = $fType;
				$festType='';
				array_push($json_res,$row_array);
			}
			$obj = (object)array("fest_list"=>$json_res);
			return $obj;
		}
	}

    public function dbGetItemMasterData()
    {
        $query="select * from master_item_category order by id desc";
        $objSql2 = new SqlClass();
        $result=$objSql2->executesql($query);
        if($result == 'no rows')
        {
                        return $msg = (object)array("status"=>"fail");
                       // return $obj = (object)array("categoryList"=>$msg);
        }
        else
        {
            $json_response=array();

            foreach($result as $data)
            {
                $cat_id=$data['id'];
                $row_array['id'] = $data['id'];
                $querysubcat="select * from master_item_subcat where cat_id='".$cat_id."' order by id desc";
                $objSql2 = new SqlClass();
                $resultsubcat=$objSql2->executesql($querysubcat);
                $json_subcat=array();
                    if($resultsubcat!='no rows')
                    {
                    foreach($resultsubcat as $subcatdata)
                        {
                        $subcat_array['id'] = $subcatdata['id'];
                        $subcat_array['name'] = $subcatdata['subcat_name'];
                        array_push($json_subcat,$subcat_array);
                        }
                    }
                $row_array['name'] = $data['cat_name'];
                $row_array['imageurl']="http://alabs.in/proddy/images/1.jpg";
                $row_array['subcat'] = $json_subcat;
                array_push($json_response,$row_array);
            }
                    $querycity="SELECT * FROM `master_city` order by id desc";
                    $objSql2 = new SqlClass();
                    $resultcity=$objSql2->executesql($querycity);
                    $json_city=array();
                    if($resultcity!='no rows')
                    {
                        foreach($resultcity as $city)
                        {
                             $city_array['id'] = $city['id'];
                             $city_array['name'] = $city['city_name'];
                             array_push($json_city,$city_array);
                        }
                    }

            
			$obj = (object)array("categoryList"=>$json_response, "cityList"=>$json_city);
            return $obj;
        }
    }

    public function dbGetFestMasterData()
    {
        $queryFType="SELECT * FROM `master_fest_type` order by id desc";
        $objSql2 = new SqlClass();
        $resultFType=$objSql2->executesql($queryFType);

        $queryCollege="SELECT * FROM `master_college_map` order by id desc";
        $objSql2 = new SqlClass();
        $resultCollege=$objSql2->executesql($queryCollege);

        $queryDepts="SELECT * FROM `master_specialization` order by id desc";
        $objSql2 = new SqlClass();
        $resultDepts=$objSql2->executesql($queryDepts);

        $queryEvents="SELECT * FROM `master_events` order by id desc";
        $objSql2 = new SqlClass();
        $resultEvents=$objSql2->executesql($queryEvents);
        
        $json_ftype=array();
        $json_college=array();
        $json_dept=array();
        $json_events=array();

        if($resultFType != 'no rows')
        {        
               foreach($resultFType as $ftypes)
               {
                        $ftype_array['id'] = $ftypes['id'];
                        $ftype_array['name'] = $ftypes['fest_type'];
                        array_push($json_ftype,$ftype_array);                        
               }                                              
        }
        if($resultCollege != 'no rows')
        {        
               foreach($resultCollege as $colleges)
               {
                        $college_array['id'] = $colleges['id'];
                        $college_array['name'] = $colleges['college_name'];
                        array_push($json_college,$college_array);                        
               }
        }
        if($resultDepts != 'no rows')
        {        
               foreach($resultDepts as $depts)
               {
                        $dept_array['id'] = $depts['id'];
                        $dept_array['name'] = $depts['spec_name'];
                        array_push($json_dept,$dept_array);                        
               }             
        }
        if($resultEvents != 'no rows')
        {        
               foreach($resultEvents as $events)
               {
                        $events_array['id'] = $events['id'];
                        $events_array['name'] = $events['event_name'];
                        array_push($json_events,$events_array);                        
               }             
        }            
	    $obj = (object)array("TypeOfFests"=>$json_ftype, "DeptList"=>$json_dept, "CollegesList"=>$json_college, "MeventsList"=>$json_events);
            return $obj;
        
    }

	public function dbAddItems($data,$key)
	{
		foreach ($data[$key] as $details)
		{
			# code...
			$user_id=$details['user_id'];
			$item_name=$details['itemname'];
			$item_desc=$details['itemDesc'];
			$cat_id=$details['cat'];
			$subcat_id=$details['subcat'];
			$phno=$details['user_phone'];
			$type=$details['type'];
			$price=$details['price'];
			$email=$details['user_email'];
			$priceStatus=$details['priceStatus'];
			if($priceStatus=='Negotiable')
			{
				$priceStatus="yes";
			}
			else
			{
				$priceStatus="no";
			}
			$city_id=$details['city'];
			$college_id=$details['user_cid'];
			$area=$details['area'];
			$show=$details['show'];
			$status_id="Pending";
			$image_string=$details['imageurl'];
                        $today = date("Ymdhis");
                        $rand = strtoupper(substr(uniqid(sha1(time())),0,4));
                        $image = $today . $rand.".png";
                        $path="../admin/images/".$image ;
                        if($details['imageurl']=='null')
                        {
			     $image="-text.png";
                        }
			$contact_status="static";
                        $timestamp = date('Y-m-d G:i:s');
			$query="INSERT INTO `items`(`user_id`, `college_map_id`, `address_id`, `cat_id`, `sub_cat_id`,
			 `status_id`, `itemname`, `description`, `email`,`hideandshow`, `homearea`,`image_path`, `type`, `price`,
			  `nagotiable`,`contact_no`, `contact_status`, `created_on`) VALUES ('".$user_id."','".$college_id."','".$city_id."','".$cat_id."','".$subcat_id."',
					'".$status_id."','".$item_name."','".$item_desc."','".$email."', '".$show."','".$area."','".$image."',
					'".$type."','".$price."','".$priceStatus."','".$phno."','".$contact_status."','".$timestamp ."')";
			$objSql2 = new SqlClass();
			$result=$objSql2->executesql($query);
			if($result)
			{
                                if($details['imageurl']!='null')
                                {
				       file_put_contents($path,base64_decode($image_string));
                                }
				return $obj = (object)array("status"=>"success");
			}
			else
			{
				return $obj = (object)array("status"=>"fail");		
			}
		}
	}

 	public function dbAddFests($data, $key)
 	{
 		foreach ($data[$key] as $details)
 		{
 			$user_id=$details['user_id'];
 			$col_id = $details['col_map_id'];
 			$deptIds = rtrim($details['depts'], ",");
 			$dept_ids = explode(",", $deptIds );
			$festsTypeIds = rtrim($details['fest_type'], ",");
 			$fests_type_ids = explode(",", $festsTypeIds);
			$fest_name = $details['fest_name'];
			$start_date = $details['start_date'];
			$end_date = $details['end_date'];
			$reg_from = $details['reg_starts_from'];
			$eventIds = rtrim($details['events'], ",");
 			$event_ids = explode(",", $eventIds);
			$weblink = $details['weblink'];
			$highlights = $details['highlights'];									
			$contact_name = $details['contact_name'];
			$contact_number = $details['contact_number'];
			$email_id = $details['contact_mail'];
			$fb_id = $details['fb_id'];			
			$twitter_id = $details['twitter_id'];
			$youtube_link = $details['youtube_link'];
			$posted_by = $details['posted_by'];
			$status_id = "Pending";
                        $timestamp = date('Y-m-d G:i:s');

			$image_string=$details['imageurl'];
                        $today = date("Ymdhis");
                        $rand = strtoupper(substr(uniqid(sha1(time())),0,4));
                        $image = $today . $rand.".png";
                        $path="../admin/images/".$image ;
                        if($details['imageurl']=='null')
                        {
			     $image="-text.png";
                        }

			$date='';
 		}
		if($data[$key]!='')
		{
			$queryFestDetails="INSERT INTO `fest_details`(`user_Id`, `college_map_id`, `name`, `start_date`, `end_date`, `reg_start_from`, `website`, `highlight`, `contact_no`, `contact_name`, `email_id`, `fb_id`, `twitter_id`, `youtube_link`, `image_path`,`status_id`, `created_on`)
 			VALUES ('".$user_id."','".$col_id."','".$fest_name."','".$start_date."','".$end_date."','".$reg_from."','".$weblink."','".$highlights."','".$contact_number."','".$contact_name."','".$email_id."','".$fb_id."','".$twitter_id."','".$youtube_link."','".$image."','".$status_id."','".$timestamp ."')";
			$this->objSql2 = new SqlClass();
			$objSql2 = new SqlClass();
			$resultFestId=$objSql2->getLstInserted($queryFestDetails);
			$fest_id=$resultFestId;
			if($dept_ids!='')
			{
				foreach ($dept_ids as $depts)
				{					
					$queryDept="INSERT INTO `dept_fest_map`(`fest_id`, `dept_id`) VALUES ('".$fest_id."','".$depts."')";				
					$this->objSql2 = new SqlClass();
					$objSql2 = new SqlClass();
					$resultDept=$objSql2->executesql($queryDept);					
				}
			}	
			if($fests_type_ids!='')
			{
				foreach ($fests_type_ids as $fest_type)
				{					
					$queryFest="INSERT INTO `festtype_fest_map`(`fest_id`, `fests_type_id`) VALUES ('".$fest_id."','".$fest_type."')";		
					$this->objSql2 = new SqlClass();
					$objSql2 = new SqlClass();
					$resultFest=$objSql2->executesql($queryFest);					
				}
			}	
			if($event_ids!='')
			{
				foreach ($event_ids as $events)
				{
					$queryEvents="INSERT INTO `events_fest_map`(`fest_id`, `event_id`) VALUES ('".$fest_id."','".$events."')";
					$this->objSql2 = new SqlClass();
					$objSql2 = new SqlClass();
					$resultEvents=$objSql2->executesql($queryEvents);					
				}
			}					
		}		
		if($resultFestId!='')
		{
                                if($details['imageurl']!='null')
                                {
				       file_put_contents($path,base64_decode($image_string));
                                }

			$status="success";
			$obj = (object)array("status"=>$status);
			return $obj;
		}
		else
		{
			$status="fail";
			return $obj = (object)array("status"=>$status);		
		}

 	}

 	public function dbAddMyaddaPost($data, $key)   
 	{
        foreach($data[$key] as $details)
        {
            $userId=$details['user_id'];
            $collegeId=$details['collage_id'];
            $title=$details['ptitle'];
            $desc=$details['postdesc'];
            $postToRadio=$details['radiostatus'];
            $type="MYFORUM";
            $postAsRadio=$details['checkstatus'];
	    
            if($postAsRadio=='0')
            {
            	$postAs="unanonymous";
            }
            elseif($postAsRadio=='1')
            {
            	$postAs="anonymous";	
            }  
          
            if($postToRadio=='My College')
            {
            	$postTo="NOTALL";
            }
            elseif($postToRadio=='Whole Engg.Community')
            {
            	$postTo="ALL";	
            }

            if($postToRadio=='My College')
            {
            	$status="Approved";
            }
            elseif($postToRadio=='Whole Engg.Community')
            {
            	$status="Pending";	
            }
		$image_string=$details['imageurl'];
                        $today = date("Ymdhis");
                        $rand = strtoupper(substr(uniqid(sha1(time())),0,4));
                        $image = $today . $rand.".png";
                        $path="../admin/images/".$image ;
                        if($details['imageurl']=='null')
                        {
			     $image="-text.png";
                        }
                        $timestamp = date('Y-m-d G:i:s');

        }
        $query="INSERT INTO `myadda_post`(`user_id`, `post`, `description`, `type`, `path`,`post_to`, `college_id`, `status_id`, `post_as`, `created_on`,`created_by`) 
		VALUES ('".$userId."','".$title."','".$desc."','".$type."','".$image."','".$postTo."','".$collegeId."', '".$status."','".$postAs."','".$timestamp."','".$userId."')";         
         	 $objSql2 = new SqlClass();
         $result=$objSql2->executesql($query);
        if($result)
        {
              if($details['imageurl']!='null')
                                {
				       file_put_contents($path,base64_decode($image_string));
                                }

	        return $obj = (object)array("status"=>"success");
        }
        else
        {
	        return $obj = (object)array("status"=>"fail");		
        }
 	  	                
 	}
        public function dbGetColDetails()
	{
		$query="select mc_map.*, mc.city_name, md.district_name, ms.state_name FROM master_college_map mc_map join master_city mc join master_district md join master_state ms on mc_map.city_id=mc.id and mc_map.district_id=md.id and mc_map.state_id=ms.id";
		$objSql2 = new SqlClass();
		$result=$objSql2->executesql($query);

		$querySpen="select * from master_specialization";
		$objSql2 = new SqlClass();
		$resultSpen=$objSql2->executesql($querySpen);

		if($result=='no rows')
		{
                     return $msg = (object)array("status"=>"fail");
                     //return $obj =(object)array("collegelist"=>$msg);
		}
                else
		{
			$json_colleges=array();
                        $json_spec=array();
                        if($result!='no rows')
                        {
			      foreach ($result as $data)
			      {				
				$row_array['id']=$data['id'];
				$row_array['name']=$data['college_name'];                               							
				$row_array['city_id']=$data['city_id'];
				$row_array['city_name']=$data['city_name'];
                                $row_array['district_id']=$data['district_id'];								
                                $row_array['district_name']=$data['district_name'];
                                $row_array['state_id']=$data['state_id'];
                                $row_array['state_name']=$data['state_name'];                                
				array_push($json_colleges,$row_array);  
			      }
			      if($resultSpen!='no rows')
			      {
                                   $json_spec=array();
			         foreach ($resultSpen as $spen)
			         {				
				       $spec_array['id']=$spen['id'];
				       $spec_array['name']=$spen['spec_name'];                               											
				       array_push($json_spec,$spec_array);  
			         }
			      }
			      $colleges_obj = (object)array("collegelist"=>$json_colleges, "specializations"=>$json_spec);
			      return $colleges_obj;
                        }
                        else
                        {
                               return $msg = (object)array("status"=>"fail");
                               //return $obj =(object)array("collegelist"=>$msg);
                        }
		 }		
	 }
        public function dbAddStudentQuery($data, $key)   //add student query in career guidence module
 	{
                $timestamp = date('Y-m-d G:i:s');
                foreach($data[$key] as $details)
                {
                      $userId=$details['user_id'];
                      $expertId=$details['exp_id'];
                      $query=$details['query'];
                }
                if($userId!='')
		{
		         $query="INSERT INTO `career_guidence`(`user_id`, `query`, `guidence_from`,`created_on`, `created_by`) VALUES ('".$userId."','".$query."','".$expertId."','".$timestamp."','".$userId."')";
                 	 $objSql2 = new SqlClass();
		         $result=$objSql2->executesql($query);
                         if($result)
		         {
			        return $obj = (object)array("status"=>"1");
		         }
		         else
		         {
			        return $obj = (object)array("status"=>"0");		
		         }
		}
		else
		{
			return $obj = (object)array("status"=>"0");		
		} 	  	                
 	}
        public function dbRegUser($data, $key)   //register user
 	{
                $timestamp = date('Y-m-d G:i:s');
                foreach($data[$key] as $details)
                {
                      $yofgrad=$details['YearPassedOut'];
                      $college=$details['CollegeName'];
                      $phno=$details['ContactNumber'];
                      $cnfpass=$details['ConfirmPassword'];
                      $state=$details['State'];
                      $email=$details['Email'];
                      $pass=$details['Password'];
                      $gender=$details['Gender'];
                      $spec=$details['Specialization'];
                      $fname=$details['FirstName'];
                      $lname=$details['LastName'];
                      $city=$details['City'];
                }
		        $querychk="select * from users where email='".$email."'";
                 	 $objSql2 = new SqlClass();
		         $resultChk=$objSql2->executesql($querychk);

                if($resultChk=='no rows')
		{
$image="-text.png";
		        $queryUd="INSERT INTO `user_details`(`college_map_id`, `fname`, `lname`, `gender`, `grad_year`, `spec_id`) VALUES ('".$college."','".$fname."','".$lname."','".$gender."','".$yofgrad."','".$spec."')";
                 	 $objSql2 = new SqlClass();
		         $ud_id=$objSql2->getLstInserted($queryUd);
                         if($ud_id!='')
		         {

		         $queryU="INSERT INTO `users`(`user_details_id`, `email`, `phno`, `password`, `password_salt`, `image_path`, `status_id`, `source`, `created_on`) VALUES ('".$ud_id."','".$email."','".$phno."','".$pass."','".$cnfpass."','".$image."','Pending','form','".$timestamp."')";
                 	 $objSql2 = new SqlClass();
		         $resultU=$objSql2->executesql($queryU);
			 if($resultU)
			 {
			        return $obj = (object)array("status"=>"1");
			 }
		         else
		         {
			        return $obj = (object)array("status"=>"0");		
		         }

		         }
		         else
		         {
			        return $obj = (object)array("status"=>"0");		
		         }
		}
		else
		{
			return $obj = (object)array("status"=>"exist");		
		} 	  	                
 	}
        public function dbGetExptListToPostStuQuery() //To get experts list for career guidence
	{
		$query="SELECT * FROM `master_expert`";
		$objSql2 = new SqlClass();
		$result=$objSql2->executesql($query);
		if($result=='no rows')
		{
                     return $msg = (object)array("status"=>"fail");
                     //return $obj =(object)array("ExptList"=>$msg);
		}
                else
		{
			$json_experts=array();
                        if($result!='no rows')
                        {
			      foreach ($result as $data)
			      {				
				$row_array['id']=$data['id'];
				$row_array['name']=$data['name'].','.$data['qualification'];                               											                             
				array_push($json_experts,$row_array);  
			      }
			      $experts_obj = (object)array("ExptList"=>$json_experts);
			      return $experts_obj;
                        }
                        else
                        {
                               return $msg = (object)array("status"=>"fail");
                        }
		}		
	 }
        public function dbGetAlertMasterData() //To get master data for fest alert settings
	{
		$query_states="SELECT * FROM `master_state`";
		$objSql2 = new SqlClass();
		$resultSates=$objSql2->executesql($query_states);

		$query_districts="SELECT * FROM `master_district`";
		$objSql2 = new SqlClass();
		$resultDistricts=$objSql2->executesql($query_districts);

		$query_cities="SELECT * FROM `master_city`";
		$objSql2 = new SqlClass();
		$resultCities=$objSql2->executesql($query_cities);

		$query_university="SELECT * FROM `master_university`";
		$objSql2 = new SqlClass();
		$resultUnis=$objSql2->executesql($query_university);

		$query_colleges="SELECT * FROM `master_college_map`";
		$objSql2 = new SqlClass();
		$resultColleges=$objSql2->executesql($query_colleges);

		$query_ftype="SELECT * FROM `master_fest_type`";
		$objSql2 = new SqlClass();
		$resultFtypes=$objSql2->executesql($query_ftype);

		$query_dept="SELECT * FROM `master_specialization`";
		$objSql2 = new SqlClass();
		$resultDepts=$objSql2->executesql($query_dept);

		$query_event="SELECT * FROM `master_events`";
		$objSql2 = new SqlClass();
		$resultEvents=$objSql2->executesql($query_event);

		$query_cats="SELECT * FROM `master_item_category`";
		$objSql2 = new SqlClass();
		$resultCats=$objSql2->executesql($query_cats);

		$query_months="SELECT * FROM `master_months`";
		$objSql2 = new SqlClass();
		$resultMonths=$objSql2->executesql($query_months);

		if($result=='no rows')
		{
                     return $msg = (object)array("status"=>"fail");
                     //return $obj =(object)array("ExptList"=>$msg);
		}
                else
		{
			$json_states=array();
			$json_districts=array();
			$json_cities=array();
			$json_unis=array();
			$json_colleges=array();
			$json_ftypes=array();
			$json_depts=array();
			$json_events=array();
			$json_cat=array();
			$json_subcat=array();
			$json_months=array();
                        if($resultSates!='no rows')
                        {
			      foreach ($resultSates as $states)
			      {				
				$state_array['id']=$states['id'];
				$state_array['name']=$states['state_name'];                               											                             
				array_push($json_states,$state_array);  
			      }
                        }
                        if($resultDistricts!='no rows')
                        {
			      foreach ($resultDistricts as $districts)
			      {				
				$district_array['id']=$districts['id'];
				$district_array['state_id']=$districts['s_id'];
				$district_array['name']=$districts['district_name'];                               											                             
				array_push($json_districts,$district_array);  
			      }
                        }
                        if($resultCities!='no rows')
                        {
			      foreach ($resultCities as $cities)
			      {				
				$cities_array['id']=$cities['id'];
				$cities_array['district_id']=$cities['district_id'];
				$cities_array['state_id']=$cities['state_id'];
				$cities_array['name']=$cities['city_name'];                               											                             
				array_push($json_cities,$cities_array);  
			      }
                        }
                        if($resultUnis!='no rows')
                        {
			      foreach ($resultUnis as $universities)
			      {				
				$uni_array['id']=$universities['id'];
				$uni_array['city_id']=$universities['city_id'];
				$uni_array['district_id']=$universities['district_id'];
				$uni_array['state_id']=$universities['state_id'];
				$uni_array['name']=$universities['name'];                               											                             
				array_push($json_unis,$uni_array);  
			      }
                        }
                        if($resultColleges!='no rows')
                        {
			      foreach ($resultColleges as $colleges)
			      {				
				$col_array['id']=$colleges['id'];
				$col_array['univ_id']=$colleges['university_id'];
				$col_array['city_id']=$colleges['city_id'];
				$col_array['district_id']=$colleges['district_id'];
				$col_array['state_id']=$colleges['state_id'];
				$col_array['university_id']=$colleges['university_id'];
				$col_array['college_type']=$colleges['college_type'];
				$col_array['name']=$colleges['college_name'];                               											                             
				array_push($json_colleges,$col_array);  
			      }
                        }
                        if($resultFtypes!='no rows')
                        {
			      foreach ($resultFtypes as $festTypes)
			      {				
				$ftype_array['id']=$festTypes['id'];
				$ftype_array['name']=$festTypes['fest_type'];                               											                             
				array_push($json_ftypes,$ftype_array);  
			      }
                        }
                        if($resultDepts!='no rows')
                        {
			      foreach ($resultDepts as $depts)
			      {				
				$dept_array['id']=$depts['id'];
				$dept_array['name']=$depts['spec_name'];                               											                             
				array_push($json_depts,$dept_array);  
			      }
                        }
                        if($resultEvents!='no rows')
                        {
			      foreach ($resultEvents as $events)
			      {				
				$event_array['id']=$events['id'];
				$event_array['name']=$events['event_name'];                               											                             
				array_push($json_events,$event_array);  
			      }
                        }
                        if($resultMonths!='no rows')
                        {
			      foreach ($resultMonths as $months)
			      {				
				$month_array['id']=$months['id'];
				$month_array['name']=$months['name'];                               											                             
				array_push($json_months,$month_array);  
			      }
                        }
                        if($resultCats!='no rows')
                        {
			      foreach ($resultCats as $cats)
			      {				
				$cat_id=$cats['id'];
                                $querysubcat="select * from master_item_subcat where cat_id='".$cat_id."' order by id desc";
                                $objSql2 = new SqlClass();
                                $resultsubcat=$objSql2->executesql($querysubcat);
 			        $cat_array['id']=$cats['id'];
 			        $cat_array['name']=$cats['cat_name'];
 			        //$cat_array['subcat']=$cats['id']
                                $json_subcat=array();
                                if($resultsubcat!='no rows')
                                {
                                  foreach($resultsubcat as $subcatdata)
                                  {
                                     $subcat_array['id'] = $subcatdata['id'];
                                     $subcat_array['name'] = $subcatdata['subcat_name'];
                                     array_push($json_subcat,$subcat_array);
                                  }
                                }
 			        $cat_array['subcat']=$json_subcat;			
				array_push($json_cat,$cat_array);  
			      }
                        }

			      $experts_obj = (object)array("states"=>$json_states, "Districts"=>$json_districts, "cities"=>$json_cities, "University"=>$json_unis,
"colleges"=>$json_colleges, "TypeOfFests"=>$json_ftypes, "DeptList"=>$json_depts, "MeventsList"=>$json_events, "months"=>$json_months, "categeoryList"=>$json_cat);
			      return $experts_obj;

                        
		}		
	 }
        public function dbGetUserProfile($user_id) //To get user profile
	{
		$query="select u.id as uid, ud.fname, ud.lname, u.email, u.phno, u.image_path, ud.gender, ud.study_year, ud.grad_year, ms.spec_name, ms.id as spec_id, mc.college_name, mc.id as col_id from users u join user_details ud join master_specialization ms join master_college_map mc where u.id='".$user_id."' and u.user_details_id=ud.id and ud.spec_id=ms.id and ud.college_map_id=mc.id";
		$objSql2 = new SqlClass();
		$result=$objSql2->executesql($query);

		$querySpec="select * from master_specialization";
		$objSql2 = new SqlClass();
		$resultSpec=$objSql2->executesql($querySpec);

			$json_spec=array();
                        if($resultSpec!='no rows')
                        {
			      foreach ($resultSpec as $spec)
			      {				
				$spec_array['id']=$spec['id'];
				$spec_array['name']=$spec['spec_name'];                               											                             
				array_push($json_spec,$spec_array);  
			      }			     
                        }

		if($result=='no rows')
		{
                     return $msg = (object)array("status"=>"fail");
                     //return $obj =(object)array("userdetails"=>$msg);
		}
                else
		{
			$json_userdata=array();
                        if($result!='no rows')
                        {
			      foreach ($result as $data)
			      {				
				$row_array['id']=$data['uid'];
				$row_array['name']=$data['fname'].','.$data['lname'];                               											                             
				$row_array['gender']=$data['gender'];
				$row_array['email']=$data['email'];
				$row_array['phno']=$data['phno'];
				$image=$data['image_path'];
				$row_array['image'] = "http://alabs.in/proddy_final/admin/images/".$image;
				$row_array['spec_id']=$data['spec_id'];
				$row_array['spec_name']=$data['spec_name'];
				$row_array['college_id']=$data['col_id'];
				$row_array['college_name']=$data['college_name'];
				$row_array['passing_year']=$data['grad_year'];
				$row_array['study_year']=$data['study_year'];
				array_push($json_userdata,$row_array);  
			      }
			      $json_resp = (object)array("userdetails"=>$json_userdata, "specializations"=>$json_spec);
			      return $json_resp ;
                        }
                        else
                        {
                               return $msg = (object)array("status"=>"fail");
                        }
		}		
	 }
	public function dbSaveFestSettings($data, $key)
 	{
 		foreach ($data[$key] as $details)
 		{
 			$user_id=$details['user_id'];
 			$state_ids = $details['state_id'];
			$district_ids = $details['district_id'];
			$city_ids = $details['city_id'];
			$months_ids = $details['month'];
			$universitys = $details['university'];
			$college_ids = $details['college_id'];									
			$festtypes = $details['festtype'];
			$departments = $details['departments'];
			$majorEvents = $details['majorEvents'];
			$timestamp = date('Y-m-d G:i:s');

                        $deptIds = rtrim($details['depts'], ",");
 			$dept_ids = explode(",", $deptIds );
			$festsTypeIds = rtrim($details['fest_type'], ",");
 			$fests_type_ids = explode(",", $festsTypeIds);
 		}
 		if($state_ids!='' || $district_ids!='' || $city_ids!='' || $months_ids!='' || $universitys!='' || $college_ids!='' || $festtypes!='' || $departments!='' || $majorEvents!='')
 		{
 			$set_to='FILTER';
 		}
 		else
 		{
 			$set_to='ALL';
 		}
		if($data[$key]!='')
		{
			$queryFestSettings="INSERT INTO `fest_settings`(`user_id`, `set_to`, `created_on`)
			 VALUES ('".$user_id."','".$set_to."','".$timestamp."')";
			$this->objSql2 = new SqlClass();
			$objSql2 = new SqlClass();
			$resultFsId=$objSql2->getLstInserted($queryFestSettings);
			$fset_id=$resultFsId;
			if($state_ids!='')
			{
                                $stateIds = rtrim($state_ids, ",");
 	          		$st_ids = explode(",", $stateIds);
				foreach ($st_ids as $states)
				{					
					$queryState="INSERT INTO `fset_state_map`(`fset_id`, `state_id`) VALUES ('".$fset_id."','".$states."')";				
					$this->objSql2 = new SqlClass();
					$objSql2 = new SqlClass();
					$resultState=$objSql2->executesql($queryState);					
				}
			}	
			if($district_ids!='')
			{
                                $DistrictIds= rtrim($district_ids, ",");
 	          		$District_Ids = explode(",", $DistrictIds);
				foreach ($District_Ids as $districts)
				{					
					$queryDistrict="INSERT INTO `fset_district_map`(`fset_id`, `district_id`) VALUES ('".$fset_id."','".$districts."')";				
					$this->objSql2 = new SqlClass();
					$objSql2 = new SqlClass();
					$resultDistrict=$objSql2->executesql($queryDistrict);					
				}
			}	
			if($city_ids!='')
			{
                                $City_Ids= rtrim($city_ids, ",");
 	          		$city_Ids= explode(",", $City_Ids);
				foreach ($city_Ids as $cities)
				{					
					$queryCity="INSERT INTO `fset_city_map`(`fset_id`, `city_id`) VALUES ('".$fset_id."','".$cities."')";				
					$this->objSql2 = new SqlClass();
					$objSql2 = new SqlClass();
					$resultCity=$objSql2->executesql($queryCity);					
				}
			}	
			if($departments!='')
			{
                                $deptIds = rtrim($departments , ",");
 	          		$dept_ids = explode(",", $deptIds);
				foreach ($dept_ids as $depts)
				{					
					$queryDept="INSERT INTO `fset_dept_map`(`fset_id`, `dept_id`) VALUES ('".$fset_id."','".$depts."')";				
					$this->objSql2 = new SqlClass();
					$objSql2 = new SqlClass();
					$resultDept=$objSql2->executesql($queryDept);					
				}
			}				
			if($majorEvents!='')
			{
                                $major_Events= rtrim($majorEvents, ",");
 	          		$mEvents = explode(",", $major_Events);
				foreach ($mEvents as $events)
				{
					$queryEvents="INSERT INTO `fset_event_map`(`fset_id`, `event_id`) VALUES ('".$fset_id."','".$events."')";
					$this->objSql2 = new SqlClass();
					$objSql2 = new SqlClass();
					$resultEvents=$objSql2->executesql($queryEvents);					
				}
			}			
			if($college_ids!='')
			{
                                $college_Ids= rtrim($college_ids, ",");
 	          		$colIds = explode(",", $college_Ids);
				foreach ($colIds as $college_id)
				{					
					$queryCollege="INSERT INTO `fset_college_map`(`fset_id`, `college_id`) VALUES ('".$fset_id."','".$college_id."')";				
					$this->objSql2 = new SqlClass();
					$objSql2 = new SqlClass();
					$resultCollege=$objSql2->executesql($queryCollege);					
				}
			}
			if($months_ids!='')
			{
                                $mon_ids= rtrim($months_ids, ",");
 	          		$monIds= explode(",", $mon_ids);
				foreach ($monIds as $month_id)
				{					
					$queryMonth="INSERT INTO `fset_months_map`(`fset_id`, `month_id`) VALUES ('".$fset_id."','".$month_id."')";				
					$this->objSql2 = new SqlClass();
					$objSql2 = new SqlClass();
					$resultMonth=$objSql2->executesql($queryMonth);					
				}
			}							
		}
		if($resultFsId!='')
		{        
			$status="success";
			$obj = (object)array("status"=>$status);
			return $obj;
		}
		else
		{
			$status="fail";
			return $obj = (object)array("status"=>$status);		
		}

 	}       
	public function dbSaveBuySellSettings($data, $key)
 	{
 		foreach ($data[$key] as $details)
 		{
 			$user_id=$details['user_id'];
 			$cat_ids = $details['cat'];
			$subcat_ids = $details['subcat'];
			$city_ids = $details['city'];
			$price = $details['price'];
			$min_price='200';
			$max_price='500';			
			$timestamp = date('Y-m-d G:i:s');
 		}
 		if($cat_ids!='' || $subcat_ids!='' || $city_ids!='')
 		{
 			$set_to='FILTER';
 		}
 		else
 		{
 			$set_to='ALL';
 		}
		if($data[$key]!='')
		{
			$queryItemSettings="INSERT INTO `buysell_item_settings`(`user_id`, `min_price`, `max_price`, `set_to`, `created_on`) 
			 VALUES ('".$user_id."','".$min_price."','".$max_price."','".$set_to."','".$timestamp."')";
			$this->objSql2 = new SqlClass();
			$objSql2 = new SqlClass();
			$resultIsId=$objSql2->getLstInserted($queryItemSettings);
			$iset_id=$resultIsId;
			if($cat_ids!='')
			{
                                $catIds= rtrim($cat_ids, ",");
 	          		$cIds= explode(",", $catIds);
				foreach ($cIds as $cat)
				{					
					$queryCat="INSERT INTO `iset_cat_map`(`iset_id`, `cat_id`) VALUES ('".$iset_id."','".$cat."')";				
					$this->objSql2 = new SqlClass();
					$objSql2 = new SqlClass();
					$resultCat=$objSql2->executesql($queryCat);					
				}
			}	
			if($subcat_ids!='')
			{
                                $subcatIds= rtrim($subcat_ids, ",");
 	          		$s_catIds= explode(",", $subcatIds);
				foreach ($s_catIds as $subcat)
				{					
					$querySubcat="INSERT INTO `iset_subcat_map`(`iset_id`, `subcat_id`) VALUES ('".$iset_id."','".$subcat_ids."')";				
					$this->objSql2 = new SqlClass();
					$objSql2 = new SqlClass();
					$resultSubcat=$objSql2->executesql($querySubcat);					
				}
			}							
			if($city_ids !='')
			{
                                $cityIds= rtrim($city_ids , ",");
 	          		$city_Ids= explode(",", $cityIds);
				foreach ($city_Ids as $cityid)
				{					
					$queryLoc="INSERT INTO `iset_loc_map`(`iset_id`, `city_id`) VALUES ('".$iset_id."','".$cityid."')";				
					$this->objSql2 = new SqlClass();
					$objSql2 = new SqlClass();
					$resultLoc=$objSql2->executesql($queryLoc);					
				}
			}							

		}
		if($resultIsId!='')
		{        
			$status="success";
			$obj = (object)array("status"=>$status);
			return $obj;
		}
		else
		{
			$status="fail";
			return $obj = (object)array("status"=>$status);		
		}

 	}       
public function dbGetItemDetailsToEdit($id, $user_id)
 	{	
if($id!='')
{
 		$status="fail";
		$message="No data found";
 		$query="select i.*, i.id as item_id, mi_cat.cat_name, mi_subcat.subcat_name, m_city.city_name, m_city.id as city_id, md.district_name, md.id as did, mc_map.college_name, mc_map.id as college_id from items i join master_city m_city join master_district md join master_item_category mi_cat join master_item_subcat mi_subcat join master_college_map mc_map where i.id='".$id."' and i.cat_id=mi_cat.id and i.sub_cat_id=mi_subcat.id and i.college_map_id=mc_map.id and mc_map.city_id=m_city.id and mc_map.district_id=md.id "; 				 		 		 				 		 		
		$objSql2 = new SqlClass();
		$result=$objSql2->executesql($query);		

		$query_cats="SELECT * FROM `master_item_category`";
		$objSql2 = new SqlClass();
		$resultCats=$objSql2->executesql($query_cats);

		$query_states="SELECT * FROM `master_state`";
		$objSql2 = new SqlClass();
		$resultSates=$objSql2->executesql($query_states);

		$query_districts="SELECT * FROM `master_district`";
		$objSql2 = new SqlClass();
		$resultDistricts=$objSql2->executesql($query_districts);

		$query_cities="SELECT * FROM `master_city`";
		$objSql2 = new SqlClass();
		$resultCities=$objSql2->executesql($query_cities);

			$json_states=array();
			$json_districts=array();
			$json_cities=array();
                        if($resultSates!='no rows')
                        {
			      foreach ($resultSates as $states)
			      {				
				$state_array['state_id']=$states['id'];
				$state_array['state_name']=$states['state_name'];                               											                             
				array_push($json_states,$state_array);  
			      }
                        }
                        if($resultDistricts!='no rows')
                        {
			      foreach ($resultDistricts as $districts)
			      {				
				$district_array['district_id']=$districts['id'];
				$district_array['state_id']=$districts['s_id'];
				$district_array['district_name']=$districts['district_name'];                               											                             
				array_push($json_districts,$district_array);  
			      }
                        }
                        if($resultCities!='no rows')
                        {
			      foreach ($resultCities as $cities)
			      {				
				$cities_array['city_id']=$cities['id'];
				$cities_array['district_id']=$cities['district_id'];
				$cities_array['city_name']=$cities['city_name'];                               											                             
				array_push($json_cities,$cities_array);  
			      }
                        }


		if($result == 'no rows')
		{
			return $msg = (object)array("status"=>"fail");
                        //return $obj =(object)array("item_details"=>$msg);
		}
		else
		{
		    $json_response=array();
		    foreach($result as $data)
			{  	
				$row_array['id'] = $data['item_id'];
				$row_array['title'] = $data['itemname'];
                                $row_array['category'] = $data['cat_name'];
                                $row_array['subcategory'] = $data['subcat_name'];
                                $row_array['cat_id'] = $data['cat_id'];
                                $row_array['subcat_id'] = $data['sub_cat_id'];
				$row_array['type'] = $data['type'];
				$row_array['description'] = $data['description'];
				$image = $data['image_path'];
				$row_array['image'] = "http://alabs.in/proddy_final/admin/images/".$image;
				$row_array['price'] = $data['price'];
$nagotiable=$data['nagotiable'];						
if($nagotiable=='yes' && $nagotiable!='')
{
      $row_array['negotiable'] = "Negotiable";						
}
else if($nagotiable=='no' && $nagotiable!='')
{
       $row_array['negotiable'] = "Non Negotiable";	
}
				$row_array['college_id'] = $data['college_id'];				
				$row_array['collegename'] = $data['college_name'];
				$row_array['location'] = $data['homearea'];
				$row_array['district_id'] = $data['did'];
				$row_array['district'] = $data['district_name'];
				$row_array['city_id'] = $data['city_id'];
				$row_array['city'] = $data['city_name'];
				$row_array['contactnumber'] = $data['hideandshow'];

				$row_array['date'] = $data['created_on'];
                                //$row_array['imageurl']="http://alabs.in/proddy/images/1.jpg";								

		        array_push($json_response,$row_array);   
			}	

                        if($resultCats!='no rows')
                        {
$json_cat=array();
			      foreach ($resultCats as $cats)
			      {				
				$cat_id=$cats['id'];
                                $querysubcat="select * from master_item_subcat where cat_id='".$cat_id."' order by id desc";
                                $objSql2 = new SqlClass();
                                $resultsubcat=$objSql2->executesql($querysubcat);
 			        $cat_array['id']=$cats['id'];
 			        $cat_array['name']=$cats['cat_name'];
 			        //$cat_array['subcat']=$cats['id']
                                $json_subcat=array();
                                if($resultsubcat!='no rows')
                                {
                                  foreach($resultsubcat as $subcatdata)
                                  {
                                     $subcat_array['id'] = $subcatdata['id'];
                                     $subcat_array['name'] = $subcatdata['subcat_name'];
                                     array_push($json_subcat,$subcat_array);
                                  }
                                }
 			        $cat_array['subcat']=$json_subcat;			
				array_push($json_cat,$cat_array);  
			      }
                        }

			$obj = (object)array("editBuySellPosts"=>$json_response, "categoryList"=>$json_cat, "states"=>$json_states, "Districts"=>$json_districts, "cities"=>$json_cities);
			return $obj;
		}	
}
else
{
return $msg = (object)array("status"=>"fail");
//return $obj =(object)array("item_details"=>$msg);
}
 	}
        public function dbEditUserProfile($data, $key)   //edit user profile
 	{
                $timestamp = date('Y-m-d G:i:s');
                foreach($data[$key] as $details)
                {
                      $user_id=$details['user_id'];
                      $image=$details['image'];
                      $name=$details['uname'];
                      $gender=$details['gender'];
                      $phno=$details['phonenumber'];
                      $email=$details['email'];
                      $spec=$details['spec'];
                      $yofgrad=$details['PassYear'];
                      $yofstudy=$details['YearOfStudy'];
                }
		        $querychk="select * from users where id='".$user_id."'";
                 	 $objSql2 = new SqlClass();
		         $resultChk=$objSql2->executesql($querychk);
		         $ud_id=$resultChk[0]['id'];

                if($user_id!='' && $ud_id!='')
		{
		        $queryUd="UPDATE `user_details` SET `fname`='".$name."',`lname`='', `gender`='".$gender."',`study_year`='".$yofstudy."',`grad_year`='".$yofgrad."',`spec_id`='".$spec."' WHERE id='".$ud_id."'";
                 	 $objSql2 = new SqlClass();
		         $resultUd=$objSql2->executesql($queryUd);
                         if($ud_id!='')
		         {
if($image!='null')
{
                     

   $image_string=$image;
                        $today = date("Ymdhis");
                        $rand = strtoupper(substr(uniqid(sha1(time())),0,4));
                        $image = $today . $rand.".png";
                        $path="../admin/images/".$image ;
$queryU="UPDATE `users` SET `email`='".$email."', image_path='".$image."', `phno`='".$phno."' WHERE id='".$user_id."'";
}
else if($image=='null')
{
$queryU="UPDATE `users` SET `email`='".$email."', `phno`='".$phno."' WHERE id='".$user_id."'";
}

 		            
                 	    $objSql2 = new SqlClass();
		            $resultU=$objSql2->executesql($queryU);
			    if($resultU)
			    {
if($image!='null')
{
                                file_put_contents($path,base64_decode($image_string));
}
			        return $obj = (object)array("status"=>"1");
			    }
		            else
		            {
			        return $obj = (object)array("status"=>"0");		
		            }

		         }
		         else
		         {
			        return $obj = (object)array("status"=>"0");		
		         }
		}
		else
		{
			return $obj = (object)array("status"=>"0");		
		} 	  	                
 	}
        public function dbGetFestdetailsToEdit($festId)
	{

$master_data=$this->dbGetFestMasterData();
//print_r($master_data);
		$queryfestinfo="select ud.fname, ud.lname, fd.*, fd.id as fid, mc_map.college_name, mc_map.id as col_id from fest_details fd join master_college_map mc_map join users u join user_details ud on fd.id='".$festId."' and fd.college_map_id=mc_map.id and fd.user_Id=u.id and u.user_details_id=ud.id";
		$objSql2 = new SqlClass();
		$resultFestInfo=$objSql2->executesql($queryfestinfo);		

                $queryfesttype="select mft.id, mft.fest_type, ft_map.fest_id from festtype_fest_map ft_map join master_fest_type mft where ft_map.fest_id='".$festId."' and ft_map.fests_type_id=mft.id";
		$objSql2 = new SqlClass();
		$resultFestType=$objSql2->executesql($queryfesttype);
                $querydept="select ms.id, ms.spec_name from dept_fest_map dpt_map join master_specialization ms where dpt_map.fest_id='".$festId."' and dpt_map.dept_id=ms.id";
		$objSql2 = new SqlClass();
		$resultdept=$objSql2->executesql($querydept);

                $queryEvents = "select me.id, me.event_name from master_events me join events_fest_map eve_map where eve_map.fest_id='".$festId."' and eve_map.event_id=me.id";
		$objSql2 = new SqlClass();
		$resultEvents=$objSql2->executesql($queryEvents);
$festtype=array();
$dept_array=array();
$event_array=array();
$json_fests=array();
		if($resultFestType!= 'no rows')
		{
     			foreach ($resultFestType as $ftypes)
     			{
				$festtype[]=$ftypes['id'];				
     			}
                        $fest_type_id=implode(",",$festtype);
		}

		if($resultdept!= 'no rows')
		{
     			foreach ($resultdept as $fdepts)
     			{
				$dept_array[]=$fdepts['id'];
     			}
                        $dept_id=implode(",",$dept_array);
		}

		if($resultEvents!= 'no rows')
		{
     			foreach ($resultEvents as $fevents)
     			{
				$event_array[]=$fevents['id'];
     			}
                        $event_ids=implode(",",$event_array);
		}

		if($resultFestInfo!= 'no rows')
		{
     			foreach ($resultFestInfo as $festinfo)
     			{
				$fest_array['user_id']=$festinfo['user_Id'];
				$fest_array['fest_name']=$festinfo['name'];
				$fest_array['fest_type']=$fest_type_id;
				$image=$festinfo['image_path'];
				$fest_array['image']="http://alabs.in/proddy_final/admin/images/".$image;
				$fest_array['college_map_id']=$festinfo['college_map_id'];
				$fest_array['col_name']=$festinfo['college_name'];
				$fest_array['start_date']=$festinfo['start_date'];
				$fest_array['end_date']=$festinfo['end_date'];
				$fest_array['depts']=$dept_id;
				$fest_array['fb_id']=$festinfo['fb_id'];
				$fest_array['events']=$event_ids;
				$fest_array['reg_starts_from']=$festinfo['reg_start_from'];
				$fest_array['weblink']=$festinfo['website'];
				$fest_array['highlights']=$festinfo['highlight'];
				$fest_array['twitter_id']=$festinfo['twitter_id'];
				$fest_array['youtube_link']=$festinfo['youtube_link'];
				$fest_array['contact_name']=$festinfo['contact_name'];
				$fest_array['contact_number']=$festinfo['contact_no'];
				$fest_array['contact_mail']=$festinfo['email_id'];
          			array_push($json_fests,$fest_array);
     			}

		}

		if($resultFestInfo== 'no rows')
		{
                     return $msg = (object)array("status"=>"fail");
                     //return $obj =(object)array("festdetails"=>$msg);
		}
	 	else
	 	{
$masterData=get_object_vars($master_data);
$festtypes=$masterData['TypeOfFests'];
$depts=$masterData['DeptList'];
$collist=$masterData['CollegesList'];
$events=$masterData['MeventsList'];
		        return	$obj= (object)array("festdetails"=>$json_fests, "TypeOfFests"=>$festtypes, "DeptList"=>$depts,"CollegesList"=>$collist, "MeventsList"=>$events);
		}
	}

	public function dbEditFests($data, $key)
 	{
 		foreach ($data[$key] as $details)
 		{
 			$user_id=$details['user_id'];
 			$col_id = $details['col_map_id'];
 			$fest_id = $details['fest_id'];
 			$deptIds = rtrim($details['depts'], ",");
 			$dept_ids = explode(",", $deptIds );
			$festsTypeIds = rtrim($details['fest_type'], ",");
 			$fests_type_ids = explode(",", $festsTypeIds);
			$fest_name = $details['fest_name'];
			$start_date = $details['start_date'];
			$end_date = $details['end_date'];
			$reg_from = $details['reg_starts_from'];
			$eventIds = rtrim($details['events'], ",");
 			$event_ids = explode(",", $eventIds);
			$weblink = $details['weblink'];
			$highlights = $details['highlights'];									
			$contact_name = $details['contact_name'];
			$contact_number = $details['contact_number'];
			$email_id = $details['contact_mail'];
			$fb_id = $details['fb_id'];			
			$twitter_id = $details['twitter_id'];
			$youtube_link = $details['youtube_link'];
			$posted_by = $details['user_id'];
			$status_id = "Pending";
                        $timestamp = date('Y-m-d G:i:s');

			$image_string=$details['imageurl'];
                        $today = date("Ymdhis");
                        $rand = strtoupper(substr(uniqid(sha1(time())),0,4));
                        $image = $today . $rand.".png";
                        $path="../admin/images/".$image ;
                        if($details['imageurl']=='null')
                        {
			     $image="null";
                        }
			$date='';
 		}
		if($data[$key]!='')
		{
			$queryFestDetails="INSERT INTO `fest_details_duplicate`(`user_Id`, `fest_id`, `college_map_id`, `name`, `start_date`, `end_date`, `reg_start_from`, `website`, `highlight`, `contact_no`, `contact_name`, `email_id`, `fb_id`, `twitter_id`, `youtube_link`, `image_path`,`status_id`, `created_on`)
 			VALUES ('".$user_id."','".$fest_id."','".$col_id."','".$fest_name."','".$start_date."','".$end_date."','".$reg_from."','".$weblink."','".$highlights."','".$contact_number."','".$contact_name."','".$email_id."','".$fb_id."','".$twitter_id."','".$youtube_link."','".$image."','".$status_id."','".$timestamp ."')";
			$this->objSql2 = new SqlClass();
			$objSql2 = new SqlClass();
			$resultFestId=$objSql2->getLstInserted($queryFestDetails);
			$fest_id=$resultFestId;
			if($dept_ids!='')
			{
				foreach ($dept_ids as $depts)
				{					
					$queryDept="INSERT INTO `dept_fest_map_duplicate`(`fest_id`, `dept_id`) VALUES ('".$fest_id."','".$depts."')";				
					$this->objSql2 = new SqlClass();
					$objSql2 = new SqlClass();
					$resultDept=$objSql2->executesql($queryDept);					
				}
			}	
			if($fests_type_ids!='')
			{
				foreach ($fests_type_ids as $fest_type)
				{					
					$queryFest="INSERT INTO `festtype_fest_map_duplicate`(`fest_id`, `fests_type_id`) VALUES ('".$fest_id."','".$fest_type."')";		
					$this->objSql2 = new SqlClass();
					$objSql2 = new SqlClass();
					$resultFest=$objSql2->executesql($queryFest);					
				}
			}	
			if($event_ids!='')
			{
				foreach ($event_ids as $events)
				{
					$queryEvents="INSERT INTO `events_fest_map_duplicate`(`fest_id`, `event_id`) VALUES ('".$fest_id."','".$events."')";
					$this->objSql2 = new SqlClass();
					$objSql2 = new SqlClass();
					$resultEvents=$objSql2->executesql($queryEvents);					
				}
			}					
		}		
		if($resultFestId!='')
		{
                                if($details['imageurl']!='null')
                                {
				       file_put_contents($path,base64_decode($image_string));
                                }

			$status="success";
			$obj = (object)array("status"=>$status);
			return $obj;
		}
		else
		{
			$status="fail";
			return $obj = (object)array("status"=>$status);		
		}
 	}
 	public function dbEditItems($data,$key)
	{
		foreach ($data[$key] as $details)
		{
			$user_id=$details['user_id'];
			$item_id=$details['itemId'];
			$item_name=$details['itemname'];
			$item_desc=$details['itemDesc'];
			$cat_id=$details['cat'];
			$subcat_id=$details['subcat'];
			$phno=$details['user_phone'];
			$type=$details['type'];
			$price=$details['price'];
			$email=$details['user_email'];
			$priceStatus=$details['priceStatus'];
			if($priceStatus=='Negotiable')
			{
				$priceStatus="yes";
			}
			else
			{
				$priceStatus="no";
			}
			$city_id=$details['city'];
			$college_id=$details['user_cid'];
			$area=$details['area'];
			$show=$details['show'];
			$status_id="Pending";
			$image_string=$details['imageurl'];
                        $today = date("Ymdhis");
                        $rand = strtoupper(substr(uniqid(sha1(time())),0,4));
                        $image = $today . $rand.".png";
                        $path="../admin/images/".$image ;
                        if($details['imageurl']=='null')
                        {
			     $image="null";
                        }
			$contact_status="static";
                        $timestamp = date('Y-m-d G:i:s');
			$query="INSERT INTO `items_duplicate`(`item_id`, `user_id`, `college_map_id`, `address_id`, `cat_id`, `sub_cat_id`,
			 `status_id`, `itemname`, `description`, `email`,`hideandshow`, `homearea`,`image_path`, `type`, `price`,
			  `nagotiable`,`contact_no`, `contact_status`, `created_on`) VALUES ('".$item_id."','".$user_id."','".$college_id."','".$city_id."','".$cat_id."','".$subcat_id."',
					'".$status_id."','".$item_name."','".$item_desc."','".$email."', '".$show."','".$area."','".$image."',
					'".$type."','".$price."','".$priceStatus."','".$phno."','".$contact_status."','".$timestamp ."')";
			$objSql2 = new SqlClass();
			$result=$objSql2->executesql($query);
			if($result)
			{
                                if($details['imageurl']!='null')
                                {
				       file_put_contents($path,base64_decode($image_string));
                                }
				return $obj = (object)array("status"=>"success");
			}
			else
			{
				return $obj = (object)array("status"=>"fail");		
			}
		}
	}
    public function dbDeleteFest($data,$key)
	{
		foreach ($data[$key] as $details)
		{
			$user_id=$details['user_id'];
			$fest_id=$details['fest_id'];		
			$query="UPDATE `fest_details` SET delete_status='Pending' where id='".$fest_id."' and user_Id='".$user_id."'";
			$objSql2 = new SqlClass();
			$result=$objSql2->executesql($query);
			if($result)
			{                                
				return $obj = (object)array("status"=>"success");
			}
			else
			{
				return $obj = (object)array("status"=>"fail");		
			}
		}
	}
	public function dbGetPostEditData($post_id)
 	{	
			if($post_id!='')
			{ 		
		 		$query="SELECT * FROM `myadda_post` where id='".$post_id."'"; 				 		 		 				 		 		
				$objSql2 = new SqlClass();
				$result=$objSql2->executesql($query);		
				$json_states=array();
				if($result == 'no rows')
				{
					return $msg = (object)array("status"=>"fail");
		            //return $obj =(object)array("item_details"=>$msg);
				}
				else
				{
				    $json_response=array();
				    foreach($result as $data)
					{  	
						$row_array['post_id'] = $data['id'];
						$row_array['post_title'] = $data['post'];
		                $row_array['status'] = $data['post_to'];
		                $row_array['post_desc'] = $data['description'];
		                $row_array['checkstatus'] = $data['post_as'];
		                $image = $data['path'];
				$row_array['imageurl']="http://alabs.in/proddy_final/admin/images/".$image;

				        array_push($json_response,$row_array);   
					}	
					$obj = (object)array("EditPostData"=>$json_response);
					return $obj;
				}	
			}
			else
			{
				return $msg = (object)array("status"=>"fail");
				//return $obj =(object)array("item_details"=>$msg);
			}
 	}
	public function dbEditMyaddaPost($data, $key)   
 	{
        foreach($data[$key] as $details)
        {
            $userId=$details['user_id'];
                $post_id=$details['post_id'];
            $collegeId=$details['collage_id'];
            $title=$details['ptitle'];
            $desc=$details['postdesc'];
            $postToRadio=$details['radiostatus'];
            $type="MYFORUM";
            $postAsRadio=$details['checkstatus'];	   
            if($postAsRadio=='0')
            {
            	$postAs="unanonymous";
            }
            elseif($postAsRadio=='1')
            {
            	$postAs="anonymous";	
            }            
            if($postToRadio=='My College')
            {
            	$postTo="NOTALL";
            }
            elseif($postToRadio=='Whole Engg.Community')
            {
            	$postTo="ALL";	
            }
            if($postToRadio=='My College')
            {
            	$status="Approved";
            }
            elseif($postToRadio=='Whole Engg.Community')
            {
            	$status="Pending";	
            }
			$image_string=$details['imageurl'];
                        $today = date("Ymdhis");
                        $rand = strtoupper(substr(uniqid(sha1(time())),0,4));
                        $image = $today . $rand.".png";
                        $path="../admin/images/".$image ;
                        if($details['imageurl']=='null')
                        {
						     $image="null";
                        }

                        if($image!='null')
                        {
                        	$subquery=$image;
                        }
                        else if($image=='null')
                        {
                        	$subquery='';
                        }

                        $timestamp = date('Y-m-d G:i:s');
        
        $query="UPDATE `myadda_post` SET post='".$title."', description='".$desc."', type='".$type."', ".$subquery." post_to='".$postTo."', college_id='".$collegeId."',  post_as='".$postAs."' where id='".$post_id."'";         
         	 $objSql2 = new SqlClass();
         $result=$objSql2->executesql($query);
        if($result)
        {
              if($details['imageurl']!='null')
                                {
				       file_put_contents($path,base64_decode($image_string));
                                }

	        return $obj = (object)array("status"=>"success");
        }
        else
        {
	        return $obj = (object)array("status"=>"fail");		
        } 	  	                
 	}
}
        public function dbGetMasterFestTypes()
	{
		$query="SELECT * FROM `master_fest_type` order by id desc";
		$objSql2 = new SqlClass();
		$result=$objSql2->executesql($query);
		$status="fail";
		$message="no data found";
		if($resultProddies== 'no rows')
		{
$msg = (object)array("status"=>"fail");
return $obj =(object)array("festTypes"=>$msg);
		}
		else
		{
			$json_response=array();
			foreach ($result as $ftypes)
			{
				// print_r($proddy);
				$row_array['id']=$ftypes['id'];
				$row_array['name']=$ftypes['fest_type'];				
				array_push($json_response,$row_array);  
			}
			$obj = (object)array("festTypes"=>$json_response);
			return $obj;
		 }
	 }

 	public function dbDeleteItem($data,$key)
	{
		foreach ($data[$key] as $details)
		{
			$user_id=$details['user_id'];
			$item_id=$details['item_id'];		
			$query="UPDATE `items` SET delete_status='Pending' where id='".$item_id."' and user_Id='".$user_id."'";
			$objSql2 = new SqlClass();
			$result=$objSql2->executesql($query);
			if($result)
			{                                
				return $obj = (object)array("status"=>"success");
			}
			else
			{
				return $obj = (object)array("status"=>"fail");		
			}
		}
	}

	public function dbDeletePost($data,$key)
	{
		foreach ($data[$key] as $details)
		{
			$user_id=$details['user_id'];
			$item_id=$details['post_id'];		
			$query="UPDATE `myadda_post` SET status_id='Deleted' where id='".$post_id."' and user_Id='".$user_id."'";
			$objSql2 = new SqlClass();
			$result=$objSql2->executesql($query);
			if($result)
			{                                
				return $obj = (object)array("status"=>"success");
			}
			else
			{
				return $obj = (object)array("status"=>"fail");		
			}
		}
	}

}