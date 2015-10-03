<?php get_header(); ?>
<?php get_template_part('logo'); ?>
<?php if ( have_posts() ) : ?> 
	<?php get_sidebar(); ?>
		<div id="page">
			<?php while ( have_posts() ) : the_post(); ?> 
            	<h3 class="page-title"><?php the_title(); ?></h3>
				<?php the_content () ; ?>
			<?php endwhile; ?> 
			<div style="clear:both;"></div>
    		<?php else: ?>
			<p><?php _e('Lo siento. No se ha encontrado lo que busca'); ?></p>
        	<?php endif; ?>
            </div> 
</div> <!-- End wrap for st6icky footer -->
<?php get_footer(); ?>

