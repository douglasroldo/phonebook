<?php 

	return array(
	    "database" => array(
			"host"		=>		"localhost",
			"username"	=>		"root",
			"password"	=>		"",
			"dbname"	=>		"phonebook"
		),
	     "app" => array(
	        "controllersDir" => "../app/controllers/",
	        "modelsDir"      => "../app/models/",
	        "viewsDir"       => "../app/views/",
	        "baseUri"		 => "/phonebook/",
	        "setDefaultController"	=> "contacts"
	    )
	);