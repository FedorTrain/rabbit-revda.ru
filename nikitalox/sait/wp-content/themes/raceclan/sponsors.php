<div class="bigbox squarebanner">
<div class='sidetitl'>Спонсоры</div>
<ul>

<li>
<?php 
	$ban1 = get_option('rcn_banner1'); 
	$url1 = get_option('rcn_url1'); 
?>
	
<a href="<?php echo ($url1); ?>" rel="bookmark" title=""><img src="<?php echo ($ban1); ?>" alt="" style="vertical-align:bottom;" /></a>

</li>			


<li>
<?php 
	$ban2 = get_option('rcn_banner2'); 
	$url2 = get_option('rcn_url2'); 
	?>
<a href="<?php echo ($url2); ?>" rel="bookmark" title=""><img src="<?php echo ($ban2); ?>" alt="" style="vertical-align:bottom;" /></a>
</li>

<li>
<?php 
	$ban3 = get_option('rcn_banner3'); 
	$url3 = get_option('rcn_url3'); 
	?>
<a href="<?php echo ($url3); ?>" rel="bookmark" title=""><img src="<?php echo ($ban3); ?>" alt="" style="vertical-align:bottom;" /></a>
</li>

<li>
<?php 
	$ban4 = get_option('rcn_banner4'); 
	$url4 = get_option('rcn_url4'); 
	?>
<a href="<?php echo ($url4); ?>" rel="bookmark" title=""><img src="<?php echo ($ban4); ?>" alt="" style="vertical-align:bottom;" /></a>
</li>

</ul>
</div>