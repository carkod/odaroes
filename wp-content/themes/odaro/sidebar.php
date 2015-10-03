<div id="sidebar">
<script type="text/javascript">
jQuery(document).ready(function() {
    
var mapping = {
    "Bufanda": "Bufandas",
    "Pañuelo": "Pañuelos",
	"Gorro": "Gorros",
	"Cinturón": "Cinturones",
	
    // ...and so on...
};

$("#sidebar a").text(function(index, originalText) {
    return mapping[originalText];
});
	
});
</script>
<ul class="categories">
<li><a href="<?php bloginfo("url");?>" title="<?php _e("Nuevos artículos") ; ?>" ><?php _e("Novedades") ; ?></a></li>
<?php wp_list_categories('exclude=1&orderby=count&hide_empty=0&orderby=count&title_li=&hierarchical=0&number=6&'); ?>

</ul>


</div>
