<section class="content-header">
    <h1>
        <?php echo $data->pen_name;?>
        <small>Control Panel</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>" onClick="load_page(this);return false"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo base_url('items');?>" onClick="load_page(this);return false">Barang</a></li>
        <li><a href="<?php echo base_url('pengajuan');?>" onClick="load_page(this);return false">Pengajuan</a></li>
        <li class="active"><?php echo $data->pen_name;?></li>
    </ol>
</section>
<section class="content">
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title"><?php echo $data->pen_name;?></h3>
            <div class="box-tools">
                <div class="input-group input-group-sm pull-right" style="width: 150px;margin-left:5px">
                    <input type="text" name="table_search" id="table_search" class="form-control pull-right" placeholder="Search">
                    <div class="input-group-btn">
	                    <button type="submit" class="btn btn-default" onclick="load_table();"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <?php if ($this->session->userdata('inv_level') == 99){ ?>
                <?php if ($data->count_cart > 0 && $data->pen_status == 1) { ?>
                	<a class="btnpen_<?php echo $data->pen_id;?> btn btn-default btn-sm" href="javascript:;" data-id="<?php echo $data->pen_id;?>" onclick="ajukan(this);return false" data-toggle="tooltip" title="Submit dan Kunci Pengajuan ini"><i class="fa fa-send"></i> Submit Pengajuan</a>
                <?php } ?>
            	<a class="btn btn-sm btn-default pull-right" onclick="show_modal(this);return false" title="Tambah Barang Baru" href="<?php echo base_url('pengajuan/new_data_items/'.$data->pen_id);?>" style="margin-left:5px"><i class="fa fa-plus-square"></i> Tambah Barang Baru</a>
                <?php } ?>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
        	<form id="form_table">
                <table class="table table-bordered table-hover table-striped" id="data_table">
                    <thead>
                        <tr>
                            <!--<th width="5%">
                            	<div class="ckbox ckbox-default">
                                	<input type="checkbox" id="checkboxDefault" />
                                    <label for="checkboxDefault"></label>
                            	</div>
                            </th>-->
                            <th width="30%">
                            	<a href="javascript:;" id="sort-btn" data-page="1" data-order="items_name" data-direction="ASC">Nama Barang / Merek &amp; Model / Keterangan</a>
                            </th>
                            <th width="10%">
                            	<a href="javascript:;" id="sort-btn" data-page="1" data-order="items_type" data-direction="ASC">Jenis</a>
                            </th>
                            <th width="10%">
                            	<a href="javascript:;" id="sort-btn" data-page="1" data-order="items_qty" data-direction="ASC">Jumlah</a>
                            </th>
                            <th width="15%">
                            	<a href="javascript:;" id="sort-btn" data-page="1" data-order="cart_price" data-direction="ASC">Kisaran Harga</a>
                            </th>
                            <th width="15%">
                            	Total Kisaran Harga
                            </th>
                            <th width="20%">
                            	Keterangan
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                    	<tr class="bg-green">
                        	<th colspan="4"><span class="pull-right">Total</span></th>
                            <!--<th colspan="2"><span class="totalQty text-center">0</span></th>-->
                            <th colspan="2" style="font-size:14px;font-weight:bold">
                            	<span class="pull-left">Rp.</span><span class="pull-right totalAjuan">0</span>
                            </th>
                        </tr>
                    </tfoot>
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
function totalUpdate(){
//	var sum = 0;
//	$('#total_items').each(function(){
//		sum += parseFloat($(this).text());  // Or this.innerHTML, this.innerText
//	});
	var price = 0;
	$('#data_table tbody #total_items').each(function() {
		this_price = $(this).text().replace(/\./g,'');
		//console.log(this_price);
		this_price = parseInt(this_price);
        price += this_price;
    });
	$('.totalAjuan').html(number_format(price,0,'.','.'));
	var qty = 0;
	$('#data_table tbody #qty').each(function() {
        this_qty = parseInt($(this).val());
		qty += this_qty;
    });
	$('.totalQty').html(qty);
}
function load_table(page,order,dir){
	show_loading();
	var keyword	= $('#table_search').val();
	var pen_id	= '<?php echo $data->pen_id;?>';
	$.ajax({
		url		: base_url + 'pengajuan/data_table_items',
		type	: 'POST',
		data	: {keyword:keyword,page:page,order:order,dir:dir,pen_id:pen_id},
		dataType: 'JSON',
		success	: function(dt){
			if (dt.t == 0){
				$('#data_table tbody').html('<tr id="row_0"><td colspan="'+$('#data_table thead th').length+'">'+dt.msg+'</td></tr>');
				$('#data_table thead a').attr({'data-direction':dt.dir});
				hide_loading();
			} else {
				$('#data_table tbody').html(dt.html);
				$('#data_table thead a').attr({'data-direction':dt.dir});
				totalUpdate();
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
function update_qty(obj){
	var id 		= $(obj).attr('data-id');
	var qty		= $(obj).val();
	var price	= $('.price_'+id).val();
	var orig_val= $(obj).attr('data-value');
	if (qty.length > 0){
		$('.total_'+id).html(number_format((qty*price),0,',','.'));
		totalUpdate();
		$.ajax({
			url		: base_url + 'pengajuan/update_qty',
			type	: 'POST',
			data	: { id:id, qty:qty },
			dataType: "JSON",
			success	: function(dt){
				if (dt.t == 0){
					alert(dt.msg);
				}
			}
		});
	}
}
function update_price(obj){
	var id 		= $(obj).attr('data-id');
	var qty		= $('.qty_'+id).val();
	var price	= $(obj).val();
	var orig_val= $(obj).attr('data-value');
	if (price.length > 0){
		$('.total_'+id).html(number_format((qty*price),0,',','.'));
		totalUpdate();
		$.ajax({
			url		: base_url + 'pengajuan/update_price',
			type	: 'POST',
			data	: { id:id, price:price },
			dataType: "JSON",
			success	: function(dt){
				if (dt.t == 0){
					alert(dt.msg);
				}
			}
		});
	}
}
function update_notes(obj){
	var id 		= $(obj).attr('data-id');
	var notes	= $(obj).val();
	if (notes.length > 0){
		$.ajax({
			url		: base_url + 'pengajuan/update_notes',
			type	: 'POST',
			data	: { id:id, notes:notes },
			dataType: "JSON",
			success	: function(dt){
				if (dt.t == 0){
					alert(dt.msg);
				}
			}
		});
	}
}
function number_format (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
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
