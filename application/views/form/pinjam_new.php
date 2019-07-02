<?php if ($this->session->userdata('inv_level') == 99){ ?>
<h3 class="control-sidebar-heading"><i class="fa fa-list"></i> Pinjam</h3>
<form class="nopadding" id="formPinjam">
    <div class="form-group">
        <label for="pin_userid" class="control-label">Nama Peminjam</label>
        <select name="pin_userid" id="pin_userid" class="form-control">
            <option value="">--Pilih Nama Peminjam--</option>
		<?php
		if (isset($peminjam)){
			if ($peminjam){
				foreach($peminjam as $val){
					echo '<option value="'.$val->user_id.'">'.$val->user_fullname.'</option>';
				}
			}
		}
		?>
        </select>
    </div>
    <div class="form-group">
        <table class="table table-bordered table-hover table-stripped" id="pinjam_table">
            <thead>
                <tr>
                    <th width="70%">Nama Barang</th>
                    <th width="30%">Jml</th>
                </tr>
            </thead>
            <tbody>
            	<tr id="pin_0">
                	<td colspan="2" align="center">Tidak ada data peminjaman</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="form-group">
        <button type="submit" class="btn-pinjam btn btn-flat btn-block btn-success"><i class="fa fa-check-circle"></i> Pinjam</button>
    </div>
</form>
<script>
$('#pin_userid').select2();
$('#formPinjam').submit(function(e) {
	if ($('#pinjam_table tbody input[type="hidden"]').length == 0){
		alert('Pilih dulu barang yang ingin dipinjam');
	} else {
		$('.btn-pinjam').html('<i class="fa fa-spin fa-refresh"></i> Pinjam').prop('disabled',true);
		$.ajax({
			url		: base_url + 'peminjam/add_pinjam',
			data	: $(this).serialize(),
			dataType: 'JSON',
			type	: 'POST',
			success	: function(dt){
				if (dt.t == 0){
					alert(dt.msg);
				} else {
					if ($('#TablePinjam').length > 0){
						$('#TablePinjam tbody').append(dt.html);
					}
					$('#pinjam_table tbody').html('<tr id="pin_0"><td colspan="2" align="center">Tidak ada data peminjaman</td></tr>');
					$('.btn-pinjam').html('<i class="fa fa-check-circle"></i> Pinjam').prop('disabled',false)
					alert('Barang berhasil dipinjam');
				}
			}
		});
	}
    return false;
});
</script>
<?php } ?>