<section class="content-header">
    <h1>
        Data Kategori
        <small>Control Panel</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>" onClick="load_page(this);return false"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo base_url('items');?>" onClick="load_page(this);return false">Barang</a></li>
        <li class="active">Data Kategori</li>
    </ol>
</section>
<section class="content">
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Tabel Data Kategori</h3>
            <div class="box-tools">
                <div class="input-group input-group-sm pull-right" style="width: 150px;margin-left:5px">
                    <input type="text" name="table_search" id="table_search" class="form-control pull-right" placeholder="Search">
                    <div class="input-group-btn">
	                    <button type="submit" class="btn btn-default" onclick="load_table();"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <?php if ($this->session->userdata('inv_level') == 99){ ?>
            	<a class="btn btn-sm btn-default pull-right" onclick="show_modal(this);return false" title="Tambah Kategori Baru" href="<?php echo base_url('category/new_data');?>" style="margin-left:5px"><i class="fa fa-plus-square"></i> Tambah Kategori</a>
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
                            <th width="90%">
                            	<a href="javascript:;" id="sort-btn" data-page="1" data-order="cat_name" data-direction="ASC">Nama Kategori</a>
                            </th>
                            <th width="10%">
                            	Jml Barang
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
//	$('#cat').select2();
	$('*#sort-btn').click(function(e) {
		load_table($(this).attr('data-page'),$(this).attr('data-order'),$(this).attr('data-direction'));
    });
	$('#checkboxDefault').click(function(e) {
        if ($(this).is(':checked')){
			$('#data_table tbody tr').addClass('row-active');
			$('#data_table tbody :checkbox').prop('checked',true);
			$('.btn-delete').removeClass('disabled');
		} else {
			$('#data_table tbody tr').removeClass('row-active');
			$('#data_table tbody :checkbox').prop('checked',false);
			$('.btn-delete').addClass('disabled');
		}
    });
	$('#form_table').submit(function(e) {
		var count	= $('#data_table tbody input[type="checkbox"]:checked').length;
		if (count == 0){
			alert('Pilih dulu Mata Pelajaran yang akan dihapus');
		} else {
			var konfirm = confirm('Anda yakin ingin menghapus data Mata Pelajaran yang dipilih ?');
			if (konfirm){
				show_loading();
				$.ajax({
					url		: base_url + 'mapel/delete_mapel_bulk',
					type	: 'POST',
					data	: $(this).serialize(),
					success	: function(data){
						var dt = $.parseJSON(data);
						if (typeof dt != 'object'){
							alert('Invalid response from server');
						} else if (dt.t == 0) {
							window.location.href = base_url + 'account/login';
						} else if (dt.t == 1){
							alert(dt.msg);
							hide_loading();
						} else {
							alert(dt.msg);
							load_table();
							hide_loading();
						}
					}
				});
			}
		}
        return false;
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
	var cat_id	= $('#cat').val();
	var jenis	= $('#type').val();
	$.ajax({
		url		: base_url + 'category/data_table',
		type	: 'POST',
		data	: {keyword:keyword,page:page,order:order,dir:dir,jenis:jenis,cat_id:cat_id},
		success	: function(data){
			dt = $.parseJSON(data);
			if (typeof dt != 'object') {
				alert('Invalid response from server');
			} else if (dt.t == 0){
				$('#data_table tbody').html('<tr id="row_0"><td colspan="'+$('#data_table thead th').length+'">'+dt.msg+'</td></tr>');
				$('#data_table thead a').attr({'data-direction':dt.dir});
				$('.totrustot').html('0');
				$('.totrus').html('0');
				$('.totnorm').html('0');
				$('.totbaru').html('0');
				$('.total').html('0');
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
</script>
