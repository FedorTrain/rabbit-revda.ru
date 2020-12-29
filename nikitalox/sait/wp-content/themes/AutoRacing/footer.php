    <div class="outer">
	<div id="footer">Все права защищены. &copy; <a href="<?php bloginfo('home'); ?>"><strong><?php bloginfo('name'); ?></strong></a>  - <?php bloginfo('description'); ?> </div>
   <?php // This theme is released free for use under creative commons licence. http://creativecommons.org/licenses/by/3.0/
            // All links in the footer should remain intact.
            // Warning! Your site may stop working if these links are edited or deleted  ?>
 <div id="info"><a href="http://www.wptheme.us/">Wptheme</a> | <a href="http://wp-templates.ru/">Шаблоны WordPress</a></div>
</div>
</div>
</div></div></div>
<?php
	 wp_footer();
	echo get_theme_option("footer")  . "\n";
?>
</body>
</html>