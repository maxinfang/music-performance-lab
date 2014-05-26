<?php 
 
class tutor_model extends CI_Model
{
      function __construct() 
      {     
            parent::__construct();
 
      }
 
      function getdata() 
      { 
            return "mode";
      }



       //for testing the model
      function dummylogin()
      {
      	$CI =& get_instance(); //To fetch the object
        $session_data =   array(
					'user_id'=>"z3285237",
					'user_first_name' => "Xinfang",
					'user_last_name' => "Ma",
          'user_type' =>"tutor", 
					);
			 
        $CI->session->set_userdata($session_data);
        $CI->session->set_userdata('login_ok', 1);
       
      }



        function login($email,$password)
      {

        $find = $this->fm_db->newFindCommand('www_tutor_list');
        $find->addFindCriterion('email','=='.$email);
        $result = $find->execute(); 
       if (FileMaker::isError($result)) { 
       $errors[] = 'You do not have an account with this system';
        return "You do not have an account with this system"; 
       //Found Student Record  
        } elseif ($result->getFoundSetCount() == 1) { 
       //*** Shortcut to results
        
              $records = $result->getRecords();
              $record = $records[0];
      
           //*** Check that pwassword
      if ( $record->getField('password') == $password ) 
       { 
        $CI =& get_instance(); //To fetch the object
        $session_data =   array(
          'user_id'=>$record->getField('tutor_id') ,
          'user_first_name' =>$record->getField('first_name'),
          'user_last_name' => $record->getField('last_name'), 
          'tutor_rid'=>$record->getRecordId(),
          'user_type' =>"tutor", 
          );
       
        $CI->session->set_userdata($session_data);
        $CI->session->set_userdata('login_ok', 1); 
         } 
       else{return "password invalid";} 
      }
 
      }

 

      function getPersonal()
      { 
        $find = $this->fm_db->newFindCommand('www_tutor_list');
        $find->addFindCriterion('email','==tutor2@test.com');
        $result = $find->execute();
       
   
       if (FileMaker::isError($result)) {
            
       $errors[] = 'You do not have an account with this system';
        return "error";

       //Found Student Record  
        } elseif ($result->getFoundSetCount() == 1) { 
       //*** Shortcut to results
       $records = $result->getRecords();
       $record = $records[0];
       print_ob($record);
         
         }
      }

      function  getStudentslist()
      {  //$find = $this->fm_db->newFindCommand('www_tutor_list');




      }
}