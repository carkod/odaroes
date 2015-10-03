<div id="topnav">

	<div id="language">
    
    <a href="http://cn.odaro.es/" class="chinese">中文网站</a>
    
    </div>
    
    
    <div id="pages">
    <ul class="navigation">
    
    <script type="text/javascript">
	
	jQuery(document).ready(function (){
		
		$("#pages").find("span").last().remove("span")
		
		});
	
	</script>
    
    
    
    <?php $new = '<span>|</span>'; wp_list_pages('orderby=count&hide_empty=0&orderby=count&title_li=&hierarchical=0&number=6&link_after='.$new.'</li>'); ?>
    
    
    </ul>
    </div>

</div>