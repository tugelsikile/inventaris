<tr id="row_<?php echo $data->user_id;?>">
	<td>
		<span class="fullname_<?php echo $data->user_id;?>"><?php echo $data->user_fullname;?></span>
    </td>
    <td>
    	<table width="100%" id="">
        	<thead>
            	<tr>
                	<th width="60%">Nama Barang</th>
                    <th width="10%">Rusak Total</th>
                    <th width="10%">Rusak</th>
                    <th width="10%">Normal</th>
                    <th width="10%">Baru</th>
                </tr>
            </thead>
            <tbody id="bodyPinjam_<?php echo $data->user_id;?>">
            <?php
			?>
            </tbody>
        </table>
    </td>
</tr>