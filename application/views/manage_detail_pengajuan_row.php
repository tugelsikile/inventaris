<tr id="row_<?php echo $data->cart_id;?>">
	<td>
    	<span class="name_<?php echo $data->cart_id;?>"><?php echo $data->items_name;?></span>
        <strong class="brand_<?php echo $data->cart_id;?>"><?php echo $data->brand_name;?></strong>
        <strong class="model_<?php echo $data->cart_id;?>"><?php echo $data->items_model;?></strong>
        <div class="text-description desk_<?php echo $data->cart_id;?>"><?php echo $data->items_description;?></div>
    </td>
    <td align="center" class="jenis_<?php echo $data->cart_id;?>">
		<?php echo $this->conv->items_jenis($data->items_type);?>
    </td>
    <td align="center" >
    <?php if ($pen->pen_status == 1) { ?>
    	<input type="text" onblur="update_qty(this)" data-id="<?php echo $data->cart_id;?>" id="qty" value="<?php echo $data->items_qty;?>" data-value="<?php echo $data->items_qty;?>" class="form-control qty_<?php echo $data->cart_id;?>" style="text-align:center" />
	<?php } else { ?>
    	<input type="hidden" id="qty" value="<?php echo $data->items_qty;?>" />
    	<?php echo $data->items_qty.' '.$data->sat_name; ?>
    <?php } ?>        
    </td>
    <td align="right" >
    <?php if ($pen->pen_status == 1) { ?>
    	<input type="text" onblur="update_price(this)" data-id="<?php echo $data->cart_id;?>" id="price" value="<?php echo $data->cart_price;?>" data-value="<?php echo $data->cart_price;?>" class="form-control price_<?php echo $data->cart_id;?>" style="text-align:center" />
	<?php } else { ?>
    	<input type="hidden" id="price" value="<?php echo $data->cart_price;?>" />
    	<?php echo '<span class="pull-left">Rp.</span><span class="pull-right">'.number_format($data->cart_price,0,'','.').'</span>'; ?>
    <?php } ?>        
    </td>
    <td align="right">
    	<strong class="pull-left">Rp.</strong>
    	<strong id="total_items" class="total_<?php echo $data->cart_id;?>"><?php echo number_format($data->items_qty * $data->cart_price,0,',','.'); ?></strong>
    </td>
    <td>
    <?php if ($pen->pen_status == 1) { ?>
    	<textarea onblur="update_notes(this)" data-id="<?php echo $data->cart_id;?>" id="notes_<?php echo $data->cart_id;?>" class="form-control" placeholder="Keterangan"><?php echo $data->cart_notes;?></textarea>
	<?php } else { ?>
    	<?php echo $data->cart_notes; ?>
    <?php } ?>        
    </td>
</tr>