<tr id="row_<?php echo $data->cat_id;?>">
	<td>
    	<?php if ($this->session->userdata('inv_level') == 99){ ?>
    	<div class="pull-right btn-group btn-group-xs">
        	<a href="<?php echo base_url('category/edit_data/'.$data->cat_id);?>" class="btn btn-default btn-flat" data-toggle="tooltip" title="Edit Kategori" onclick="show_modal(this);return false"><i class="fa fa-pencil"></i></a>
            <a href="<?php echo base_url('category/delete_data');?>" class="btn btn-danger btn-flat" data-toggle="tooltip" title="Hapus Kategori" data-id="<?php echo $data->cat_id;?>" onclick="delete_data(this);return false"><i class="fa fa-trash-o"></i></a>
        </div>
        <?php } ?>
		<span class="name_<?php echo $data->cat_id;?>"><?php echo $data->cat_name;?></span>
    </td>
    <td><?php echo $data->items_count;?></td>
</tr>