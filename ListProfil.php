<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Profil Donatur</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/listProfil.css">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
				  <div class="panel-heading">PROFIL DONATUR</div>
				  <div class="panel-body table-responsive">
				  <div class="row">
				  	<div class="col-md-8">
				  		<div class="col-sm-6">
				  			<table class="data-table">
							  <?php
							  	include "config.php";

							  	$sql = mysql_query("SELECT b.id_muzakki, b.muzakki, TIMESTAMPDIFF(YEAR, b.tgl_lahir, CURDATE()) AS umur,
													b.alamat, YEAR(NOW()) - TIMESTAMPDIFF(YEAR, b.tgl_reg, CURDATE()) AS lma_gb, c.karyawan,
													b.foto, b.tgl_lahir, b.telpon
			  										FROM 
			  											corez_muzakki b
													LEFT JOIN hcm_karyawan c ON c.id_karyawan = b.id_crm
													WHERE b.id_muzakki='10111-150619027'
												  ");
							  	$r = mysql_fetch_array($sql);
							  
							  	echo "
							    	<tr>
							    		<td>ID Donatur</td>
							    		<td>:</td>
							    		<td>".$r['id_muzakki']."</td>
							    	</tr>
							    	<tr>
							    		<td>Nama</td>
							    		<td>:</td>
							    		<td>".$r['muzakki']."</td>
							    	</tr>
							    	<tr>
							    		<td>Tanggal Lahir</td>
							    		<td>:</td>
							    		<td>".$r['tgl_lahir']."</td>
							    	</tr>
							    	<tr>
							    		<td>Usia</td>
							    		<td>:</td>
							    		<td>".$r['umur']."</td>
							    	</tr>
							    	<tr>
							    		<td>Alamat</td>
							    		<td>:</td>
							    		<td>".$r['alamat']."</td>
							    	</tr>
							    	<tr>
							    		<td>Alamat</td>
							    		<td>:</td>
							    		<td><a href=tel:$r[telpon]>".$r['telpon']."</a></td>
							    	</tr>
							    	<tr>
							    		<td>Mulai Bergabung</td>
							    		<td>:</td>
							    		<td>".$r['lma_gb']."</td>
							    	</tr>
							    	<tr>
							    		<td>Nama CRM</td>
							    		<td>:</td>
							    		<td>".$r['karyawan']."</td>
							    	</tr>
							    	";
							    ?>
						    </table>
				  		</div>
				  	</div>
				  	<div class="col-md-4">
					    <a href="#" class="thumbnail img">
					      <img src="img/<?php echo $r['foto']; ?>" alt="">
					    </a>
				  	</div>
				  </div>
				  </div>
				</div>
			</div>
		</div>

		<div class="row">
		  <div class="col-md-8">
			  	<div class="panel panel-default">
				  <div class="panel-heading">
				  	DATA TRANSAKSI PROGRAM PER BULAN
				  	<div class="pull-right">
						<select name="filter1" class="filter1">
							<option value="2015">2015</option>
							<option value="2014">2014</option>
						</select>
					</div>
				  </div>
				  <div class="panel-body table-responsive" id="dtProg">
				    <table class="table table-bordered">
				    	<tr style='background:#99ccff'>
				    		<th rowspan="2">Jenis Program</th>
				    		<th colspan="12">Bulan</th>
				    		<th rowspan="2">Grand Total</th>
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
				    	</tr>
				    	<?php

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
									WHERE id_muzakki='10111-1506240001' AND YEAR(tgl_transaksi) = '2015'
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
				    	?>
				  </div>
				</div>
		  </div>
		  <div class="col-md-4">
			  	<div class="panel panel-default">
				  <div class="panel-body table-responsive">
					<div id="grafik_transaksi"></div>
				  </div>
				</div>
			  </div>
		</div>

		<div class="row">
		  <div class="col-xs-12 col-sm-6 col-md-8">
			  	<div class="panel panel-default">
				  <div class="panel-heading">
				  	DATA PENGUNAAN METODE TRANSAKSI PER BULAN
				  	<div class="pull-right">
						<select name="filter2" class="filter2">
							<option value="2015">2015</option>
							<option value="2014">2014</option>
						</select>
					</div>
				  </div>
				  <div class="panel-body table-responsive" id="dtMetode">
				    <table class="table table-bordered">
				    	<tr style='background:#99ccff'>
				    		<th rowspan="2">Jenis Program</th>
				    		<th colspan="12">Bulan</th>
				    		<th rowspan="2">Grand Total</th>
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
				    	</tr>
				    	<?php

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
													WHERE id_muzakki='10111-1506240001' AND YEAR(tgl_transaksi) = '2015'
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
				    	?>
				  </div>
				</div>
		  </div>
		  <div class="col-xs-6 col-md-4">
		  	<div class="panel panel-default">
				  <div class="panel-body">
				    <div id="grafik_donasi"></div>
				  </div>
				</div>
		  </div>
		</div>

		<div class="row">
		  <div class="col-xs-12 col-sm-6 col-md-8">
			  	<div class="panel panel-default">
				  <div class="panel-body">
				    <p>
				    	Selama rentang waktu 2010-2014 donatur ini telah:
				    </p>
				    -Berdonasi sebesar .. <br>
				    -Metode donasi .. <br>
					-Donasi Paling Banyak
				  </div>
				</div>
		  </div>
		  <div class="col-xs-6 col-md-4">
		  	<div class="panel panel-default">
				<div class="panel-heading">Catatan</div>
				<div class="panel-body">
					<textarea name="" id="ctt" cols="30" rows="10" class="ctt">
						
					</textarea>
				</div>
				<div class="panel-footer">
					<input class="btn btn-primary" type="button" value="Simpan">
				</div>
			</div>
		  </div>
		</div>
	</div>


	<script type="text/javascript" src="js/jquery.min_.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/highcharts.js"></script>
	<script src="js/app.js"></script>
	<?php
	//transaksi grafik
		function gftransaksi() {
			$q2 = mysql_query("SELECT YEAR(tgl_transaksi) AS tahun, SUM(transaksi) AS transaksi
							FROM corez_transaksi 
							WHERE
							id_muzakki='10111-1506240001'
							GROUP BY tahun
							ORDER BY tahun
 						");
			while($d = mysql_fetch_array($q2)){
				$tahun[] = $d['tahun'];
				$transaksi[] = (float)$d['transaksi'];
			}

			$array = array(
					array(
							"name" => "Transaksi",
							"data" => $transaksi
						)
				);

			return array("tahun"=>$tahun, "transaksi"=>$array);
		}

		$gftransaksi = gftransaksi();

		function gfdonat(){

			$q4 = mysql_query("SELECT b.program, COUNT(b.program) AS jmlh
								FROM corez_transaksi a
								INNER JOIN setting_program b ON b.id_program = a.id_program
								WHERE
								id_muzakki='10111-1506240001'
								GROUP BY b.program
							");

			$qjmlh = mysql_query("SELECT COUNT(b.program) AS jmlh
								FROM corez_transaksi a
								INNER JOIN setting_program b ON b.id_program = a.id_program
								WHERE
								id_muzakki='10111-1506240001'");
			$j=0;
			$jum=0;
			while ($dataj = mysql_fetch_array($qjmlh)) {
				$jum = $jum + $dataj['jmlh'];
			}

			while ($dataP = mysql_fetch_array($q4)) {
				$program[] = $dataP['program'];
				$jmlhD[] = round((int)$dataP['jmlh']/$jum*100, 2);
			}

			$array = array(
					array(
							"name" => "Donasi",
							"data" => $jmlhD
						)
				);

			return array("program"=>$program, "jmlhDonat"=>$array);
		}

		$gfdonat = gfdonat();

	?>
	<script>
	var gft = <?php echo json_encode($gftransaksi['tahun']); ?>;
	var data_transaksi = <?php echo json_encode($gftransaksi['transaksi']); ?>;
	gf('grafik_transaksi','Grafik Transaksi', '(Rp)','line', gft, data_transaksi);
	
	var gfd = <?php echo json_encode($gfdonat['program']); ?>;
	var data_donasi = <?php echo json_encode($gfdonat['jmlhDonat']); ?>;
	gf('grafik_donasi','Grafik Donasi Program', '%','column', gfd, data_donasi);
	document.getElementById('ctt').value = '';

	$(document).on('change', '.filter1', function(){
		var thn = $(this).val();
		$.ajax({
            type: 'POST',
            url: 'loadData.php?aksi=filter1',
            data: 'tahun=' + thn,
            success: function(response) {
                $('#dtProg').html(response); 
            }
        });
	});

	$(document).on('change', '.filter2', function(){
		var thn = $(this).val();
		$.ajax({
            type: 'POST',
            url: 'loadData.php?aksi=filter2',
            data: 'tahun=' + thn,
            success: function(response) {
                $('#dtMetode').html(response); 
            }
        });
	});

	</script>
</body>
</html>