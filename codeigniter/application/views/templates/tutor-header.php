<?php // sort_navigation($navigation); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">

<head>
<base href="https://sam.arts.unsw.edu.au/music-performance-lab/" />
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<title><?php echo $title; ?> | Music Performance Lab | UNSW Arts & Social Sciences</title>

<link href="assets/css/reset.css" type="text/css" media="all" rel="stylesheet" />
<link href="assets/css/style.css" type="text/css" media="all" rel="stylesheet" />
<link href="assets/css/smoothness/jquery-ui-1.10.3.custom.min.css" type="text/css" media="all" rel="stylesheet" />
<link href="assets/css/custom.css" type="text/css" media="all" rel="stylesheet" />
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.9.1.js');?>"></script> 
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-ui-1.10.3.custom.min.js');?>"></script> 
<script type="text/javascript" src="<?php echo base_url('assets/js/custom.js');?>"></script> 

<meta http-equiv="description" content="" />
<meta http-equiv="keywords" content="" />

</head>

<body class="school-site no-sidebar">
<div id="container">
	
    <div id="header">
    	<a class="jump-to-content" href="#content">Jump to content</a>
    	<div id="unsw-banner">
        
			<div id="site-logo"><a href="http://sam.arts.unsw.edu.au" title="School of the Arts & Media"><img src="assets/css/imgs/logos/school-sam-logo.jpg" alt="School of the Arts & Media" /></a></div>

						<div id="secondary-links">
                <ul>
                    <li class="first"><a href="http://www.arts.unsw.edu.au" class="external-link">Faculty Home</a></li>
                    <li><a href="http://www.arts.unsw.edu.au/faculty/units/" class="external-link">Schools &amp; Centres</a></li>
                    <li><a href="http://www.unsw.edu.au/" class="external-link">UNSW</a></li>
                    <li class="last"><a href="https://sam.arts.unsw.edu.au/contact-us/" class="external-link">Contact Us</a></li>
                </ul>
            </div>
                        
                        
		</div>
    
        <?php if ($this->session->userdata('login_ok') == 1) { ?>
				<div id="main-navigation">
        	<div id="greeting"><?php echo $this->session->userdata('user_first_name'); ?> <?php echo $this->session->userdata('user_last_name'); ?></div>
        	<ul>
            <?php
						$navigation = sort_navigation($navigation); foreach ($navigation as $url=>$item) { 
							if ($item['name']) {					
						?>
	            	<li <?php if ($item['active'] == 1) echo 'class="active"'; ?> ><a href="<?php echo $url; ?>"><?php echo $item['name']; ?></a></li>            
            <?php
            	} //End if
						} //End foreach ?>
            <li><a href="tutor/logout">Logout</a></li>
	       	</ul>
        </div>
        <?php } ?>
                
                <div id="site-banner"><a href="#">Music Performance Lab</a>

        </div>
                
	</div>
    
    <div id="main">
        <div id="content">
        <h1><?php echo $title; ?></h1>