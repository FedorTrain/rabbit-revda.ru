<div id="tabzine" class="widgets">

<?php 
	$featucat = get_option('rcn_featucat');
	$featucount = get_option('rcn_featucount');
	$my_query = new WP_Query('showposts='. $featucount .'&category_name='. $featucat .'');	 
	if ($my_query->have_posts()) :
?>

<ul id="tabnav" >
	<?php while ($my_query->have_posts()) : $my_query->the_post();$do_not_duplicate = $post->ID; ?>	
        <li>
		<a class="listab" href="#feature-<?php the_ID(); ?>"> 
		<?php boxim() ?>
		<h3> <?php the_title(); ?></h3>
		<p><?php the_content_rss('', TRUE, '', 20); ?> </p>
		 </a>
		</li>
		<?php endwhile; ?>

	</ul>

	<?php endif; ?>
<?php if ($my_query->have_posts()) :?>
<div class="tabdiv">
	<?php while ($my_query->have_posts()) : $my_query->the_post();$do_not_duplicate = $post->ID; ?>	
	<div id="feature-<?php the_ID(); ?>" class="videoid">
		<a href="<?php the_permalink() ?>"><?php slidim() ?></a>
			<div class="featpost">
				<p><?php the_content_rss('', TRUE, '', 40); ?> </p>
			</div>
	</div>
	<?php endwhile; ?>
</div>
<?php endif; ?>

</div>
