<div class="right">


	
<div class="sidebar">
	<ul>
<?php if ( !function_exists('dynamic_sidebar')
        || !dynamic_sidebar("Sidebar1") ) : ?>    
		<div class="sidebox">
		
	</div>

	
	<?php endif; ?>
	</ul>
</div>

<div class="sidebar2">
	
	<ul>
<?php if ( !function_exists('dynamic_sidebar')
        || !dynamic_sidebar("Sidebar2") ) : ?>    
		<div class="sidebox">
		
	</div>
	<?php endif; ?>
	</ul>
</div>

</div>