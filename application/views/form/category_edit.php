        <!-- form start -->
        <form role="form" id="form_siswa">
        	<input type="hidden" name="cat_id" value="<?php echo $data->cat_id;?>" />
            <div class="box-body">
                <div class="form-group nopadding nama">
                    <label for="nama" class="control-label">Nama Kategori <span class="text-warning">*</span></label>
                    <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $data->cat_name;?>" placeholder="Nama Kategori">
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
			url		: base_url + 'category/edit_data_submit',
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
						$('.name_'+response.id).html(response.nama);
						hide_modal();
					}
				}
				$('.btn-submit').html('<i class="fa fa-floppy-o"></i> Simpan').prop('disabled',false);
			}
		});
        return false;
    });
});
</script>