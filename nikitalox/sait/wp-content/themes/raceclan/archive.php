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

		 <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
<?php /* If this is a category archive */ if (is_category()) { ?>
		<h2 class="pagetitle">Архив рубрики &#8216;<?php echo single_cat_title(); ?>&#8217; </h2>

 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="pagetitle">Архив <?php the_time('jS F Y'); ?></h2>

	 <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="pagetitle">Архив <?php the_time('F, Y'); ?></h2>

		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2 class="pagetitle">Архив <?php the_time('Y'); ?></h2>

	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2 class="pagetitle">Архив автора</h2>

		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="pagetitle">Архив блога</h2>

		<?php } ?>



<?php while (have_posts()) : the_post(); ?>
		
<div class="single" id="post-<?php the_ID(); ?>">
<div class="title">

<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Постоянная ссылка на <?php the_title(); ?>"><?php the_title(); ?></a></h2>

</div>


<div class="date"><span class="author"> <?php the_author(); ?></span> <span class="clock">  <?php the_time('j - F - Y'); ?></span></div>	
<div class="cover">
<div class="entry">

<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) { the_post_thumbnail(array(300,225), array("class" => "alignleft post_thumbnail")); } ?>

	<?php the_excerpt(); ?> 


<div class="clear"></div>
	
</div>

</div>

<div class="singleinfo">

<span class="category">Рубрика: <?php the_category(', '); ?> </span>

</div>


</div>
		<?php endwhile; ?>

 <div id="navigation">
  <?php if(function_exists('wp_pagenavi')) : ?>
        <?php wp_pagenavi() ?>
 	   <?php else : ?>
        <div class="alignleft"><?php next_posts_link(__('&laquo; Раньше','arclite')) ?></div>
        <div class="alignright"><?php previous_posts_link(__('Позже &raquo;','arclite')) ?></div>
        <div class="clear"></div>
       <?php endif; ?>

</div>

	<?php else : ?>

		<h1 class="title">Страница не найдена</h1>
		<p>К сожалению, здесь нет того, что вы ищите.</p>

	<?php endif; ?>

</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>