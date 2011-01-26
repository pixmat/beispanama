<?php if (! defined('BASEPATH')) exit('No direct script access');

class App extends CI_Controller {
	
	public $equipos = array(
		"bocas"			=> "Bocas Del Toro",
		"cocle"			=> "Cocl&eacute;",
		"colon"			=> "Colon",
		"chiriqui"		=> "Chiriqu&iacute;",
		"occidente"		=> "Chiriqu&iacute; Occidente",
		"darien"			=> "Dari&eacute;n",
		"herrera"		=> "Herrera",
		"los_santos"	=> "Los Santos",
		"metro"			=> "Panam&aacute; Metro",
		"oeste"			=> "Panam&aacute; Oeste",
		"veraguas"		=> "Veraguas"
	);

	function __construct() {
		parent::__construct();
		
		$this->load->library("twitter");
	}
	
	/**
	 * The entry poin to this controller
	 *
	 * @param string $sigin 
	 * @return void
	 * @author demogar
	 */
	public function index($sigin = NULL)
	{
		$here = $this->twitter->tmhOAuth->php_self();
		
		if ( $this->twitter->isAuthed() ) { // <- Already logged in
			$this->twitter->tmhOAuth->config['user_token']  = $this->session->userdata("a_oauth_token");
			$this->twitter->tmhOAuth->config['user_secret'] = $this->session->userdata("a_oauth_token_secret");
			
			// Get user data
			$this->twitter->getUserData();
			
			// Header
			$header = array(
				"title"			=> "Beispanama &ndash; Cambia tu avatar",
				"description"	=> "Cambia tu avatar de Twitter, usando la bandera de tu equipo favorito del b&eacute;isbol nacional de Panam&aacute;",
				"keywords"		=> "beisbol-panama,baseball-panama,beis-panama,twitter-panama,deportes-panama"
			);
			$this->load->view("header", $header);
			
			// Body
			$body = array(
				"avatar"		=> $this->twitter->getAvatarUrl(),
				"equipos"	=> $this->equipos
			);
			$this->load->view("app", $body);
			
			// Footer
			$this->load->view("footer");
		} elseif (isset($_GET['oauth_verifier'])) { // <- Coming from Twitter
			$this->twitter->getAccessToken($_GET['oauth_verifier']);
			header("Location: {$here}");
		} elseif ( $sigin != NULL ) { // <- Tryinig to log in
			$this->twitter->getOauthToken($here);
		}
	}
	
	/**
	 * Process the form after its submission
	 *
	 * @author demogar
	 */
	public function process()
	{
		if ( $this->input->post("equipo") != "" ) {
			if ( $this->twitter->isAuthed() ) {
				// Loader
				$this->load->library('image_lib');
				$this->load->model("users_model");
				
				$this->twitter->tmhOAuth->config['user_token']  = $this->session->userdata("a_oauth_token");
				$this->twitter->tmhOAuth->config['user_secret'] = $this->session->userdata("a_oauth_token_secret");
				
				$this->twitter->getUserData();
				$this->twitter->downloadAvatar();
				
				// Manipulate the image
				$path = $this->twitter->path;
				$config['source_image']			= $path;
				$config['wm_overlay_path']		= FCPATH . "images/" . $this->input->post('equipo') . ".png";
				$config['quality']				= "100%";
				$config['wm_type']				= 'overlay';
				$config['wm_vrt_alignment']	= 'bottom';
				$config['wm_hor_alignment']	= 'left';
				$config['wm_padding']			= '0';
				$this->image_lib->initialize($config);
				
				if ( $this->image_lib->watermark() ) {
					// Send avatar to twitter
					$this->twitter->sendAvatarToTwitter($path, $this->twitter->tempImage);
					
					// Follow users?
					if ( $this->input->post("follow") === "yes" ) {
						// Following users
						$this->twitter->followUser("demogar");
						$this->twitter->followUser("pixmat");
					}
					
					// Send Tweet?
					if ( $this->input->post("send_tweet") === "yes" ) {
						$this->twitter->sendTweet("Yo apoyo a mi equipo favorito de beis en Panama usando http://www.beispanama.com por @pixmat.");
					}
					
					/* ----- FINISHING ----- */
					$this->users_model->addUser( $this->twitter->userdata->screen_name, $this->input->post("equipo") );
					
					// Header
					$header = array(
						"title"			=> "Beispanama &ndash; Avatar cambiado",
						"description"	=> "Tu avatar ha sido cambiado, gracias por utilizar nuestro servicio",
						"keywords"		=> "beisbol-panama,baseball-panama,beis-panama,twitter-panama,deportes-panama"
					);
					$this->load->view("header", $header);
					
					// Body
					$this->load->view("end");
					
					// Footer
					$this->load->view("footer");
				} else {
					show_error( $this->image_lib->display_errors() );
				}
			} else {
				$this->logout();
			}
		} else {
			show_error("Error, no seleccioniaste un equipo.");
		}
	}
	
	/**
	 * Destroys the session
	 *
	 * @author demogar
	 */
	public function logout()
	{
		$this->session->sess_destroy();
		redirect();
	}

}

/* End of file app.php  */
/* Location: ./application/controllers/app.php */