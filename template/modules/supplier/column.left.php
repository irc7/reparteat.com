<section id="column-left-supplier">
	<h2>Filtrar</h2>
	<h3>Todas las cocinas</h3>
	<ul class="list-filter">
	<?php foreach($catsAll as $filter) { ?>
		<li><?php echo $filter->TITLE; ?></li>
	<?php } ?>
	</ul>
</section>

