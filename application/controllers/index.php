<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Index Controller
 *
 * @package default
 * @author demogar
 */
class Index extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		// Header
		$header = array(
			"title"			=>	"Beispanama &ndash; Comparte el fervor por tu equipo favorito del b&eacute;isbol nacional en Twitter",
			"description"	=> "Beispanama: Comparte el fervor por tu equipo favorito del b&eacute;isbol nacional en Twitter",
			"keywords"		=> "beisbol-panama,baseball-panama,beis-panama,twitter-panama,deportes-panama"
		);
		$this->load->view("header", $header);
		
		// Body
		$this->load->view("index");
		
		// Footer
		$this->load->view('footer');
	}
}

/* End of file index.php */
/* Location: ./application/controllers/index.php */