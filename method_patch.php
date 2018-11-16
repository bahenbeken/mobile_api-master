<?php
 
	function upload_nota($table, $field, $id, $nama_file, $photo) {
		
		$filename = $nama_file.'.jpg';
		$url_path = '/var/www/html/backend/backend/assets/photo/nota/'.$id.'-'.$filename;
		$img = stripslashes($photo);
		$img = str_replace('data:image/jpeg;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$data64 = base64_decode($img);
		//$file = __DIR__ . $url_path;
		$file = $url_path;
		$success = file_put_contents($file, $data64);
		
		$query = $db['ret']->query("UPDATE $table SET $field='$filename' where `id` = $id");
		
	}

	if($input != "") {
		// Decode jason to get the data	
		$object = json_decode($input, true);
		
		/** Form : Taking Orders */
		if(isset($_GET['taking-orders'])) {

			// Save data
			try {
				
				// Save backup
				$copyQuery = "";
				$copyQuery .= "insert ordertableupdate(id, id_retail, id_customer, delivery_status, total_amount, code_booking, order_date, sales_type, reason, sales_name, closed_date, approved_date, canceled_date, update_date) ";
				$copyQuery .= "select id, id_retail, id_customer, 'P', total_amount, code_booking, order_date, sales_type, reason, sales_name, closed_date, approved_date, canceled_date, NOW() ";
				$copyQuery .= "from ordertable where id = ".$object['id'];
				$db['ret']->query($copyQuery);

				$copyQuery = "";
				$copyQuery .= "insert ordertranstableupdate (id_order, id_item, id_uom, qty, discount, price, total_amount) ";
				$copyQuery .= "select id_order, id_item, id_uom, qty, discount , price, total_amount ";
				$copyQuery .= "from ordertranstable where id_order = ".$object['id'];
				$db['ret']->query($copyQuery);

				$deleteQuery = "delete from ordertable where id = ".$object['id'];
				$db['ret']->query($deleteQuery);

				$deleteQuery = "delete from ordertranstable where id_order = ".$object['id'];
				$db['ret']->query($deleteQuery);

				// Execute query
				$order = $db['ret']->query("INSERT INTO `ordertable` SET `id_retail` = '$object[retail]', 
																		 `id_customer` = '$object[customer]', 
																		 `total_amount` = '$object[total_amount]',
																		 `code_booking` = '$object[code_booking]',
																		 `order_date` = CURDATE(),
                                                                         `reason` = '$object[reason]',
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
				$data['data'] = $object['code_booking'];
				$data['code'] = 1;
				$data['success'] = "Success add new order!";
			} catch(PDOException $e) {
				// Create error data
				$data['error'] = $e->getMessage();
			}

			// // Save data
			// try {
			// 	// Initialize value
			// 	$value = "";
				
			// 	// Validate each column
			// 	if($object['retail'] != null)
			// 		$value .= "`id_retail` = " . $object['retail'] . ",";
				
			// 	if($object['customer'] != null)
			// 		$value .= "`id_customer` = " . $object['customer'] . ",";
				
			// 	if($object['delivery_date'] != null)
			// 		$value .= "`delivery_date` = '" . $object['delivery_date'] . "',";
				
			// 	if($object['delivery_status'] != null)
			// 		$value .= "`delivery_status` = '" . $object['delivery_status'] . "',";
				
			// 	if($object['total_amount'] != null)
			// 		$value .= "`total_amount` = " . $object['total_amount'] . ",";
				
			// 	if($object['reason'] != null)
			// 		$value .= "`reason` = '" . $object['reason'] . "',";
				
			// 	// Remove right comma
			// 	$value = rtrim($value, ",");
				
			// 	// Execute query
			// 	$order = $db['ret']->query("UPDATE `ordertable` SET $value WHERE `id` = $object[id]");
				
			// 	if($order) {
			// 		// Create success data
			// 		$data['success'] = "Success update an order!";
			// 	}
			// 	else {
			// 		// Create error data
			// 		$data['error'] = "Failed to update order!";
			// 	}
			// } catch(PDOException $e) {
			// 	// Create error data
			// 	$data['error'] = $e->getMessage();
			// }
		}

		/** Form : Taking Orders Retail*/
		else if(isset($_GET['taking-orders-retail'])) {

			// Save data
			try {

				// Save backup
				$copyQuery = "";
				$copyQuery .= "insert orderretailtableupdate(id, id_retail, id_distributor, delivery_status, total_amount, code_booking, order_date, sales_type, reason, sales_name, closed_date, approved_date, canceled_date, update_date) ";
				$copyQuery .= "select id, id_retail, id_distributor, 'P', total_amount, code_booking, order_date, sales_type, reason, sales_name, closed_date, approved_date, canceled_date, NOW() ";
				$copyQuery .= "from orderretailtable where id = ".$object['id'];

				$db['ret']->query($copyQuery);
				$id = $db['ret']->lastInsertId();

				$copyQuery = "";
				$copyQuery .= "insert orderretailtranstableupdate (id_order, id_item, id_uom, qty, discount, price, total_amount) ";
				$copyQuery .= "select id_order, id_item, id_uom, qty, discount , price, total_amount from orderretailtranstable ";
				$copyQuery .= "where id_order = ".$object['id'];
				$db['ret']->query($copyQuery);

				$deleteQuery = "delete from orderretailtable where id = ".$object['id'];
				$db['ret']->query($deleteQuery);

				$deleteQuery = "delete from orderretailtranstable where id_order = ".$object['id'];
				$db['ret']->query($deleteQuery);

				// Execute query
				$order = $db['ret']->query("INSERT INTO `orderretailtable` SET `id_retail` = '$object[retail]', 
																		 `id_distributor` = '$object[distributor]', 
																		 `total_amount` = '$object[total_amount]',
																		 `code_booking` = '$object[code_booking]',
																		 `order_date` = CURDATE(),
                                                                         `reason` = '$object[reason]',
																		 `sales_type` = '$object[sales_type]',
																		 `sales_name` = '$object[sales_name]'");
				console.log("insert orderretailtable");

				if($order) {
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
					$data['data'] = $object['code_booking'];
					$data['code'] = 1;
					$data['success'] = "Success add new order!";
				}
				else {
					// Create error data
					$data['code'] = 0;
					$data['error'] = "Failed to add new order!";
				}
			} catch(PDOException $e) {
				// Create error data
				$data['error'] = $e->getMessage();
			}
		}

		/** Form : Taking Orders for update approved date */
		else if(isset($_GET['taking-orders-update-approved'])) {
			// Save data
			try {
				// Initialize value
				$value = "";
				$today = date("Y-m-d");
				$value .= "`delivery_status` = 'Y',";
				$value .= "`approved_date` = '". $today ."' ";
				
				// Execute query
				$order = $db['ret']->query("UPDATE `ordertable` SET ".$value." WHERE `id` = ".$object['id']);
				
				// Create success data
				$data['data']['id']= $object['id'];
				$data['success'] = "Success update an order!";
				
			} catch(PDOException $e) {
				// Create error data
				$data['data'] = $e->getMessage();
				$data['error'] = "Failed to update order!";
			} catch(Exception $e) {
				$data['data'] = $e->getMessage();
				$data['error'] = "Failed to update order!";
			}
		}
		else if(isset($_GET['taking-orders-update-approved-2'])) {
			// Save data
			try {
				// Initialize value
				$value = "";
				$today = date("Y-m-d");
				$value .= "`delivery_status` = 'Y',";
				$value .= "`approved_date` = '". $today ."' ";
				
				if ($object['kode_voucher'] != null && $object['kode_voucher'] != '') {
					$value .= ",`kode_voucher` = '". $object['kode_voucher'] ."' ";
				}
				
				// Execute query
				$order = $db['ret']->query("UPDATE `ordertable` SET ".$value." WHERE `id` = ".$object['id']);
				
				$filename = $object['id'].'-'.$object['code_booking'].'_1.jpg';
				$url_path = '/var/www/html/backend/assets/photo/nota/customer/'.$filename;
				$img = stripslashes($object['nota_1']);
				$img = str_replace('data:image/jpeg;base64,', '', $img);
				$img = str_replace(' ', '+', $img);
				$data64 = base64_decode($img);
				//$file = __DIR__ . $url_path;
				$file = $url_path;
				$success = file_put_contents($file, $data64);
				
				$query = $db['ret']->query("UPDATE `ordertable` SET `nota_1`='$filename' where `id` = ".$object['id']);
				
				if ($object['nota_2'] != '' ) {
					$filename = $object['id'].'-'.$object['code_booking'].'_2.jpg';
					$url_path = '/var/www/html/backend/assets/photo/nota/customer/'.$filename;
					$img = stripslashes($object['nota_2']);
					$img = str_replace('data:image/jpeg;base64,', '', $img);
					$img = str_replace(' ', '+', $img);
					$data64 = base64_decode($img);
					//$file = __DIR__ . $url_path;
					$file = $url_path;
					$success = file_put_contents($file, $data64);
					
					$query = $db['ret']->query("UPDATE `ordertable` SET `nota_2`='$filename' where `id` = ".$object['id']);
				}
				if ($object['nota_3'] != '' ) {
					$filename = $object['id'].'-'.$object['code_booking'].'_3.jpg';
					$url_path = '/var/www/html/backend/assets/photo/nota/customer/'.$filename;
					$img = stripslashes($object['nota_3']);
					$img = str_replace('data:image/jpeg;base64,', '', $img);
					$img = str_replace(' ', '+', $img);
					$data64 = base64_decode($img);
					//$file = __DIR__ . $url_path;
					$file = $url_path;
					$success = file_put_contents($file, $data64);
					
					$query = $db['ret']->query("UPDATE `ordertable` SET `nota_3`='$filename' where `id` = ".$object['id']);
				}
				
				// Create success data
				$data['data']['id']= $object['id'];
				$data['success'] = "Success update an order!";
				
			} catch(PDOException $e) {
				// Create error data
				$data['data'] = $e->getMessage();
				$data['error'] = "Failed to update order!";
			} catch(Exception $e) {
				$data['data'] = $e->getMessage();
				$data['error'] = "Failed to update order!";
			}
		}
		/** Form : Taking Orders for update canceled date */
		else if(isset($_GET['taking-orders-update-canceled'])) {
			// Save data
			try {
				// Initialize value
				$value = "";
				$today = date("Y-m-d");

				// Validate each column
				$value .= "`delivery_status` = 'C',";
				$value .= "`canceled_date` = '". $today ."',";
				$value .= "`reason` = '" . $object['reason'] . "' ";
				
				// Execute query
				$order = $db['ret']->query("UPDATE `ordertable` SET ".$value." WHERE `id` = ".$object['id']);
				
				// Create success data
				$data['data']['id']= $object['id'];
				$data['success'] = "Success update an order!";
				
			} catch(PDOException $e) {
				// Create error data
				$data['data'] = $e->getMessage();
				$data['error'] = "Failed to update order!";
			}
		}

		/** Form : Taking Orders Retail for approve*/
		else if(isset($_GET['taking-orders-retail-update-approved'])) {
			// Save data
			try {
				// Initialize value
				$value = "";
				$today = date("Y-m-d");
				$value .= "`delivery_status` = 'Y',";
				$value .= "`approved_date` = '". $today ."' ";
				
				// Execute query
				$order = $db['ret']->query("UPDATE `orderretailtable` SET ".$value." WHERE `id` = ".$object['id']);
				
				// Create success data
				$data['data']['id']= $object['id'];
				$data['success'] = "Success update an order!";
				
			} catch(PDOException $e) {
				// Create error data
				$data['data'] = $e->getMessage();
				$data['error'] = "Failed to update order!";
			}
		}
		else if(isset($_GET['taking-orders-retail-update-approved-2'])) {
			// Save data
			try {
				// Initialize value
				$value = "";
				$today = date("Y-m-d");
				$value .= "`delivery_status` = 'Y',";
				$value .= "`approved_date` = '". $today ."' ";
				
				// Execute query
				if ($object['kode_voucher'] != null && $object['kode_voucher'] != '') {
					$value .= ",`kode_voucher` = '". $object['kode_voucher'] ."' ";
				}
				
				// Execute query
				$order = $db['ret']->query("UPDATE `orderretailtable` SET ".$value." WHERE `id` = ".$object['id']);
				
				
				$filename = $object['id'].'-'.$object['code_booking'].'_1.jpg';
				$url_path = '/var/www/html/backend/assets/photo/nota/retailer/'.$filename;
				$img = stripslashes($object['nota_1']);
				$img = str_replace('data:image/jpeg;base64,', '', $img);
				$img = str_replace(' ', '+', $img);
				$data64 = base64_decode($img);
				//$file = __DIR__ . $url_path;
				$file = $url_path;
				$success = file_put_contents($file, $data64);
				
				$query = $db['ret']->query("UPDATE `orderretailtable` SET `nota_1`='$filename' where `id` = ".$object['id']);
				
				if ($object['nota_2'] != '' ) {
					$filename = $object['id'].'-'.$object['code_booking'].'_2.jpg';
					$url_path = '/var/www/html/backend/assets/photo/nota/retailer/'.$filename;
					$img = stripslashes($object['nota_2']);
					$img = str_replace('data:image/jpeg;base64,', '', $img);
					$img = str_replace(' ', '+', $img);
					$data64 = base64_decode($img);
					//file = __DIR__ . $url_path;
					file = $url_path;
					$success = file_put_contents($file, $data64);
					
					$query = $db['ret']->query("UPDATE `orderretailtable` SET `nota_2`='$filename' where `id` = ".$object['id']);
				}
				if ($object['nota_3'] != '' ) {
					$filename = $object['id'].'-'.$object['code_booking'].'_3.jpg';
					$url_path = '/var/www/html/backend/assets/photo/nota/retailer/'.$filename;
					$img = stripslashes($object['nota_3']);
					$img = str_replace('data:image/jpeg;base64,', '', $img);
					$img = str_replace(' ', '+', $img);
					$data64 = base64_decode($img);
					//$file = __DIR__ . $url_path;
					$file = $url_path;
					$success = file_put_contents($file, $data64);
					
					$query = $db['ret']->query("UPDATE `orderretailtable` SET `nota_3`='$filename' where `id` = ".$object['id']);
				}
				
				// Create success data
				$data['data']['id']= $object['id'];
				$data['success'] = "Success update an order!";
				
			} catch(PDOException $e) {
				// Create error data
				$data['data'] = $e->getMessage();
				$data['error'] = "Failed to update order!";
			}
		}

		/** Form : Taking Orders Retail for approve*/
		else if(isset($_GET['taking-orders-retail-update-canceled'])) {
			// Save data
			try {
				// Initialize value
				$value = "";
				$today = date("Y-m-d");

				// Validate each column
				$value .= "`delivery_status` = 'C',";
				$value .= "`canceled_date` = '". $today ."',";
				$value .= "`reason` = '" . $object['reason'] . "' ";
				
				// Execute query
				$order = $db['ret']->query("UPDATE `orderretailtable` SET ".$value." WHERE `id` = ".$object['id']);
				
				// Create success data
				$data['data']['id']= $object['id'];
				$data['success'] = "Success update an order!";
				
			} catch(PDOException $e) {
				// Create error data
				$data['data'] = $e->getMessage();
				$data['error'] = "Failed to update order!";
			}
		} else {
			// Create error data
			$data['error'] = "Form is not provided";
		}
	}
		
?>