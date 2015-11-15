<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Grafik</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/pencapaian.css">
	<style>
		#legend {
		    background: white;
		    padding: 10px;
		    border-radius: 2px
		  }
	</style>
</head>
<body onload="load()">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="nav">
					<div class="panel panel-default">
					  <div class="panel-heading">Excecutive Summary</div>
					  <div class="panel-body">
					    <ul class="list-group">
					    	<li><a href="ProfilDonatur.php" class="list-group-item">Profil Donatur</a></li>
					    	<li><a href="" class="list-group-item">Data Transaksi Donatur</a></li>
					    	<li><a href="" class="list-group-item">Metode Donasi</a></li>
					    	<li><a href="" class="list-group-item">Top 10 Program</a></li>
					    </ul>
					  </div>
					</div>
				</div>

				<div class="section">
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-default">
							  <div class="panel-heading">Perbandingan Pencapaian Terhadap Target</div>
							  <div class="panel-body table-responsive">
							    <div id="map" style="width: 100%; height: 450px"></div>
							  </div>
							</div>
							<div id="loaddata">
								
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-12">
				<div class="section">
					<div class="row">
						<div class="col-md-12">
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="legend">
	  Status
	</div>

	<script type="text/javascript" src="js/jquery.min_.js"></script>
	<script src="js/highcharts.js"></script>
	<script src="http://maps.google.com/maps/api/js?sensor=true"type="text/javascript"></script>
	<script src="http://code.highcharts.com/modules/exporting.js"></script>
	<script src="js/app.js"></script>
</body>
</html>