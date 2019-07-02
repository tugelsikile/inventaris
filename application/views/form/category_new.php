        <!-- form start -->
        <form role="form" id="form_siswa">
            <div class="box-body">
                <div class="form-group nopadding nama">
                    <label for="nama" class="control-label">Nama Kategori <span class="text-warning">*</span></label>
                    <input type="text" class="form-control" id="nama" name="nama" value="" placeholder="Nama Kategori">
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
			url		: base_url + 'category/new_data_submit',
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
					}
				}
				$('.btn-submit').html('<i class="fa fa-floppy-o"></i> Simpan').prop('disabled',false);
			}
		});
        return false;
    });
});
</script>