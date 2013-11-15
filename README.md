Paginate
========

Example:

```php
 $config = array(
 	'totalItems'		=> 1234,
	'itemsPerPage'		=>	20,
	'displayedPages'	=>	7,
	'method'			=>	'query',
	'param'				=>	'page',
	'show_PrevNext'		=>	TRUE
);
$paginate = new Paginate($config);
```

Public functions:
```php
 # returns all pages, ready to be loaded in a template.
function getPages(){}

# returns builded pagination HTML
function renderHtml($layout, $engine = FALSE, $engineObject = FALSE){} 

# get current page numb 
function getCurrent(){}

# get offset limit for queries
function getOffset(){}

# Set totalItems if you cant count them on class init
function setTotalItems($totalItems){}

# Set current page - overwrites if there is set.
function setCurrentPage($page){}

# Resets any param from the init params
function setParam($item, $value){} 

# Resets any param from the init params for multiple elements ($params must be array!)
function setParams($params){} 
```



Params for function renderHtml:
* $layout - fullpath to a layout file. You can see layouts for examples
* $engine - a template engine. Currently you can set:
	* FALSE or nothing = clean PHP code will be loaded
	* smarty - this will use smarty template engine to build your template
* $engineObject - if $engine is selected here you pass its object !
! Remember ! Paginate class won't create any template engine object. That's way you may only pass TE object to it!


Params avaible for config:
* totalItems - Total items ! REQUIRED ! In construct its the first param to be set next after it is array with all other params.
* itemsPerPage - Items in a page needed for counting pages.
* displayedPages - Max page links to be displayed.
* baseUrl - base url for generating pages and catching current page.
* current - you can set current page if you want to fix current page. 
* method - method to get/set page (query or segment)
	* param = needed for method query! This equals to $_GET param
	* segment = needed for method segment! This equals to segment id from url separated by slash.. : "domain/1/2/3/4/5/6/.."
* Options
	* show_PrevNext - display Previous and Next buttons
	* show_FristLast - display First and Last buttons
	* FirstToLast - display pages in order as: 1 [separator] 7 8 9 [separator] last 
* PrevNext array
	* prevName - Value for Previous button (default: '<<')
	* nextName - Value for Next button (default: '>>')
* FirstLast array
	* firstName - Value for First button (default: 'First')
	* lastName - Value for Last button (default: 'Last')

When calling


--------------

<script data-gittip-username="sv3tli0" src="//gttp.co/v1.js"></script>

[![Flattr this git repo](http://api.flattr.com/button/flattr-badge-large.png)](https://flattr.com/submit/auto?user_id=sv3tli0&url=https://github.com/sv3tli0/Paginate&description=PHP+Paginate+script&title=Paginate&tags=github&category=software)
