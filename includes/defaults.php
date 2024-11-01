<?php
if ( ! defined( 'ABSPATH' ) ) die;
/**
 * Default Data Array
 */
	
$defaults = array();

// Title, Icon, Thumbnail, Code, Loop, Markup, Scripts and Styles

$defaults[ 'title' ] = '';
$defaults[ 'icon_source' ] = '';
$defaults[ 'thumbnail_source' ] = '';
$defaults[ 'code' ] = '';
$defaults[ 'main_loop' ] = '';
$defaults[ 'main_content' ] = '';
$defaults[ 'scripts' ] = '';
$defaults[ 'styles' ] = '';

// Parameters, arguments and embed

$defaults[ 'params' ] = '[]';
$defaults[ 'arguments' ] = '[]';
$defaults[ 'embed_scripts' ] = '[]';
$defaults[ 'embed_styles' ] = '[]';
		
// Allowed HTML Tags

$defaults[ 'tags' ] = array(
	'a','abbr','article','aside','b','big','blockquote','button','caption','code',
	'div','em','figure','font','footer','form','h1','h2','h3','h4','h5','h6','header','i',
	'label','nav','p','pre','span','section','small','strong','sub','u','ul','ol'
);

// Wordpress Template Tags

$defaults[ 'post' ] = array( 
	'post.id' => __( 'ID', 'shortcode-mastery' ),
	'post.title' => __( 'Title', 'shortcode-mastery' ),
	'post.name' => __( 'Name', 'shortcode-mastery' ),
	'post.type' => __( 'Type', 'shortcode-mastery' ),
	'post.status' => __( 'Status', 'shortcode-mastery' ),
	'post.postParent' => __( 'Parent', 'shortcode-mastery' ),
	'post.children|join(\',\')' => __( 'Children', 'shortcode-mastery' ),
	'post.date' => __( 'Date Created', 'shortcode-mastery' ),
	'post.time' => __( 'Time Created', 'shortcode-mastery' ),
	'post.modified' => __( 'Modified', 'shortcode-mastery' ),
	'post.link' => __( 'Permalink', 'shortcode-mastery' ),
	'post.content|raw' => __( 'Content', 'shortcode-mastery' ),
	'post.excerpt|raw' => __( 'Excerpt', 'shortcode-mastery' ),
	'post.meta(\'field_name\')|join(\',\')' => __( 'Meta Field', 'shortcode-mastery' ),
);

$defaults[ 'post_terms' ] = array( 
	'post.terms(\'all\')|join(\',\')' => __( 'Terms', 'shortcode-mastery' ),
	'post.categories|join(\',\')' => __( 'Categories', 'shortcode-mastery' ),
	'post.tags|join(\',\')' => __( 'Tags', 'shortcode-mastery' ),
);

$defaults[ 'post_author' ] = array(
	'post.author.name' => __( 'Author Name', 'shortcode-mastery' ),
	'post.author.nicename' => __( 'Author Nicename', 'shortcode-mastery' ),
	'post.author.email' => __( 'Author Email', 'shortcode-mastery' ),
);

$defaults[ 'post_thumbnail' ] = array( 
	'post.thumbnail(\'full\').src' => __( 'Thumbnail Source', 'shortcode-mastery' ),
	'post.thumbnail(\'full\').alt' => __( 'Thumbnail Alt', 'shortcode-mastery' ),
	'post.thumbnail(\'full\').width' => __( 'Thumbnail Widht', 'shortcode-mastery' ),
	'post.thumbnail(\'full\').height' => __( 'Thumbnail Height', 'shortcode-mastery' ),
);

$defaults[ 'loop' ] = array(
	'loop.all' => __( 'Number of posts' ),
	'loop.current' => __( 'Current Post' ),
	'loop.first' => __( 'Is First Post' ),
	'loop.last' => __( 'Is Last Post' ),
);

$defaults[ 'globals' ] = array(
	'GLOBALS' => __( 'GLOBALS' ),
	'COOKIE' => __( 'COOKIE' ),
	'GET' => __( 'GET' ),
	'POST' => __( 'POST' ),
	'FILES' => __( 'FILES' ),
	'SESSION' => __( 'SESSION' ),
	'REQUEST' => __( 'REQUEST' ),
	'ENV' => __( 'ENV' ),
	'SERVER' => __( 'SERVER' ),
);

// All Query methods

$defaults[ 'groups' ] = array(
	array( 'name' => 'Author', 'id' => 'author', 'methods' => 
		array(
			array( 
				'name' => __( 'Author', 'shortcode-mastery' ),
				'method' => 'author',
				'type' => 'string'
			),
			array( 
				'name' => __( 'Author Name', 'shortcode-mastery' ),
				'method' => 'author_name',
				'type' => 'string'
			),
			array( 
				'name' => __( 'Author IN', 'shortcode-mastery' ),
				'method' => 'author__in',
				'type' => 'array'
			),
			array( 
				'name' => __( 'Author NOT IN', 'shortcode-mastery' ),
				'method' => 'author__not_in',
				'type' => 'array'
			)	
		),
	),
	array( 'name' => 'Category', 'id' => 'cat', 'methods' => 
		array(
			array( 
				'name' => __( 'Cat', 'shortcode-mastery' ),
				'method' => 'cat',
				'type' => 'string'
			),
			array( 
				'name' => __( 'Category Name', 'shortcode-mastery' ),
				'method' => 'category_name',
				'type' => 'string'
			),
			array( 
				'name' => __( 'Category AND', 'shortcode-mastery' ),
				'method' => 'category__and',
				'type' => 'array'
			),
			array( 
				'name' => __( 'Category IN', 'shortcode-mastery' ),
				'method' => 'category__in',
				'type' => 'array'
			),
			array( 
				'name' => __( 'Category NOT IN', 'shortcode-mastery' ),
				'method' => 'category__not_in',
				'type' => 'array'
			)	
		),
	),
	array( 'name' => 'Tag', 'id' => 'tag', 'methods' => 
		array(
			array( 
				'name' => __( 'Tag', 'shortcode-mastery' ),
				'method' => 'tag',
				'type' => 'string'
			),
			array( 
				'name' => __( 'Tag ID', 'shortcode-mastery' ),
				'method' => 'tag_id',
				'type' => 'string'
			),
			array( 
				'name' => __( 'Tag AND', 'shortcode-mastery' ),
				'method' => 'tag__and',
				'type' => 'array'
			),
			array( 
				'name' => __( 'Tag IN', 'shortcode-mastery' ),
				'method' => 'tag__in',
				'type' => 'array'
			),
			array( 
				'name' => __( 'Tag NOT IN', 'shortcode-mastery' ),
				'method' => 'tag__not_in',
				'type' => 'array'
			),
			array( 
				'name' => __( 'Tag Slug AND', 'shortcode-mastery' ),
				'method' => 'tag_slug__and',
				'type' => 'array'
			),
			array( 
				'name' => __( 'Tag Slug IN', 'shortcode-mastery' ),
				'method' => 'tag_slug__in',
				'type' => 'array'
			),
		),
	),
	array( 'name' => 'Taxonomy', 'id' => 'tax', 'methods' => 
		array(
			array( 
				'name' => __( 'Tax Query', 'shortcode-mastery' ),
				'method' => 'tax_query',
				'type' => 'taxonomy'
			),	
		),
	),
	array( 'name' => 'Post', 'id' => 'post', 'methods' => 
		array(
			array( 
				'name' => __( 'P', 'shortcode-mastery' ),
				'method' => 'p',
				'type' => 'string'
			),
			array( 
				'name' => __( 'Post Name', 'shortcode-mastery' ),
				'method' => 'name',
				'type' => 'string'
			),
			array( 
				'name' => __( 'Post Title', 'shortcode-mastery' ),
				'method' => 'title',
				'type' => 'string'
			),
			array( 
				'name' => __( 'Page ID', 'shortcode-mastery' ),
				'method' => 'page_id',
				'type' => 'string'
			),
			array( 
				'name' => __( 'Page Name', 'shortcode-mastery' ),
				'method' => 'pagename',
				'type' => 'string'
			),
			array( 
				'name' => __( 'Post Parent', 'shortcode-mastery' ),
				'method' => 'post_parent',
				'type' => 'string'
			),
			array( 
				'name' => __( 'Posts Parent IN', 'shortcode-mastery' ),
				'method' => 'post_parent__in',
				'type' => 'array'
			),
			array( 
				'name' => __( 'Post Parent NOT IN', 'shortcode-mastery' ),
				'method' => 'post_parent__not_in',
				'type' => 'array'
			),
			array( 
				'name' => __( 'Post IN', 'shortcode-mastery' ),
				'method' => 'post__in',
				'type' => 'array'
			),
			array( 
				'name' => __( 'Post NOT IN', 'shortcode-mastery' ),
				'method' => 'post__not_in',
				'type' => 'array'
			),
			array( 
				'name' => __( 'Post Name IN', 'shortcode-mastery' ),
				'method' => 'post_name__in',
				'type' => 'array'
			),
		),
	),
	array( 'name' => 'Type', 'id' => 'post_type', 'methods' => 
		array(
			array( 
				'name' => __( 'Post Type', 'shortcode-mastery' ),
				'method' => 'post_type',
				'type' => 'string'
			),
		),
	),
	array( 'name' => 'Status', 'id' => 'post_status', 'methods' => 
		array(
			array( 
				'name' => __( 'Post Status', 'shortcode-mastery' ),
				'method' => 'post_status',
				'type' => 'string'
			),
		),
	),
	array( 'name' => 'Pagination', 'id' => 'pagination', 'methods' => 
		array(
			array( 
				'name' => __( 'No Paging', 'shortcode-mastery' ),
				'method' => 'nopaging',
				'type' => 'string'
			),
			array( 
				'name' => __( 'Posts Per Page', 'shortcode-mastery' ),
				'method' => 'posts_per_page',
				'type' => 'string'
			),
			array( 
				'name' => __( 'Posts Offset', 'shortcode-mastery' ),
				'method' => 'offset',
				'type' => 'string'
			),
			array( 
				'name' => __( 'Posts Paged', 'shortcode-mastery' ),
				'method' => 'paged',
				'type' => 'string'
			),
			array( 
				'name' => __( 'Posts Page', 'shortcode-mastery' ),
				'method' => 'page',
				'type' => 'string'
			),
			array( 
				'name' => __( 'Ignore Sticky Posts', 'shortcode-mastery' ),
				'method' => 'ignore_sticky_posts',
				'type' => 'string'
			),
		),
	),
	array( 'name' => 'Order', 'id' => 'order', 'methods' => 
		array(
			array( 
				'name' => __( 'Posts Order', 'shortcode-mastery' ),
				'method' => 'order',
				'type' => 'string'
			),
			array( 
				'name' => __( 'Posts Order By', 'shortcode-mastery' ),
				'method' => 'orderby',
				'type' => 'string'
			),
		),
	),
	array( 'name' => 'Date', 'id' => 'date', 'methods' => 
		array(
			array( 
				'name' => __( 'Year', 'shortcode-mastery' ),
				'method' => 'year',
				'type' => 'year'
			),
			array( 
				'name' => __( 'Month', 'shortcode-mastery' ),
				'method' => 'monthnum',
				'type' => 'month'
			),
			array( 
				'name' => __( 'Week Of The Year', 'shortcode-mastery' ),
				'method' => 'w',
				'type' => 'week'
			),
			array( 
				'name' => __( 'Day', 'shortcode-mastery' ),
				'method' => 'day',
				'type' => 'day'
			),
			array( 
				'name' => __( 'Hour', 'shortcode-mastery' ),
				'method' => 'hour',
				'type' => 'hour'
			),
			array( 
				'name' => __( 'Minute', 'shortcode-mastery' ),
				'method' => 'minute',
				'type' => 'minute'
			),
			array( 
				'name' => __( 'Second', 'shortcode-mastery' ),
				'method' => 'second',
				'type' => 'second'
			),
			array( 
				'name' => __( 'Year And Month', 'shortcode-mastery' ),
				'method' => 'm',
				'type' => 'yearmonth'
			),
			array( 
				'name' => __( 'Date Query', 'shortcode-mastery' ),
				'method' => 'date_query',
				'type' => 'date'
			),			
		),
	),
	array( 'name' => 'Meta', 'id' => 'meta', 'methods' => 
		array(
			array( 
				'name' => __( 'Meta Key', 'shortcode-mastery' ),
				'method' => 'meta_key',
				'type' => 'string'
			),
			array( 
				'name' => __( 'Meta Value', 'shortcode-mastery' ),
				'method' => 'meta_value',
				'type' => 'string'
			),
			array( 
				'name' => __( 'Meta Value Num', 'shortcode-mastery' ),
				'method' => 'meta_value_num',
				'type' => 'string'
			),
			array( 
				'name' => __( 'Meta Compare', 'shortcode-mastery' ),
				'method' => 'meta_compare',
				'type' => 'string'
			),
			array( 
				'name' => __( 'Meta Query', 'shortcode-mastery' ),
				'method' => 'meta_query',
				'type' => 'meta'
			),			
		),
	)
);

$defaults[ 'script_position' ] = array(
	array( 
		'name' => '0',
		'value' => 'In Footer'
	),
	array(
		'name' => '1',
		'value' => 'In Header',
	),
);

$defaults[ 'standard_types' ] = array( 
	array( 
		'name' => '0',
		'value' => 'String'
	),
	array(
		'name' => '1',
		'value' => 'Boolean',
		'tooltip' => __( 'True or False', 'shortcode-mastery' )
	),
	array(
		'name' => '2',
		'value' => 'Extended',
		'tooltip' => __( 'Use with additional options for advanced control elements', 'shortcode-mastery' )
	)
);

$defaults[ 'wpbackery_types' ] = array(
	array( 
		'name' => 'textfield',
		'value' => __( 'Textfield' ),
	),
	array( 
		'name' => 'textarea',
		'value' => __( 'Textarea' )
	),
	array( 
		'name' => 'textarea_html',
		'value' => __( 'HTML' )
	),
	array( 
		'name' => 'checkbox',
		'value' => __( 'Checkbox' )
	),
	array( 
		'name' => 'posttypes',
		'value' => __( 'Post Types' )
	),	
	array( 
		'name' => 'attach_image',
		'value' => __( 'Single Image' )
	),
	array( 
		'name' => 'attach_images',
		'value' => __( 'Multiple Images' )
	),
	array( 
		'name' => 'colorpicker',
		'value' => __( 'ColorPicker' )
	)
);

$defaults[ 'elementor_types' ] = array(
	array( 
		'name' => 'TEXT',
		'value' => __( 'Textfield' )
	),
	array( 
		'name' => 'TEXTAREA',
		'value' => __( 'Textarea' )
	),
	array( 
		'name' => 'SWITCHER',
		'value' => __( 'Switcher' )
	),
	array( 
		'name' => 'CODE',
		'value' => __( 'Code' )
	),
	array( 
		'name' => 'SELECT',
		'value' => __( 'Select' ),
		'tooltip' => __( 'Use Extended type of parameter and additional options to define values. Example: {"key1":"value 1","key2":"value 2"}', 'shortcode-mastery' )
	),
	array( 
		'name' => 'SELECT2',
		'value' => __( 'Select 2' ),
		'tooltip' => __( 'Use Extended type of parameter and additional options to define values. Example: {"key1":"value 1","key2":"value 2"}', 'shortcode-mastery' )
	),
	array( 
		'name' => 'COLOR',
		'value' => __( 'ColorPicker', 'shortcode-mastery' ),
		'tooltip' => __( 'Default color in RGB, RGBA, or HEX format', 'shortcode-mastery' )
	),
	array( 
		'name' => 'ICON',
		'value' => __( 'Icon', 'shortcode-mastery' ),
		'tooltip' => __( 'Use Extended type of parameter and additional options to define values. Example: ["fa fa-facebook","fa fa-twitter"]', 'shortcode-mastery' )
	),
	array( 
		'name' => 'FONT',
		'value' => __( 'Google Font', 'shortcode-mastery' ),
	),
	array( 
		'name' => 'DATE_TIME',
		'value' => __( 'Date Time', 'shortcode-mastery' ),
		'tooltip' => __( 'Default date/time in MySQL format (YYYY-mm-dd HH:ii)', 'shortcode-mastery' )
	),
	array( 
		'name' => 'ANIMATION',
		'value' => __( 'Animation', 'shortcode-mastery' ),
	),
	array( 
		'name' => 'HOVER_ANIMATION',
		'value' => __( 'Hover animation', 'shortcode-mastery' ),
	),
	array( 
		'name' => 'GALLERY',
		'value' => __( 'Gallery', 'shortcode-mastery' ),
		'tooltip' => __( 'Use JSON format to define values. Example: [{"id":"1","url":"http://link-to-media.jpg"}]', 'shortcode-mastery' )
	),
);

return $defaults;
?>