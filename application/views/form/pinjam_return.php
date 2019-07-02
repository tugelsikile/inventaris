<!-- form start -->
<form role="form" id="form_siswa">
    <input type="hidden" name="pin_id" value="<?php echo $data->pin_id;?>" />
    <div class="box-body">
        <div class="form-group nopadleft nama col-lg-6 col-xs-6">
            <label for="nama" class="control-label">Nama Yang Meminjam</label>
            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $data->yangpinjam;?>" disabled="disabled" placeholder="Nama Kategori">
        </div>
        <div class="form-group nopadright nama col-lg-6 col-xs-6">
            <label for="nama" class="control-label">Nama Peminjam</label>
            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $data->peminjam;?>" disabled="disabled" placeholder="Nama Kategori">
        </div>
        <div class="form-group">
        	<table class="table table-bordered table-stripped">
            	<thead>
                	<tr>
                        <th width="25%">Nama Barang</th>
                        <th width="10%">Rusak Total</th>
                        <th width="10%">Rusak</th>
                        <th width="10%">Normal</th>
                        <th width="10%">Baru</th>
                        <th width="10%">Total</th>
                        <th width="25%">Catatan</th>
                    </tr>
                </thead>
                <tbody>
            <?php
			if ($items){
				$tot = $rustot = $rus = $nor = $bar = $sum = $i = 0;
				foreach($items as $valPinjam){
					$rustot = $rustot + $valPinjam->items_rusak_total;
					$rus	= $rus + $valPinjam->items_rusak_sedang;
					$nor	= $nor + $valPinjam->items_normal;
					$bar	= $bar + $valPinjam->items_baru;
					$sum	= $valPinjam->items_rusak_total + $valPinjam->items_rusak_sedang + $valPinjam->items_normal + $valPinjam->items_baru;
					$tot	= $tot + $sum;
					echo '<tr>
							<input type="hidden" name="pind_id['.$i.']" value="'.$valPinjam->pind_id.'"/>
							<td>'.$valPinjam->items_name.'</td>
							<td>
								<input type="text" name="rustot['.$i.']" class="form-control" style="text-align:center" value="'.$valPinjam->items_rusak_total.'"/>
							</td>
							<td>
								<input type="text" name="rus['.$i.']" class="form-control" style="text-align:center" value="'.$valPinjam->items_rusak_sedang.'"/>
							</td>
							<td>
								<input type="text" name="nor['.$i.']" class="form-control" style="text-align:center" value="'.$valPinjam->items_normal.'"/>
							</td>
							<td>
								<input type="text" name="bar['.$i.']" class="form-control" style="text-align:center" value="'.$valPinjam->items_baru.'"/>
							</td>
							<td>'.$sum.'</td>
							<td><textarea class="form-control" name="notes['.$i.']" placeholder="Kosongkan jika tidak perlu"></textarea></td>
						  </tr>';
					$i++;
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
                </tbody>
            </table>
        </div>
        <div class="clearfix"></div>
        <p class="text-danger">&nbsp;</p>
    </div>
</form>
                    
<script>
$(document).ready(function(e) {
	$('.modal-footer').html('<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button><button type="button" class="btn btn-primary btn-submit" onclick="$(\'#form_siswa\').submit()"><i class="fa fa-floppy-o"></i> Simpan</button>');
	$('#form_siswa').submit(function(e) {
		$('.btn-submit').html('<i class="fa fa-spin fa-refresh"></i> Simpan').prop('disabled',true);
		$('.text-danger').html('');
		$('.has-error').removeClass('has-error');
		$.ajax({
			url		: base_url + 'items/pinjam_return_submit',
			type	: 'POST',
			data	: $(this).serialize(),
			dataType: 'JSON',
			success	: function(dt){
				if (dt.t == 0){
					alert(dt.msg);
				} else {
					if (dt.hide == 2){
						$('#btngroup_'+dt.id).fadeToggle(500);
					}
					$('.status_'+dt.id).html(dt.status);
					hide_modal();
				}
				$('.btn-submit').html('<i class="fa fa-floppy-o"></i> Simpan').prop('disabled',false);
			}
		});
        return false;
    });
});
</script>