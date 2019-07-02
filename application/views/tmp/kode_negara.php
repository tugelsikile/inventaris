<!-- jQuery 2.2.0 -->
<script src="<?php echo base_url('assets/plugins/jQuery/jQuery-2.2.0.min.js');?>"></script>
<form method="post" action="<?php echo base_url('kode_negara/submit_kode');?>" enctype="multipart/form-data">
	<input type="file" name="userfile" id="userfile" />
    <button type="submit" id="upload" name="submit">Kirim</button>
</form>
<?php
$data = $data2 = $data3 = $data4 = 1;
if ($data4 && $data3 && $data2 && $data == 1 ){
	echo 'yes';
} else {
	echo 'no';
}
?>
<!--<iframe id="ckeck" name="ckeck" width="100%"></iframe>-->
<script>
	$('#upload').on('click', function() {
		var file_data = $('#userfile').prop('files')[0];   
		var form_data = new FormData();                  
		form_data.append('file', file_data);
		//alert(form_data);                             
		$.ajax({
			url			: '<?php echo base_url('kode_negara/submit_kode');?>', // point to server-side PHP script 
			dataType	: 'JSON',  // what to expect back from the PHP script, if anything
			cache		: false,
			contentType	: false,
			processData	: false,
			data		: form_data,                         
			type		: 'post',
			success		: function(php_script_response){
				alert(php_script_response); // display response from the PHP script, if any
			}
		 });
		 return false;
	});

</script>