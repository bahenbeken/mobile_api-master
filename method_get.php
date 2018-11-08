<?php

	/** Entity : Items */
	if(isset($_GET['items'])) {
		// Request data
		$items = $db['dist']->prepare("SELECT * FROM `itemtable` ". ($filter != "" ? "WHERE " . $filter : "") ." ORDER BY `name` ASC");
		$items->execute();
		
		// Counter
		$i = 0;
		
		// Loop for every data
		while($row = $items->fetch()) {
			$data['data'][$i] = array(
				'id' => $row['id'], 
				'name' => $row['name'], 
				'price' => $row['price'], 
				'pricekrt' => $row['pricekrt']
			);
			
			// Update counter
			$i++;
		}
	}
	
	/** Entity : Customers */
	else if(isset($_GET['customers'])) {
		// Request data
		$customers = $db['ret']->prepare("SELECT * FROM `custtable` ". ($filter != "" ? "WHERE " . $filter : "") ." ORDER BY `name` ASC");
		$customers->execute();
		
		// Counter
		$i = 0;
		
		// Loop for every data
		while($row = $customers->fetch()) {
			$data['data'][$i] = array(
				'id' => $row['id'], 
				'name' => $row['name']
			);
			
			// Update counter
			$i++;
		}
	}
	
	// /** Entity : Distributor */
	// else if(isset($_GET['distributors'])) {
	// 	// Request data
	// 	$customers = $db['ret']->prepare("SELECT * FROM `distributortable` ". ($filter != "" ? "WHERE " . $filter : "") ." ORDER BY `name` ASC");
	// 	$customers->execute();
		
	// 	// Counter
	// 	$i = 0;
		
	// 	// Loop for every data
	// 	while($row = $customers->fetch()) {
	// 		$data['data'][$i] = array(
	// 			'id' => $row['id'], 
	// 			'value' => $row['name']
	// 		);
			
	// 		// Update counter
	// 		$i++;
	// 	}
	// }
	
	/** Entity : Distributor */
	else if(isset($_GET['distributors'])) {
		// Request data
		$users = $db['ret']->prepare("SELECT * FROM `distributortable` ". ($filter != "" ? "WHERE " . $filter : "") ." ORDER BY `name` ASC");
		$users->execute();
		
		// Counter
		$i = 0;
		
		// Loop for every data
		while($row = $users->fetch()) {
			$data['data'][$i] = array('id' => $row['id'], 
									  'name' => $row['name'],
									  'telephone' => $row['telephone'],
									  'address' => $row['address'],
									  'region' => $row['region'],
									);
									
			// Update counter
			$i++;
		}
	}

	/** Entity : UOMs */
	else if(isset($_GET['uoms'])) {
		// Request data
		$uoms = $db['dist']->prepare("SELECT * FROM `uomtable` ". ($filter != "" ? "WHERE " . $filter : "") ." ORDER BY `name` ASC");
		$uoms->execute();
		
		// Counter
		$i = 0;
		
		// Loop for every data
		while($row = $uoms->fetch()) {
			//$data['data'][$i] = array('id' => $row['id'], 'value' => $row['name']);
			$data['data'][$row['id']] = $row['name'];
			// Update counter
			$i++;
		}
	}
	else if(isset($_GET['regions'])) {
		// Request data
		$uoms = $db['ret']->prepare("SELECT * FROM `region` ". ($filter != "" ? "WHERE " . $filter : "") ." ORDER BY `region_name` ASC");
		$uoms->execute();
		
		// Counter
		$i = 0;
		
		// Loop for every data
		while($row = $uoms->fetch()) {
			//$data['data'][$i] = array('id' => $row['id'], 'value' => $row['name']);
			$data['data'][$row['id']] = $row['region_name'];
			// Update counter
			$i++;
		}
	}

	/** Entity : Sales Types */
	else if(isset($_GET['salestypes'])) {
		// Request data
		$salestypes = $db['dist']->prepare("SELECT * FROM `salestypetable` ". ($filter != "" ? "WHERE " . $filter : "") ." ORDER BY `name` ASC");
		$salestypes->execute();
		
		// Counter
		$i = 0;
		
		// Loop for every data
		while($row = $salestypes->fetch()) {
			$data['data'][$i] = $row;//array('id' => $row['id'], 'name' => $row['name']);
			
			// Update counter
			$i++;
		}
	}
	
	/** Entity : Retails */
	else if(isset($_GET['retailers'])) {
		// Request data
		$retails = $db['ret']->prepare("SELECT * FROM `retailtable` ". ($filter != "" ? "WHERE " . $filter : "") ." ORDER BY `name` ASC");
		$retails->execute();
		
		// Counter
		$i = 0;
		
		// Loop for every data
		while($row = $retails->fetch()) {
			$data['data'][$i] = array(
				'id' => $row['id'], 
				'name' => $row['name']
			);
			
			// Update counter
			$i++;
		}
	}

	/** Entity : Order Retail */
	else if(isset($_GET['order-retails'])) {
		// Request data
		$customers = $db['ret']->prepare("SELECT * FROM `orderretailtable` ". ($filter != "" ? "WHERE " . $filter : "") ." ORDER BY `name` ASC");
		$customers->execute();
		
		// Counter
		$i = 0;
		
		// Loop for every data
		while($row = $customers->fetch()) {
			$data['data'][$i] = array('id' => $row['id'], 'value' => $row['name']);
			
			// Update counter
			$i++;
		}
	}
	
	/** Entity : Taking Orders */
	else if(isset($_GET['taking-orders'])) {
		// Request data
		$taking_orders = $db['ret']->prepare("SELECT `ordertable`.* FROM `ordertable` JOIN `usertable` u on `ordertable`.sales_name = u.id ". ($filter != "" ? "WHERE " . $filter : "") ." ORDER BY `id` DESC");
		$taking_orders->execute();
		
		// Counter
		$i = 0;
		
		// Loop for every data
		while($row = $taking_orders->fetch()) {

			// Get customer name
			$name_customer = $db['ret']->query("SELECT `name` FROM `custtable` WHERE `id` = $row[id_customer]")->fetchColumn();
			
			// Get retail name
			$name_retail = $db['ret']->query("SELECT `name` FROM `retailtable` WHERE `id` = $row[id_retail]")->fetchColumn();

			// Get sales type name
			$name_sales_type = $db['dist']->query("SELECT `name` FROM `salestypetable` WHERE `id` = '$row[sales_type]'")->fetchColumn();

			// Get sales name
			$name_sales_text = $db['ret']->query("SELECT `username` FROM `usertable` WHERE `id` = '$row[sales_name]'")->fetchColumn();

			
			$data['data'][$i] = array('id' => $row['id'], 
									  'id_retail' => $row['id_retail'],
									  'id_customer' => $row['id_customer'],
									  'name_retail' => $name_retail,
									  'name_customer' => $name_customer,
									  'delivery_status' => $row['delivery_status'],
									  'total_amount' => $row['total_amount'],
									  'code_booking' => $row['code_booking'],
									  'order_date' => $row['order_date'],
									  'sales_type' => $row['sales_type'],
									  'sales_type_name' => $name_sales_type,
									  'reason' => $row['reason'],
									  'sales_name' => $row['sales_name'],
									  'sales_name_text' => $name_sales_text,
									  'closed_date' => $row['closed_date'],
									  'approved_date' => $row['approved_date'],
									  'canceled_date' => $row['canceled_date']
									);
			
			console.log($data);
			console.log("Bisa ngeget data i");
			//echo $name_sales;
			console.log($name_sales);
			// Update counter
			$i++;
		}
	}

	/** Entity : Taking Orders Detail (Item) */
	else if(isset($_GET['taking-orders-detail'])) {
		// Request data
		$taking_orders = $db['ret']->prepare("SELECT * FROM `ordertable` ". ($filter != "" ? "WHERE " . $filter : "") ." ORDER BY `id` DESC");
		$taking_orders->execute();
		
		while($headerRow = $taking_orders->fetch()) {
			
			$headerRow['total_amount'] = doubleVal($headerRow['total_amount']);
			$data['data']['header'] = $headerRow;

			// Get item name
			$data['data']['customer'] = $db['ret']->query("SELECT `id`,`name` FROM `custtable` WHERE `id` = $headerRow[id_customer]")->fetch();

			// Get retail name
			$data['data']['retailer'] = $db['ret']->query("SELECT `id`,`name` FROM `retailtable` WHERE `id` = $headerRow[id_retail]")->fetch();

			// Get sales type name
			$data['data']['salestype'] = $db['dist']->query("SELECT `id`,`name` FROM `salestypetable` WHERE `id` = '$headerRow[sales_type]'")->fetch();

			// Get sales name
			$data['data']['sales'] = $db['ret']->query("SELECT `id`,`username` FROM `usertable` WHERE `id` = '$headerRow[sales_name]'")->fetch();

			// Counter
			$i = 0;

			$taking_orders_detail = $db['ret']->prepare("SELECT * FROM `ordertranstable` WHERE id_order = ".$headerRow['id']." ORDER BY `id` DESC");
			$taking_orders_detail->execute();
			
			// Loop for every data
			while($row = $taking_orders_detail->fetch()) {
				
				$row['price'] = doubleVal($row['price']);
				$row['total_amount'] = doubleVal($row['total_amount']);
				$row['qty'] = doubleVal($row['qty']);
				$row['discount'] = doubleVal($row['discount']);

				$data['data']['items'][$i] = $row;

				$itemDetail = $db['dist']->query("SELECT `id`, `name`, `price`, `pricekrt` FROM `itemtable` WHERE `id` = $row[id_item]")->fetch();
				$itemDetail['price'] = doubleVal($itemDetail['price']);
				$itemDetail['pricekrt'] = doubleVal($itemDetail['pricekrt']);
				$data['data']['items'][$i]['item'] = $itemDetail;

				$data['data']['items'][$i]['uom'] = $db['dist']->query("SELECT `id`,`name` FROM `uomtable` WHERE `id` = $row[id_uom]")->fetch();
				// Update counter
				$i++;
			}

			// Take 1 data only
			break;
		}
	}
	// /** Entity : Taking Orders for supervisor */
	// else if(isset($_GET['taking-orders-supervisor'])) {
	// 	// Request data
	// 	$taking_orders = $db['ret']->prepare("SELECT * FROM `ordertable` ". ($filter != "" ? "WHERE " . $filter : "") ." ORDER BY `id` DESC");
	// 	$taking_orders->execute();
		
	// 	// Counter
	// 	$i = 0;
		
	// 	// Loop for every data
	// 	while($row = $taking_orders->fetch()) {

	// 		// Get customer name
	// 		$name_customer = $db['ret']->query("SELECT `name` FROM `custtable` WHERE `id` = $row[id_customer]")->fetchColumn();
			
	// 		// Get retail name
	// 		$name_retail = $db['ret']->query("SELECT `name` FROM `retailtable` WHERE `id` = $row[id_retail]")->fetchColumn();

	// 		// Get retail name sales
	// 		$name_sales_type = $db['dist']->query("SELECT `name` FROM `salestypetable` WHERE `id` = '$row[sales_type]'")->fetchColumn();

	// 		// Get sales name
	// 		$name_sales_text = $db['ret']->query("SELECT `username` FROM `usertable` WHERE `id` = '$row[sales_name]'")->fetchColumn();
			
	// 		$data['data'][$i] = array('id' => $row['id'], 
	// 								  'id_retail' => $row['id_retail'],
	// 								  'id_customer' => $row['id_customer'],
	// 								  'name_retail' => $name_retail,
	// 								  'name_customer' => $name_customer,
	// 								  'delivery_status' => $row['delivery_status'],
	// 								  'total_amount' => $row['total_amount'],
	// 								  'code_booking' => $row['code_booking'],
	// 								  'order_date' => $row['order_date'],
	// 								  'sales_type' => $row['sales_type'],
	// 								  'sales_type_name' => $name_sales_type,
	// 								  'reason' => $row['reason'],
	// 								  'sales_name' => $row['sales_name'],
	// 								  'sales_name_text' => $name_sales_text,
	// 								  'closed_date' => $row['closed_date'],
	// 								  'approved_date' => $row['approved_date'],
	// 								  'canceled_date' => $row['canceled_date']
	// 								);
			
	// 		console.log($data);
	// 		console.log("Bisa ngeget data i");
	// 		echo $name_sales;
	// 		console.log($name_sales);
	// 		// Update counter
	// 		$i++;
	// 	}
	// }

	// /** Entity : Taking Orders for Supervisor */
	// else if(isset($_GET['taking-orders-retail-supervisor'])) {
	// 	// Request data
	// 	$taking_orders = $db['ret']->prepare("SELECT * FROM `orderretailtable` ". ($filter != "" ? "WHERE " . $filter : "") ." ORDER BY `id` DESC");
	// 	$taking_orders->execute();
		
	// 	// Counter
	// 	$i = 0;
		
	// 	// Loop for every data
	// 	while($row = $taking_orders->fetch()) {
	// 		// Get customer name
	// 		$name_customer = $db['ret']->query("SELECT `name` FROM `distributortable` WHERE `id` = $row[id_distributor]")->fetchColumn();
			
	// 		// Get retail name
	// 		$name_retail = $db['ret']->query("SELECT `name` FROM `retailtable` WHERE `id` = $row[id_retail]")->fetchColumn();

	// 		// Get retail name sales
	// 		$name_sales_type = $db['dist']->query("SELECT `name` FROM `salestypetable` WHERE `id` = '$row[sales_type]'")->fetchColumn();

	// 		// Get sales name
	// 		$name_sales_text = $db['ret']->query("SELECT `username` FROM `usertable` WHERE `id` = '$row[sales_name]'")->fetchColumn();
			
	// 		$data['data'][$i] = array('id' => $row['id'], 
	// 								  'id_retail' => $row['id_retail'],
	// 								  'id_distributor' => $row['id_distributor'],
	// 								  'name_retail' => $name_retail,
	// 								  'name_distributor' => $name_customer,
	// 								  'delivery_status' => $row['delivery_status'],
	// 								  'total_amount' => $row['total_amount'],
	// 								  'code_booking' => $row['code_booking'],
	// 								  'order_date' => $row['order_date'],
	// 								  'sales_type' => $row['sales_type'],
	// 								  'sales_type_name' => $name_sales_type,
	// 								  'reason' => $row['reason'],
	// 								  'sales_name' => $row['sales_name'],
	// 								  'sales_name_text' => $name_sales_text,
	// 								  'closed_date' => $row['closed_date'],
	// 								  'approved_date' => $row['approved_date'],
	// 								  'canceled_date' => $row['canceled_date']
	// 								);
			
	// 		console.log($data);
	// 		console.log("Bisa ngeget data i");
	// 		// Update counter
	// 		$i++;
	// 	}
	// }


	/** Entity : Taking Orders */
	else if(isset($_GET['taking-orders-retail'])) {
		// Request data
		$taking_orders = $db['ret']->prepare("SELECT `orderretailtable`.* FROM `orderretailtable` JOIN `usertable` u on `orderretailtable`.sales_name = u.id ". ($filter != "" ? "WHERE " . $filter : "") ." ORDER BY `id` DESC");
		$taking_orders->execute();
		
		// Counter
		$i = 0;
		
		// Loop for every data
		while($row = $taking_orders->fetch()) {
			// Get customer name
			$name_customer = $db['ret']->query("SELECT `name` FROM `distributortable` WHERE `id` = $row[id_distributor]")->fetchColumn();
			
			// Get retail name
			$name_retail = $db['ret']->query("SELECT `name` FROM `retailtable` WHERE `id` = $row[id_retail]")->fetchColumn();

			// Get retail name sales
			$name_sales_type = $db['dist']->query("SELECT `name` FROM `salestypetable` WHERE `id` = '$row[sales_type]'")->fetchColumn();

			// Get sales name
			$name_sales_text = $db['ret']->query("SELECT `username` FROM `usertable` WHERE `id` = '$row[sales_name]'")->fetchColumn();
			
			$data['data'][$i] = array('id' => $row['id'], 
									  'id_retail' => $row['id_retail'],
									  'id_distributor' => $row['id_distributor'],
									  'name_retail' => $name_retail,
									  'name_distributor' => $name_customer,
									  'delivery_status' => $row['delivery_status'],
									  'total_amount' => $row['total_amount'],
									  'code_booking' => $row['code_booking'],
									  'order_date' => $row['order_date'],
									  'sales_type' => $row['sales_type'],
									  'sales_type_name' => $name_sales_type,
									  'reason' => $row['reason'],
									  'sales_name' => $row['sales_name'],
									  'sales_name_text' => $name_sales_text,
									  'closed_date' => $row['closed_date'],
									  'approved_date' => $row['approved_date'],
									  'canceled_date' => $row['canceled_date']
									);
			
			console.log($data);
			console.log("Bisa ngeget data i");
			// Update counter
			$i++;
		}
	}


	/** Entity : Taking Orders Detail (Item) (retail)*/
	else if(isset($_GET['taking-orders-detail-retail'])) {

		// Request data
		$taking_orders = $db['ret']->prepare("SELECT * FROM `orderretailtable` ". ($filter != "" ? "WHERE " . $filter : "") ." ORDER BY `id` DESC");
		$taking_orders->execute();
		
		while($headerRow = $taking_orders->fetch()) {
            
            $headerRow['total_amount'] = doubleVal($headerRow['total_amount']);
			$data['data']['header'] = $headerRow;

			// Get item name
			$data['data']['distributor'] = $db['ret']->query("SELECT `id`,`name` FROM `distributortable` WHERE `id` = $headerRow[id_distributor]")->fetch();

			// Get retail name
			$data['data']['retailer'] = $db['ret']->query("SELECT `id`,`name` FROM `retailtable` WHERE `id` = $headerRow[id_retail]")->fetch();

			// Get sales type name
			$data['data']['salestype'] = $db['dist']->query("SELECT `id`,`name` FROM `salestypetable` WHERE `id` = '$headerRow[sales_type]'")->fetch();

			// Get sales name
			$data['data']['sales'] = $db['ret']->query("SELECT `id`,`username` FROM `usertable` WHERE `id` = '$headerRow[sales_name]'")->fetch();

			// Request data
			$taking_orders_detail = $db['ret']->prepare("SELECT * FROM `orderretailtranstable` WHERE id_order =".$headerRow['id']." ORDER BY `id` DESC");
			$taking_orders_detail->execute();
			
			// Counter
			$i = 0;
			
			// Loop for every data
			while($row = $taking_orders_detail->fetch()) {
				
				$row['price'] = doubleVal($row['price']);
				$row['total_amount'] = doubleVal($row['total_amount']);
				$row['qty'] = doubleVal($row['qty']);
				$row['discount'] = doubleVal($row['discount']);

				$data['data']['items'][$i] = $row;

				$itemDetail = $db['dist']->query("SELECT `id`, `name`, `price`, `pricekrt` FROM `itemtable` WHERE `id` = $row[id_item]")->fetch();
				$itemDetail['price'] = doubleVal($itemDetail['price']);
				$itemDetail['pricekrt'] = doubleVal($itemDetail['pricekrt']);
                $data['data']['items'][$i]['item'] = $itemDetail;
				$data['data']['items'][$i]['uom'] = $db['dist']->query("SELECT `id`,`name` FROM `uomtable` WHERE `id` = $row[id_uom]")->fetch();
				// Update counter
				$i++;
            }
            
            break;
		}
	}

	// /** Entity : Taking Orders Detail Update(Item) */
	// else if(isset($_GET['taking-orders-detail-update'])) {
	// 	// Request data
	// 	$taking_orders_detail = $db['ret']->prepare("SELECT * FROM `ordertranstableupdate` ". ($filter != "" ? "WHERE " . $filter : "") ." ORDER BY `id` DESC");
	// 	$taking_orders_detail->execute();
		
	// 	// Counter
	// 	$i = 0;
		
	// 	// Loop for every data
	// 	while($row = $taking_orders_detail->fetch()) {
	// 		// Get item name
	// 		$name_item = $db['dist']->query("SELECT `name` FROM `itemtable` WHERE `id` = $row[id_item]")->fetchColumn();
			
	// 		// Get uom name
	// 		$name_uom = $db['dist']->query("SELECT `name` FROM `uomtable` WHERE `id` = $row[id_uom]")->fetchColumn();
			
	// 		$data['data'][$i] = array('id' => $row['id'], 
	// 								  'id_order' => $row['id_order'],
	// 								  'id_item' => $row['id_item'],
	// 								  'id_uom' => $row['id_uom'],
	// 								  'name_item' => $name_item,
	// 								  'name_uom' => $name_uom,
	// 								  'qty' => $row['qty'],
	// 								  'discount' => $row['discount'],
	// 								  'price' => $row['price'],
	// 								  'total_amount' => $row['total_amount']);
									
	// 		// Update counter
	// 		$i++;
	// 	}
	// }


	
	// /** Entity : Taking Orders Detail (Item) (retail) UPDATE*/
	// else if(isset($_GET['taking-orders-detail-retail-update'])) {
	// 	// Request data
	// 	$taking_orders_detail = $db['ret']->prepare("SELECT * FROM `orderretailtranstableupdate` ". ($filter != "" ? "WHERE " . $filter : "") ." ORDER BY `id` DESC");
	// 	$taking_orders_detail->execute();
		
	// 	// Counter
	// 	$i = 0;
		
	// 	// Loop for every data
	// 	while($row = $taking_orders_detail->fetch()) {
	// 		// Get item name
	// 		$name_item = $db['dist']->query("SELECT `name` FROM `itemtable` WHERE `id` = $row[id_item]")->fetchColumn();
			
	// 		// Get uom name
	// 		$name_uom = $db['dist']->query("SELECT `name` FROM `uomtable` WHERE `id` = $row[id_uom]")->fetchColumn();
			
	// 		$data['data'][$i] = array('id' => $row['id'], 
	// 								  'id_order' => $row['id_order'],
	// 								  'id_item' => $row['id_item'],
	// 								  'id_uom' => $row['id_uom'],
	// 								  'name_item' => $name_item,
	// 								  'name_uom' => $name_uom,
	// 								  'qty' => $row['qty'],
	// 								  'discount' => $row['discount'],
	// 								  'price' => $row['price'],
	// 								  'total_amount' => $row['total_amount']);
									
	// 		// Update counter
	// 		$i++;
	// 	}
	// }

	// /** Entity : User */
	// else if(isset($_GET['users'])) {
	// 	// Request data
	// 	$users = $db['ret']->prepare("SELECT * FROM `usertable` ". ($filter != "" ? "WHERE " . $filter : "") ." ORDER BY `id` DESC");
	// 	$users->execute();
		
	// 	// Counter
	// 	$i = 0;
		
	// 	// Loop for every data
	// 	while($row = $users->fetch()) {
	// 		$data['data'][$i] = array('id' => $row['id'], 
	// 								  'type' => $row['type'],
	// 								  'username' => $row['username'],
	// 								  'token' => $row['token']);
									
	// 		// Update counter
	// 		$i++;
	// 	}
	// }
	
	/** Entity : Login */
	else if(isset($_GET['login'])) {
		// Request data
		$users = $db['ret']->prepare("SELECT * FROM `usertable` ". ($filter != "" ? "WHERE " . $filter : "") ." ORDER BY `id` DESC");
		$users->execute();
		
		// Counter
		$i = 0;
		
		// Loop for every data
		while($row = $users->fetch()) {
			$data['data'][$i] = array('id' => $row['id'], 
									  'type' => $row['type'],
									  'username' => $row['username'],
									  'id_region' => $row['id_region'],
									  );
									
			// Update counter
			$i++;
			if ($i > 1) {
				unset($data);
				break;
			}
		}
	}
	
	// /** Entity : Login */
	// else if(isset($_GET['login-detail'])) {
	// 	// Request data
	// 	$users = $db['ret']->prepare("SELECT * FROM `userlogintable` ". ($filter != "" ? "WHERE " . $filter : "") ." ORDER BY `id` DESC");
	// 	$users->execute();
		
	// 	// Counter
	// 	$i = 0;
		
	// 	// Loop for every data
	// 	while($row = $users->fetch()) {
	// 		$id = $db['ret']->lastInsertId();
	// 		$data['data'][$i] = array('id' => $id,
	// 								  'username' => $row['username'],
	// 								  'password' => $row['password']);
									
	// 		// Update counter
	// 		$i++;
	// 	}
	// }
	

	else {
		// Create error data
		$data['error'] = "Data entity is not provided !";
	}

?>
