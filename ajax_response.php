<?php
require "config.php";
define('CREATE_SQL', "Here, order status contains 'Pending', 'Warehouse (Processing)', 'Complete', 'Canceled', 'Payment Issue', 'Refunded', 'Quote', 'Tech Docs', 'Documents Uploaded', 'Invoicing', 'Ship Prep', 'Treatment', 'Backordered', 'Confirmed', 'Warehouse Settled', 'Production', 'Received', 'Loading', 'Awaiting Approval', 'Awaiting Payment', 'Internal', 'Packed'). Table definition: CREATE TABLE `ssiegel_customer`  (
	  `customer_id` int NOT NULL AUTO_INCREMENT,
	  `customer_group_id` int NOT NULL,
	  `store_id` int NOT NULL DEFAULT 0,
	  `profile_picture` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `language_id` int NOT NULL,
	  `firstname` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `lastname` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `email` varchar(96) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `telephone` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `fax` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `password` varchar(40) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `salt` varchar(9) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `cart` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
	  `wishlist` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
	  `newsletter` tinyint(1) NOT NULL DEFAULT 0,
	  `address_id` int NOT NULL DEFAULT 0,
	  `custom_field` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `ip` varchar(40) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `status` tinyint(1) NOT NULL,
	  `approved` tinyint(1) NOT NULL,
	  `safe` tinyint(1) NOT NULL,
	  `token` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `code` varchar(40) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `date_added` datetime NOT NULL,
	  `avail_credit` decimal(15, 4) NOT NULL DEFAULT 0.0000,
	  `shipping_account_method` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NULL DEFAULT NULL,
	  `shipping_account_number` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NULL DEFAULT NULL,
	  `additional_emails` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  PRIMARY KEY (`customer_id`) USING BTREE,
	  INDEX `customer_group_id`(`customer_group_id` ASC) USING BTREE,
	  INDEX `store_id`(`store_id` ASC) USING BTREE,
	  INDEX `language_id`(`language_id` ASC) USING BTREE,
	  INDEX `address_id`(`address_id` ASC) USING BTREE
	) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;
	CREATE TABLE `ssiegel_order`  (
	  `order_id` int NOT NULL AUTO_INCREMENT,
	  `invoice_no` int NOT NULL DEFAULT 0,
	  `invoice_prefix` varchar(26) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `store_id` int NOT NULL DEFAULT 0,
	  `store_name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `store_url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `customer_id` int NOT NULL DEFAULT 0,
	  `customer_group_id` int NOT NULL DEFAULT 0,
	  `firstname` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `lastname` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `email` varchar(96) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `telephone` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `fax` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `custom_field` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `payment_firstname` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `payment_lastname` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `payment_company` varchar(40) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `payment_address_1` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `payment_address_2` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `payment_city` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `payment_postcode` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `payment_country` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `payment_country_id` int NOT NULL,
	  `payment_zone` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `payment_zone_id` int NOT NULL,
	  `payment_address_format` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `payment_custom_field` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `payment_method` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `payment_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `shipping_firstname` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `shipping_lastname` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `shipping_company` varchar(40) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `shipping_address_1` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `shipping_address_2` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `shipping_city` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `shipping_postcode` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `shipping_country` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `shipping_country_id` int NOT NULL,
	  `shipping_zone` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `shipping_zone_id` int NOT NULL,
	  `shipping_address_format` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `shipping_custom_field` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `shipping_method` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `shipping_code` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `comment` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `total` decimal(15, 4) NOT NULL DEFAULT 0.0000,
	  `order_status_id` int NOT NULL DEFAULT 0,
	  `affiliate_id` int NOT NULL,
	  `commission` decimal(15, 4) NOT NULL,
	  `marketing_id` int NOT NULL,
	  `tracking` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `language_id` int NOT NULL,
	  `currency_id` int NOT NULL,
	  `currency_code` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `currency_value` decimal(15, 8) NOT NULL DEFAULT 1.00000000,
	  `ip` varchar(40) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `forwarded_ip` varchar(40) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `user_agent` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `accept_language` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `date_added` datetime NOT NULL,
	  `date_modified` datetime NOT NULL,
	  `weight` decimal(15, 8) NOT NULL DEFAULT 0.00000000,
	  `invoice_filename` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `payment_cost` decimal(15, 4) NOT NULL DEFAULT 0.0000,
	  `shipping_cost` decimal(15, 4) NOT NULL DEFAULT 0.0000,
	  `extra_cost` decimal(15, 4) NOT NULL DEFAULT 0.0000,
	  `banner_id` int NOT NULL,
	  `tax_exempt` tinyint(1) NOT NULL DEFAULT 0,
	  `payment_address_id` int NOT NULL DEFAULT 0,
	  `shipping_address_id` int NOT NULL DEFAULT 0,
	  `optional_fees` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `tax_custom_ship` tinyint(1) NOT NULL DEFAULT 0,
	  `layaway_deposit` decimal(15, 4) NOT NULL DEFAULT 0.0000,
	  `customer_ref` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
	  `transaction_id` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL DEFAULT '',
	  `order_refunded` tinyint(1) NOT NULL DEFAULT 0,
	  `cc_last_4` int NOT NULL DEFAULT 0,
	  `order_paid` tinyint(1) NOT NULL DEFAULT 0,
	  `custorderref` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
	  `recipients` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `tax_override` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `sales_agent` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
	  `check_number` int NOT NULL DEFAULT 0,
	  `check_date` int NOT NULL DEFAULT 0,
	  `bank_name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
	  `purchase_order` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
	  `cart_weight` decimal(5, 2) NOT NULL DEFAULT 0.00,
	  `tracking_number` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
	  `invoice_number` varchar(35) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
	  `catalog_admin` tinyint(1) NOT NULL DEFAULT 0,
	  `po_number` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
	  `payment_date` int NOT NULL DEFAULT 0,
	  `invoice_sent` tinyint(1) NOT NULL DEFAULT 0,
	  `invoice_date` datetime NOT NULL,
	  `date_invoice` datetime NULL DEFAULT NULL,
	  `user_create` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
	  `user_edit` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
	  `ga_client_id` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
	  PRIMARY KEY (`order_id`) USING BTREE,
	  INDEX `customer_id`(`customer_id` ASC, `date_added` ASC, `total` ASC, `email` ASC, `firstname` ASC, `lastname` ASC, `payment_company` ASC) USING BTREE,
	  INDEX `store_id`(`store_id` ASC) USING BTREE,
	  INDEX `customer_group_id`(`customer_group_id` ASC) USING BTREE,
	  INDEX `payment_country_id`(`payment_country_id` ASC) USING BTREE,
	  INDEX `payment_zone_id`(`payment_zone_id` ASC) USING BTREE,
	  INDEX `shipping_country_id`(`shipping_country_id` ASC) USING BTREE,
	  INDEX `shipping_zone_id`(`shipping_zone_id` ASC) USING BTREE,
	  INDEX `order_status_id`(`order_status_id` ASC) USING BTREE,
	  INDEX `affiliate_id`(`affiliate_id` ASC) USING BTREE,
	  INDEX `marketing_id`(`marketing_id` ASC) USING BTREE,
	  INDEX `language_id`(`language_id` ASC) USING BTREE,
	  INDEX `currency_id`(`currency_id` ASC) USING BTREE,
	  INDEX `banner_id`(`banner_id` ASC) USING BTREE,
	  INDEX `payment_address_id`(`payment_address_id` ASC) USING BTREE,
	  INDEX `shipping_address_id`(`shipping_address_id` ASC) USING BTREE,
	  INDEX `transaction_id`(`transaction_id` ASC) USING BTREE
	) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;
	CREATE TABLE `ssiegel_order_product`  (
	  `order_product_id` int NOT NULL AUTO_INCREMENT,
	  `order_id` int NOT NULL,
	  `product_id` int NOT NULL,
	  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `model` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `quantity` int NOT NULL,
	  `price` decimal(15, 4) NOT NULL DEFAULT 0.0000,
	  `total` decimal(15, 4) NOT NULL DEFAULT 0.0000,
	  `tax` decimal(15, 4) NOT NULL DEFAULT 0.0000,
	  `reward` int NOT NULL,
	  `sku` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
	  `upc` varchar(12) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
	  `location` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
	  `weight` decimal(5, 2) NOT NULL DEFAULT 0.00,
	  `weight_class_id` int NOT NULL DEFAULT 0,
	  `ship` tinyint(1) NOT NULL DEFAULT 0,
	  `cost` decimal(15, 4) NOT NULL DEFAULT 0.0000,
	  `base_price` decimal(15, 4) NOT NULL DEFAULT 0.0000,
	  PRIMARY KEY (`order_product_id`) USING BTREE,
	  INDEX `product_id`(`product_id` ASC, `total` ASC, `price` ASC, `tax` ASC, `quantity` ASC) USING BTREE,
	  INDEX `order_id`(`order_id` ASC) USING BTREE,
	  INDEX `weight_class_id`(`weight_class_id` ASC) USING BTREE
	) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;
	CREATE TABLE `ssiegel_order_status`  (
	  `order_status_id` int NOT NULL AUTO_INCREMENT,
	  `language_id` int NOT NULL,
	  `name` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `fontcolor` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `bgcolor` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
	  `background` varchar(7) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
	  PRIMARY KEY (`order_status_id`, `language_id`) USING BTREE,
	  INDEX `language_id`(`language_id` ASC) USING BTREE
	) ENGINE = InnoDB AUTO_INCREMENT = 30 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;");

function getResult($request)
{
	$question = "Create a SQL for MySQL and give me it without explain, resulting \"$request\" from these tables, and don't change the field names dynamically after you named it. " . CREATE_SQL;

	$prompt = [["role"=> "system", "content"=> $question]];

	$ch = curl_init(API_ENDPOINT);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
		'model' => 'gpt-3.5-turbo-1106',
		'messages' => $prompt,
		'max_tokens' => 500,
	]));

	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		'Content-Type: application/json',
		'Authorization: Bearer ' . API_KEY,
	]);

	$response = curl_exec($ch);

	if (curl_errno($ch)) {
		curl_close($ch);
		$res = array(
			'status' => 'failed',
			'message' => 'Can not get valid SQL.'
		);
		echo json_encode($res);
	} else {
		$result = json_decode($response, true);
		curl_close($ch);
		if(isset($result['choices']))
		{
			$sql = $result['choices'][0]['message']['content'];

			$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);  
  			if ($db->connect_errno) {  
				$res = array(
					'status' => 'failed',
					'message' => 'Can not connect MySQL server.'
				);
				echo json_encode($res);
				die;
			}

			$result = mysqli_query($db, $sql);
			if (!$result) {
				$res = array(
					'status' => 'failed',
					'message' => mysqli_error($db)
				);
				echo json_encode($res);
				die;
			}

			$res = array();
			$count = 0;
			if (mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_assoc($result)) {
					foreach($row as $id => $val)
						$res[$count][$id] = $val;
					$count++;
				}
			}
			mysqli_close($db);
			
			$res = array(
				'status' => 'success',
				'message' => $res
			);
			echo json_encode($res);
		}else{
			$res = array(
				'status' => 'failed',
				'message' => 'Can not get answer.'
			);
			echo json_encode($res);
		}
	}
}

function getAvailable($request)
{
	$question = "Say only answer for 'Yes' or 'No' whether you can generate the correct sql in MySQL for \"$request\" from these tables: " . CREATE_SQL;

	$prompt = [["role"=> "system", "content"=> $question]];

	$ch = curl_init(API_ENDPOINT);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
		'model' => 'gpt-3.5-turbo-1106',
		'messages' => $prompt,
		'max_tokens' => 10,
	]));

	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		'Content-Type: application/json',
		'Authorization: Bearer ' . API_KEY,
	]);

	$response = curl_exec($ch);

	if (curl_errno($ch)) {
		curl_close($ch);
		$res = array(
			'status1' => 'failed',
			'message1' => 'Can not get valid query.'
		);
		echo json_encode($res);
	} else {
		$result = json_decode($response, true);
		curl_close($ch);
		if(isset($result['choices']))
		{
			$res = array(
				'status1' => 'success',
				'message1' => $result['choices'][0]['message']['content']
			);
			echo json_encode($res);
		}else{
			$res = array(
				'status1' => 'failed',
				'message1' => 'Can not get answer.'
			);
			echo json_encode($res);
		}
	}
}

$jsonStr = file_get_contents('php://input'); 
$jsonObj = json_decode($jsonStr); 

if($jsonObj->request_type == 'getAvailable')
{
	getAvailable($jsonObj->question);
	die;
}
else if($jsonObj->request_type == 'getResult') {
	getResult($jsonObj->question);
	die;
}
?>