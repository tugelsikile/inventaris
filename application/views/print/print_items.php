<!html>
<head>
	<link rel="stylesheet" href="<?php echo base_url('assets/cetak-min.css');?>" type="text/css">
    <title>Cetak Data Barang</title>
</head>
<body>
<?php
if (!$data){
	echo '<center>TIDAK ADA DATA BARANG</center>';
} else {
	$data_items = $data;
	$data_items = array_chunk($data_items,30);
	$arrayKeys	= array_keys($data_items);
	$lastArrayKey = array_pop($arrayKeys);
	$nomor		= 1;
	$pages		= 1;
	$jumPages	= count($data_items);
	$rustot = $rus = $nor = $tot = $baru = 0;
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
                    <th rowspan="2" width="5%">No.</th>
                    <th rowspan="2" width="10%">Kode Barang</th>
                    <th rowspan="2">Nama Barang<br></th>
                    <th rowspan="2" width="10%">Kategori</th>
                    <th width="25%" colspan="5">Jumlah Barang</th>
                </tr>
                <tr>
                	<th width="5%">Rusak Total</th>
                    <th width="5%">Rusak</th>
                    <th width="5%">Normal</th>
                    <th width="5%">Baru</th>
                    <th width="5%">Total</th>
                </tr>
                <tbody>
                <?php
				foreach($valItems as $dataItems){
					$rustot = $rustot + $dataItems->items_rusak_total;
					$rus	= $rus + $dataItems->items_rusak_sedang;
					$nor	= $nor + $dataItems->items_normal;
					$baru	= $baru + $dataItems->items_baru;
					$tot	= $tot + $dataItems->items_stock;
					?>
                    <tr>
                    	<td align="center"><?php echo $nomor;?></td>
                        <td><?php echo $dataItems->items_code;?></td>
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
                        <td align="center"><?php echo $dataItems->items_rusak_total;?></td>
                        <td align="center"><?php echo $dataItems->items_rusak_sedang;?></td>
                        <td align="center"><?php echo $dataItems->items_normal;?></td>
                        <td align="center"><?php echo $dataItems->items_baru;?></td>
                        <td align="center"><?php echo $dataItems->items_stock;?></td>
                    </tr>
                    <?php
					$nomor++;
				}
				?>
                </tbody>
                <?php if ($k == $lastArrayKey){ ?>
                <tr>
                	<td align="right" colspan="4"><strong>Total Barang</strong></td>
                    <td align="center"><strong><?php echo $rustot;?></strong></td>
                    <td align="center"><strong><?php echo $rus;?></strong></td>
                    <td align="center"><strong><?php echo $nor;?></strong></td>
                    <td align="center"><strong><?php echo $baru;?></strong></td>
                    <td align="center"><strong><?php echo $tot;?></strong></td>
                </tr>
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
            <div class="footer">
                <table height="30px" width="100%">
                    <tbody>
                        <tr>
                            <td width="50px" style="border:1px solid black"></td>
                            <td width="5px"> </td>
                            <td style="border:1px solid black;font-weight:bold;font-size:14px;text-align:center;">SMK MUHAMMADIYAH KANDANGHAUR</td>
                            <td width="5px"> </td>
                            <td width="50px" style="border:1px solid black" align="center">
                            	<?php echo $pages.'/'.$jumPages; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
		$pages++;
	}//end pages
}
?>
</body>
</html>