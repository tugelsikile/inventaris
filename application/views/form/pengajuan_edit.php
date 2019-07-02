        <!-- form start -->
        <form role="form" id="form_siswa">
        	<input type="hidden" name="pen_id" value="<?php echo $data->pen_id;?>" />
            <div class="box-body">
                <div class="form-group nopadding nama">
                    <label for="nama" class="control-label">Judul Pengajuan <span class="text-warning">*</span></label>
                    <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $data->pen_name;?>" placeholder="Judul Pengajuan">
                </div>
                <div class="form-group nopadding notes">
                	<label for="notes" class="control-label">Deskripsi Pengajuan <span class="text-warning">*</span></label>
                    <textarea name="notes" id="notes" class="form-control" placeholder="Deskripsi Pengajuan"><?php echo $data->pen_notes;?></textarea>
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
			url		: base_url + 'pengajuan/edit_data_submit',
			type	: 'POST',
			data	: $(this).serialize(),
			dataType: 'JSON',
			success	: function(dt){
				if (dt.t == 0){
					$('.text-danger').html(dt.msg);
					$('.'+dt.elem).addClass('has-error');
					$('#'+dt.elem).focus();
				} else {
					$('.nama_'+dt.id).html(dt.nama);
					$('.notes_'+dt.id).html(dt.notes);
					hide_modal();
				}
				$('.btn-submit').html('<i class="fa fa-floppy-o"></i> Simpan').prop('disabled',false);
			}
		});
        return false;
    });
});
</script>