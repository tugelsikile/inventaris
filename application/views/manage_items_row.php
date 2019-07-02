<tr id="row_<?php echo $data->items_id;?>">
	<td>
    	<div class="btn-group btn-group-xs pull-right">
        	<a class="btn btn-info btn-flat" href="javascript:;" onclick="$('.desk_<?php echo $data->items_id;?>').slideToggle(500);return false" data-toggle="tooltip" title="Spesifikasi Barang"><i class="fa fa-eye"></i></a>
            <?php if ($this->session->userdata('inv_level') == 99){ ?>
	            <?php if ($this->session->userdata('pen_id')){ ?>
    	        <a class="btn btn-default btn-flat" href="javascript:;" onclick="insert_ajuan(this);return false" data-toggle="tooltip" title="Tambahkan ke Data Pengajuan Barang" data-id="<?php echo $data->items_id;?>" ><i class="fa fa-plus-square"></i></a>
                <?php } ?>
        	<a class="btn btn-default btn-flat" href="<?php echo base_url('items/edit_data/'.$data->items_id);?>" onclick="show_modal(this);return false" data-toggle="tooltip" title="Edit Barang"><i class="fa fa-pencil"></i></a>
            <a class="btn btn-danger btn-flat" href="<?php echo base_url('items/delete_data');?>" onclick="delete_data(this);return false" data-id="<?php echo $data->items_id;?>" data-toggle="tooltip" title="Hapus Barang"><i class="fa fa-trash-o"></i></a>
            <?php } ?>
        </div>
        <?php if ($this->session->userdata('inv_level') == 99){ ?>
        <div class="dropdown pull-left btn-group btn-group-xs" style="margin-right:5px">
            <button type="button" class="btn btn-warning btn-flat dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown">
            	<i class="fa fa-cube"></i>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                <li role="presentation">
                	<a role="menuitem" tabindex="-1" href="javascript:;" onclick="pinjam(this);return false" data-id="<?php echo $data->items_id;?>" data-name="<?php echo $data->items_name;?> (Rusak Total)" data-flag="items_rusak_total" data-max="<?php echo $data->items_rusak_total - $data->pinjam->rusak_total;?>">Pinjam Rusak Total</a>
                </li>
                <li role="presentation">
                	<a role="menuitem" tabindex="-1" href="javascript:;" onclick="pinjam(this);return false" data-id="<?php echo $data->items_id;?>" data-name="<?php echo $data->items_name;?> (Rusak)" data-flag="items_rusak_sedang" data-max="<?php echo $data->items_rusak_sedang - $data->pinjam->rusak;?>">Pinjam Rusak</a>
                </li>
                <li role="presentation">
                	<a role="menuitem" tabindex="-1" href="javascript:;" onclick="pinjam(this);return false" data-id="<?php echo $data->items_id;?>" data-name="<?php echo $data->items_name;?> (Normal)" data-flag="items_normal" data-max="<?php echo $data->items_normal - $data->pinjam->normal;?>">Pinjam Normal</a>
                </li>
                <li role="presentation">
                	<a role="menuitem" tabindex="-1" href="javascript:;" onclick="pinjam(this);return false" data-id="<?php echo $data->items_id;?>" data-name="<?php echo $data->items_name;?> (Baru)" data-flag="items_baru" data-max="<?php echo $data->items_baru - $data->pinjam->baru;?>">Pinjam Baru</a>
                </li>
            </ul>
        </div>     
        <?php } ?>   
        <strong class="name_<?php echo $data->items_id;?>"><?php echo $data->items_name;?></strong>
        <div class="desk_<?php echo $data->items_id;?>" style="display:none">
            <label class="text-info"><small>Kategori : </small></label> <a href="javasript:;" onclick="$('#cat').val('<?php echo $data->cat_id;?>');load_table();return false"><?php echo $data->cat_name;?></a><br/>
            <label class="text-info"><small>Spesifikasi : </small></label><br/><?php echo nl2br($data->items_description);?>
        </div>
    </td>
    <td class="jenis_<?php echo $data->items_id;?>">
		<a href="javascript:;" onclick="$('#type').val('<?php echo $data->items_type;?>');load_table();return false"><?php echo $this->conv->items_jenis($data->items_type); ?></a>
    </td>
    <td>
		<?php
		echo '<a href="javascript:;" onclick="$(\'#table_search\').val(\''.$data->brand_name.'\');load_table();return false" class="brand_'.$data->items_id.'">'.$data->brand_name.'</a> 
			  <a href="javascript:;" onclick="$(\'#table_search\').val(\''.$data->items_model.'\');load_table();return false" class="model_'.$data->items_id.'">'.$data->items_model.'</a>';
		?>
    </td>
    <td class="tot_<?php echo $data->items_id;?>">
		<?php
		$rowtot = $tot = 0;
		if ($data->pinjam->rusak_total > 0){
			$rowtot = $rowtot + $data->pinjam->rusak_total;
			$tot = $rowtot + $tot;
			echo $data->items_rusak_total.'/'.$data->pinjam->rusak_total;
		} else {
			echo $data->items_rusak_total;
		}
		?>
    </td>
    <td class="sed_<?php echo $data->items_id;?>">
		<?php
		if ($data->pinjam->rusak > 0){
			$rowtot = $rowtot + $data->pinjam->rusak;
			$tot = $rowtot + $tot;
			echo $data->items_rusak_sedang.'/'.$data->pinjam->rusak;
		} else {
			echo $data->items_rusak_sedang;
		}
		?>
    </td>
    <td class="norm_<?php echo $data->items_id;?>">
		<?php
		if ($data->pinjam->normal > 0){
			$rowtot = $rowtot + $data->pinjam->normal;
			$tot = $rowtot + $tot;
			echo $data->items_normal.'/'.$data->pinjam->normal;
		} else {
			echo $data->items_normal;
		}
		?>
    </td>
    <td class="baru_<?php echo $data->items_id;?>">
		<?php
		if ($data->pinjam->baru > 0){
			$rowtot = $rowtot + $data->pinjam->baru;
			$tot = $rowtot + $tot;
			echo $data->items_baru.'/'.$data->pinjam->baru;
		} else {
			echo $data->items_baru;
		}
		?>
    </td>
    <td class="total_<?php echo $data->items_id;?>">
		<?php
		if ($rowtot > 0){
			echo $data->items_stock.'/'.$tot;
		} else {
			echo $data->items_stock;
		}
		?>
    </td>
</tr>