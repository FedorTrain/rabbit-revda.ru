<div class="sidecont rightsector">
	
	<div class="sidebar">
    <?php if(get_theme_option('socialnetworks') != '') {
    		?>
    			<div class="addthis_toolbox">   
    			    <div class="custom_images">
    			            <a rel="nofollow" class="addthis_button_twitter"><img src="<?php bloginfo('template_directory'); ?>/images/socialicons/twitter.png" width="32" height="32" alt="Twitter" /></a>
    			            <a rel="nofollow" class="addthis_button_delicious"><img src="<?php bloginfo('template_directory'); ?>/images/socialicons/delicious.png" width="32" height="32" alt="Delicious" /></a>
    			            <a rel="nofollow" class="addthis_button_facebook"><img src="<?php bloginfo('template_directory'); ?>/images/socialicons/facebook.png" width="32" height="32" alt="Facebook" /></a>
    			            <a rel="nofollow" class="addthis_button_digg"><img src="<?php bloginfo('template_directory'); ?>/images/socialicons/digg.png" width="32" height="32" alt="Digg" /></a>
    			            <a rel="nofollow" class="addthis_button_stumbleupon"><img src="<?php bloginfo('template_directory'); ?>/images/socialicons/stumbleupon.png" width="32" height="32" alt="Stumbleupon" /></a>
    			            <a rel="nofollow" class="addthis_button_favorites"><img src="<?php bloginfo('template_directory'); ?>/images/socialicons/favorites.png" width="32" height="32" alt="Favorites" /></a>
    			            <a rel="nofollow" class="addthis_button_more"><img src="<?php bloginfo('template_directory'); ?>/images/socialicons/more.png" width="32" height="32" alt="More" /></a>
    			    </div>
    			    <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js?pub=xa-4a65e1d93cd75e94"></script>
    			</div>	<?php
    	}
    ?>


    
    <?php if(get_theme_option('video') != '') {
    		?>
    		<div class="sidebarvideo">
    			<ul> <li><h2 style="margin-bottom: 7px;">Популярное видео</h2>
    			<object width="284" height="240"><param name="movie" value="http://www.youtube.com/v/<?php echo get_theme_option('video'); ?>&hl=en&fs=1&rel=0&border=1"></param>
    				<param name="allowFullScreen" value="true"></param>
    				<param name="allowscriptaccess" value="always"></param>
    				<embed src="http://www.youtube.com/v/<?php echo get_theme_option('video'); ?>&hl=en&fs=1&rel=0&border=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="284" height="240"></embed>
    			</object>
    			</li>
    			</ul>
    		</div>
    	<?php
    	}
    	?>
        
		<ul>
			<?php 
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>

				
				<li><h2><?php _e('Недавние записи'); ?></h2>
			               <ul>
					<?php wp_get_archives('type=postbypost&limit=5'); ?>  
			               </ul>
				</li>
				
				<li><h2>Архивы</h2>
					<ul>
					<?php wp_get_archives('type=monthly'); ?>
					</ul>
				</li>
				
				<li> 
					<h2>Календарь</h2>
					<?php get_calendar(); ?> 
				</li>
				
				<?php wp_list_categories('hide_empty=0&show_count=1&depth=1&title_li=<h2>Рубрики</h2>'); ?>
				
				<li id="tag_cloud"><h2>Теги</h2>
					<?php wp_tag_cloud('largest=16&format=flat&number=20'); ?>
				</li>
				
				
				<?php include (TEMPLATEPATH . '/recent-comments.php'); ?>
				<?php if (function_exists('get_recent_comments')) { get_recent_comments(); } ?>
				
				
					
			<?php endif; ?>
			</ul>
        
        
	</div>
</div>
