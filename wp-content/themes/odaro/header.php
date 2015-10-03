<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>><head>
<!-- SEO -->
<?php if (have_posts()):  ?>
<?php if (is_page()): ?>
<title><?php the_title(); echo (' | '); bloginfo('name'); ?></title>
<?php endif; ?>

<?php if (is_single()): ?>
<title><?php the_title(); echo(' - '); $category = get_the_category(); echo $category[0]->cat_name; ?></title>
<?php endif; ?>
    <?php if (is_category() ): ?> 
   <title><?php $category = get_the_category(); echo $category[0]->cat_name; echo (' | '); echo ('Category archives'); ?></title>
   <?php endif; ?>
<?php if (is_tag() ): ?> 
   <title><?php single_tag_title(); echo (' | '); echo ('Tag archives'); ?></title>
   <?php endif; ?>

<?php if ( is_home() ): ?>
<title><?php bloginfo('name'); echo(' | '); bloginfo('description'); ?></title>
<?php endif; else : ?>
<title><?php the_title('',''); echo(' - '); bloginfo('description'); ?></title>
<?php endif; ?> 

<meta http-equiv="content-language" content="<?php bloginfo('language'); ?>" />
<meta http-equiv="author" content="Carkod" />
<meta http-equiv="contact" content="<?php bloginfo('admin_email'); ?>" />
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> 
<?php if (is_single() || is_page() ) : if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<meta name="description" content="<?php the_excerpt_rss(); ?>" />
<?php endwhile; endif; elseif(is_home()) : ?>
<meta name="description" content="<?php bloginfo('description'); ?>" />
<?php endif; ?>

<?php if (is_single()) : if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<meta name="keywords" content="<?php $articletags = strip_tags(get_the_tag_list('',', ','')); echo $articletags;
?>" />
<?php endwhile; endif; elseif(is_home()) : ?>
<meta name="keywords" content="carkod,personal,blog,my social,life,works" />
<?php endif; ?>

<?php if (is_single() || is_page() ) : if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<link rel="canonical" href="<?php the_permalink(); ?>" />
<?php endwhile; endif; elseif(is_home()) : ?>
<link rel="canonical" href="<?php bloginfo('url'); ?>" />
<?php endif; ?>

<meta name="viewport" content="width=1024">
<!-- stylesheets -->

<link href="<?php bloginfo('stylesheet_url'); ?>" rel="stylesheet" type="text/css" charset="utf-8" />

<link href="<?php bloginfo('template_url'); ?>/carousel.css" rel="stylesheet" type="text/css" charset="utf-8" />

<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/favicon.ico" />
<!-- stylesheets for other browsers -->

<!-- Sticky footer -->


<!--[if !IE 7]>
	<style type="text/css">
		#wrap {display:table;height:100%}
	</style>
<![endif]-->

<?php
add_filter('body_class','browser_body_class');
function browser_body_class($classes) {
	global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;

	if($is_lynx) $classes[] = 'lynx';
	elseif($is_gecko) $classes[] = 'gecko';
	elseif($is_opera) $classes[] = 'opera';
	elseif($is_NS4) $classes[] = 'ns4';
	elseif($is_safari) $classes[] = 'safari';
	elseif($is_chrome) $classes[] = 'chrome';
	elseif($is_IE) $classes[] = 'ie';
	else $classes[] = 'unknown';

	if($is_iphone) $classes[] = 'iphone';
	return $classes;
}
?>
 
<!-- rss and pingback -->
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<!-- scripts  -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script src="<?php bloginfo('template_url'); ?>/carousel.js"></script>
<!-- Disable double click text selection -->
<script type="text/javascript">
$(function(){
    $.extend($.fn.disableTextSelect = function() {
        return this.each(function(){
            if($.browser.mozilla){//Firefox
                $(this).css('MozUserSelect','none');
            }else if($.browser.msie){//IE
                $(this).bind('selectstart',function(){return false;});
            }else{//Opera, etc.
                $(this).mousedown(function(){return false;});
            }
        });
    });
    $('.noSelect').disableTextSelect();//No text selection on elements with a class of 'noSelect'
});
</script>
<?php wp_head(); ?>
</head>

<!-- This template is for SEO purposes do not add or modify -->