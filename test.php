
<?php
	//try {
		
		//$img = stripslashes($object['photo']);
		//$img = str_replace('data:image/jpeg;base64,', '', $img);
		//$img = str_replace(' ', '+', $img);
		//$data = base64_decode($img);
		//$file = __DIR__ . '/backend/assets/photo/customer/test.jpg';
		//$success = file_put_contents($file, $data);
		
		//echo($file);
	//} catch(Exception $e) {
		//echo ($e->getMessage());
	//}
	
	$img = str_replace('data:image/jpeg;base64,', '', 'AAAFBfj42Pj4');
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	//$file = __DIR__ . '\photo\test.jpg';
	$file = __DIR__ . '/backend/assets/photo/customer/test.jpg';
	$success = file_put_contents($file, $data);
	echo $success;
	echo $file;
?>