<?php
if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}
/* *************************************************
*
*  config.inc.php
*  Samstyle PHP Framework
*  Framework Configuration
*
************************************************* */

/* *************************************************
*
*  $_SITE
*  Website information: Application name, Version, Copyright,
*  	MySQL information, GZip, application root
*
************************************************* */
$_SITE = array();

/* Application name */

/* specify whether will auto parse HTTP arguments ($_GET or $_POST)
into $get and $post to prevent injection/XSS or other threats */
$_SITE['autoparsehttpargs'] = true;

/* the length of the session in seconds */
$_SITE['session_length'] = 36000;

/* maintenance mode - false or message string */
$_SITE['maintenance'] = false;
/* hint: you can use 'maintenance' from a static HTML file
    i.e. $_SITE['maintenance'] = file_get_contents('path/to/my.html');
*/

/* the website's default charset */
$_SITE['charset'] = 'utf-8';

/* the website's language */
$_SITE['language'] = 'en';

/* translate */
$_SITE['lang_translate'] = 'cn';
/* set $_SITE['lang_translate'] to false to disable automatic translation. */

/* whether or not to automatically register all PHP-JS enabled functions. boolean*/
$_SITE['autoregisterjsfunction'] = true;
/* NOTE: only works when $_ajax is not set to false */

/* error handling settings */
$_SITE['error'] = array(
'level' => E_ALL & ~E_NOTICE,
'handler_func' => '',
'display'=>true,
'log'=>true,
'logfile' => 'error.log'
);
/* To disable error handling, set $_SITE['error'] to false */
/* error handling settings */


/* *************************************************
*
*  $_CONF
*  Configuration information, your configuration like API keys and so on
*
************************************************* */
$_CONF = array();

/* EXAMPLE
$_CONF['fb_api'] = array('key'=>'39ab360839e0c5b858c69da01060e25','secret'=>'39ab360839e0c5b858c69da01060e25');
*  EXAMPLE */

/* *************************************************
*
*  $_PAGE
*  Page information
*
************************************************* */
$_PAGE = array();
$_PAGE['title'] = $_SITE['name'];
$_PAGE['keywords'] = '';
$_PAGE['description'] = '';
$_PAGE['header'] = '';
$_PAGE['logourl'] = '';
$_PAGE['filename'] = basename($_SERVER['PHP_SELF']);
$_PAGE['css'] = '';
$_PAGE['template'] = 'templates/default.html';
$_PAGE['content'] = '';
$_PAGE['buffer'] ='';
$_PAGE['footer'] = $_SITE['copyright'];
$_PAGE['robots'] = 'index,follow';
$_PAGE['blocks'] = array('menubar'=>'blocks/menubar.php','footer'=>'blocks/footer.php');


/* *************************************************
*
*  $_includes
*  files that will be included at head.inc.php
*  pathnames must be relative from the application root folder.
*
************************************************* */
$_includes = array(
'inc/library.inc.php',
'class/base.class.php',
'class/string.class.php',
'class/firebug.class.php',
'class/validate.class.php',
'class/http.class.php',
'class/php.class.php',
'class/html.class.php',
'class/form.class.php',
'class/bit.class.php',
'class/pwd.class.php',
'class/limit.class.php',
'class/lang.class.php',
'class/enum.class.php',
'inc/func.inc.php',
'inc/dao.inc.php', // mysql DAO management
'inc/cache.inc.php', // cache
'dao/settings.dao.php' // settings dao
);
// $_includes = false; /* Set $_includes = false; to disable all includes for debugging */


/* *************************************************
*
*  $_ajax
*  information about the ajax deck
*
************************************************* */
$_ajax = array(

/*
*   $_ajax['callback'] is a string of the Javascript callback function to call
*/
'callback'=>'',

/*
*   $_ajax['func'] is an array of string which all are name of functions
*   which are allowed to call from Javascript. Functions not listed on this
*   list will be disabled.
*/
'func'=>array( 
'AJAXCall',
'getVersions'
),

/*
*   $_ajax['err'] is an array of error messages to display
*/
'err'=>array(
'funcNotFound'=>'{\'err\':\'Function not found or disabled.\'}',
'sessionFail'=>'{\'err\':\'Session is invalid.\'}',
'invalidRef'=>'{\'err\':\'Call was from a non-local domain.\'}'
),

/*
*   whether to check $get['sh'] against $session_hash to see
*   if calling from same session.
*/
'sessCheck'=>true,

/*
*   whether to check HTTP_REFERER against $_SITE['app_root'] to
*   see if call was from own domain or not.
*/
'refCheck'=>false

);
// $_ajax = false; /* Set $_ajax to false to disable AJAX to call PHP functions */

?>