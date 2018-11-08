
<?php
		
	if($input != "") {
		// Decode jason to get the data	
		$object = json_decode($input, true);
		
		if(isset($_GET['test'])) {
			// Save data
			try {
				
				$img = stripslashes($object['photo']);
				$img = str_replace('data:image/jpeg;base64,', '', $img);
				$img = str_replace(' ', '+', $img);
				$data = base64_decode($img);
				$file = __DIR__ . '/assets/photo/customer/test.jpg';
				$success = file_put_contents($file, $data);
				
				// Create success data
				$data['data'] = $success;
				$data['code'] = 1;
				$data['success'] = "Success add new order!";
			} catch(Exception $e) {
				// Create error data
				$data['input'] = $input;
				$data['error'] = $e->getMessage();
			}
		}

		/** Form : Taking Orders */
		if(isset($_GET['taking-orders'])) {
			// Save data
			try {
				
				$date = date_create();
				$code_booking = $object[id_order].$object[retail].date_timestamp_get($date);

				// Execute query
				$order = $db['ret']->query("INSERT INTO `ordertable` SET `id_retail` = '$object[retail]', 
																		 `id_customer` = '$object[customer]', 
																		 `total_amount` = '$object[total_amount]',
																		 `code_booking` = '$code_booking',
																		 `order_date` = CURDATE(),
																		 `sales_type` = '$object[sales_type]',
																		 `sales_name` = '$object[sales_name]'");
				
				$id = $db['ret']->lastInsertId();
					
				// Insert item list
				foreach($object['items'] as $index => $value) {
					// Execute query
					$item = $db['ret']->query("INSERT INTO `ordertranstable` SET `id_order` = '$id', 
																					`id_uom` = '$value[id_uom]', 
																					`id_item` = '$value[id_item]',
																					`qty` = '$value[qty]',
																					`discount` = '$value[discount]',
																					`price` = '$value[price]',
																					`total_amount` = '$value[total_amount]'");
					console.log("insert ordertranstable 2");
				}
				
				// Create success data
				$data['data'] = $code_booking;
				$data['code'] = 1;
				$data['success'] = "Success add new order!";
			} catch(PDOException $e) {
				// Create error data
				$data['error'] = $e->getMessage();
			}
		}

		/** Form : Taking Order Retail */
		else if(isset($_GET['taking-orders-retail'])) {
			// Save data
			try {

				$date = date_create();
				$code_booking = $object[id_order].$object[retail].date_timestamp_get($date);

				// Execute query
				$order = $db['ret']->query("INSERT INTO `orderretailtable` SET `id_retail` = '$object[retail]', 
																		 `id_distributor` = '$object[distributor]', 
																		 `total_amount` = '$object[total_amount]',
																		 `code_booking` = '$code_booking',
																		 `order_date` = CURDATE(),
																		 `sales_type` = '$object[sales_type]',
																		 `sales_name` = '$object[sales_name]'");
				console.log("insert orderretailtable");

				// Order/sales ID
				$id = $db['ret']->lastInsertId();
					
				// Insert item list
				foreach($object['items'] as $index => $value) {
					// Execute query
					$item = $db['ret']->query("INSERT INTO `orderretailtranstable` SET `id_order` = '$id', 
																				 `id_uom` = '$value[id_uom]', 
																				 `id_item` = '$value[id_item]',
																				 `qty` = '$value[qty]',
																				 `discount` = '$value[discount]',
																				 `price` = '$value[price]',
																				 `total_amount` = '$value[total_amount]'");
					console.log("insert ordertranstable 2");
				}
				
				// Create success data
				$data['data'] = $code_booking;
				$data['code'] = 1;
				$data['success'] = "Success add new order!";
			} catch(PDOException $e) {
				// Create error data
				$data['error'] = $e->getMessage();
			}
		}

		/** Form : Customers */
		else if(isset($_GET['customers'])) {
			// Save data
			try {

				// Execute query 
				$result = $db['ret']->query("INSERT INTO `custtable` SET `name` = '$object[name]', 
														`identity_number` = '$object[identity_number]', 
														`telephone` = '$object[telephone]', 
														`email` = '$object[email]', 
														`address` = '$object[address]',  
														`region` = '$object[region]',
														`id_region` = $object[id_region],
														`place_birth` = '$object[place_birth]',
														`date_birth` = '$object[date_birth]'"
														);
							
				
				$id = $db['ret']->lastInsertId();
				$filename = $id.'.jpg';
				$url_path = '/assets/photo/customer/'.$filename;
				$img = stripslashes($object['photo']);
				$img = str_replace('data:image/jpeg;base64,', '', $img);
				$img = str_replace(' ', '+', $img);
				$data64 = base64_decode($img);
				$file = __DIR__ . $url_path;
				$success = file_put_contents($file, $data64);
				
				$query = $db['ret']->query("UPDATE `custtable` SET `photo`='$filename' where `id` = '$id'");
				
				$data['data'] = $id;
				$data['code'] = 1;
				$data['success'] = "Success add new customer!";
			} catch(PDOException $e) {
				// Create error data
				$data['error'] = $e->getMessage();
			}
		}

		/** Form : Counter/ Retail */
		else if(isset($_GET['retailers'])) {
			// Save data
			try {
				// Execute query
				$order = $db['ret']->query("INSERT INTO `retailtable` SET `name` = '$object[name]', 
																		  `identity_number` = '$object[identity_number]', 
																		  `telephone` = '$object[telephone]',
																		  `email` = '$object[email]',
																		  `address` = '$object[address]', 
																		  `region` = '$object[region]',
																		  `id_region` = $object[id_region],
																		  `place_birth` = '$object[place_birth]',
																		  `date_birth` = '$object[date_birth]'");
									
				$id = $db['ret']->lastInsertId();
				$filename = $id.'.jpg';
				$url_path = '/assets/photo/retailer/'.$filename;
				$img = stripslashes($object['photo']);
				$img = str_replace('data:image/jpeg;base64,', '', $img);
				$img = str_replace(' ', '+', $img);
				$data64 = base64_decode($img);
				$file = __DIR__ . $url_path;
				$success = file_put_contents($file, $data64);
				
				$query = $db['ret']->query("UPDATE `retailtable` SET `photo`='$filename' where `id` = '$id'");
				

				// Create success data
				$data['data'] = $id;
				$data['code'] = 1;
				$data['success'] = "Success add new retailer!";
			} catch(PDOException $e) {
				// Create error data
				$data['error'] = $e->getMessage();
			}
		}

		// 	/** Form : Confirm Delivery Update */
		// else if(isset($_GET['taking-orders-update'])) {
		// 		// Save data
		// 		try {
		// 			$date = date_create();
		// 			$code_booking = $object[id_order].$object[retail].date_timestamp_get($date);

		// 			// Execute query
		// 			$order = $db['ret']->query("INSERT INTO `ordertableupdate` SET `id` = '$object[id_order]',
        //                                                                      `id_retail` = '$object[retail]', 
		// 																	 `id_customer` = '$object[customer]', 
		// 																	 `total_amount` = '$object[total_amount]',
		// 																	 `code_booking` = '$code_booking',
		// 																	 `order_date` = CURDATE(),
		// 																	 `sales_type` = '$object[sales_type]',
		// 																	 `sales_name` = '$object[sales_name]'");
					
		// 			if($order) {
		// 				// Order/sales ID
		// 				$id = $db['ret']->lastInsertId();
						
		// 				// Insert item list
		// 				foreach($object['items'] as $index => $value) {
		// 					$data['info_' . $index] = "INSERT INTO `ordertranstableupdate` SET `id_order` = '$object[id_order]', 
		// 																				 `id_uom` = '$value[uom]', 
		// 																				 `id_item` = '$value[id]',
		// 																				 `qty` = '$value[qty]',
		// 																				 `discount` = '$value[discount]',
		// 																				 `price` = '$value[price]',
		// 																				 `total_amount` = '$value[totalPrice]'";
		// 					console.log("insert ordertranstable 1");
							
		// 					// Execute query
		// 					$item = $db['ret']->query("INSERT INTO `ordertranstableupdate` SET `id_order` = '$object[id_order]', 
		// 																				 `id_uom` = '$value[uom]', 
		// 																				 `id_item` = '$value[id]',
		// 																				 `qty` = '$value[qty]',
		// 																				 `discount` = '$value[discount]',
		// 																				 `price` = '$value[price]',
		// 																				 `total_amount` = '$value[totalPrice]'");
		// 					console.log("insert ordertranstable 2");
		// 				}
						
		// 				// Create success data
		// 				$data['data'] = $id;
		// 				$data['code'] = 1;
		// 				$data['success'] = "Success add new order!";
		// 			}
		// 			else {
		// 				// Create error data
		// 				$data['code'] = 0;
		// 				$data['error'] = "Failed to add new order!";
		// 			}
		// 		} catch(PDOException $e) {
		// 			// Create error data
		// 			$data['error'] = $e->getMessage();
		// 		}
		// 	}
		
        // /** Form : Taking Order Retail Update */
		// else if(isset($_GET['taking-orders-retail-update'])) {
		// 	// Save data
		// 	try {
		// 		$date = date_create();
		// 		$code_booking = $object[id_order].$object[retail].date_timestamp_get($date);

		// 		// Execute query
		// 		$order = $db['ret']->query("INSERT INTO `orderretailtableupdate` SET
        //                                                                  `id_retail` = '$object[retail]', 
		// 																 `id_distributor` = '$object[distributor]', 
		// 																 `total_amount` = '$object[total_amount]',
		// 																 `code_booking` = '$code_booking',
		// 																 `order_date` = CURDATE(),
		// 																 `sales_type` = '$object[sales_type]',
		// 																 `sales_name` = '$object[sales_name]'");
		// 		console.log("insert orderretailtable");

		// 		if($order) {
        //             foreach($object['items'] as $index => $value) {
		// 				$data['info_' . $index] = "INSERT INTO `orderretailtranstableupdate` SET `id_order` = '$object[retail]', 
		// 																			 `id_uom` = '$value[uom]', 
		// 																			 `id_item` = '$value[id]',
		// 																			 `qty` = '$value[qty]',
		// 																			 `discount` = '$value[discount]',
		// 																			 `price` = '$value[price]',
		// 																			 `total_amount` = '$value[totalPrice]'";
		// 				console.log("insert ordertranstable 1");
						
		// 				// Execute query
		// 				$item = $db['ret']->query("INSERT INTO `orderretailtranstableupdate` SET `id_order` = '$object[retail]', 
		// 																			 `id_uom` = '$value[uom]', 
		// 																			 `id_item` = '$value[id]',
		// 																			 `qty` = '$value[qty]',
		// 																			 `discount` = '$value[discount]',
		// 																			 `price` = '$value[price]',
		// 																			 `total_amount` = '$value[totalPrice]'");
		// 				console.log("insert ordertranstable 2");
        //             }
                    
		// 			// Create success data
		// 			$data['data'] = $code_booking;
		// 			$data['code'] = 1;
		// 			$data['success'] = "Success add new order!";
		// 		}
		// 		else {
		// 			// Create error data
		// 			$data['code'] = 0;
		// 			$data['error'] = "Failed to add new order!";
		// 		}
		// 	} catch(PDOException $e) {
		// 		// Create error data
		// 		$data['error'] = $e->getMessage();
		// 	}
        // }
        
		
		// /** Form : Customers */
		// else if(isset($_GET['customers'])) {
		// 	// Save data
		// 	try {
		// 		// Execute query
		// 		$order = $db['ret']->query("INSERT INTO `custtable` SET `name` = '$object[name]', `identity_number` = '$object[identity_number]', `telephone` = '$object[telephone]', `email` = '$object[email]', `address` = '$object[address]',  `region` = '$object[region]'");
				
		// 		if($order) {
		// 			// Create success data
		// 			$data['data'] = 1;
		// 			$data['code'] = 1;
		// 			$data['success'] = "Success add new customer!";
		// 		}
		// 		else {
		// 			// Create error data
		// 			$data['code'] = 0;
		// 			$data['error'] = "Failed to add new customer!";
		// 		}
		// 	} catch(PDOException $e) {
		// 		// Create error data
		// 		$data['error'] = $e->getMessage();
		// 	}
		// }

		// /** Form : Login */
		// else if(isset($_GET['login-user'])) {
		// 	// Save data
		// 	try {
		// 		// Execute query
		// 		$order = $db['ret']->query("INSERT INTO `userlogintable` SET `username` = '$object[username]', 
		// 																  `password` = '$object[password]'");
				
		// 		if($order) {
		// 			// Request data
		// 			$users = $db['ret']->prepare("SELECT * FROM `userlogintable` ". ($filter != "" ? "WHERE " . $filter : "") ." ORDER BY `id` DESC");
		// 			$users->execute();
					
		// 			// Counter
		// 			$i = 0;
					
		// 			// Loop for every data
		// 			while($row = $users->fetch()) {
		// 				$id = $db['ret']->lastInsertId();
		// 				$data['data'][$i] = array('id' => $id,
		// 										'username' => $row['username'],
		// 										'password' => $row['password']);
												
		// 				// Update counter
		// 				$i++;
		// 			}

		// 			// Create success data
		// 			$data['success'] = "Success add new login record!";
		// 		}
		// 		else {
		// 			// Create error data
		// 			$data['error'] = "Failed to add new login record!";
		// 		}
		// 	} catch(PDOException $e) {
		// 		// Create error data
		// 		$data['error'] = $e->getMessage();
		// 	}
		// }

		// /** Form : Distributor */
		// else if(isset($_GET['distis'])) {
		// 	// Save data
		// 	try {
		// 		// Execute query
		// 		$order = $db['ret']->query("INSERT INTO `distitable` SET `name` = '$object[name]', 
		// 																`identity_number` = '$object[identity_number]', 
		// 																`telephone` = '$object[telephone]', 
		// 																`email` = '$object[email]', 
		// 																`address` = '$object[address]'");
					
		// 		if($order) {
		// 			// Create success data
		// 			$data['success'] = "Success add new disti!";
		// 		}
		// 		else {
		// 			// Create error data
		// 			$data['error'] = "Failed to add new disti!";
		// 		}
		// 		} catch(PDOException $e) {
		// 			// Create error data
		// 			$data['error'] = $e->getMessage();
		// 		}
		// }
		
		else {
			// Create error data
			$data['error'] = "Form is not provided";
		}
	}
		
?>