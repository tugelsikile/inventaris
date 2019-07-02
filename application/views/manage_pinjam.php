<section class="content-header">
    <h1>
        Data Peminjaman Barang
        <small>Data</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>" onClick="load_page(this);return false"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo base_url('items');?>" onClick="load_page(this);return false">Barang</a></li>
        <li class="active">Data Peminjaman Barang</li>
    </ol>
</section>
<section class="content">
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Tabel Data Peminjaman Barang</h3>
            <div class="box-tools">
                <div class="input-group input-group-sm pull-right" style="width: 150px;margin-left:5px">
                    <input type="text" name="table_search" id="table_search" class="form-control pull-right" placeholder="Search">
                    <div class="input-group-btn">
	                    <button type="submit" class="btn btn-default" onclick="load_table();"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <div class="input-group input-group-sm pull-right" style="margin-left:5px">
                	<input type="text" id="tgl" class="form-control" placeholder="Tanggal"/>
                </div>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
        	<form id="form_table">
                <table class="table table-bordered table-hover table-stripped" id="TablePinjam">
                    <thead>
                        <tr>
                            <th width="45%">
                            	<a href="javascript:;" id="sort-btn" data-page="1" data-order="us1.user_name" data-direction="ASC">Nama Peminjam &amp; Detail Peminjaman</a>
                            </th>
                            <th width="10%">
                            	<a href="javascript:;" id="sort-btn" data-page="1" data-order="pin_date" data-direction="ASC">Tgl Peminjaman</a>
                            </th>
                            <th width="10%">
                            	<a href="javascript:;" id="sort-btn" data-page="1" data-order="pin_date_return" data-direction="ASC">Tgl Pengembalian</a>
                            </th>
                            <th width="10%">
                            	Dipinjamkan Oleh
                            </th>
                            <th width="10%">
                            	<a href="javascript:;" id="sort-btn" data-page="1" data-order="pin_status" data-direction="ASC">Status Peminjaman</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </form>
        </div>
        <div class="box-footer clearfix"><ul class="pagination pagination-sm no-margin pull-right" id="paging"></ul></div>
    </div>
</section>
<script>
$(document).ready(function(e) {
	$('#tgl').datepicker({
		format: 'yyyy-mm-dd',
		endDate:'1d'
		//startDate: '-3d'
	});
	load_table();
	$('#tgl').change(function(e) {
        if ($(this).val().length == 10){
			load_table();
		}
    });
});
//setup before functions
var typingTimer;                //timer identifier
var doneTypingInterval		= 500;  //time in ms, 5 second for example
var $value	 				= $('#table_search');
var cur_int_count	= 0;
//on keyup, start the countdown
$value.on('keyup', function () {
	clearTimeout(typingTimer);
	typingTimer = setTimeout(doneTyping, doneTypingInterval);
});
//on keydown, clear the countdown 
$value.on('keydown', function () {
	clearTimeout(typingTimer);
});
//user is "finished typing," do something
function doneTyping() {
	cur_int_count	= 0;
	load_table();
}
function cbxcount(){
	var counter = $('#data_table tbody input[type="checkbox"]:checked').length;
	return counter;
}
function load_table(page,order,dir){
	show_loading();
	var keyword	= $('#table_search').val();
	var tanggal	= $('#tgl').val();
	$.ajax({
		url		: base_url + 'items/pinjam_data_table',
		type	: 'POST',
		data	: {keyword:keyword,page:page,order:order,dir:dir,tanggal:tanggal},
		dataType: 'JSON',
		success	: function(dt){
			if (dt.t == 0){
				$('#TablePinjam tbody').html('<tr><td colspan="'+$('#TablePinjam thead th').length+'">'+dt.msg+'</td></tr>');
				$('#TablePinjam thead a').attr({'data-direction':dt.dir});
				hide_loading();
			} else {
				$('#TablePinjam tbody').html(dt.html);
				$('#TablePinjam thead a').attr({'data-direction':dt.dir});
				hide_loading();
			}
		}
	});
}
function delete_siswa(obj){
	var konf 	= confirm('Anda yakin ingin menghapus data Mata Pelajaran ini ?');
	var id		= $(obj).attr('data-id');
	if (konf){
		show_loading();
		$.ajax({
			url		: base_url + 'mapel/delete_mapel',
			type	: 'POST',
			data	: { id : id },
			success	: function(data){
				dt = $.parseJSON(data);
				if (typeof dt != 'object'){
					alert('Invalid response from server');
				} else if (dt.t == 0){
					window.location.href = base_url + 'account/login';
				} else if (dt.t == 1){
					alert(dt.msg);
				} else {
					$('#row_'+dt.id).remove();
					hide_loading();
				}
			}
		});
	}
}
</script>
