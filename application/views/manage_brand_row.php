<tr id="row_<?php echo $data->brand_id;?>">
	<td>
    	<?php if ($this->session->userdata('inv_level') == 99){ ?>
        <div class="btn-group btn-group-xs pull-right">
        	<a class="btn btn-default btn-flat" href="<?php echo base_url('brand/edit_data/'.$data->brand_id);?>" onclick="show_modal(this);return false" data-toggle="tooltip" title="Edit Merek"><i class="fa fa-pencil"></i></a>
            <a class="btn btn-danger btn-flat" href="<?php echo base_url('brand/delete_data');?>" onclick="delete_data(this);return false" data-id="<?php echo $data->brand_id;?>" data-toggle="tooltip" title="Hapus Merek"><i class="fa fa-trash-o"></i></a>
        </div>
        <?php } ?>
		<span class="nama_<?php echo $data->brand_id;?>"><?php echo $data->brand_name;?></span>
    </td>
    <td><?php echo $data->items_count;?></td>
</tr>