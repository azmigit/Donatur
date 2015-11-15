<?php

/**
* 
*/
class Funel
{
	
	private $host = "localhost";
	private $db_name = "corez_aq3";
	private $username = "root";
	private $password = "";
	public $conn;
	private $tahun;

	function __construct()
	{
		$this->conn = null;

		try {
			$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
		} catch (PDOException $exception) {
			echo "Connection error: " . $exception->getMessage();
		}

	}

	function ZakatFunnel_Data(){
		$this->tahun = ($this->tahun) ? $this->tahun : date("Y");
		$a['name'] = 'Zakat Funel';
		$a['data'] = array(
						array('Donatur',(int)$this->JumlahDonatur()),
						array('Berdonasi',(int)$this->JumlahTransaksi()),
						array('Berdonasi Zakat',(int)$this->JumlahTransaksiZakat()),
						array('Zakat Rutin Bulanan',(int)$this->JumlahTransaksiZakatRutin())
					);
		echo json_encode($a);
	}

	function JumlahDonatur(){
		#echo
        $sqlc="select 
						count(1) total 
					from 
						corez_muzakki a		
					where YEAR(a.tgl_reg) <= '".$this->tahun."'";
        $rc_sqlc = $this->conn->prepare($sqlc);
        $rc_sqlc->execute();

        $row = $rc_sqlc->fetch(PDO::FETCH_ASSOC);

		return $row['total'];
	}

	function JumlahTransaksi(){
        $sqlc="select 
						count(1) total 
					from 
						corez_muzakki a				
						inner join corez_transaksi b on b.id_muzakki = a.id_muzakki		
					where YEAR(a.tgl_reg) <= '".$this->tahun."' and YEAR(b.tgl_transaksi) = '".$this->tahun."'
					group by a.id_muzakki";
        $rc_sqlc = $this->conn->prepare($sqlc);
        $rc_sqlc->execute();

        $recordCount = 0;
        while($row = $rc_sqlc->fetch(PDO::FETCH_ASSOC)){
        	$recordCount = $recordCount + $row['total'];
        }

		return $recordCount;
	}

	function JumlahTransaksiZakat(){
        $sqlc="select 
						count(1) total
					from 
						corez_muzakki a
						inner join corez_transaksi b on b.id_muzakki = a.id_muzakki		
						inner join setting_program c on c.id_program = b.id_program		
						inner join setting_sumber_dana d on d.id_sumber_dana = c.id_sumber_dana		
					where YEAR(a.tgl_reg) <= '".$this->tahun."' and YEAR(b.tgl_transaksi) = '".$this->tahun."' and d.sumber_dana like '%Zakat%'
					group by a.id_muzakki";
        $rc_sqlc = $this->conn->prepare($sqlc);
        $rc_sqlc->execute();

		$recordCount = 0;
        while($row = $rc_sqlc->fetch(PDO::FETCH_ASSOC)){
        	$recordCount = $recordCount + $row['total'];
        }

		return $recordCount;
	}

	function JumlahTransaksiZakatRutin(){
		$bulan = date("m");
		#echo
        $sqlc="select 
						count(1) total
					from 
						corez_muzakki a
						inner join corez_transaksi b on b.id_muzakki = a.id_muzakki		
						inner join setting_program c on c.id_program = b.id_program		
						inner join setting_sumber_dana d on d.id_sumber_dana = c.id_sumber_dana		
					where YEAR(a.tgl_reg) <= '".$this->tahun."' and YEAR(b.tgl_transaksi) = '".$this->tahun."' and d.sumber_dana like '%Zakat%' 
					group by a.id_muzakki";
        $rc_sqlc = $this->conn->prepare($sqlc);
        $rc_sqlc->execute();
		$i=0;
		foreach($rc_sqlc as $fields){
			if ($fields['total']=='2') $i++;
		}
		return $i;
	}
}

?>