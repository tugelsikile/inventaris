<!html>
<head>
	<link rel="stylesheet" href="<?php echo base_url('assets/cetak-min.css');?>" type="text/css">
    <title>Cetak Pengajuan</title>
</head>
<body>
<div class="page">
	<center>
		<h1 style="font-size:18pt;margin-top:50px;font-weight:bold">PROPOSAL<br><?php echo strtoupper($pen->pen_name);?></h1>
        
        <p><img src="<?php echo base_url('assets/img/logo.png');?>" width="200px" style="margin-top:50px"/></p>
    </center>
    <div class="footer">
        <table height="30px" width="100%">
            <tbody>
                <tr>
                    <td width="50px" style="border:1px solid black"></td>
                    <td width="5px"> </td>
                    <td style="border:1px solid black;font-weight:bold;font-size:14px;text-align:center;">SMK MUHAMMADIYAH KANDANGHAUR</td>
                    <td width="5px"> </td>
                    <td width="50px" style="border:1px solid black" align="center"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="page">
    <table width="100%">
        <tr>
            <td width="100px" valign="middle">
                <img src="<?php echo base_url('assets/img/logo-dinas-bw.jpg');?>" width="60px"/>
            </td>
            <td>
                <center>
                    <strong class="f12">
                    <small>PIMPINAN DAERAH MUHAMMADIYAH INDRAMAYU</small><br>
                    SMK MUHAMMADIYAH KANDANGHAUR<br/>
                    <small>Jl. Karanganyar No. 28/A Kec Kandanghaur Kab. Indramayu 45254</small>
                </center>
            </td>
            <td width="100px" align="center" valign="middle">
                <img src="<?php echo base_url('assets/img/logo-bw.png');?>" width="50px"/>
            </td>
        </tr>
    </table>
    <div style="border-bottom:solid 2px #000;height:10px"></div>
    <div style="border-bottom:solid 1px #000;height:3px;margin-bottom:20px"></div>
    <div style="float:left;width:50%">
    	<span style="width:80px;display:inline-block">Lampiran </span>: 1 (bendel)<br>
        <span style="width:80px;display:inline-block">Perihal </span>: <?php echo $pen->pen_name;?>
    </div>
    <div style="float:right;width:50%;text-align:right">
    	Kandanghaur, <?php echo $this->conv->tglIndo($pen->pen_date);?><br>
        Kepada Yth.<br>
        Bapak Kepala SMK Muhammadiyah Kandanghaur<br>
        di<br>Tempat
    </div>
    <div style="clear:both"></div>
    <div style="text-align:justify;line-height:13pt;margin-top:50px">
    	<center>السَّلاَمُ عَلَيْكُمْ وَرَحْمَةُ اللهِ وَبَرَكَاتُهُ</center><br>
	    <span style="width:30px;display:inline-block"></span>Atas rahmat allah SWT, kami atas nama Kepala Program Bidang Studi Teknik Komputer dan Jaringan. Bermaksud mengajukan permohonan bantuan dana yang akan kami pergunakan <?php echo $pen->pen_notes; ?>.<br>
    
    	<span style="width:30px;display:inline-block"></span>Sebagai bahan pertimbangan kami lampirkan beserta proposal ini daftar peralatan dan bahan yang diperlukan. Dengan harapan, dapat memberikan gambaran yang lebih jelas tentang <?php echo $pen->pen_name;?>.<br>
    
		<span style="width:30px;display:inline-block"></span>Demikian proposal ini kami sampaikan. Akhirnya, atas perhatian dan bantuan yang diberikan, sebelumnya kami sampaikan terima kasih.
    </div>
    
    <div style="margin-top:50px;float:right;text-align:right">
    	Kandanghaur, <?php echo $this->conv->tglIndo($pen->pen_date);?><br>
        Kepala Program Studi Teknik Komputer dan Jaringan
        <br><br><br><br><br><br><br>
        (<nip></nip>)
    </div>
</div>
<?php
if (!$data){
	echo '<center>TIDAK ADA DATA BARANG</center>';
} else {
	$data_items = $data;
	$data_items = array_chunk($data_items,25);
	$arrayKeys	= array_keys($data_items);
	$lastArrayKey = array_pop($arrayKeys);
	$nomor		= 1;
	$pages		= 1;
	$jumPages	= count($data_items);
	$rustot = $rus = $nor = $tot = $baru = 0;
	//var_dump($data_items);
	foreach($data_items as $k => $valItems){
		?>
        <div class="page">
            <table width="100%">
                <tr>
                    <td width="100px" valign="middle">
                        <img src="<?php echo base_url('assets/img/logo-dinas-bw.jpg');?>" width="60px"/>
                    </td>
                    <td>
                        <center>
                            <strong class="f12">
                            <small>PIMPINAN DAERAH MUHAMMADIYAH INDRAMAYU</small><br>
                            SMK MUHAMMADIYAH KANDANGHAUR<br/>
                            <small>Jl. Karanganyar No. 28/A Kec Kandanghaur Kab. Indramayu 45254</small>
                        </center>
                    </td>
                    <td width="100px" align="center" valign="middle">
                        <img src="<?php echo base_url('assets/img/logo-bw.png');?>" width="50px"/>
                    </td>
                </tr>
            </table>
            <div style="border-bottom:solid 2px #000;height:10px"></div>
            <div style="border-bottom:solid 1px #000;height:3px;margin-bottom:20px"></div>
            <h1 style="margin:10px 0;text-align:center;font-size:14px;font-weight:bold">
            	DAFTAR ALAT DAN BAHAN HABIS PAKAI
            </h1>
            <table class="it-grid it-cetak" width="100%">
            	<tr style="height:30px">
                    <th width="5%">No.</th>
                    <th>Nama / Merek &amp; Model Barang<br></th>
                    <th width="10%">Kategori</th>
                    <th width="10%">Jumlah Barang</th>
                    <th width="15%">Kisaran Harga</th>
                    <th width="20%">Total Kisaran Harga</th>
                </tr>
                <tbody>
                <?php
				$sum_total	= 0;
				foreach($valItems as $dataItems){
					$sum_total += $dataItems->items_qty*$dataItems->cart_price;
					?>
                    <tr>
                    	<td align="center"><?php echo $nomor;?></td>
                        <td>
							<?php
							$brand = $model = '';
							if (strlen(trim($dataItems->brand_name)) > 0){
								$brand = $dataItems->brand_name;
							}
							if (strlen(trim($dataItems->items_model)) > 0){
								$model = '<strong>'.$dataItems->items_model.'</strong>';
							}
							echo $dataItems->items_name.' '.$brand.' '.$model;
							?>
                        </td>
                        <td align="center"><?php echo $this->conv->items_jenis($dataItems->items_type);?></td>
                        <td align="center"><?php echo $dataItems->items_qty;?></td>
                        <td>Rp. <span class="pull-right"><?php echo number_format($dataItems->cart_price,0,'.','.');?></span></td>
                        <td>
                        	Rp.
                            <span class="pull-right">
                            	<?php echo number_format(($dataItems->items_qty*$dataItems->cart_price),0,'.','.');?>
                            </span>
                        </td>
                    </tr>
                    <?php
					$nomor++;
				}
				?>
                </tbody>
                <?php if ($k == $lastArrayKey){ ?>
                <tfoot>
                	<th colspan="5" align="right">Total Harga</th>
                    <th>
                    	<span class="pull-left">Rp.</span>
                        <span class="pull-right">
                            <?php echo number_format($sum_total,0,'.','.');?>
                        </span>
                    </th>
                </tfoot>
                <?php } ?>
            </table>
			<?php
            //jika ini adalah page terakhir
            if($k == $lastArrayKey) {
                ?>
                <table width="100%" style="margin-top:20px">
                    <tr>
                        <td>
                        </td>
                        <td align="center" width="300">
                        	<br>&nbsp;<br>
                            Wakasek Bidang Sarana dan Prasarana
                            <br><br><br><br><br><br><br>(<nip></nip>)
                        </td>
                        <td align="center" width="300">
                        	Kandanghaur, <?php echo $this->conv->tglIndo(date('Y-m-d'));?><br>
                            Mengetahui<br>
                            Kepala SMK Muhammadiyah Kandanghaur
                            <br><br><br><br><br><br><br>(<nip></nip>)
                        </td>
                    </tr>
                </table>
                <?php
            }
            ?>
        </div>
        <?php
		$pages++;
	}//end pages
}
?>
</body>
</html>