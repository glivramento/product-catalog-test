1 - Modeling
	1.1 - Relational  (SQL)
		On this approach I designed 5 tables so we need a select with 4 joins to obtain the full data. On the other hand it saves the disk size because we never repeat sizes names or category names. It can be seen in db/model.png  (image) and db/model.sql
	1.2 - noSQL (Mongo DB)
		Considering that we already have a template (used in WMS ws) we could use it to store data with Mongo DB :
			db.catalog.insertOne(
				{
					"sku": "KRYPT-123",
        			"price": "122.99",
        			"name": "Kryptonite",
        			"description": "Anti Superman material",
        			"size": ["22", "23", "24"],
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

3 - Web Services (I didn´t implement those tasks, but if it´s needed just tell me) 

	3.1 - WMS 
		For these one (considering Relational database) i would create a column `updated_at` in `gfg_catalog_products` and also create a table `gfg_wms_requests` with the columns `id_request` (pk ai), `user` (int) and `time` (datetime). Every time an user make a request to WMS we update the column `time` with the time that he performed the request (or insert a new register if the user never did it) and every time that a product is updated we update the column `updated_at` with the time of the update. So every time an user make a request we check his last request time  and only return the products with updated_at > time (returning all if he never performed this request).
		

	3.2 - Stock Webservice
		Here i would create a table `cgf_product_quantities` with `id_quant` (pk ai), `product_id` (that references `gfg_catalog_products.id_product`), `size_id` (that references `gfg_sizes.id_size`), `quantity` and `warehouse`. When the user request the quantity I would just need to select from this new table with 2 joins (to get `size` and product `sku`) and return the user the quantity and the warehouse from the records that `size` and `sku` matches the inputed ones.

	3.3 - CMS 
		Here i belive that the returned field 'region' changes if we input a category or an sku (as the placeholder if not exist). So i would check first if the input is a category or a product sku, get the information from `gfg_catalog_products` and deal with the data according to the input type and then return the html code and the region. 

