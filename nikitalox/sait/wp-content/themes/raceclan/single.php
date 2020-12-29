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
<div class="date"><span class="author"> <?php the_author(); ?></span> <span class="clock">  <?php the_time('j - F - Y'); ?></span></div>	
<div class="cover">
<div class="entry">
<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) { the_post_thumbnail(array(300,225), array("class" => "alignleft post_thumbnail")); } ?>
<?php the_content('Читать всю запись &raquo;'); ?>

<div class="clear"></div>
 <?php wp_link_pages(array('before' => '<p><strong>Страниц: </strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
</div>

</div>

<div class="singleinfo">

<span class="category">Рубрика: <?php the_category(', '); ?> </span>

</div>



</div>

<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<h1 class="title">Страница не найдена</h1>
		<p>К сожалению, здесь нет того, что вы ищите.</p>

<?php endif; ?>

</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>