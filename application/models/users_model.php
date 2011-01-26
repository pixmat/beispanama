<?php if (! defined('BASEPATH')) exit('No direct script access');

class Users_Model extends CI_Model {
	
	public $table = "users";

	function __construct() {
		parent::__construct();
	}
	
	public function addUser($screen_name, $equipo)
	{
		$data = array(
			"screen_name"	=> $screen_name,
			"equipo"			=> $equipo
		);
		if ( ! $this->userExists($screen_name) ) {	
			return $this->db->insert($this->table, $data);
		} else {
			$this->db->where(array("screen_name" => $screen_name));
			return $this->db->update($this->table, $data);
		}
	}
	
	public function userExists($screen_name)
	{
		$this->db->select("id")->from($this->table)->where(array("screen_name" => $screen_name))->limit(1);
		return ($this->db->count_all_results() > 0) ? TRUE : FALSE;
	}

}

/* End of file users.php  */
/* Location: ./application/models/users.php */