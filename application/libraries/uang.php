<?php

/** uang.php
 * @author soniibrol 
 * @copyright 2012
 */

class Uang{
	function rupiah($nominal){
		$rupiah =  number_format($nominal,0, ",",".");
 		$rupiah = "Rp "  . $rupiah . ",-";
		return $rupiah;
	}
	
	function format_uang($nominal){
		$rupiah =  number_format($nominal,0, ",",".");
 		$rupiah = $rupiah . ",-";
		return $rupiah;
	}
	
	function tanggal_indo($tanggal){
		$hari = substr($tanggal,8,2);
		$bulan = substr($tanggal,5,2);
		$tahun = substr($tanggal,0,4);
		$tgl = $hari."/".$bulan."/".$tahun;
		return $tgl;
	}
	
	function hari_indo($hari_bule){
		switch($hari_bule){
			case "Mon" : return "Senin"; break;
			case "Tue" : return "Selasa"; break;
			case "Wed" : return "Rabu"; break;
			case "Thu" : return "Kamis"; break;
			case "Fri" : return "Jum'at"; break;
			case "Sat" : return "Sabtu"; break;
			case "Sun" : return "Minggu"; break;
		}
	}
	
	function tanggal_indo_huruf($tanggal){
		$hari = substr($tanggal,8,2);
		$bulan = substr($tanggal,5,2);
		$tahun = substr($tanggal,0,4);
		if($bulan=="01"){
			$b = "Januari";
		}elseif($bulan=="02"){
			$b = "Februari";
		}elseif($bulan=="03"){
			$b = "Maret";
		}elseif($bulan=="04"){
			$b = "April";
		}elseif($bulan=="05"){
			$b = "Mei";
		}elseif($bulan=="06"){
			$b = "Juni";
		}elseif($bulan=="07"){
			$b = "Juli";
		}elseif($bulan=="08"){
			$b = "Agustus";
		}elseif($bulan=="09"){
			$b = "September";
		}elseif($bulan=="10"){
			$b = "Oktober";
		}elseif($bulan=="11"){
			$b = "November";
		}elseif($bulan=="12"){
			$b = "Desember";
		}
		
		$tgl = $hari." ".$b." ".$tahun;
		return $tgl;
	}
	
	function terbilang($angka){
	    // pastikan kita hanya berususan dengan tipe data numeric
	    //$angka = (float)$x;
	     
	    // array bilangan
	    // sepuluh dan sebelas merupakan special karena awalan 'se'
	    $bilangan = array(
	            '',
	            'SATU',
	            'DUA',
	            'TIGA',
	            'EMPAT',
	            'LIMA',
	            'ENAM',
	            'TUJUH',
	            'DELAPAN',
	            'SEMBILAN',
	            'SEPULUH',
	            'SEBELAS'
	    );
	     
	    // pencocokan dimulai dari satuan angka terkecil
	    if ($angka < 12){
	        // mapping angka ke index array $bilangan
	        $huruf = $bilangan[$angka];
	    }elseif($angka < 20){
	        // bilangan 'belasan'
	        // misal 18 maka 18 - 10 = 8
	        $huruf = $bilangan[$angka - 10] . ' BELAS';
	    }elseif($angka < 100){
	        // bilangan 'puluhan'
	        // misal 27 maka 27 / 10 = 2.7 (integer => 2) 'dua'
	        // untuk mendapatkan sisa bagi gunakan modulus
	        // 27 mod 10 = 7 'tujuh'
	        $hasil_bagi = (int)($angka / 10);
	        $hasil_mod = $angka % 10;
	        $huruf = trim(sprintf('%s PULUH %s', $bilangan[$hasil_bagi], $bilangan[$hasil_mod]));
	    }elseif($angka < 200){
	        // bilangan 'seratusan' (itulah indonesia knp tidak satu ratus saja? :))
	        // misal 151 maka 151 = 100 = 51 (hasil berupa 'puluhan')
	        // daripada menulis ulang rutin kode puluhan maka gunakan
	        // saja fungsi rekursif dengan memanggil fungsi terbilang(51)
	        $huruf = sprintf('SERATUS %s', $this->terbilang($angka - 100));
	    }elseif($angka < 1000){ 
	        // bilangan 'ratusan'
	        // misal 467 maka 467 / 100 = 4,67 (integer => 4) 'empat'
	        // sisanya 467 mod 100 = 67 (berupa puluhan jadi gunakan rekursif terbilang(67))
	        $hasil_bagi = (int)($angka / 100);
	        $hasil_mod = $angka % 100;
	        $huruf = trim(sprintf('%s RATUS %s', $bilangan[$hasil_bagi], $this->terbilang($hasil_mod)));
	    }elseif($angka < 2000){
	        // bilangan 'seribuan'
	        // misal 1250 maka 1250 - 1000 = 250 (ratusan)
	        // gunakan rekursif terbilang(250)
	        $huruf = trim(sprintf('SERIBU %s', $this->terbilang($angka - 1000)));
	    }elseif($angka < 1000000){
	        // bilangan 'ribuan' (sampai ratusan ribu
	        $hasil_bagi = (int)($angka / 1000); // karena hasilnya bisa ratusan jadi langsung digunakan rekursif
	        $hasil_mod = $angka % 1000;
	        $huruf = sprintf('%s RIBU %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod));
	    }elseif($angka < 1000000000){
	        // bilangan 'jutaan' (sampai ratusan juta)
	        // 'satu puluh' => SALAH
	        // 'satu ratus' => SALAH
	        // 'satu juta' => BENAR
	        // @#$%^ WT*
	         
	        // hasil bagi bisa satuan, belasan, ratusan jadi langsung kita gunakan rekursif
	        $hasil_bagi = (int)($angka / 1000000);
	        $hasil_mod = $angka % 1000000;
	        $huruf = trim(sprintf('%s JUTA %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
	    }elseif($angka < 1000000000000){
	        // bilangan 'milyaran'
	        $hasil_bagi = (int)($angka / 1000000000);
	        // karena batas maksimum integer untuk 32bit sistem adalah 2147483647
	        // maka kita gunakan fmod agar dapat menghandle angka yang lebih besar
	        $hasil_mod = fmod($angka, 1000000000);
	        $huruf = trim(sprintf('%s MILYAR %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
	    }elseif($angka < 1000000000000000){
	        // bilangan 'triliun'
	        $hasil_bagi = $angka / 1000000000000;
	        $hasil_mod = fmod($angka, 1000000000000);
	        $huruf = trim(sprintf('%s TRILYUN %s', $this->terbilang($hasil_bagi), $this->terbilang($hasil_mod)));
	    }else{
	        $huruf = 'Wow...';
	    }
	    return $huruf;
	}
	
	function tanggal_pesanan($tanggal){
		//mengubah format tgl indonesia menjadi tgl Y-m-d
		$tgl = substr($tanggal,0,2);
		$bln = substr($tanggal,3,2);
		$thn = substr($tanggal,6,4);
		$x = $thn."-".$bln."-".$tgl;
		return $x;
	}
	
	function format_npwpd($npwpd){
		$sub1 = substr($npwpd,0,1);
		$sub2 = substr($npwpd,1,1);
		$sub3 = substr($npwpd,2,7);
		$sub4 = substr($npwpd,9,2);
		$sub5 = substr($npwpd,11,2);
		
		$new_npwpd = $sub1.".".$sub2.".".$sub3.".".$sub4.".".$sub5;
		
		return $new_npwpd;
 	}

}

?>