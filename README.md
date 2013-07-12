Paginate
========

Example:

```php
$total = 123;# Count for all items in your pages;
$config = array(
	'itemsPerPage'		=>	20,
	'displayedPages'	=>	7,
	'method'			=>	'query',
	'param'				=>	'page',
	'show_PrevNext'		=>	TRUE
);
$paginate = new Paginate($total, $config);
```


Params avaible for config:
* itemsTotal - Total items ! REQUIRED ! In construct its the first param to be set next after it is array with all other params.
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