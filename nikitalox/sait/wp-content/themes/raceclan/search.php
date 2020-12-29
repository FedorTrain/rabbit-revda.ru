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
<h2 class="pagetitle">Результаты поиска</h2>
<?php if (have_posts()) : ?>

<?php while (have_posts()) : the_post(); ?>
		
<div class="single" id="post-<?php the_ID(); ?>">
<div class="title">

<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Постоянная ссылка на <?php the_title(); ?>"><?php the_title(); ?></a></h2>


</div>
<div class="date">
<span class="author">Опубликовал <?php the_author(); ?></span> 
<span class="comm"><?php comments_popup_link('Добавить комментарий', '1 комментарий', '% коммент.'); ?></span> 	
</div>		

<div class="cover">
<div class="entry">


	<?php the_excerpt(); ?> 


<div class="clear"></div>
	
</div>

</div>

<div class="singleinfo">



<span class="category">рубрика: <?php the_category(', '); ?> </span>


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


<h1>Not Found</h1>


<div class="cover">
<div class="entry">
		<p>К сожалению, ваш поиск не дал результата. Попробуете еще?</p>
				
</div>

</div>
<?php endif; ?>

</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>