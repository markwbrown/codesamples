<?php

	if(isset($_SESSION["p_total"])) {
		//$path = realpath(dirname(__FILE__) . '/uploads');
		define("MAX_UPLOAD_SIZE", 1000000);
		
		if($_POST['MAX_FILE_SIZE'] <= MAX_UPLOAD_SIZE) {
			
			if(array_key_exists("foto", $_FILES)) {
	
				$filename = $_FILES['foto']['tmp_name']; 
				$target_path = "uploads/" . basename($_FILES['foto']['name']);
				$for_db_path = "/admin/" . $target_path;
			
				if(move_uploaded_file($filename, $target_path)) {
				    $_POST['fileuploaded'] = 1;
				} else {
					$_POST['filenotuploaded'] = 1;
				}
			
				$query = "INSERT INTO `images` (`images_title`, `images_desc`, `images_path`, `images_cliente`) 
										VALUES (
											" . "'" . $_POST['titulo'] . "'" . ",
											" . "'" . $_POST['desc'] . "'" . ",
											" . "'" . $for_db_path . "'" . ",
											" . "'" . $_POST['select_cliente'] . "'
											)";
			
				$result = mysql_query($query) or die("Error: " . $query . "<br/><br/>  " . mysql_error());
			
				function returnThumb($filename) {
					header('Content-type: image/jpeg');
					$image = new Imagick($filename);
					$image->thumbnailImage(150, 0); // Zero maintains aspect ratio

				 	return $image;
				}
			}
		} else {
			$_POST['larger_than_max_size'] = 1;
		}
	} else {
		//Nao possui permissao para ver essa pagina
		echo "	<script>	
					alert('Sua conta nao tem pivilegios suficientes para ver essa pagina.'); 
					location.href='index.php';
				</script>
			";
		die();
	}
?>