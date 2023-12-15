import json
import logging
import sys
from openai import OpenAI
import pandas as pd
from pprint import pprint

client = OpenAI(api_key="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")

def extract_information(question, model="gpt-3.5-turbo"):
	completion = client.chat.completions.create(
		model=model,
		temperature=0,
		messages=[
			{
				"role": "system",
				"content": system_prompt
			},
			{
				"role": "user",
				"content": user_prompt.format(
				  	specification=question
				)
			}
		]
    )
	return completion.choices[0].message.content

system_prompt = """You are an expert agent specialized in analyzing order and product specifications from product and order database from MySQL.
Your task is to generate the SQL for the special value and value lists from queries requested with the user prompt, from a given product and order database.
You must generate the output in a correct SQL according to the queries with joining tables.
If the query asks to return some value of 'order', SQL should return 'name','model','quantity','price','total' from 'ordered_product' table, and 'customer_id','firstname','lastname','email','telephone','payment_company' from 'order' table.
If the query asks to return some value of 'product', SQL should return 'name','model','quantity','price','total' from 'ordered_product' table.
If the query asks to return some value of 'customer', SQL should return 'customer_id','firstname','lastname','email','telephone' from 'customer' table.
If the query asks to return aggregation of the objects, SQL should return number of correct calculation.
Attempt to extract as correct SQL as you can.
"""

user_prompt = """Based on the following example, extract result from the provided text.
Use the columns from following tables:

# TABLES:
{{
  "ssiegel_customer": "https://olek:ydu-ajn!ftb7WUD3xax@steves76.sg-host.com/gpt/customer.php", 
  "ssiegel_order": "https://olek:ydu-ajn!ftb7WUD3xax@steves76.sg-host.com/gpt/order.php", 
  "ssiegel_order_product": "https://olek:ydu-ajn!ftb7WUD3xax@steves76.sg-host.com/gpt/ordered_product.php", 
  "ssiegel_order_status": "https://olek:ydu-ajn!ftb7WUD3xax@steves76.sg-host.com/gpt/order_status.php"
}}

--> Beginning of example

# Specification 1
"What is the total number of orders placed this month?"
################
# Output
[
  {{
    "SQL": "SELECT COUNT(1) orders FROM ssiegel_order LEFT JOIN ssiegel_order_status ON ssiegel_order.order_status_id=ssiegel_order_status.order_status_id WHERE ssiegel_order_status.name='Complete' AND ssiegel_order.date_added >= DATE_SUB( CURDATE(), INTERVAL 1 WEEK)"
  }}
]

# Specification 2
"Can you list the recent orders placed by Steve Siegel?"

################

# Output
[
  {{
    "SQL": "SELECT ssiegel_order_product.NAME, ssiegel_order_product.model, ssiegel_order_product.quantity, ssiegel_order_product.price, ssiegel_order_product.total, ssiegel_order.customer_id, ssiegel_order.firstname, ssiegel_order.lastname, ssiegel_order.email, ssiegel_order.telephone, ssiegel_order.payment_company FROM ssiegel_order LEFT JOIN ssiegel_order_product ON ssiegel_order.order_id = ssiegel_order_product.order_id WHERE ssiegel_order.firstname = 'Tom' AND ssiegel_order.lastname = 'Lezzi' ORDER BY ssiegel_order.order_id DESC LIMIT 10"
  }}
]


# Specification 3
"What is the most popular product ordered in the last quarter?"

################

You have to use 'date_added' field as the ordered data.

# Output
[
	{{
		"SQL": "SELECT ssiegel_order_product.product_id, ssiegel_order_product.name, ssiegel_order_product.model, COUNT(ssiegel_order_product.product_id) AS total_orders FROM ssiegel_order_product LEFT JOIN ssiegel_order ON ssiegel_order_product.order_id = ssiegel_order.order_id WHERE ssiegel_order.date_added >= DATE_SUB( CURDATE(), INTERVAL 3 MONTH ) GROUP BY ssiegel_order_product.product_id, ssiegel_order_product.name, ssiegel_order_product.model ORDER BY total_orders DESC LIMIT 1"
	}}
]

# Specification 4
"How many repeat customers did we have in the past month?"

################

# Output
[
	{{
		"SQL": "SELECT COUNT(DISTINCT ssiegel_order.customer_id) FROM ssiegel_order LEFT JOIN ssiegel_order_status ON ssiegel_order.order_status_id=ssiegel_order_status.order_status_id WHERE ssiegel_order_status.name='Complete' AND ssiegel_order.date_added >= DATE_SUB( CURDATE(), INTERVAL 1 MONTH )"
	}}
]

# Specification 5
"What was the average order value last month?"

################

# Output
[
	{{
		"SQL": "SELECT AVG(ssiegel_order_product.total) FROM ssiegel_order_product LEFT JOIN ssiegel_order ON ssiegel_order_product.order_id = ssiegel_order.order_id LEFT JOIN ssiegel_order_status ON ssiegel_order.order_status_id=ssiegel_order_status.order_status_id WHERE ssiegel_order_status.name='Complete' AND ssiegel_order.date_added >= DATE_SUB( CURDATE(), INTERVAL 1 MONTH )"
	}}
]


# Specification 6
"What was the average order value last month?"

################

You have to use 'date_added' field as the ordered data.
And, you have to calculate order count where 'order_status_id' field value from 'order' table is same as one to be 'Completed' in 'ssiegel_order_status' table.
SQL should return 'customer_id','firstname','lastname','email',and 'telephone'.

# Output
[
	{{
		"SQL": "SELECT ssiegel_order.customer_id, ssiegel_customer.firstname, ssiegel_customer.lastname, ssiegel_customer.email, ssiegel_customer.telephone, SUM(ssiegel_order_product.total) AS total_order_value FROM ssiegel_order LEFT JOIN ssiegel_order_product ON ssiegel_order.order_id = ssiegel_order_product.order_id LEFT JOIN ssiegel_customer ON ssiegel_order.customer_id = ssiegel_customer.customer_id WHERE ssiegel_order.order_status_id IN (SELECT order_status_id FROM ssiegel_order_status WHERE name = 'Complete') GROUP BY ssiegel_order.customer_id, ssiegel_customer.firstname, ssiegel_customer.lastname, ssiegel_customer.email, ssiegel_customer.telephone ORDER BY total_order_value DESC LIMIT 1"
	}}
]

# Specification 7
"Can you show the order history for Customer ID 3?"

################

# Output
[
	{{
		"SQL": "SELECT ssiegel_order.date_added, ssiegel_order_product.name, ssiegel_order_product.model, ssiegel_order_product.quantity, ssiegel_order_product.price, ssiegel_order_product.total, ssiegel_order.customer_id, ssiegel_order.firstname, ssiegel_order.lastname, ssiegel_order.email, ssiegel_order.telephone, ssiegel_order.payment_company FROM ssiegel_order LEFT JOIN ssiegel_order_product ON ssiegel_order.order_id = ssiegel_order_product.order_id WHERE ssiegel_order.customer_id = 3"
	}}
]

# Specification 8
"What are the top five zip codes with the highest number of orders?"

################

# Output
[
	{{
		"SQL": "SELECT ssiegel_order.payment_postcode, COUNT(ssiegel_order.order_id) AS total_orders FROM ssiegel_order LEFT JOIN ssiegel_order_status ON ssiegel_order.order_status_id=ssiegel_order_status.order_status_id WHERE ssiegel_order_status.name='Complete' GROUP BY ssiegel_order.payment_postcode ORDER BY total_orders DESC LIMIT 5"
	}}
]

# Specification 9
"How many new customers did we acquire in the previous month?"

################

You have to calculate order count where 'order_status_id' field value from 'order' table is same as one to be 'Completed' in 'ssiegel_order_status' table.

# Output
[
	{{
		"SQL": "SELECT COUNT(DISTINCT ssiegel_order.customer_id) FROM ssiegel_order LEFT JOIN ssiegel_order_status ON ssiegel_order.order_status_id=ssiegel_order_status.order_status_id WHERE ssiegel_order_status.name='Complete' AND ssiegel_order.date_added >= DATE_SUB( CURDATE(), INTERVAL 1 MONTH ) AND ssiegel_order.customer_id NOT IN (SELECT customer_id FROM ssiegel_order WHERE date_added < DATE_SUB( CURDATE(), INTERVAL 1 MONTH ))"
	}}
]

# Specification 10
"What is the status of Order ID 3?"

################

# Output
[
	{{
		"SQL": "SELECT ssiegel_order_status.name FROM ssiegel_order LEFT JOIN ssiegel_order_status ON ssiegel_order.order_status_id=ssiegel_order_status.order_status_id WHERE ssiegel_order.order_id=3"
	}}
]

# Specification 12
"How many orders were completed last week?"

You have to calculate order count where 'order_status_id' field value from 'order' table is same as one to be 'Completed' in 'ssiegel_order_status' table.

################

# Output
[
	{{
		"SQL": "SELECT ssiegel_order_status.name FROM ssiegel_order LEFT JOIN ssiegel_order_status ON ssiegel_order.order_status_id=ssiegel_order_status.order_status_id WHERE ssiegel_order.order_id=3"
	}}
]

# Specification 13
"Can you list all orders in Quote status?"

################

# Output
[
	{{
		"SQL": "SELECT ssiegel_order_product.NAME, ssiegel_order_product.model, ssiegel_order_product.quantity, ssiegel_order_product.price, ssiegel_order_product.total, ssiegel_order.customer_id, ssiegel_order.firstname, ssiegel_order.lastname, ssiegel_order.email, ssiegel_order.telephone, ssiegel_order.payment_company FROM ssiegel_order LEFT JOIN ssiegel_order_product ON ssiegel_order.order_id = ssiegel_order_product.order_id LEFT JOIN ssiegel_order_status ON ssiegel_order_status.order_status_id = ssiegel_order.order_status_id WHERE ssiegel_order_status.name = 'Quote'"
	}}
]

# Specification 14
"What is the average time from when order is 'dated_added' to 'complete'?"

################

# Output
[
	{{
		"SQL": "SELECT AVG(TIMESTAMPDIFF(DAY, ssiegel_order.date_added, ssiegel_order.date_modified)) FROM ssiegel_order LEFT JOIN ssiegel_order_status ON ssiegel_order.order_status_id=ssiegel_order_status.order_status_id WHERE ssiegel_order_status.name='Complete'"
	}}
]

# Specification 15
"How many orders were canceled in the last six months?"

################

# Output
[
	{{
		"SQL": "SELECT COUNT(1) Canceled_Order FROM ssiegel_order LEFT JOIN ssiegel_order_status ON ssiegel_order.order_status_id=ssiegel_order_status.order_status_id WHERE ssiegel_order_status.name='Canceled' AND ssiegel_order.date_added >= DATE_SUB( CURDATE(), INTERVAL 6 MONTH )"
	}}
]

# Specification 16
"Who are our top three customers in terms of order frequency?"

################

You have to calculate order count where 'order_status_id' field value from 'order' table is same as one to be 'Completed' in 'ssiegel_order_status' table.

# Output
[
	{{
		"SQL": "SELECT ssiegel_customer.customer_id, ssiegel_customer.firstname, ssiegel_customer.lastname, ssiegel_customer.email, ssiegel_customer.telephone, COUNT(ssiegel_order.order_id) AS order_frequency FROM ssiegel_customer LEFT JOIN ssiegel_order ON ssiegel_customer.customer_id = ssiegel_order.customer_id WHERE ssiegel_order.order_status_id IN (SELECT order_status_id FROM ssiegel_order_status WHERE name = 'Complete') GROUP BY ssiegel_customer.customer_id, ssiegel_customer.firstname, ssiegel_customer.lastname ORDER BY order_frequency DESC LIMIT 3"
	}}
]

# Specification 17
"What percentage of customers made repeat purchases within 30 days?"

################

You have to calculate order count where 'order_status_id' field value from 'order' table is same as one to be 'Completed' in 'ssiegel_order_status' table.

# Output
[
	{{
		"SQL": "SELECT (COUNT(repeat_purchase) / (SELECT COUNT(DISTINCT ssiegel_customer.customer_id) FROM ssiegel_customer) * 100) AS repeat_purchase_percentage FROM (SELECT ssiegel_order.customer_id, COUNT(ssiegel_order.customer_id) repeat_purchase FROM ssiegel_order LEFT JOIN ssiegel_order_status ON ssiegel_order.order_status_id = ssiegel_order_status.order_status_id WHERE ssiegel_order_status.name = 'Complete' AND ssiegel_order.date_added >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) GROUP BY ssiegel_order.customer_id) t WHERE repeat_purchase > 2;"
	}}
]

# Specification 18
"How many customers have not made any purchases in the last year?"

################

# Output
[
	{{
		"SQL": "SELECT COUNT(DISTINCT ssiegel_customer.customer_id) Idle_Customers FROM ssiegel_customer LEFT JOIN ssiegel_order ON ssiegel_customer.customer_id = ssiegel_order.customer_id LEFT JOIN ssiegel_order_status ON ssiegel_order.order_status_id=ssiegel_order_status.order_status_id WHERE ssiegel_order.customer_id IS NULL OR ssiegel_order.date_added < DATE_SUB( CURDATE(), INTERVAL 1 YEAR ) OR ssiegel_order_status.name <> 'Completed'"
	}}
]

# Specification 19
"Which day of the week do we see the highest number of orders?"

################

You have to calculate order count where 'order_status_id' field value from 'order' table is same as one to be 'Completed' in 'ssiegel_order_status' table.

# Output
[
	{{
		"SQL": "SELECT DAYNAME(ssiegel_order.date_added) AS day_of_week, COUNT(ssiegel_order.order_id) AS total_orders FROM ssiegel_order LEFT JOIN ssiegel_order_status ON ssiegel_order.order_status_id=ssiegel_order_status.order_status_id WHERE ssiegel_order_status.name='Complete' GROUP BY day_of_week ORDER BY total_orders DESC LIMIT 1"
	}}
]

# Specification 20
"Can the chatbot identify any trends in customer purchasing behavior?"
"Can the chatbot forecast demand for products based on historical sales data and current market trends?"

################

# Output
[
	{{
		"SQL": "SELECT 'NOT YET' AS answer"
	}}
]

# Specification 21
"Who are the top clients in New York?"

################

You have to calculate order count where 'order_status_id' field value from 'order' table is same as one to be 'Completed' in 'ssiegel_order_status' table.

# Output
[
	{{
		"SQL": "SELECT ssiegel_customer.customer_id, ssiegel_customer.firstname, ssiegel_customer.lastname, ssiegel_customer.email, ssiegel_customer.telephone, COUNT(ssiegel_order.order_id) AS order_frequency FROM ssiegel_customer LEFT JOIN ssiegel_order ON ssiegel_customer.customer_id = ssiegel_order.customer_id WHERE ssiegel_order.order_status_id IN (SELECT order_status_id FROM ssiegel_order_status WHERE name = 'Complete') AND ssiegel_order.payment_city = 'New York' GROUP BY ssiegel_customer.customer_id, ssiegel_customer.firstname, ssiegel_customer.lastname ORDER BY order_frequency DESC LIMIT 5"
	}}
]

# Specification 22
"Which client has a the most quotes in the system versus completed status orders?"

################

# Output
[
	{{
		"SQL": "SELECT * FROM (SELECT ssiegel_customer.customer_id, ssiegel_customer.firstname, ssiegel_customer.lastname, ssiegel_customer.email, ssiegel_customer.telephone, COUNT(DISTINCT CASE WHEN ssiegel_order_status.name = 'Quote' THEN ssiegel_order.order_id END) AS quote_count, COUNT(DISTINCT CASE WHEN ssiegel_order_status.name = 'Complete' THEN ssiegel_order.order_id END) AS completed_count FROM ssiegel_customer LEFT JOIN ssiegel_order ON ssiegel_customer.customer_id = ssiegel_order.customer_id LEFT JOIN ssiegel_order_status ON ssiegel_order.order_status_id = ssiegel_order_status.order_status_id GROUP BY ssiegel_customer.customer_id, ssiegel_customer.firstname, ssiegel_customer.lastname, ssiegel_customer.email, ssiegel_customer.telephone) t ORDER BY quote_count/completed_count DESC LIMIT 1"
	}}
]

# Specification 24
"How many new clients did I get in a year?"

################

You have to calculate order count where 'order_status_id' field value from 'order' table is same as one to be 'Completed' in 'ssiegel_order_status' table.

# Output
[
	{{
		"SQL": "SELECT COUNT(DISTINCT ssiegel_customer.customer_id) New_Customers FROM ssiegel_customer LEFT JOIN ssiegel_order ON ssiegel_customer.customer_id = ssiegel_order.customer_id LEFT JOIN ssiegel_order_status ON ssiegel_order.order_status_id=ssiegel_order_status.order_status_id WHERE ssiegel_order.date_added >= DATE_SUB( CURDATE(), INTERVAL 1 YEAR ) AND ssiegel_order.customer_id NOT IN (SELECT customer_id FROM ssiegel_order WHERE date_added < DATE_SUB( CURDATE(), INTERVAL 1 YEAR )) AND ssiegel_order_status.`name`='Completed'"
	}}
]

# Specification 24
"What products have not been purchased in a year?"

################

# Output
[
	{{
		"SQL": "SELECT DISTINCT ssiegel_order_product.product_id, ssiegel_order_product.name, ssiegel_order_product.model FROM ssiegel_order_product LEFT JOIN ssiegel_order ON ssiegel_order_product.order_id = ssiegel_order.order_id LEFT JOIN ssiegel_order_status ON ssiegel_order.order_status_id=ssiegel_order_status.order_status_id WHERE ssiegel_order.date_added < DATE_SUB( CURDATE(), INTERVAL 1 YEAR ) AND ssiegel_order_product.product_id NOT IN (SELECT product_id FROM ssiegel_order_product WHERE date_added >= DATE_SUB( CURDATE(), INTERVAL 1 YEAR )) AND ssiegel_order_status.name <> 'Complete'"
	}}
]

# Specification 25
"What is the average unit price for Bamboo Powder product? Based on what quantity?"

################

# Output
[
	{{
		"SQL": "SELECT SUM(price * quantity) / SUM(quantity) FROM ssiegel_order_product WHERE name = 'Bamboo Powder'"
	}}
]

# Specification 26
"What is the profit margin for Bamboo Powder product?"

################

# Output
[
	{{
		"SQL": "SELECT (SUM(ssiegel_order_product.total) - SUM(ssiegel_order_product.cost * ssiegel_order_product.quantity)) / SUM(ssiegel_order_product.total) * 100 profit margin FROM ssiegel_order_product WHERE ssiegel_order_product.name = 'Bamboo Powder'"
	}}
]

# Specification 27
"How many total customers are there in New York?"

################

# Output
[
	{{
		"SQL": "SELECT COUNT(DISTINCT customer_id) FROM ssiegel_order WHERE shipping_city = 'New York'"
	}}
]

# Specification 28
"How many customers have canceled product?"

################

# Output
[
	{{
		"SQL": "SELECT COUNT(DISTINCT ssiegel_order.customer_id) customers FROM ssiegel_order LEFT JOIN ssiegel_order_status ON ssiegel_order.order_status_id=ssiegel_order_status.order_status_id WHERE ssiegel_order_status.name='Canceled'"
	}}
]

--> End of example

For the following specification, generate values or lists as in the provided example.

# Specification
{specification}
################

# Output

"""

kg = []

try:
	result = extract_information(sys.argv[1])
	data = json.loads(result)
	kg.extend(data)
except Exception as e:
	logging.error(e)

kg_relations = pd.DataFrame(kg)

for _, row in kg_relations.iterrows():
  print(row['SQL'])

