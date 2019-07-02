<section class="content-header">
    <h1>
        Pengajuan Alat dan Bahan
        <small>Control Panel</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>" onClick="load_page(this);return false"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo base_url('items');?>" onClick="load_page(this);return false">Barang</a></li>
        <li class="active">Data Pengajuan Alat dan Bahan</li>
    </ol>
</section>
<section class="content">
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Tabel Data Pengajuan Alat dan Bahan</h3>
            <div class="box-tools">
                <div class="input-group input-group-sm pull-right" style="width: 150px;margin-left:5px">
                    <input type="text" name="table_search" id="table_search" class="form-control pull-right" placeholder="Search">
                    <div class="input-group-btn">
	                    <button type="submit" class="btn btn-default" onclick="load_table();"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <div class="input-group input-group-sm pull-right" style="margin-left:5px">
                	<input type="text" id="tgl" class="form-control" placeholder="Tgl Pengajuan" onblur="load_table()" />
                </div>
                <?php if ($this->session->userdata('inv_level') == 99){ ?>
            	<a class="btn btn-sm btn-default pull-right" onclick="show_modal(this);return false" title="Buat Pengajuan Baru" href="<?php echo base_url('pengajuan/new_data');?>" style="margin-left:5px"><i class="fa fa-plus-square"></i> Buat Pengajuan Baru</a>
                <?php } ?>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
        	<form id="form_table">
                <table class="table table-bordered table-hover table-stripped" id="data_table">
                    <thead>
                        <tr>
                            <!--<th width="5%">
                            	<div class="ckbox ckbox-default">
                                	<input type="checkbox" id="checkboxDefault" />
                                    <label for="checkboxDefault"></label>
                            	</div>
                            </th>-->
                            <th width="40%">
                            	<a href="javascript:;" id="sort-btn" data-page="1" data-order="pen_name" data-direction="ASC">Judul Pengajuan &amp; Deskripsi Pengajuan</a>
                            </th>
                            <th width="10%">
                            	<a href="javascript:;" id="sort-btn" data-page="1" data-order="pen_date" data-direction="ASC">Tgl Pengajuan</a>
                            </th>
                            <th width="10%">
                            	<a href="javascript:;" id="sort-btn" data-page="1" data-order="pen_status" data-direction="ASC">Diajukan Oleh</a>
                            </th>
                            <th width="10%">
                            	<a href="javascript:;" id="sort-btn" data-page="1" data-order="pen_status" data-direction="ASC">Status Pengajuan</a>
                            </th>
                            <th width="30%">
                            	<a href="javascript:;" id="sort-btn" data-page="1" data-order="pen_alasan" data-direction="ASC">Alasan Penolakan (jika ditolak)</a>
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
	$('*#sort-btn').click(function(e) {
		load_table($(this).attr('data-page'),$(this).attr('data-order'),$(this).attr('data-direction'));
    });
	load_table();
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
	var tgl		= $('#tgl').val();
	$.ajax({
		url		: base_url + 'pengajuan/data_table',
		type	: 'POST',
		data	: {keyword:keyword,page:page,order:order,dir:dir,tgl:tgl},
		dataType: 'JSON',
		success	: function(dt){
			if (dt.t == 0){
				$('#data_table tbody').html('<tr id="row_0"><td colspan="'+$('#data_table thead th').length+'">'+dt.msg+'</td></tr>');
				$('#data_table thead a').attr({'data-direction':dt.dir});
				hide_loading();
			} else {
				$('#data_table tbody').html(dt.html);
				$('#data_table thead a').attr({'data-direction':dt.dir});
				hide_loading();
			}
		}
	});
}
function delete_data(obj){
	var konf 	= confirm('Anda yakin ingin menghapus data ini ?');
	var id		= $(obj).attr('data-id');
	var url		= $(obj).attr('href');
	if (konf){
		show_loading();
		$('#row_'+id).fadeToggle(500);
		$.ajax({
			url		: url,
			type	: 'POST',
			dataType: 'JSON',
			data	: { id : id },
			success	: function(dt){
				if (dt.t == 0){
					alert(dt.msg);
					$('#row_'+id).fadeToggle(500);
					hide_loading();
				} else {
					alert(dt.msg);
					$('#row_'+id).remove();
					hide_loading();
				}
			}
		});
	}
}
function set_pengajuan(obj){
	$('*.btn-pengajuan').fadeToggle(500);
	var id	= $(obj).attr('data-id');
	$.ajax({
		url		: base_url + 'pengajuan/set_active',
		type	: 'POST',
		data	: { id:id },
		dataType: "JSON",
		success	: function(dt){
			if (dt.t == 0){
				alert(dt.msg);
				$(obj).fadeToggle(500);
			}
		}
	});
}
function ajukan(obj){
	var id = $(obj).attr('data-id');
	var konf = confirm('Kirim pengajuan barang ini ?');
	if (konf && id){
		$('.btnpen_'+id).fadeToggle(500);
		$.ajax({
			url		: base_url + 'pengajuan/submit_pengajuan',
			type	: 'POST',
			data	: { id : id },
			dataType: "JSON",
			success	: function(dt){
				if (dt.t == 0){
					alert(dt.msg);
					$('.btnpen_'+id).fadeToggle(500);
				} else {
					$('.btnpen_'+id).remove();
				}
			}
		});
	}
}
</script>
