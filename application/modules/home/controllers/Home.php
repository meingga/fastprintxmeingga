<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		require_once 'vendor/autoload.php'; // Path ke autoload.php dari Composer

		// Membaca isi file readme.md
		$markdownContent = file_get_contents('README.md');

		// Mengonversi Markdown ke HTML menggunakan Parsedown
		$parsedown = new Parsedown();
		$htmlContent = $parsedown->text($markdownContent);
		$data = array(
			'htmlContent' => $htmlContent,
			'title' 	 => 'DOCUMENTATION',
			'content' 	 => 'home',
		);
		$this->load->view('_template/index', $data);
	}
}
