<tr id="row_<?php echo $data->pen_id;?>">
	<td>
    	<div class="pull-right">
        	<a href="<?php echo base_url('pengajuan/cetak_pengajuan/'.$data->pen_id);?>" class="btn btn-xs btn-flat btn-default" data-toggle="tooltip" title="Cetak Proposal Pengajuan" onclick="load_page(this);return false"><i class="fa fa-print"></i></a>
        	<a href="<?php echo base_url('pengajuan/detail_pengajuan/'.$data->pen_id);?>" class="btn btn-xs btn-flat btn-default" data-toggle="tooltip" title="Lihat detail" onclick="load_page(this);return false"><i class="fa fa-eye"></i></a>
    <?php if ($this->session->userdata('inv_level') == 99) { ?>
        	<?php
			if ($data->count_cart > 0 && $data->pen_status == 1){
				echo '<a class="btnpen_'.$data->pen_id.' btn btn-xs btn-flat btn-primary" href="javascript:;" data-id="'.$data->pen_id.'" onclick="ajukan(this);return false" data-toggle="tooltip" title="Submit dan Kunci Pengajuan ini"><i class="fa fa-send"></i></a> ';
			}
			$active = 'style="display:none"';
			if ($this->session->userdata('inv_level') == 99 && $this->session->userdata('pen_id') != $data->pen_id){
				$active = '';
			}
			if ($data->pen_status == 1){
				echo '<a '.$active.' class="btn-xs btn-pengajuan btn btn-flat btn-default" href="javascript:;" data-id="'.$data->pen_id.'" onclick="set_pengajuan(this);return false" data-toggle="tooltip" title="Set Sebagai Pengajuan Aktif"><i class="fa fa-check-square"></i></a> ';
				echo '<a href="'.base_url('pengajuan/edit_data/'.$data->pen_id).'" onclick="show_modal(this);return false" class="btn btn-default btn-flat btn-xs" data-toggle="tooltip" title="Rubah data pengajuan"><i class="fa fa-pencil"></i></a> ';
				echo '<a href="javascript:;" onclick="delete_data(this);return false" class="btn btn-danger btn-flat btn-xs" data-toggle="tooltip" title="Hapus Pengajuan" data-id="'.$data->pen_id.'"><i class="fa fa-trash-o"></i></a> ';
			}
			?>
	<?php } ?>    
        </div>
		<a class="nama_<?php echo $data->pen_id;?>" href="<?php echo base_url('pengajuan/detail_pengajuan/'.$data->pen_id);?>" data-toggle="tooltip" title="Lihat detail pengajuan" onclick="load_page(this);return false"><?php echo $data->pen_name;?></a>
        <div class="text-description notes_<?php echo $data->pen_id;?>"><?php echo $data->pen_notes;?></div>
    </td>
    <td><?php echo $this->conv->tglIndo($data->pen_date);?></td>
    <td><?php echo $data->user_fullname;?></td>
    <td><?php echo $this->conv->statuspengajuan($data->pen_status);?></td>
    <td><?php echo $data->pen_alasan;?></td>
</tr>