<?php get_header(); ?>

<div id="catmenucontainer">
		<div id="catmenu">
			<ul>
				<?php wp_list_categories('sort_column=name&title_li=&depth=4'); ?>
			</ul>
	</div>		
	
</div>
<div id="casing">
<div id="content">


<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
		
<div class="single" id="post-<?php the_ID(); ?>">
<div class="title">
<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Постоянная ссылка на <?php the_title(); ?>"><?php the_title(); ?></a></h2>
</div>

<div class="cover">
<div class="entry">
<?php the_content('Читать всю записьполностью &raquo;'); ?>
		<div class="clear"></div>
 <?php wp_link_pages(array('before' => '<p><strong>Страниц: </strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
</div>

</div>

</div>

<?php endwhile; endif; ?>
	</div>		

<?php get_sidebar(); ?>

<?php get_footer(); ?>