<section class="content-header">
    <h1>
        Cetak Pengajuan
        <small>Control Panel</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>" onClick="load_page(this);return false"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo base_url('items');?>" onClick="load_page(this);return false">Barang</a></li>
        <li><a href="<?php echo base_url('pengajuan');?>" onClick="load_page(this);return false">Pengajuan</a></li>
        <li class="active">Cetak Pengajuan</li>
    </ol>
</section>
<section class="content">
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Cetak Pengajuan</h3>
            <form class="box-tools" target="cetak-wrapper" action="<?php echo base_url('pengajuan/pengajuan_print/'.$data->pen_id);?>" method="post">
                <a class="btn btn-sm btn-default pull-right" onclick="print_page();return false" title="Cetak Data barang" style="margin-left:5px"><i class="fa fa-print"></i> Cetak </a>
                <button class="btn btn-sm btn-default pull-right" title="Refresh Data barang" type="submit" style="margin-left:5px"><i class="fa fa-refresh"></i> Update </button>
            </form>
        </div>
        <div class="clearfix"></div>
	</div>
    <div class="clearfix" id="flags"></div>
    <iframe name="cetak-wrapper" src="<?php echo base_url('pengajuan/pengajuan_print/'.$data->pen_id);?>" id="cetak-wrapper" style="border:solid 1px #CCC;margin:0;padding:0"></iframe>
</section>
<script>
function print_page(){
	window.frames["cetak-wrapper"].focus();
	window.frames["cetak-wrapper"].print();
}
function refresh_iframe(){
	//document.getElementById('cetak-wrapper').contentWindow.location.reload();
	var tanggal = $('#tgl').val();
	document.getElementById('cetak-wrapper').contentWindow.location.reload();
}
$(document).ready(function(e) {
	$('#tgl').datepicker({
		format: 'yyyy-mm-dd',
		endDate:'1d'
		//startDate: '-3d'
	});
	var height = $(window).height();
	height = height - $('.sidebar-form').outerHeight(true);
	height = height - $('.user-panel').outerHeight(true);
	height = height - $('.main-header').outerHeight(true);
	height = height - $('.main-footer').outerHeight(true);
	height = height - $('.sidebar-menu .header').outerHeight(true);
	$('#cetak-wrapper').height(height);
	$('#cetak-wrapper').width($('.content').width());
});
</script>
