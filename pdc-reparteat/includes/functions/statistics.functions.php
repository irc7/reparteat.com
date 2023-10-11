<?php 

	function orderStatistics($A, $camp){
	    $n= count($A);
		for($i=1;$i<$n;$i++){
    	    for($j=0;$j<$n-$i;$j++) {
				if($A[$j][$camp]<$A[$j+1][$camp]){
					$k=$A[$j+1];
					$A[$j+1]=$A[$j];
					$A[$j]=$k;
				}
	       }
        }
    	return $A;
	}











?>