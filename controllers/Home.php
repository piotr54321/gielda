<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {
    function __construct(){
        parent::__construct();
        $this->czy_zalogowano();
        if($this->acl_library->funkcjav2() == false) show_404();

    }

    /**
     *Strona główna aplikacji po zalogowaniu
     *
     */
    public function index()
	{
		$this->load->view('head');
		$this->load->view('navbar');
		//$data['uzytkownik']=$this->uzytkownik_model->uzytkownik($this->session->id_uzytkownika);
		//var_dump($this->uzytkownik_model->uzytkownik($this->session->id_uzytkownika));
		//$this->load->view('home', $data);
        $data['role_nazwy']=$this->acl_library->uzytkownik_role_nazwy($this->session->id_uzytkownika);
		$this->load->view('home', $data);
		//$this->acl_library->url();
		$this->load->view('footer');
	}
}
