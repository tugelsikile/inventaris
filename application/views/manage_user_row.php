<tr id="row_<?php echo $data->user_id;?>">
	
	<td>
		<span class="fullname_<?php echo $data->user_id;?>"><?php echo $data->user_fullname;?></span>
    </td>
	<td>
		<span class="name_<?php echo $data->user_id;?>"><?php echo $data->user_name;?></span>
    </td>
    <td>
    	<?php echo $this->conv->user_level($data->user_level);?>
    </td>
    <td>
    	<?php if ($this->session->userdata('inv_level') == 99){ ?>
    	<div class="btn-group btn-group-xs">
        <?php if ($this->session->userdata('inv_id') == $data->user_id){ ?>
        	<a href="<?php echo base_url('account/edit_password/'.$data->user_id);?>" class="btn btn-default btn-flat" data-toggle="tooltip" title="Edit Password" onclick="show_modal(this);return false"><i class="fa fa-lock"></i></a>
        <?php } ?>
        	<a href="<?php echo base_url('account/edit_data/'.$data->user_id);?>" class="btn btn-default btn-flat" data-toggle="tooltip" title="Edit Pengguna" onclick="show_modal(this);return false"><i class="fa fa-pencil"></i></a>
            <a href="<?php echo base_url('account/delete_data');?>" class="btn btn-danger btn-flat" data-toggle="tooltip" title="Hapus Pengguna" data-id="<?php echo $data->user_id;?>" onclick="delete_data(this);return false"><i class="fa fa-trash-o"></i></a>
        </div>
        <?php } ?>
	</td>
</tr>