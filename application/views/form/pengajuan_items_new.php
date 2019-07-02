        <!-- form start -->
        <form role="form" id="form_siswa" class="nopadding">
        	<input type="hidden" name="pen_id" value="<?php echo $data->pen_id;?>" />
            <div class="box-body">
                <div class="col-lg-6 col-xs-6 form-group nopadleft jenis">
                    <label for="jenis" class="control-label">Jenis Barang</label><br/>
                    <select name="jenis" id="jenis" class="form-control" style="width:100%">
                        <option value="1">Alat</option>
                        <option value="2">Bahan</option>
                    </select>
                </div>
                <div class="col-lg-6 col-xs-6 form-group nopadright kategori">
                    <label for="kategori" class="control-label">Kategori Barang <span class="text-warning">*</span></label><br/>
                    <select name="kategori" id="kategori" class="form-control" style="width:100%">
                        <option value="">--Pilih Kategori--</option>
                    <?php
                    if ($cat){
                        foreach($cat as $val){
                            echo '<option value="'.$val->cat_id.'">'.$val->cat_name.'</option>';
                        }
                    }
                    ?>
                    </select>
                </div>
                <div class="col-lg-6 col-xs-6 form-group brand nopadleft">
                	<label for="brand" class="control-label">Merek</label><br/>
                    <select name="brand" id="brand" class="form-control" style="width:100%">
                    	<option value="">--Pilih Merek--</option>
                    <?php
					if ($brand){
						foreach($brand as $val){
							echo '<option value="'.$val->brand_id.'">'.$val->brand_name.'</option>';
						}
					}
					?>
                    </select>
                </div>
                <div class="col-lg-6 col-xs-6 form-group model nopadright">
                	<label for="model" class="control-label">Model</label>
                    <input type="text" name="model" id="model" class="form-control" placeholder="Model barang" />
                </div>
                <div class="form-group nopadding nama">
                    <label for="nama" class="control-label">Nama Barang <span class="text-warning">*</span></label>
                    <input type="text" class="form-control" id="nama" name="nama" value="" placeholder="Nama Barang">
                </div>
                <div class="form-group nopadding descr">
                	<label for="descr" class="control-label">Deskripsi / Spesifikasi Barang <span class="text-warning">*</span></label>
                    <textarea name="descr" id="descr" class="form-control" placeholder="Deskripsi Barang"></textarea>
                </div>
                <div class="col-lg-3 col-xs-3 jumlah form-group nopadleft">
                	<label for="jumlah" class="control-label">Jumlah <span class="text-warning">*</span></label>
                    <input type="number" name="jumlah" min="0" value="0" id="jumlah" class="form-control" />
                </div>
                <div class="col-lg-5 col-xs-5 harga form-group">
                	<label for="harga" class="control-label">Kisaran Harga Satuan <span class="text-warning">*</span></label>
                    <input type="number" name="harga" min="0" value="0" id="harga" class="form-control" />
                </div>
                <div class="col-lg-4 col-xs-4 satuan form-group nopadright">
                	<label for="satuan" class="control-label">Satuan <span class="text-warning">*</span></label><br/>
                    <select name="satuan" id="satuan" class="form-control" style="width:100%">
                    	<option value="">--Pilih Satuan--</option>
                    <?php
					if ($satuan){
						foreach($satuan as $val){
							echo '<option value="'.$val->sat_id.'">'.$val->sat_name.'</option>';
						}
					}
					?>
                    </select>
                </div>
                <div class="clearfix"></div>
                <div class="form-group notes">
                	<label for="notes" class="control-label">Catatan <span class="text-warning">*</span></label>
                    <textarea name="notes" id="notes" class="form-control" placeholder="Catatan pengajuan"></textarea>
                </div>
                <p class="text-danger">&nbsp;</p>
            </div>
        </form>
                    
<script>
$(document).ready(function(e) {
	$('#kategori,#jenis,#brand,#satuan').select2();
	$('.modal-footer').html('<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button><button type="button" class="btn btn-primary btn-submit" onclick="$(\'#form_siswa\').submit()"><i class="fa fa-floppy-o"></i> Simpan</button>');
	$('#form_siswa').submit(function(e) {
		$('.btn-submit').html('<i class="fa fa-spin fa-refresh"></i> Simpan').prop('disabled',true);
		$('.text-danger').html('');
		$('.has-error').removeClass('has-error');
		$.ajax({
			url		: base_url + 'pengajuan/new_data_items_submit',
			type	: 'POST',
			data	: $(this).serialize(),
			success	: function(dt){
				response = $.parseJSON(dt);
				if(typeof response != 'object') {
					$('.text-danger').html('Error. Invalid Response');
				} else {
					if (response.t == 0){
						$('.text-danger').html(response.msg);
						$('.'+response.elem).addClass('has-error');
						$('#'+response.elem).focus();
					} else {
						if ($('#data_table tbody #row_0').length > 0){
							$('#data_table tbody #row_0').remove();
						}
						$('#data_table tbody').append(response.html);
						hide_modal();
						totalUpdate();
					}
				}
				$('.btn-submit').html('<i class="fa fa-floppy-o"></i> Simpan').prop('disabled',false);
			}
		});
        return false;
    });
});
</script>