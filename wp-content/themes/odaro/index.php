<?php get_header(); ?>
<?php get_template_part('logo'); ?>
<?php if( is_home() )  { ; ?>
<script type="text/javascript">
jQuery(document).ready(function() {




function mycarousel_initCallback(carousel)
{
    // Disable autoscrolling if the user clicks the prev or next button.
    carousel.buttonNext.bind('click', function() {
        carousel.startAuto(0);
    });

    carousel.buttonPrev.bind('click', function() {
        carousel.startAuto(0);
    });

    // Pause autoscrolling if the user moves with the cursor over the clip.
    carousel.clip.hover(function() {
        carousel.stopAuto();
    }, function() {
        carousel.startAuto();
    });
};
    
$('.jcarousel')
     $('.jcarousel').jcarousel({wrap: 'both', initCallback: mycarousel_initCallback});

        $('.jcarousel-control-prev')
            .on('jcarouselcontrol:active', function() {
                $(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                $(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '-=3'
            });

        $('.jcarousel-control-next')
            .on('jcarouselcontrol:active', function() {
                $(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                $(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '+=3'
            })
    .jcarouselAutoscroll({
        interval: 3000,
        target: '+=3',
        autostart: true,
	
    });
	$(".jcarousel-controls").click(function() {
		
$('.jcarousel').jcarouselAutoscroll('stop');

		});

	
});

</script>
<?php } else { ?>
<script type="text/javascript">
jQuery(document).ready(function() {

    $(function() {
        $('.jcarousel').jcarousel({wrap: 'both'});

        $('.jcarousel-control-prev')
            .on('jcarouselcontrol:active', function() {
                $(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                $(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '-=3'
            });

        $('.jcarousel-control-next')
            .on('jcarouselcontrol:active', function() {
                $(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                $(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '+=3'
            });

    });


});

</script>

<?php } ?>

<?php get_sidebar(); ?>

<?php if ( have_posts() ) : ?> 


<div class="jcarousel-wrapper">  
<div class="jcarousel">

<div class="jcarousel-container">

<?php while ( have_posts() ) : the_post(); ?> 


<?php if( function_exists( 'catch_that_image' ) ) { ; ?>
<a href="<?php the_permalink (); ?>" title="<?php _e('Ver Artículo'); ?>"><img src=" <?php echo catch_that_image(); ?>" /><?php foreach((get_the_category()) as $category) {echo '<p class="category">'. $category->cat_name . '</p>';} ?>

<?php echo '<p class="codigo">'; the_title(); echo '</p>' ?>


</a>
 



<?php } else {echo '¡Error! Contactar con el Desarrollador Web';} ?>


		<?php endwhile; ?> 
</div>        
 
 </div>
 <div class="jcarousel-controls">
                <a class="jcarousel-control-prev"></a>
                <a class="jcarousel-control-next"></a>
 </div>

</div> 
        
    <?php else: ?>
		<p class="error"><?php _e('Lo sentimos, &eacute;sta p&aacute;gina a&uacute;n no tiene contenido.'); ?></p>
        
	<?php endif; ?>
   

<?php get_footer(); ?>

