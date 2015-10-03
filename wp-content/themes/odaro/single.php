<div class="wrap">
<?php get_header(); ?>
<?php get_template_part('logo'); ?>


<?php get_sidebar(); ?>
<?php if ( have_posts() ) : ?> 



<div class="product">


<?php while ( have_posts() ) : the_post(); ?> 
<div class="content-left">


<?php the_content (); ?>
</div>
<div class="content-right">
<h3 class="content-right-title"><?php the_title(); ?></h3>
<p><?php echo get_post_meta($post->ID, 'mb_descripcion', true); ?></p>
<h2 class='contact'>Para más información contáctanos: </h2>
<p>Teléfono / Fax: (0034) 916 420 488<br>
Móvil: (0034) 635 101 888<br>
E-mail: 32279906@qq.com<br>
QQ: 32279906</p>

</div>
		<?php endwhile; ?> 
      
 </div>
         
        
<div style="clear:both;"></div>
    <?php else: ?>
		<p><?php _e('Lo siento. No se ha encontrado lo que busca'); ?></p>
        
	<?php endif; ?>

<?php get_footer(); ?>

