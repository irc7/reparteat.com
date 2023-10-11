<?php 	
	//
	$cp = 6;
	//Minimo 6 en control de paginas
	$cp_middle = round($cp / 2);
	$middle = round($num_pages / 2);
	if($cp < $num_pages && $page != 1 && $page != $num_pages) {
		if($page >= ($num_pages-$cp_middle)) {
			$start_pag = true;
			$finish_pag = false;
			$i=$num_pages-$cp+2;	
			$stop = $num_pages;
		}elseif($page <= $cp_middle+1) {
			$start_pag = false;
			$finish_pag = true;
			$i=1;
			$stop = $cp-1;
		}else {
			$start_pag = true;
			$finish_pag = true;
			$i=$page-2;
			$stop = $page+2;	
		}
	} elseif ($cp < $num_pages && $page == 1) {
		$i=$page;
		$stop = $cp - 1;
		$start_pag = false;
		$finish_pag = true;
	} elseif ($cp < $num_pages && $page == $num_pages) {
		$i=$num_pages - $cp_middle - 1;
		$stop = $num_pages;
		$start_pag = true;
		$finish_pag = false;
	} else {
		$i=1;
		$stop = $num_pages;
		$start_pag = false;
		$finish_pag = false;
	}
	
	if($sectionBD->TITLE_SEO != "") {
		$titleUrl = stripslashes($sectionBD->TITLE_SEO);	
	} else {
		$titleUrl = stripslashes($sectionBD->TITLE);
	}
	
	if($view == "article") {
		$urlPag = DOMAIN.$sectionBD->SLUG;
	}else if($view == "search") {
		$urlPag = DOMAIN.$urlforPag;	
	}
?>
<div id="pagination">
	<center>	
		<nav>
			<ul class="pagination blog-pagination">
				<?php for($p=$i;$p<=$stop;$p++): ?>
					<?php if($start_pag && $p == $i): ?>
						<li>
							<a aria-label="Inicio" class="active transition<?php if($page==1){echo " disabled-link";} ?>" href="<?php if($page>1){echo $urlPag."/1";}else{echo "#";} ?>">
								1
							</a>
						</li>
						<li>
							<a href="#" class="textBox transition">...</a>
						</li>
					<?php endif; ?>
					<li>
						<a href="<?php if($p != $page){echo $urlPag."/".$p;}else{echo "#";} ?>" class="textBox transition<?php if($page==$p){echo " disabled-link";} ?>">
							<?php echo $p; ?>
						</a>
					</li>
					<?php if($finish_pag && $p==$stop): ?>
						<li>
							<a href="#" class="textBox transition">...</a>
						</li>
						<li>
							<a aria-label="Final" class="active transition<?php if($page==$num_pages){echo " disabled-link";} ?>" href="<?php if($page < $num_pages){echo $urlPag."/".$num_pages;}else{echo "#";} ?>">
								<?php echo $num_pages; ?>
							</a>
						</li>
					<?php endif; ?>
				<?php endfor; ?>
			</ul>
		</nav>
	</center>
</div>