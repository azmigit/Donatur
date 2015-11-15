
<?php
	include 'config.php';
	if($_GET['aksi']== 'pencapaian'){
?>
<div class="panel panel-default">
  <div class="panel-heading">Grafik Pencapaian Perbulan</div>
  <div class="panel-body table-responsive">
    <div class="" id="grafik_pencapaian"></div>
  </div>
</div>

<?php
error_reporting(0);

function target($kota){
	$q = mysql_query("SELECT bulan AS bulan, target , kantor FROM setting_target a INNER JOIN hcm_kantor b ON b.id_kantor = a.id_jenis WHERE jenis='id_kantor' AND b.kantor='$kota' AND tanggal=0 AND tahun=2015 AND bulan!=0 GROUP BY bulan");
	while ($row = mysql_fetch_array($q)) {
		$rows[] = $row;
		foreach ($rows as $fields) {
			$z[$fields['bulan']] = $fields['target'];
		}
	}
	return $z;
}

function pencapaian($kota){
	$q = mysql_query("SELECT 
						MONTH(tgl_transaksi) AS bulan, 
						SUM(transaksi) AS pencapaian ,
						kantor
						FROM 
							corez_transaksi a
						INNER JOIN hcm_kantor b ON b.`id_kantor` = a.`id_kantor_transaksi`
						WHERE kantor='$kota' AND YEAR(tgl_transaksi)=2015 GROUP BY MONTH(tgl_transaksi)
					");
	$f = target($kota);
	$i = 0;
	
	while ($row = mysql_fetch_array($q)) {
		$rows[] = $row;
	}
	
	foreach ($rows as $fields) {
		$g[$fields['bulan']] = (float)$fields['pencapaian'];
	}

	for ($i=1; $i <= 12; $i++) { 
		$pencapaian2[] = ($g[$i]) ? (float)$g[$i] : 0 ;
		$target2[] = ((float)$f[$i]) ? (float)$f[$i] : 0;
		$bulan[] = date('M', strtotime('2015-'.$i.'-25'));
	}

	$array = array(
			array(
				"name" => "Pencapaian",
				"data" => $pencapaian2
				),
			array(
				"name" => "Target",
				"data" => $target2
				)
		);
	return array("data"=>$array,"bulan"=>$bulan);
}
$te = pencapaian($_POST['kota']);
?>

<script type="text/javascript">
	var gft = <?php echo json_encode($te['bulan']); ?>;
	var nama = "<?php echo $_POST['kota']; ?>";
	var data_transaksi = <?php echo json_encode($te['data']); ?>;
	gf('grafik_pencapaian','Grafik Pencapaian Per Bulan ('+nama+')', '(Rp)','', gft, data_transaksi, true);
</script> 
<?php
//filter 1 profil
}else if($_GET['aksi']=='filter1'){
	echo "<table class='table table-bordered'>
	    	<tr style='background:#99ccff'>
	    		<th rowspan='2'>Jenis Program</th>
	    		<th colspan='12'>Bulan</th>
	    		<th rowspan='2'>Grand Total</th>
	    	</tr>
	    	<tr style='background:#99ccff'>
	    		<th>Jan</th>
	    		<th>Feb</th>
	    		<th>Mar</th>
	    		<th>Apr</th>
	    		<th>Mei</th>
	    		<th>Jun</th>
	    		<th>Jul</th>
	    		<th>Agu</th>
	    		<th>Sep</th>
	    		<th>Okt</th>
	    		<th>Nov</th>
	    		<th>Des</th>
	    	</tr>";

	    	$q = mysql_query("SELECT
								program,
								COUNT(IF(bulan = 1,1,NULL)) AS 'Jan',
								SUM(IF(bulan = 1,transaksi,0)) AS '1',
								COUNT(IF(bulan = 2,1,NULL)) AS 'Feb',
								SUM(IF(bulan = 2,transaksi,0)) AS '2',
								COUNT(IF(bulan = 3,1,NULL)) AS 'Mar',
								SUM(IF(bulan = 3,transaksi,0)) AS '3',
								COUNT(IF(bulan = 4,1,NULL)) AS 'Apr',
								SUM(IF(bulan = 4,transaksi,0)) AS '4',
								COUNT(IF(bulan = 5,1,NULL)) AS 'Mei',
								SUM(IF(bulan = 5,transaksi,0)) AS '5',
								COUNT(IF(bulan = 6,1,NULL)) AS 'Jun',
								SUM(IF(bulan = 6,transaksi,0)) AS '6',
								COUNT(IF(bulan = 7,1,NULL)) AS 'Jul',
								SUM(IF(bulan = 7,transaksi,0)) AS '7',
								COUNT(IF(bulan = 8,1,NULL)) AS 'Ags',
								SUM(IF(bulan = 8,transaksi,0)) AS '8',
								COUNT(IF(bulan = 9,1,NULL)) AS 'Sep',
								SUM(IF(bulan = 9,transaksi,0)) AS '9',
								COUNT(IF(bulan = 10,1,NULL)) AS 'Okt',
								SUM(IF(bulan = 10,transaksi,0)) AS '10',
								COUNT(IF(bulan = 11,1,NULL)) AS 'Nov',
								SUM(IF(bulan = 11,transaksi,0)) AS '11',
								COUNT(IF(bulan = 12,1,NULL)) AS 'Des',
								SUM(IF(bulan = 12,transaksi,0)) AS '12',
								transaksi, SUM(transaksi) AS Tot,
								COUNT(bulan) as totBul
								FROM (
								SELECT MONTH(tgl_transaksi) AS bulan, id_muzakki , program, transaksi
								FROM
								corez_transaksi a
								INNER JOIN setting_program b ON b.id_program = a.id_program
								WHERE id_muzakki='10111-1506240001' AND YEAR(tgl_transaksi) = $_POST[tahun]
								) AS dummy_table
								GROUP BY program
										");
						
						$grand_tot = 0;
				    	for($i=1;$i<=12;$i++){
				    		${'jmlhGT'.$i} = 0;
				    	}
			    		while($d = mysql_fetch_array($q)){
			    			echo "<tr>
						    		<td>".$d['program']."</td>
						    		<td>".change($d['Jan'])."</td>
						    		<td>".change($d['Feb'])."</td>
						    		<td>".change($d['Mar'])."</td>
						    		<td>".change($d['Apr'])."</td>
						    		<td>".change($d['Mei'])."</td>
						    		<td>".change($d['Jun'])."</td>
						    		<td>".change($d['Jul'])."</td>
						    		<td>".change($d['Ags'])."</td>
						    		<td>".change($d['Sep'])."</td>
						    		<td>".change($d['Okt'])."</td>	
						    		<td>".change($d['Nov'])."</td>
						    		<td>".change($d['Des'])."</td>
						    		<td>".change($d['totBul'])."</td>
						    	</tr>
								<tr>
									<td><b>Setoran Tunai</b></td>
									<td>".change($d['1'])."</td>
						    		<td>".change($d['2'])."</td>
						    		<td>".change($d['3'])."</td>
						    		<td>".change($d['4'])."</td>
						    		<td>".change($d['5'])."</td>
						    		<td>".change($d['6'])."</td>
						    		<td>".change($d['7'])."</td>
						    		<td>".change($d['8'])."</td>
						    		<td>".change($d['9'])."</td>
						    		<td>".change($d['10'])."</td>	
						    		<td>".change($d['11'])."</td>
						    		<td>".change($d['12'])."</td>
						    		<td>Rp.".number_format($d['Tot'], 2)."</td>
								</tr>
						    	";
						    	for($i=1;$i<=12;$i++){
						    		${'jmlhGT'.$i} = ${'jmlhGT'.$i} + $d[$i];
						    	}
						    	$grand_tot = $grand_tot + $d['Tot'];
			    		}
			    		echo "<tr style='background:#99ccff'>
									<td><b>Grand Total</b></td>";
									for($i=1;$i<=12;$i++){
							    		${'jmlhGT'.$i} = ${'jmlhGT'.$i} + $d[$i];
							    		echo "<td>".number_format(${'jmlhGT'.$i}, 2)."</td>";
							    	}
						    		
						echo "<td>Rp.".number_format($grand_tot,2)."</td>
							  </tr>
			    	</table>";

}else if($_GET['aksi']=='filter2'){

	echo '<table class="table table-bordered">
    	<tr style="background:#99ccff">
    		<th rowspan="2">Jenis Program</th>
    		<th colspan="12">Bulan</th>
    		<th rowspan="2">Grand Total</th>
    	</tr>
    	<tr style="background:#99ccff"">
    		<th>Jan</th>
    		<th>Feb</th>
    		<th>Mar</th>
    		<th>Apr</th>
    		<th>Mei</th>
    		<th>Jun</th>
    		<th>Jul</th>
    		<th>Agu</th>
    		<th>Sep</th>
    		<th>Okt</th>
    		<th>Nov</th>
    		<th>Des</th>
    	</tr>';

	$q3 = mysql_query("SELECT
						program,
						COUNT(IF(bulan = 1,1,NULL)) AS '1',
						COUNT(IF(bulan = 2,1,NULL)) AS '2',
						COUNT(IF(bulan = 3,1,NULL)) AS '3',
						COUNT(IF(bulan = 4,1,NULL)) AS '4',
						COUNT(IF(bulan = 5,1,NULL)) AS '5',
						COUNT(IF(bulan = 6,1,NULL)) AS '6',
						COUNT(IF(bulan = 7,1,NULL)) AS '7',
						COUNT(IF(bulan = 8,1,NULL)) AS '8',
						COUNT(IF(bulan = 9,1,NULL)) AS '9',
						COUNT(IF(bulan = 10,1,NULL)) AS '10',
						COUNT(IF(bulan = 11,1,NULL)) AS '11',
						COUNT(IF(bulan = 12,1,NULL)) AS '12',
						COUNT(bulan) AS totBul
						FROM (
						SELECT MONTH(tgl_transaksi) AS bulan, id_muzakki , program
							FROM
							corez_transaksi a
							INNER JOIN setting_program b ON b.id_program = a.id_program
							WHERE id_muzakki='10111-1506240001' AND YEAR(tgl_transaksi) = $_POST[tahun]
						) AS dummy_table
						GROUP BY program
					");

			for($i=1;$i<=12;$i++){
	    		${'jmlhProg'.$i} = 0;
	    	}
	    	$grand_totProg = 0;
			while($dataD = mysql_fetch_array($q3)){

				echo "
					<tr>
			    		<td>".$dataD['program']."</td>
			    		<td>".change($dataD['1'])."</td>
			    		<td>".change($dataD['2'])."</td>
			    		<td>".change($dataD['3'])."</td>
			    		<td>".change($dataD['4'])."</td>
			    		<td>".change($dataD['5'])."</td>
			    		<td>".change($dataD['6'])."</td>
			    		<td>".change($dataD['7'])."</td>
			    		<td>".change($dataD['8'])."</td>
			    		<td>".change($dataD['9'])."</td>
			    		<td>".change($dataD['10'])."</td>	
			    		<td>".change($dataD['11'])."</td>
			    		<td>".change($dataD['12'])."</td>
			    		<td>".change($dataD['totBul'])."</td>
			    	</tr>
					<tr>
						<td><b>Setoran Tunai</b></td>
						<td>".change($dataD['1'])."</td>
			    		<td>".change($dataD['2'])."</td>
			    		<td>".change($dataD['3'])."</td>
			    		<td>".change($dataD['4'])."</td>
			    		<td>".change($dataD['5'])."</td>
			    		<td>".change($dataD['6'])."</td>
			    		<td>".change($dataD['7'])."</td>
			    		<td>".change($dataD['8'])."</td>
			    		<td>".change($dataD['9'])."</td>
			    		<td>".change($dataD['10'])."</td>	
			    		<td>".change($dataD['11'])."</td>
			    		<td>".change($dataD['12'])."</td>
			    		<td>".change($dataD['totBul'])."</td>
					</tr>
				";
				for($i=1;$i<=12;$i++){
		    		${'jmlhProg'.$i} = ${'jmlhProg'.$i} + $dataD[$i];
		    	}
		    	$grand_totProg = $grand_totProg + $dataD['totBul'];
			}

			echo "	<tr style='background:#99ccff'>
						<td><b>Grand Total</b></td>";
					for($i=1;$i<=12;$i++){
			    		echo "<td>".${'jmlhProg'.$i}."</td>";
			    	}	
			echo "		<td>".$grand_totProg."</td>
					</tr>
		</table>";

} else if ($_GET['aksi'] == 'pencarian') {

	$qr = mysql_query("SELECT a.`muzakki`, a.`id_muzakki`, a.`telpon`
			FROM 
				corez_muzakki a
				INNER JOIN corez_transaksi b ON b.id_muzakki = a.id_muzakki		
				INNER JOIN setting_program c ON c.id_program = b.id_program		
				INNER JOIN setting_sumber_dana d ON d.id_sumber_dana = c.id_sumber_dana		
			WHERE YEAR(a.tgl_reg) <= YEAR(NOW()) AND YEAR(b.tgl_transaksi) = YEAR(NOW()) AND d.sumber_dana LIKE '%Zakat%'
			GROUP BY a.id_muzakki");

			
	echo '<div class="modal-dialog modal-lg">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title">Berdonasi Zakat</h4>
		      </div>
		      <div class="modal-body">
			      <table id="provinsi" class="table table-bordered" cellspacing="0" width="100%"><thead>
			            <tr>
			                <th width="10%">ID Muzakki</th>
			                <th width="15%">Muzakki</th>
			                <th width="15%">Telpon</th>
			            </tr>
			        </thead>
			        <tbody>';
    
					    while($dt = mysql_fetch_array($qr)){
							echo "<tr>
									<td><input type='hidden' value='$dt[id_muzakki]' name='id_muzakki'>".$dt['id_muzakki']."</td>
									<td>".$dt['muzakki']."</td>
									<td><a href='tel:$dt[telpon]'>".$dt['telpon']."</a></td>
								  </tr>";
						}

	echo '			</tbody>
		    	</table>
	    	  </div>
		    </div>
		  </div>';

	    echo '<script src="datatables/jquery.dataTables.js"></script>
    <script src="datatables/dataTables.bootstrap.js"></script>
    
	<script type="text/javascript">
        $(function() {
            $("#provinsi").dataTable();
        });
    </script>';

}

?>