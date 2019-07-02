<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Inventaris | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css');?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url('assets/font-awesome/css/font-awesome.min.css');?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url('assets/ionicons/css/ionicons.min.css');?>">
   <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/select2/select2.min.css');?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('assets/dist/css/AdminLTE.min.css');?>">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url('assets/dist/css/skins/skin-red.css');?>">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/iCheck/all.css');?>">
  <!-- Date Picker -->
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/datepicker/datepicker3.css');?>">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/daterangepicker/daterangepicker-bs3.css');?>">
  <!-- custom icon -->
  <link rel="stylesheet" href="<?php echo base_url('assets/icontinymce.css');?>">
  <!-- custom icon -->
  <link rel="stylesheet" href="<?php echo base_url('assets/style.css');?>">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<!-- jQuery 2.2.0 -->
<script src="<?php echo base_url('assets/plugins/jQuery/jQuery-2.2.0.min.js');?>"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url('assets/jquery-ui/jquery-ui.min.js');?>"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js');?>"></script>
<!-- Select2 -->
<script src="<?php echo base_url('assets/plugins/select2/select2.full.min.js');?>"></script>
<!-- InputMask -->
<script src="<?php echo base_url('assets/plugins/input-mask/jquery.inputmask.js');?>"></script>
<script src="<?php echo base_url('assets/plugins/input-mask/jquery.inputmask.date.extensions.js');?>"></script>
<script src="<?php echo base_url('assets/plugins/input-mask/jquery.inputmask.extensions.js');?>"></script>
<!-- Sparkline -->
<script src="<?php echo base_url('assets/plugins/sparkline/jquery.sparkline.min.js');?>"></script>
<!-- daterangepicker -->
<script src="<?php echo base_url('assets/moment.min.js');?>"></script>
<script src="<?php echo base_url('assets/plugins/daterangepicker/daterangepicker.js');?>"></script>
<!-- datepicker -->
<script src="<?php echo base_url('assets/plugins/datepicker/bootstrap-datepicker.js');?>"></script>
<!-- iCheck 1.0.1 -->
<script src="<?php echo base_url('assets/plugins/iCheck/icheck.min.js');?>"></script>
<!-- Slimscroll -->
<script src="<?php echo base_url('assets/plugins/slimScroll/jquery.slimscroll.min.js');?>"></script>
<!-- FastClick -->
<script src="<?php echo base_url('assets/plugins/fastclick/fastclick.js');?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('assets/dist/js/app.min.js');?>"></script>
<script>
var base_url = '<?php echo base_url();?>';
function show_loading(){
	$('.loading-bottom').slideDown(500);
}
function hide_loading(){
	$('.loading-bottom').slideUp(500);
}
function load_page(obj){
	var url	= $(obj).attr('href');
	show_loading();
	$('div.content-wrapper').load(url,function(response,status,xhr){
		hide_loading();
	});
}
function show_modal(obj){
	show_loading();
	var title 	= $(obj).attr('title');
	var url		= $(obj).attr('href');
	$('.modal-title').html(title);
	$('.modal-footer').html('');
	$('.modal-body').removeClass('no-padding');
	$('.modal-body').html('<center><i class="fa fa-spin fa-refresh"></i><br>Loading, Please wait.</center>').load(url,function(){
		$('.modal').modal('show');
		hide_loading();
	});
}
function hide_modal(){
	$('.modal-title,.modal-body,.modal-footer').html('');
	$('.modal').modal('hide');
}
function pinjam(obj){
	var id 		= $(obj).attr('data-id');
	var name	= $(obj).attr('data-name');
	var flag	= $(obj).attr('data-flag');
	var max_qty	= $(obj).attr('data-max');
	var i		= $('#pinjam_table .rowdata').length;
	if (max_qty == 0){
		alert('Barang ini tidak dapat dipinjam karena stoknya kosong !');
	} else {
		if ($('#pinjam_table #pin_0').length > 0){
			$('#pinjam_table #pin_0').remove();
		}
		if ($('#pinjam_table #pin_'+flag+'_'+id).length == 0){
			var html	= '<tr id="pin_'+flag+'_'+id+'" class="rowdata"> \
								<input type="hidden" name="items_id['+i+']" value="'+id+'"/> \
								<input type="hidden" name="items_flag['+i+']" value="'+flag+'"/> \
								<td>'+name+'</td> \
								<td> \
									<input type="text" style="text-align:center" class="form-control input-xs" name="items_qty['+i+']" value="1" id="pinqty_'+flag+'_'+id+'" /> \
								</td> \
						   </tr>';
			$('#pinjam_table tbody').append(html);
		} else {
			var input_qty = parseInt($('#pinqty_'+flag+'_'+id).val());
			input_qty = input_qty + 1;
			if (input_qty > max_qty){
				alert('Barang ini tidak dapat dipinjam karena stoknya tidak mencukupi');
			} else {
				$('#pinqty_'+flag+'_'+id).val(input_qty);
			}
		}
	}
}
</script>
</head>
<body class="hold-transition skin-red sidebar-mini fixed">
<div class="wrapper">


<?php

$this->load->view('main_header');

$this->load->view('left_menu');
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
<?php
if (isset($body)){
	$this->load->view($body);
} else {
	$this->load->view('dashboard');
}
?>  
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2016-<?php echo date('Y');?> <a href="http://tugelsikile.com">Tugelsikile Studio</a>.</strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-light">
    <!-- Tab panes -->
    <div class="tab-content">
    	<?php $this->load->view('form/pinjam_new'); ?>
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->


<div class="loading-bottom" style="display:none;width:200px;padding:20px;background:rgba(0,0,0,.8);position:fixed;bottom:20px;right:20px;">
	<div class="progress progress-sm active" style="margin:0">
	    <div class="progress-bar progress-bar-primary progress-bar-striped" style="width: 100%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="100" role="progressbar">
    		<span class="sr-only">100% Complete</span>
	    </div>
    </div>
    <center><small class="text-info">Loading, Please wait</small></center>
</div>

    <div class="modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Default Modal</h4>
                </div>
                <div class="modal-body">
                    <p>One fine body&hellip;</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

</body>
</html>
