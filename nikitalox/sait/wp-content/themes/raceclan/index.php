<?php get_header(); ?>
<div id="slidearea">
<?php include (TEMPLATEPATH . '/tab.php'); ?>	
</div>

<div id="casing">		

<div id="content">

<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>

<div class="single" id="post-<?php the_ID(); ?>">

<div class="title">
<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Постоянная ссылка на <?php the_title(); ?>"><?php the_title(); ?></a></h2>
<div class="date">
	<span class="author"> Опубликовал <?php the_author(); ?></span> <span class="clock">  <?php the_time('j - M - Y'); ?></span>
 	<span class="comm"><?php comments_popup_link('Добавить комментарий', '1 комментарий', '% коммент.'); ?></span>
</div>	
</div>

<div class="cover">
<div class="entry">
<?php postim(); ?> 
<?php the_excerpt(); ?> 
<div class="clear"></div>
</div>
</div>

<div class="singleinfo">
 <span class="more"> <a href="<?php the_permalink() ?>">Подробнее</a></span>
</div>

</div>

<?php endwhile; ?>
<div class="clear"></div>

 <div id="navigation">
<?php if(function_exists('wp_pagenavi')) : ?>
        <?php wp_pagenavi() ?>
 	   <?php else : ?>
        <div class="alignleft"><?php next_posts_link(__('&laquo; Раньше')) ?></div>
        <div class="alignright"><?php previous_posts_link(__('Позже &raquo;')) ?></div>
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