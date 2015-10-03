</div>  <!-- End wrap for footer -->   

<div id="footer">
<p><?php  echo '&copy; 2013 - ' ; echo date('Y'); _e(' Odaro.es Todos los derechos reservados a Booming &amp; Bling Gold, S.L.. | ');  $contact = get_page_by_title('Contacto'); $contacturl = get_permalink($contact->ID) ; echo '<a href="' . $contacturl . '">Contacto</a>' ; ?></p>
</div>


<!-- Google analytics (copy from carkodesign) -->

<?php wp_footer(); ?>
</body>
</html>