<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Profil Donatur</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/profilDonat.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.css"/>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<div class="title-donatur">
					<h1>PROFIL DONATUR</h1>
				</div>
			</div>

			<div class="col-md-6">
				<div class="title2">
					LAMA BERGABUNG
				</div>
				<ul class="list-gabung">
					<li class="c1">&lt; 1 Thn</li>
					<li class="c2">1 - 3 Thn</li>
					<li class="c3">3 - 5 Thn</li>
					<li class="c4">&gt; 5 Thn</li>
				</ul>

				<ul class="list-value">
				<?php
				include "config.php";

				require_once "ObjectClass.php";
				require_once "funel.php";

				$funel = new Funel();

				// Query Lama Gabung
					$q3 = mysql_query("SELECT
						     COUNT(IF(lama_gb < 1,1,NULL)) AS '<1',
						     COUNT(IF(lama_gb BETWEEN 1 AND 3,1,NULL)) AS '1-3',
						     COUNT(IF(lama_gb BETWEEN 3 AND 5,1,NULL)) AS '3-5',
						     COUNT(IF(lama_gb > 5,1,NULL)) AS '>5',
						     COUNT(*) AS jmlh_data
							FROM (SELECT tgl_reg, TIMESTAMPDIFF(YEAR, tgl_reg, CURDATE()) AS lama_gb FROM `corez_muzakki`) AS dummy_table
							");

					$d = mysql_fetch_row($q3);

					$gb1 = round($d[0]/$d[4]*100, 2);
					$gb2 = round($d[1]/$d[4]*100, 2);
					$gb3 = round($d[2]/$d[4]*100, 2);
					$gb4 = round($d[3]/$d[4]*100, 2);
					
					echo "
						<li>$gb1%</li>
						<li>$gb2%</li>
						<li>$gb3%</li>
						<li style=margin-left:-10px;>$gb4%</li>
						";
				// END Gabung
				?>
					
				</ul>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div style="padding:60px;">
					<img src="img/bgProfDonat.png" alt="">
					<ul class="jk-persen">
					<?php
						// Persentasi Lak-laki/Perempuan
						$query = mysql_query("SELECT COUNT(jk) AS jumlah_data,
											(SELECT COUNT(p.jk) FROM corez_muzakki p WHERE p.jk='L') AS jmlhL, 
											(SELECT COUNT(p.jk) FROM corez_muzakki p WHERE p.jk='P') AS jmlhP, 
											(SELECT COUNT(p.jk) FROM corez_muzakki p WHERE p.jk='') AS jmlhT 
											FROM corez_muzakki");

						$row = mysql_fetch_array($query);
						
						$jumlahL = round($row['jmlhL']/$row['jumlah_data']*100, 2);
						$jumlahP = round($row['jmlhP']/$row['jumlah_data']*100, 2);
						$jumlahT = round($row['jmlhT']/$row['jumlah_data']*100, 2);

						echo "<li>".$jumlahL."%</li>";
						echo "<li>".$jumlahP."%</li>";					
						echo "<li style=margin-left:20px;>".$jumlahT."%</li>";					
					?>
					</ul><br><br>
					<span class="title2">Jenis Kelamin</span>
					<br><br>
				</div>
			</div>

			<div class="col-md-6">
				<div id="usia"></div>
			</div>
		</div>
	
		<div class="row">
			<div class="col-md-6">
				<div id="tunel"></div>
			</div>

			<div class="col-md-6 box-pen">
				<div id="pendidikan"></div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12 box-pendapatan">
				<div id="pendapatan"></div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="myModal">
	</div>

	<script type="text/javascript" src="js/jquery.min_.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script src="http://code.highcharts.com/highcharts.js"></script>
	<script src="http://code.highcharts.com/modules/funnel.js"></script>
	<script src="http://code.highcharts.com/modules/exporting.js"></script>
	<script src="js/app.js"></script>

	<?php

		//Query UMUR
			$q1 = mysql_query("SELECT
				     COUNT(IF(umur > 45,1,NULL)) AS '>45',
				     COUNT(IF(umur BETWEEN 35 AND 45,1,NULL)) AS '35-45',
				     COUNT(IF(umur BETWEEN 25 AND 35,1,NULL)) AS '25-35',
				     COUNT(IF(umur BETWEEN 17 AND 25,1,NULL)) AS '17-25',
				     COUNT(IF(umur < 17,1,NULL)) AS '<17',
				     COUNT(*) AS jmlh_data
				FROM (SELECT tgl_lahir, TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE()) AS umur FROM `corez_muzakki`) AS dummy_table
				");

			$data = mysql_fetch_row($q1);
			$arr = array();
			for ($i=0; $i <= 4; $i++) { 
				${'g'.$i} = round($data[$i]/$data[5]*100,2);
				array_push($arr, ${'g'.$i});
			}
			$umur = array(
						array(
							"name" => "Umur",
							"data" => $arr
							)
					);

			$umur = json_encode($umur);

			echo "<script>
				var gft = ['> 45 tahun', '35 - 45 tahun', '25 - 35 tahun','17 - 25 tahun','< 17 tahun'];
				var data_pendidikan = $umur;
				gf('usia','Usia', 'Tahun','bar', gft , data_pendidikan);
				</script>";
		// END UMUR 

		// Query Pendidikan
			 $q2 = mysql_query("SELECT
				     COUNT(IF(pendidikan = 1,1,NULL)) AS 'SD',
				     COUNT(IF(pendidikan = 2,1,NULL)) AS 'SMP',
				     COUNT(IF(pendidikan = 3,1,NULL)) AS 'SMA',
				     COUNT(IF(pendidikan = 4,1,NULL)) AS 'D1',
				     COUNT(IF(pendidikan = 5,1,NULL)) AS 'D2',
				     COUNT(IF(pendidikan = 6,1,NULL)) AS 'D3',
				     COUNT(IF(pendidikan = 7,1,NULL)) AS 'D4',
				     COUNT(IF(pendidikan = 8,1,NULL)) AS 'S1',
				     COUNT(IF(pendidikan = 9,1,NULL)) AS 'S2',
				     COUNT(IF(pendidikan = 10,1,NULL)) AS 'S3'
					FROM (SELECT id_pendidikan AS pendidikan FROM `corez_muzakki`) AS dummy_table
					");

			 $r = mysql_fetch_row($q2);

			 $o_CObject = new CObject();
			 $o_CObject->i = 'id_pendidikan'; 
			 $ar_id_pendidikan = $o_CObject->Object_Array();

			 $x = array();		 	
			 	$i=0;
				 foreach ($ar_id_pendidikan as $value) {
				 	$x[] = array(
				 			'name' => $value,
				 			'y' => (int)$r[$i]
				 		);
				 	$i++;
				 }
			 $pendidikan = json_encode($x);
		// End Pendidikan 
			
		// Query Pendapatan
			$q4 = mysql_query("SELECT
								COUNT(IF(penghasilan = 1,1,NULL)) AS '<1jt',
								COUNT(IF(penghasilan = 2,1,NULL)) AS '1-3jt',
								COUNT(IF(penghasilan = 3,1,NULL)) AS '3-5jt',
								COUNT(IF(penghasilan = 4,1,NULL)) AS '5-10jt',
								COUNT(IF(penghasilan = 5,1,NULL)) AS '>10jt',
								COUNT(*) AS jmlh_data
							FROM (SELECT id_penghasilan AS penghasilan FROM `corez_muzakki`) AS dummy_table
						");

			$r2 = mysql_fetch_row($q4);
			$arrp = array();
			for ($i=0; $i < 4; $i++) { 
				${'p'.$i} = round($r2[$i]/$r2[5]*100,2);
				array_push($arrp, ${'p'.$i});
			}

			$pendapatan = array(
						array(
							"name" => "Pendapatan",
							"data" => $arrp
							)
					);

			$pendapatan = json_encode($pendapatan);

			echo "<script>
				var gft = ['< 1jt', '1-3jt', '3-5jt','5-10jt','>10jt'];
				var data_pendapatan = $pendapatan;
				gf('pendapatan','Pendapatan', '%','', gft, data_pendapatan);
				</script>";
		// End Pendapatan

	?>
	<script>
		$(function () {

		    $('#tunel').highcharts({
		        chart: {
		            type: 'funnel',
		            marginRight: 100
		        },
		        title: {
		            text: 'Zakat funnel',
		            x: -50
		        },
		        plotOptions: {
		            series: {
		                dataLabels: {
		                    enabled: true,
		                    format: '<b>{point.name}</b> ({point.y:,.0f})',
		                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
		                    softConnector: true
		                },
		                neckWidth: '30%',
		                neckHeight: '25%',
		                point: {
		                    events: {
		                        click: function () {
		                            if(this.x == 2){
		                            	$.ajax({
								            type: 'POST',
								            url: 'loadData.php?aksi=pencarian',
								            success: function(response) {
								                $('#myModal').html(response);
												$('#myModal').modal('show');
								            }
								        });
		                            }
		                        }
		                    }
		                }

		                //-- Other available options
		                // height: pixels or percent
		                // width: pixels or percent
		            }
		        },
		        legend: {
		            enabled: false
		        },
		        series: [
			        	<?php 
							$funel->ZakatFunnel_Data();
			         	?>
		         		]
		    });


			$('#pendidikan').highcharts({
		        chart: {
		            plotBackgroundColor: null,
		            plotBorderWidth: null,
		            plotShadow: false,
		            type: 'pie'
		        },
		        title: {
		            text: 'Pendidikan'
		        },
		        tooltip: {
		            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		        },
		        plotOptions: {
		            pie: {
		                allowPointSelect: true,
		                cursor: 'pointer',
		                dataLabels: {
		                    enabled: true,
		                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
		                    style: {
		                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
		                    }
		                }
		            }
		        },
		        series: [{
		            name: "Persentasi",
		            colorByPoint: true,
		            data: <?php  echo $pendidikan; ?>,
				        events: {
				            click: function (event){
				                console.log(event);
				            }
				        }
		        }]
		    });

		});

	</script>
</body>
</html>