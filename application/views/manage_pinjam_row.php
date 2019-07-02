<tr id="row_<?php echo $data->pin_id;?>">
	<td>
    	<?php if ($this->session->userdata('inv_level') == 99 && $data->pin_status < 2){ ?>
        <div class="pull-right btn-group btn-group-xs" id="btngroup_<?php echo $data->pin_id;?>">
        	<a class="btn btn-flat btn-default" href="<?php echo base_url('items/pinjam_return/'.$data->pin_id);?>" onclick="show_modal(this);return false" data-toggle="tooltip" title="Pengembalian Pinjaman"><i class="fa fa-sign-out"></i></a>
        </div>
        <?php } ?>
    	<a href="javascript:;" onclick="$('#pinj<?php echo $data->pin_id;?>').toggle();return false"><strong><?php echo $data->yangpinjam; ?></strong></a>
        <table width="100%" class="table table-stripped table-bordered" id="pinj<?php echo $data->pin_id;?>" style="display:none">
        	<tr>
            	<th width="50%">Nama Barang</th>
                <th width="10%">Rusak Total</th>
                <th width="10%">Rusak</th>
                <th width="10%">Normal</th>
                <th width="10%">Baru</th>
                <th width="10%">Total</th>
            </tr>
            <?php
			if ($data->pinjam){
				$tot = $rustot = $rus = $nor = $bar = $sum = 0;
				foreach($data->pinjam as $valPinjam){
					$rustot = $rustot + $valPinjam->items_rusak_total;
					$rus	= $rus + $valPinjam->items_rusak_sedang;
					$nor	= $nor + $valPinjam->items_normal;
					$bar	= $bar + $valPinjam->items_baru;
					$sum	= $valPinjam->items_rusak_total + $valPinjam->items_rusak_sedang + $valPinjam->items_normal + $valPinjam->items_baru;
					$tot	= $tot + $sum;
					echo '<tr>
							<td>'.$valPinjam->items_name.'</td>
							<td>'.$valPinjam->items_rusak_total.'</td>
							<td>'.$valPinjam->items_rusak_sedang.'</td>
							<td>'.$valPinjam->items_normal.'</td>
							<td>'.$valPinjam->items_baru.'</td>
							<td>'.$sum.'</td>
						  </tr>';
				}
				echo '<tr>
						  <th><span class="pull-right">Total Barang</span></th>
						  <th>'.$rustot.'</th>
						  <th>'.$rus.'</th>
						  <th>'.$nor.'</th>
						  <th>'.$bar.'</th>
						  <th>'.$tot.'</th>
					  </tr>';
			}
			?>
        </table>
    </td>
    <td><?php echo $this->conv->tglIndo($data->pin_date); ?></td>
    <td>
	<?php 
		if ($data->pin_date_return){
			echo $this->conv->tglIndo($data->pin_date_return);
		}
	?>
    </td>
    <td><?php echo $data->peminjam;?></td>
    <td class="status_<?php echo $data->pin_id;?>"><?php echo $this->conv->statuspeminjaman($data->pin_status);?></td>
</tr>