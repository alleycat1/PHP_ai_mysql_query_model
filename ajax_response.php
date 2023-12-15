<?php
require "config.php";
require "./vendor/autoload.php";
use Orhanerday\OpenAi\OpenAi;

function getResult($request)
{
	$client = new OpenAi(API_KEY);

	$content = file_get_contents("content.txt");
	$target = str_replace("##question##", $request, $content);

	$chat = $client->chat([
		'model' => 'gpt-3.5-turbo',
		'temperature' => 0,
		'messages' => [
			[
				"role" => "system",
				"content" => "You are an expert agent specialized in analyzing order and product specifications from product and order database from MySQL.
				Your task is to generate the SQL for the special value and value lists from queries requested with the user prompt, from a given product and order database.
				You must generate the output in a correct SQL according to the queries with joining tables.
				If the query asks to return some value of 'order', SQL should return 'name','model','quantity','price','total' from 'ordered_product' table, and 'customer_id','firstname','lastname','email','telephone','payment_company' from 'order' table.
				If the query asks to return some value of 'product', SQL should return 'name','model','quantity','price','total' from 'ordered_product' table.
				If the query asks to return some value of 'customer', SQL should return 'customer_id','firstname','lastname','email','telephone' from 'customer' table.
				If the query asks to return aggregation of the objects, SQL should return number of correct calculation.
				Attempt to extract as correct SQL as you can."
			],
			[
				"role" => "user",
				"content" => $target
			],
		]
	]);

	$d = json_decode($chat);
	$arr = json_decode($d->choices[0]->message->content, true);
	if(count($arr) == 1 && isset($arr[0]['SQL']))
		$output = $arr[0]['SQL'];
	if(strpos($output, "SELECT") == 0)
	{
		$sql = $output;

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
			'message' => array('answer'=>$output)
		);
		echo json_encode($res);
	}
}

$jsonStr = file_get_contents('php://input'); 
$jsonObj = json_decode($jsonStr); 

if($jsonObj->request_type == 'getResult') {
	getResult($jsonObj->question);
	die;
}
?>