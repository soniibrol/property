<?php

class Report extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->loginstatus->check_login();
		if($this->loginstatus->get_role()=='operator' || $this->loginstatus->get_role()=='member'){
			redirect('dashboard');
		}
		$this->load->library('tanggal');
		$this->load->library('uang');
		$this->load->library('fpdf');
		$this->load->model('property_model');
		$this->load->model('user_model');
		$this->load->model('rent_model');
		$this->load->model('report_model');
	}

	public function index(){
		redirect('report/rent_all');
	}

	public function rent_all(){
		$this->template->display('report/rent_all_view');
	}

	public function rent_by_user(){
		$tmp = $this->user_model->get_by_type('4')->result_array();
		$data['user'] = '<select name="user_id" id="user_id" class="form-control">';
		foreach($tmp as $row){
			$data['user'] .= '<option value="'.$row['user_id'].'">'.$row['user_fullname'].'</option>';
		}
		$data['user'] .= '</select>';
		$this->template->display('report/rent_by_user_view',$data);
	}

	public function rent_type(){
		$this->template->display('report/rent_type_view');
	}

	public function rent_status(){
		$this->template->display('report/rent_status_view');
	}

	public function rent_income(){
		$this->template->display('report/rent_income_view');	
	}

	public function generate_all($start_date,$end_date){
		$laporan = $this->report_model->get_report_rent($start_date,$end_date)->result_array();

		//inisialisasi laporan
		$this->fpdf->FPDF('L','cm','A4');
		$this->fpdf->AddPage();
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','B',9);
		$this->fpdf->SetTitle("Laporan Peminjaman Property Periode ".$this->tanggal->tanggal_indo($start_date)." - ".$this->tanggal->tanggal_indo($end_date)."");
		
		//JUDUL LAPORAN//
		$this->fpdf->setFont('Arial','B',12);
		$this->fpdf->Cell(27.5,0.5,"Laporan Semua Peminjaman",0,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','',12);
		$this->fpdf->Cell(27.5,0.5,"Periode ".$this->tanggal->tanggal_indo($start_date)." - ".$this->tanggal->tanggal_indo($end_date)."",0,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		
		//TABEL
		$this->fpdf->setFont('Arial','B',12);
		$this->fpdf->SetLineWidth(0.01);
		$this->fpdf->Cell(1,1,'No',1,0,'C');
		$this->fpdf->Cell(3.5,1,'No Peminjaman',1,0,'C');
		$this->fpdf->Cell(5,1,'Nama Peminjam',1,0,'C');
		$this->fpdf->Cell(5,1,'Nama Properti',1,0,'C');
		$this->fpdf->Cell(4,1,'Tanggal Pinjam',1,0,'C');
		$this->fpdf->Cell(3,1,'Tipe',1,0,'C');
		$this->fpdf->Cell(6,1,'Status',1,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','',12);
		$no = 1;
		foreach($laporan as $l){
			$this->fpdf->Cell(1,0.6,$no,1,0,'C');
			$this->fpdf->Cell(3.5,0.6,$l['rent_no'],1,0,'C');
			$this->fpdf->Cell(5,0.6,$l['rent_fullname'],1,0,'L');
			$this->fpdf->Cell(5,0.6,$l['property_name'],1,0,'C');
			$this->fpdf->Cell(4,0.6,$this->tanggal->tanggal_indo($l['rent_date']).' ('.$l['rent_days'].' hari)',1,0,'C');
			$this->fpdf->Cell(3,0.6,($l['rent_type']=='1') ? 'INTERNAL' : 'EKSTERNAL',1,0,'C');
			$this->fpdf->Cell(6,0.6,$this->get_status($l['rent_status']),1,0,'C');
			$this->fpdf->Ln();
			$no++;
		}

		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->Cell(27,0.5,'Bandung, '.$this->tanggal->tanggal_indo(date("Y-m-d")),0,0,'R');
		$this->fpdf->Ln();
		$this->fpdf->Cell(27,0.6,'Manager,',0,0,'R');
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','B',12);
		//$this->fpdf->Cell(27,0.6,$this->session->userdata('user_fullname'),0,0,'R');
		$this->fpdf->Cell(27,0.6,'Rully Hafna Ayudia',0,0,'R');
		$this->fpdf->Ln();

		//generate PDF
		$this->fpdf->Output();
	}

	public function generate_by_user($start_date,$end_date,$user_id){
		$laporan = $this->report_model->get_report_rent($start_date,$end_date,0,4,$user_id)->result_array();
		$tmp_user = $this->user_model->get_by_id($user_id)->row_array();

		//inisialisasi laporan
		$this->fpdf->FPDF('L','cm','A4');
		$this->fpdf->AddPage();
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','B',9);
		$this->fpdf->SetTitle("Laporan Peminjaman Property Periode ".$this->tanggal->tanggal_indo($start_date)." - ".$this->tanggal->tanggal_indo($end_date)."");
		
		//JUDUL LAPORAN//
		$this->fpdf->setFont('Arial','B',12);
		$this->fpdf->Cell(27.5,0.5,"Laporan Peminjaman Berdasarkan Peminjam",0,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','',12);
		$this->fpdf->Cell(27.5,0.5,"Peminjam ".$tmp_user['user_fullname']." Periode ".$this->tanggal->tanggal_indo($start_date)." - ".$this->tanggal->tanggal_indo($end_date)."",0,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		
		//TABEL
		$this->fpdf->setFont('Arial','B',12);
		$this->fpdf->SetLineWidth(0.01);
		$this->fpdf->Cell(1,1,'No',1,0,'C');
		$this->fpdf->Cell(3.5,1,'No Peminjaman',1,0,'C');
		$this->fpdf->Cell(5,1,'Nama Peminjam',1,0,'C');
		$this->fpdf->Cell(5,1,'Nama Properti',1,0,'C');
		$this->fpdf->Cell(4,1,'Tanggal Pinjam',1,0,'C');
		$this->fpdf->Cell(3,1,'Tipe',1,0,'C');
		$this->fpdf->Cell(6,1,'Status',1,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','',12);
		$no = 1;
		foreach($laporan as $l){
			$this->fpdf->Cell(1,0.6,$no,1,0,'C');
			$this->fpdf->Cell(3.5,0.6,$l['rent_no'],1,0,'C');
			$this->fpdf->Cell(5,0.6,$l['rent_fullname'],1,0,'L');
			$this->fpdf->Cell(5,0.6,$l['property_name'],1,0,'C');
			$this->fpdf->Cell(4,0.6,$this->tanggal->tanggal_indo($l['rent_date']).' ('.$l['rent_days'].' hari)',1,0,'C');
			$this->fpdf->Cell(3,0.6,($l['rent_type']=='1') ? 'INTERNAL' : 'EKSTERNAL',1,0,'C');
			$this->fpdf->Cell(6,0.6,$this->get_status($l['rent_status']),1,0,'C');
			$this->fpdf->Ln();
			$no++;
		}

		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->Cell(27,0.5,'Bandung, '.$this->tanggal->tanggal_indo(date("Y-m-d")),0,0,'R');
		$this->fpdf->Ln();
		$this->fpdf->Cell(27,0.6,'Manager,',0,0,'R');
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','B',12);
		//$this->fpdf->Cell(27,0.6,$this->session->userdata('user_fullname'),0,0,'R');
		$this->fpdf->Cell(27,0.6,'Rully Hafna Ayudia',0,0,'R');
		$this->fpdf->Ln();

		//generate PDF
		$this->fpdf->Output();
	}

	public function generate_type($start_date,$end_date,$rent_type){
		$laporan = $this->report_model->get_report_rent($start_date,$end_date,$rent_type)->result_array();

		//inisialisasi laporan
		$this->fpdf->FPDF('L','cm','A4');
		$this->fpdf->AddPage();
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','B',9);
		$this->fpdf->SetTitle("Laporan Peminjaman Property Periode ".$this->tanggal->tanggal_indo($start_date)." - ".$this->tanggal->tanggal_indo($end_date)."");
		
		//JUDUL LAPORAN//
		$this->fpdf->setFont('Arial','B',12);
		$this->fpdf->Cell(27.5,0.5,"Laporan Peminjaman Berdasarkan Tipe Peminjaman",0,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','',12);
		$this->fpdf->Cell(27.5,0.5,(($rent_type=='1') ? "INTERNAL" : "EKSTERNAL")." Periode ".$this->tanggal->tanggal_indo($start_date)." - ".$this->tanggal->tanggal_indo($end_date)."",0,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		
		//TABEL
		$this->fpdf->setFont('Arial','B',12);
		$this->fpdf->SetLineWidth(0.01);
		$this->fpdf->Cell(1,1,'No',1,0,'C');
		$this->fpdf->Cell(3.5,1,'No Peminjaman',1,0,'C');
		$this->fpdf->Cell(5,1,'Nama Peminjam',1,0,'C');
		$this->fpdf->Cell(5,1,'Nama Properti',1,0,'C');
		$this->fpdf->Cell(4,1,'Tanggal Pinjam',1,0,'C');
		$this->fpdf->Cell(3,1,'Total',1,0,'C');
		$this->fpdf->Cell(6,1,'Status',1,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','',12);
		$no = 1;
		foreach($laporan as $l){
			$this->fpdf->Cell(1,0.6,$no,1,0,'C');
			$this->fpdf->Cell(3.5,0.6,$l['rent_no'],1,0,'C');
			$this->fpdf->Cell(5,0.6,$l['rent_fullname'],1,0,'L');
			$this->fpdf->Cell(5,0.6,$l['property_name'],1,0,'C');
			$this->fpdf->Cell(4,0.6,$this->tanggal->tanggal_indo($l['rent_date']).' ('.$l['rent_days'].' hari)',1,0,'C');
			$this->fpdf->Cell(3,0.6,$this->uang->rupiah($l['rent_price']),1,0,'C');
			$this->fpdf->Cell(6,0.6,$this->get_status($l['rent_status']),1,0,'C');
			$this->fpdf->Ln();
			$no++;
		}

		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->Cell(27,0.5,'Bandung, '.$this->tanggal->tanggal_indo(date("Y-m-d")),0,0,'R');
		$this->fpdf->Ln();
		$this->fpdf->Cell(27,0.6,'Manager,',0,0,'R');
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','B',12);
		//$this->fpdf->Cell(27,0.6,$this->session->userdata('user_fullname'),0,0,'R');
		$this->fpdf->Cell(27,0.6,'Rully Hafna Ayudia',0,0,'R');
		$this->fpdf->Ln();

		//generate PDF
		$this->fpdf->Output();
	}

	public function generate_by_status($start_date,$end_date,$rent_status){
		$laporan = $this->report_model->get_report_rent($start_date,$end_date,0,$rent_status)->result_array();

		//inisialisasi laporan
		$this->fpdf->FPDF('L','cm','A4');
		$this->fpdf->AddPage();
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','B',9);
		$this->fpdf->SetTitle("Laporan Peminjaman Property Periode ".$this->tanggal->tanggal_indo($start_date)." - ".$this->tanggal->tanggal_indo($end_date)."");
		
		//JUDUL LAPORAN//
		$this->fpdf->setFont('Arial','B',12);
		$this->fpdf->Cell(27.5,0.5,"Laporan Peminjaman Berdasarkan Status Peminjaman",0,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','',12);
		$this->fpdf->Cell(27.5,0.5,$this->get_status($rent_status)." Periode ".$this->tanggal->tanggal_indo($start_date)." - ".$this->tanggal->tanggal_indo($end_date)."",0,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		
		//TABEL
		$this->fpdf->setFont('Arial','B',12);
		$this->fpdf->SetLineWidth(0.01);
		$this->fpdf->Cell(1,1,'No',1,0,'C');
		$this->fpdf->Cell(3.5,1,'No Peminjaman',1,0,'C');
		$this->fpdf->Cell(5,1,'Nama Peminjam',1,0,'C');
		$this->fpdf->Cell(5,1,'Nama Properti',1,0,'C');
		$this->fpdf->Cell(4,1,'Tanggal Pinjam',1,0,'C');
		$this->fpdf->Cell(3,1,'Total',1,0,'C');
		$this->fpdf->Cell(6,1,'Status',1,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','',12);
		$no = 1;
		foreach($laporan as $l){
			$this->fpdf->Cell(1,0.6,$no,1,0,'C');
			$this->fpdf->Cell(3.5,0.6,$l['rent_no'],1,0,'C');
			$this->fpdf->Cell(5,0.6,$l['rent_fullname'],1,0,'L');
			$this->fpdf->Cell(5,0.6,$l['property_name'],1,0,'C');
			$this->fpdf->Cell(4,0.6,$this->tanggal->tanggal_indo($l['rent_date']).' ('.$l['rent_days'].' hari)',1,0,'C');
			$this->fpdf->Cell(3,0.6,$this->uang->rupiah($l['rent_price']),1,0,'R');
			$this->fpdf->Cell(6,0.6,$this->get_status($l['rent_status']),1,0,'C');
			$this->fpdf->Ln();
			$no++;
		}

		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->Cell(27,0.5,'Bandung, '.$this->tanggal->tanggal_indo(date("Y-m-d")),0,0,'R');
		$this->fpdf->Ln();
		$this->fpdf->Cell(27,0.6,'Manager,',0,0,'R');
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','B',12);
		//$this->fpdf->Cell(27,0.6,$this->session->userdata('user_fullname'),0,0,'R');
		$this->fpdf->Cell(27,0.6,'Rully Hafna Ayudia',0,0,'R');
		$this->fpdf->Ln();

		//generate PDF
		$this->fpdf->Output();
	}


	public function generate_income($start_date,$end_date){
		//inisialisasi laporan
		$this->fpdf->FPDF('L','cm','A4');
		$this->fpdf->AddPage();
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','B',9);
		$this->fpdf->SetTitle("Laporan Pendapatan Eksternal Periode ".$this->tanggal->tanggal_indo($start_date)." - ".$this->tanggal->tanggal_indo($end_date)."");
		
		//JUDUL LAPORAN//
		$this->fpdf->setFont('Arial','B',12);
		$this->fpdf->Cell(27.5,0.5,"Laporan Pendapatan Eksternal",0,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','',12);
		$this->fpdf->Cell(27.5,0.5,"Periode ".$this->tanggal->tanggal_indo($start_date)." - ".$this->tanggal->tanggal_indo($end_date)."",0,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->Ln();

		//TABEL
		//semua pendapatan
		$laporan = $this->report_model->get_report_income('all',$start_date,$end_date)->result_array();
		$this->fpdf->setFont('Arial','B',12);
		$this->fpdf->SetLineWidth(0);
		$this->fpdf->Cell(10,1,'Semua Pendapatan',0,0,'L');
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','B',12);
		$this->fpdf->SetLineWidth(0.01);
		$this->fpdf->Cell(1,1,'No',1,0,'C');
		$this->fpdf->Cell(3.5,1,'No Peminjaman',1,0,'C');
		$this->fpdf->Cell(5,1,'Nama Peminjam',1,0,'C');
		$this->fpdf->Cell(6,1,'Tanggal Pinjam',1,0,'C');
		$this->fpdf->Cell(5,1,'Total',1,0,'C');
		$this->fpdf->Cell(6,1,'Keterangan',1,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','',12);
		$no = 1;
		$total_pendapatan = 0;
		foreach($laporan as $l){
			$this->fpdf->Cell(1,0.6,$no,1,0,'C');
			$this->fpdf->Cell(3.5,0.6,$l['rent_no'],1,0,'C');
			$this->fpdf->Cell(5,0.6,$l['user_fullname'],1,0,'L');
			$this->fpdf->Cell(6,0.6,$this->tanggal->tanggal_indo($l['rent_date']).' - '.$this->tanggal->tanggal_indo($l['rent_date_return']),1,0,'C');
			$this->fpdf->Cell(5,0.6,$this->uang->rupiah($l['rent_price']),1,0,'R');
			$this->fpdf->Cell(6,0.6,($l['rent_status'] > '0') ? "Sudah Dibayar" : "Belum Dibayar",1,0,'C');
			$this->fpdf->Ln();
			$total_pendapatan = $total_pendapatan + $l['rent_price'];
			$no++;
		}
		$this->fpdf->Cell(1,0.6,"",1,0,'C');
		$this->fpdf->Cell(3.5,0.6,"",1,0,'C');
		$this->fpdf->Cell(5,0.6,"",1,0,'L');
		$this->fpdf->Cell(6,0.6,"Total Pendapatan",1,0,'R');
		$this->fpdf->Cell(5,0.6,$this->uang->rupiah($total_pendapatan),1,0,'R');
		$this->fpdf->Cell(6,0.6,"",1,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->AddPage();


		//TABEL
		//pendapatan yang sudah diterima
		$laporan = $this->report_model->get_report_income('accepted',$start_date,$end_date)->result_array();
		$this->fpdf->setFont('Arial','B',12);
		$this->fpdf->SetLineWidth(0);
		$this->fpdf->Cell(10,1,'Pendapatan Yang Sudah Diterima',0,0,'L');
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','B',12);
		$this->fpdf->SetLineWidth(0.01);
		$this->fpdf->Cell(1,1,'No',1,0,'C');
		$this->fpdf->Cell(3.5,1,'No Peminjaman',1,0,'C');
		$this->fpdf->Cell(5,1,'Nama Peminjam',1,0,'C');
		$this->fpdf->Cell(6,1,'Tanggal Pinjam',1,0,'C');
		$this->fpdf->Cell(5,1,'Total',1,0,'C');
		$this->fpdf->Cell(6,1,'Keterangan',1,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','',12);
		$no = 1;
		$total_pendapatan_sudah_bayar = 0;
		foreach($laporan as $l){
			$this->fpdf->Cell(1,0.6,$no,1,0,'C');
			$this->fpdf->Cell(3.5,0.6,$l['rent_no'],1,0,'C');
			$this->fpdf->Cell(5,0.6,$l['user_fullname'],1,0,'L');
			$this->fpdf->Cell(6,0.6,$this->tanggal->tanggal_indo($l['rent_date']).' - '.$this->tanggal->tanggal_indo($l['rent_date_return']),1,0,'C');
			$this->fpdf->Cell(5,0.6,$this->uang->rupiah($l['rent_price']),1,0,'R');
			$this->fpdf->Cell(6,0.6,($l['rent_status'] > '0') ? "Sudah Dibayar" : "Belum Dibayar",1,0,'C');
			$this->fpdf->Ln();
			$total_pendapatan_sudah_bayar = $total_pendapatan_sudah_bayar + $l['rent_price'];
			$no++;
		}
		$this->fpdf->Cell(1,0.6,"",1,0,'C');
		$this->fpdf->Cell(3.5,0.6,"",1,0,'C');
		$this->fpdf->Cell(5,0.6,"",1,0,'L');
		$this->fpdf->Cell(6,0.6,"Total Pendapatan",1,0,'R');
		$this->fpdf->Cell(5,0.6,$this->uang->rupiah($total_pendapatan_sudah_bayar),1,0,'R');
		$this->fpdf->Cell(6,0.6,"",1,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->AddPage();


		//TABEL
		//pendapatan yang belum diterima
		$laporan = $this->report_model->get_report_income('not',$start_date,$end_date)->result_array();
		$this->fpdf->setFont('Arial','B',12);
		$this->fpdf->SetLineWidth(0);
		$this->fpdf->Cell(10,1,'Pendapatan Yang Belum Diterima',0,0,'L');
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','B',12);
		$this->fpdf->SetLineWidth(0.01);
		$this->fpdf->Cell(1,1,'No',1,0,'C');
		$this->fpdf->Cell(3.5,1,'No Peminjaman',1,0,'C');
		$this->fpdf->Cell(5,1,'Nama Peminjam',1,0,'C');
		$this->fpdf->Cell(6,1,'Tanggal Pinjam',1,0,'C');
		$this->fpdf->Cell(5,1,'Total',1,0,'C');
		$this->fpdf->Cell(6,1,'Keterangan',1,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','',12);
		$no = 1;
		$total_pendapatan_belum_bayar = 0;
		foreach($laporan as $l){
			$this->fpdf->Cell(1,0.6,$no,1,0,'C');
			$this->fpdf->Cell(3.5,0.6,$l['rent_no'],1,0,'C');
			$this->fpdf->Cell(5,0.6,$l['user_fullname'],1,0,'L');
			$this->fpdf->Cell(6,0.6,$this->tanggal->tanggal_indo($l['rent_date']).' - '.$this->tanggal->tanggal_indo($l['rent_date_return']),1,0,'C');
			$this->fpdf->Cell(5,0.6,$this->uang->rupiah($l['rent_price']),1,0,'R');
			$this->fpdf->Cell(6,0.6,($l['rent_status'] > '0') ? "Sudah Dibayar" : "Belum Dibayar",1,0,'C');
			$this->fpdf->Ln();
			$total_pendapatan_belum_bayar = $total_pendapatan_belum_bayar + $l['rent_price'];
			$no++;
		}
		$this->fpdf->Cell(1,0.6,"",1,0,'C');
		$this->fpdf->Cell(3.5,0.6,"",1,0,'C');
		$this->fpdf->Cell(5,0.6,"",1,0,'L');
		$this->fpdf->Cell(6,0.6,"Total Pendapatan",1,0,'R');
		$this->fpdf->Cell(5,0.6,$this->uang->rupiah($total_pendapatan_belum_bayar),1,0,'R');
		$this->fpdf->Cell(6,0.6,"",1,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->AddPage();


		//TABEL
		//semua denda
		$laporan = $this->report_model->get_report_penalty('all',$start_date,$end_date)->result_array();
		$this->fpdf->setFont('Arial','B',12);
		$this->fpdf->SetLineWidth(0);
		$this->fpdf->Cell(10,1,'Semua Denda',0,0,'L');
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','B',12);
		$this->fpdf->SetLineWidth(0.01);
		$this->fpdf->Cell(1,1,'No',1,0,'C');
		$this->fpdf->Cell(3.5,1,'No Peminjaman',1,0,'C');
		$this->fpdf->Cell(5,1,'Nama Peminjam',1,0,'C');
		$this->fpdf->Cell(6,1,'Tanggal Pinjam',1,0,'C');
		$this->fpdf->Cell(5,1,'Denda',1,0,'C');
		$this->fpdf->Cell(6,1,'Keterangan',1,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','',12);
		$no = 1;
		$total_denda = 0;
		foreach($laporan as $l){
			$this->fpdf->Cell(1,0.6,$no,1,0,'C');
			$this->fpdf->Cell(3.5,0.6,$l['rent_no'],1,0,'C');
			$this->fpdf->Cell(5,0.6,$l['user_fullname'],1,0,'L');
			$this->fpdf->Cell(6,0.6,$this->tanggal->tanggal_indo($l['rent_date']).' - '.$this->tanggal->tanggal_indo($l['rent_date_return']),1,0,'C');
			$this->fpdf->Cell(5,0.6,$this->uang->rupiah($l['rent_penalty']),1,0,'R');
			$this->fpdf->Cell(6,0.6,($l['rent_penalty_paid'] == '1') ? "Sudah Dibayar" : "Belum Dibayar",1,0,'C');
			$this->fpdf->Ln();
			$total_denda = $total_denda + $l['rent_penalty'];
			$no++;
		}
		$this->fpdf->Cell(1,0.6,"",1,0,'C');
		$this->fpdf->Cell(3.5,0.6,"",1,0,'C');
		$this->fpdf->Cell(5,0.6,"",1,0,'L');
		$this->fpdf->Cell(6,0.6,"Total Denda",1,0,'R');
		$this->fpdf->Cell(5,0.6,$this->uang->rupiah($total_denda),1,0,'R');
		$this->fpdf->Cell(6,0.6,"",1,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->AddPage();


		//TABEL
		//denda yang sudah dibayar
		$laporan = $this->report_model->get_report_penalty('accepted',$start_date,$end_date)->result_array();
		$this->fpdf->setFont('Arial','B',12);
		$this->fpdf->SetLineWidth(0);
		$this->fpdf->Cell(10,1,'Denda Yang Sudah Dibayar',0,0,'L');
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','B',12);
		$this->fpdf->SetLineWidth(0.01);
		$this->fpdf->Cell(1,1,'No',1,0,'C');
		$this->fpdf->Cell(3.5,1,'No Peminjaman',1,0,'C');
		$this->fpdf->Cell(5,1,'Nama Peminjam',1,0,'C');
		$this->fpdf->Cell(6,1,'Tanggal Pinjam',1,0,'C');
		$this->fpdf->Cell(5,1,'Denda',1,0,'C');
		$this->fpdf->Cell(6,1,'Keterangan',1,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','',12);
		$no = 1;
		$total_denda_sudah_bayar = 0;
		foreach($laporan as $l){
			$this->fpdf->Cell(1,0.6,$no,1,0,'C');
			$this->fpdf->Cell(3.5,0.6,$l['rent_no'],1,0,'C');
			$this->fpdf->Cell(5,0.6,$l['user_fullname'],1,0,'L');
			$this->fpdf->Cell(6,0.6,$this->tanggal->tanggal_indo($l['rent_date']).' - '.$this->tanggal->tanggal_indo($l['rent_date_return']),1,0,'C');
			$this->fpdf->Cell(5,0.6,$this->uang->rupiah($l['rent_penalty']),1,0,'R');
			$this->fpdf->Cell(6,0.6,($l['rent_penalty_paid'] == '1') ? "Sudah Dibayar" : "Belum Dibayar",1,0,'C');
			$this->fpdf->Ln();
			$total_denda_sudah_bayar = $total_denda_sudah_bayar + $l['rent_penalty'];
			$no++;
		}
		$this->fpdf->Cell(1,0.6,"",1,0,'C');
		$this->fpdf->Cell(3.5,0.6,"",1,0,'C');
		$this->fpdf->Cell(5,0.6,"",1,0,'L');
		$this->fpdf->Cell(6,0.6,"Total Denda",1,0,'R');
		$this->fpdf->Cell(5,0.6,$this->uang->rupiah($total_denda_sudah_bayar),1,0,'R');
		$this->fpdf->Cell(6,0.6,"",1,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->AddPage();


		//TABEL
		//Denda yang belum dibayar
		$laporan = $this->report_model->get_report_penalty('not',$start_date,$end_date)->result_array();
		$this->fpdf->setFont('Arial','B',12);
		$this->fpdf->SetLineWidth(0);
		$this->fpdf->Cell(10,1,'Denda Yang Belum Dibayar',0,0,'L');
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','B',12);
		$this->fpdf->SetLineWidth(0.01);
		$this->fpdf->Cell(1,1,'No',1,0,'C');
		$this->fpdf->Cell(3.5,1,'No Peminjaman',1,0,'C');
		$this->fpdf->Cell(5,1,'Nama Peminjam',1,0,'C');
		$this->fpdf->Cell(6,1,'Tanggal Pinjam',1,0,'C');
		$this->fpdf->Cell(5,1,'Denda',1,0,'C');
		$this->fpdf->Cell(6,1,'Keterangan',1,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','',12);
		$no = 1;
		$total_denda_belum_bayar = 0;
		foreach($laporan as $l){
			$this->fpdf->Cell(1,0.6,$no,1,0,'C');
			$this->fpdf->Cell(3.5,0.6,$l['rent_no'],1,0,'C');
			$this->fpdf->Cell(5,0.6,$l['user_fullname'],1,0,'L');
			$this->fpdf->Cell(6,0.6,$this->tanggal->tanggal_indo($l['rent_date']).' - '.$this->tanggal->tanggal_indo($l['rent_date_return']),1,0,'C');
			$this->fpdf->Cell(5,0.6,$this->uang->rupiah($l['rent_penalty']),1,0,'R');
			$this->fpdf->Cell(6,0.6,($l['rent_penalty_paid'] == '1') ? "Sudah Dibayar" : "Belum Dibayar",1,0,'C');
			$this->fpdf->Ln();
			$total_denda_belum_bayar = $total_denda_belum_bayar + $l['rent_penalty'];
			$no++;
		}
		$this->fpdf->Cell(1,0.6,"",1,0,'C');
		$this->fpdf->Cell(3.5,0.6,"",1,0,'C');
		$this->fpdf->Cell(5,0.6,"",1,0,'L');
		$this->fpdf->Cell(6,0.6,"Total Denda",1,0,'R');
		$this->fpdf->Cell(5,0.6,$this->uang->rupiah($total_denda_belum_bayar),1,0,'R');
		$this->fpdf->Cell(6,0.6,"",1,0,'C');
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->AddPage();


		$this->fpdf->SetLineWidth(0);
		$this->fpdf->Cell(10,1,'IKHTISAR',0,0,'L');
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->Cell(4,1,'Pendapatan',0,0,'L');
		$this->fpdf->Ln();
		$this->fpdf->Cell(4,1,'Pendapatan Sudah Dibayar',0,0,'L');
		$this->fpdf->Cell(8,1,$this->uang->rupiah($total_pendapatan_sudah_bayar),0,0,'R');
		$this->fpdf->Ln();
		$this->fpdf->Cell(4,1,'Pendapatan Belum Dibayar',0,0,'L');
		$this->fpdf->Cell(8,1,$this->uang->rupiah($total_pendapatan_belum_bayar),0,0,'R');
		$this->fpdf->Ln();
		$this->fpdf->Cell(4,1,'Total Pendapatan',0,0,'L');
		$this->fpdf->Cell(8,1,$this->uang->rupiah($total_pendapatan),0,0,'R');
		$this->fpdf->Ln();

		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->Cell(4,1,'Denda',0,0,'L');
		$this->fpdf->Ln();
		$this->fpdf->Cell(4,1,'Denda Sudah Dibayar',0,0,'L');
		$this->fpdf->Cell(8,1,$this->uang->rupiah($total_denda_sudah_bayar),0,0,'R');
		$this->fpdf->Ln();
		$this->fpdf->Cell(4,1,'Denda Belum Dibayar',0,0,'L');
		$this->fpdf->Cell(8,1,$this->uang->rupiah($total_denda_belum_bayar),0,0,'R');
		$this->fpdf->Ln();
		$this->fpdf->Cell(4,1,'Total Denda',0,0,'L');
		$this->fpdf->Cell(8,1,$this->uang->rupiah($total_denda),0,0,'R');
		$this->fpdf->Ln();


		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->Cell(27,0.5,'Bandung, '.$this->tanggal->tanggal_indo(date("Y-m-d")),0,0,'R');
		$this->fpdf->Ln();
		$this->fpdf->Cell(27,0.6,'Manager,',0,0,'R');
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->Ln();
		$this->fpdf->setFont('Arial','B',12);
		//$this->fpdf->Cell(27,0.6,$this->session->userdata('user_fullname'),0,0,'R');
		$this->fpdf->Cell(27,0.6,'Rully Hafna Ayudia',0,0,'R');
		$this->fpdf->Ln();

		//generate PDF
		$this->fpdf->Output();
	}

	public function get_status($status_id){
		$status = 'null';
		if($status_id=='0'){
			$status = 'Menunggu Konfirmasi';
		}elseif($status_id=='1'){
			$status = 'Dikonfirmasi';
		}elseif($status_id=='2'){
			$status = 'Konfirmasi Pengembalian';
		}elseif($status_id=='3'){
			$status = 'Selesai';
		}

		return $status;
	}
}