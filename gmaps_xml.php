<?php
require("config.php");

function parseToXML($htmlStr)
{
$xmlStr=str_replace('<','&lt;',$htmlStr);
$xmlStr=str_replace('>','&gt;',$xmlStr);
$xmlStr=str_replace('"','&quot;',$xmlStr);
$xmlStr=str_replace("'",'&#39;',$xmlStr);
$xmlStr=str_replace("&",'&amp;',$xmlStr);
return $xmlStr;
}

// Opens a connection to a MySQL server
$connection=mysql_connect ('localhost', $username, $password);
if (!$connection) {
  die('Not connected : ' . mysql_error());
}

// Set the active MySQL database
$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
  die ('Can\'t use db : ' . mysql_error());
}

// Select all the rows in the markers table
$query = "SELECT (SUM(c.transaksi)) AS pencapaian, b.lat, b.lng, b.kantor, a.id_jenis AS id_kantor, target
          FROM 
            setting_target a
          INNER JOIN hcm_kantor b ON b.id_kantor = a.id_jenis
          LEFT JOIN corez_transaksi c ON c.id_kantor_transaksi = a.id_jenis
          WHERE a.jenis='id_kantor' AND a.bulan='0' AND a.tanggal ='0' AND a.tahun='2015' AND YEAR(c.tgl_transaksi)=2015 GROUP BY c.id_kantor_transaksi";
  $result = mysql_query($query);
if (!$result) {
  die('Invalid query: ' . mysql_error());
}

header("Content-type: text/xml");

// Start XML file, echo parent node
echo '<markers>';

// Iterate through the rows, printing XML nodes for each
while ($row = @mysql_fetch_assoc($result)){
  // ADD TO XML DOCUMENT NODE
  $persentase = round($row['pencapaian']/$row['target']*100, 2);
  echo '<marker ';
  echo 'name="' . parseToXML($row['kantor']) . '" ';
  echo 'lat="' . $row['lat'] .'" ';
  echo 'lng="' . $row['lng'] . '" ';
  echo 'persentase="' . $persentase . '" ';
  echo 'target="' . number_format($row['target']) . '" ';
  echo 'pencapaian="' . number_format($row['pencapaian']) . '" ';
  echo '/>';
}

// End XML file
echo '</markers>';

?>
