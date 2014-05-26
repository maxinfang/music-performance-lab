<?php

//Check File exists
if (isset($file)) {
	
	if (file_exists($file)) {
		
		//Set file variables
		$fsize = filesize($file);
		$path_parts = pathinfo($file);
		$ext = strtolower($path_parts["extension"]);

		//Is extension allowed?
		if (in_array($ext, unserialize(FILE_TYPE))) {
		
			//Set header
			switch ($ext) {
				case "pdf":
					$content_type = 'application/pdf';
					break;
				case "ppt":
					$content_type = 'application/vnd.ms-powerpoint';
					break;
				case "pptx":
					$content_type = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
					break;
				case "rtf":
					$content_type = 'application/rtf';
					break;	
				case ('doc' or 'docx'):
					$content_type = 'application/msword';
					break;			
				default;
					$content_type = 'application/octet-stream';
					break;
			}
	
			header('Content-Description: File Transfer');
			header('Content-Type: ' . $content_type);
			header('Content-Disposition: attachment; filename="'.$path_parts["basename"] . '"');	
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . $fsize);
			ob_clean();
			flush();
			readfile($file);
			exit;

		} else {
			
			$fail = TRUE;

		}
		
	} else {
		
		$fail = TRUE;
		
	}


//Unable to open file
} else { 

	$fail = TRUE;
	
} ?>

<?php if ($fail === TRUE) { ?>

  <p style="color:#F00"><strong>ERROR:</strong> 
		<?php if (isset($error)) { echo $error; } else { ?>
    	Unable to locate file on the server. Please return to the previous page, refresh and try again.
		<?php } ?>
    
  </p>

<?php } ?>