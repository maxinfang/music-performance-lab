<?php class Initial
{
  function __construct()
  {
      $CI =& get_instance();
      $CI->load->helper(‘url’);
      $CI->load->library(‘session’);
      
      if (($CI->session->userdata(‘logged’) != true) && $CI->uri->segment(1) != ‘login’)
      {
        redirect(‘login’);
      }
  }
}