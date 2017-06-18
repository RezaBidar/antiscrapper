Scrapper Prevention
===
Information
---
* Problem number : 4
* Email : reza.smart306@gmail.com
* Programming Language : PHP
* Framework : Codeigniter

Requirements
---
1. PHP 5.4 or greater
2. MySql 5.1+
3. Apache 2.4+
3. PHP extensions : GD2

_captcha folder inside public_html should has write premission_

Installation
---
1. write your database connection in `path/to/project/application/config/database.php`
	```php

	//path/to/project/application/config/database.php
	$db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => '',
	'password' => '',
	'database' => '',

	```
2. Load migrate controller `http://localhost/path/to/project/public_html/migrate`

How It Work
---
1. I made a fake site in `path/to/project/test_site/` to test project
2. I write a rule for apache in `path/to/project/test_site/.htaccess` that it redirect all page request to app controller `path/to/project/application/controller/app.php`
3. app controller with blow algorithm proccess user ip
	1. if user ip be in white list do nothing
	2. if user ip be in black list show access denied error
	3. if user ip be in gray list show captcha
		* if user answer incorrect to captcha more than wrong_captcha_limit then it push to blacklist
	4. save user ip 
		* if user request be more than its limit then it push to gray list
4. You can change the settings in `http://localhost/path/to/project/public_html/setting`

