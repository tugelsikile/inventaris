<?php
if ($data){
	foreach($data as $val){
		$dataI['data']	= $val;
		$this->load->view('manage_pinjam_row',$dataI);
	}
	//paging
	$html	= ' ';
	if ($next > 0){
		$jumData	= $next;
		$jumPage	= ceil($jumData/$limit);
		if ($jumPage > 1){
			if (($page - 1) > 0){ $dis_prev	= ''; } else { $dis_prev	= 'disabled'; }
			//prev button start
			if ($page > 1){
				if ($page - 1 > 1){
					$html		= '<li class="'.$dis_prev.'"><a href="#" onClick="load_table(1,\\\''.$order.'\\\',\\\''.$direction.'\\\');return false">First</a></li>';	//first btn
				}
				$html		.= '<li class="'.$dis_prev.'"><a href="#" onClick="load_table('.($page-1).',\\\''.$order.'\\\',\\\''.$direction.'\\\');return false"><i class="fa fa-chevron-left"></i> Prev</a></li>'; //prev btn
			}//prev button end
			
			//page btn start
			for ($i = 1; $i <= $jumPage; $i++){
				if ($i == $page){ $dis_cur = 'active'; $href_cur = 'disabled'; } else { $dis_cur = ''; $href_cur = ''; }
				if ($i == $page || $i == ($page-3) || $i == ($page-2) || $i == ($page-1) || $i == ($page+1) || $i == ($page+2) || $i == ($page+3)){
					$html .= '<li class="'.$dis_cur.'"><a href="#" onClick="load_table('.$i.',\\\''.$order.'\\\',\\\''.$direction.'\\\');return false" class="'.$href_cur.'">'.$i.'</a></li>';
				}
			}//page btn end
			
			//next button start
			if ($page < $jumPage){
				if (($page + 1) >= ($jumPage+1)){ $dis_next = 'disabled'; } else { $dis_next = ''; }
				$html		.= '<li class="'.$dis_next.'"><a href="#" onClick="load_table('.($page+1).',\\\''.$order.'\\\',\\\''.$direction.'\\\');return false"><i class="fa fa-chevron-right"></i> Next</a></li>';		//next btn
				if ($page + 1 < $jumPage){
					$html		.= '<li class="'.$dis_next.'"><a href="#" onClick="load_table('.$jumPage.',\\\''.$order.'\\\',\\\''.$direction.'\\\');return false">Last</a></li>';	//last btn
				}
			}
			//next button end
			$html		.= '<li class="disabled"><a href="javascript:;">showing '.count($data).'/'.$next.'</a></li>';
		}
	}
}
?>
<script>
$('#paging').html('<?php echo $html; ?>');
$('*#sort_btn').attr('data-page','<?php echo $page; ?>');

</script>
