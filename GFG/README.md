1 - Modeling
	1.1 - Relational  (SQL)
		On this approach I designed 8 tables so we need a select with 7 joins to obtain the full data. On the other hand it saves the disk size because we never repeat sizes names or category names. It can be seen in db/model.png  (image) and db/model.sql
	1.2 - noSQL (Mongo DB)
		Considering that we already have a template (used in WMS ws) we could use it to store data with Mongo DB :
			db.catalog.insertOne(
				{
					"sku": "KRYPT-123",
        			"price": "122.99",
        			"name": "Kryptonite",
        			"description": "Anti Superman material",
        			"warehouse_control": [
        				{
        					"size": "22",
        					"warehouse": "South Warehouse",
        					"quantity": "100"        				
        				},
        				{
        					"size": "23",
        					"warehouse": "South Warehouse",
        					"quantity": "93"        				
        				},
        				{
        					"size": "24",
        					"warehouse": "West Warehouse",
        					"quantity": "34"        				
        				}
        			],
        			"brand": "Lex Luthor",
        			"categories": ["Super Heroes", "Superman", "Accessories"],
        			"product_image_url": "http://cdn.gfg.com.br/kryptonite.jpg",
        			"special_price": "0.99"
				}
			)
		That would create the Colection (named 'catalog') needed to store our catalog products. This approach scales better than the relational one and is better for applications with big data, but since it's  a newer technology (less mature) it´s harder to find good professionals to administrate.

2 - Filter and Validate
	I submited the pull request as it was ordered in the study case, but I also hosted this code at http://pousadaadanegri.com.br/gfg, there you can paste your json and use buttons Validate and Filter. Validate function only return if its valid or not and wich fields/registers have problems while Filter first make a post to Validate to check if it´s valid and if is it do the changes needed (casting types and http prefix), shows the changed data and rewrite the inputed json with the new values.

	Since i made it as webservices you can also test it using some client like Postman, you just need to send the json as raw data and use the following urls
	Validator : pousadaadanegri.com.br/gfg/validator.php 		   	
	Filter : pousadaadanegri.com.br/gfg/filter.php 	
	The returns are :

	Validator return {
	    "valid": 1|0,
	    "errors": [0..n] [	
	    	{
			   	"code": int required,
			    "field_name": string optional,
			    "sku": string optional,
			    "desc": string required
			}
	    ]
	}

	Filter return {
	    "valid": 1|0,
	    "errors": [0..n] [	
	    	{
			   	"code": 0 required,
			    "field_name": string optional,
			    "sku": string optional,
			    "desc": string required
			}
	    ]
	    "newJson" : json optional
	}

	In postman.jpg theres a print of a post to validator.php using your example json with Postman tool.	 

3 - Web Services Fail
	
	First, jQuery ajax calls have some handling on errors (as you can see in lib/script.js  line 25). If it passes this validation we can use this function: 

	function isJson($ws_response) {
		json_decode($ws_response);
		return (json_last_error() == JSON_ERROR_NONE);
	}
	
	(that is in lib/lib.php line 7) to check if the return from my WS is a json. If !isJson($ws_response) I could display a message like "Service unaviable, try later".  
