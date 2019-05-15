<?php if (! defined('BASEPATH')) exit('No direct script access');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //$this->load->library('session');
        //$this->load->library('acl_library');
    }

    /**
     * @param bool $wyloguj
     * @return bool
     */
    public function czy_zalogowano($wyloguj=false){
		if('logowanie'==$this->router->fetch_class() && $this->session->userdata('zalogowano')==1 && $wyloguj==false){
            redirect('/home','location');
        }elseif($this->session->userdata('zalogowano')==1 && 'logowanie'==$this->router->fetch_class() && $wyloguj==true){
            return true;
        }elseif($this->session->userdata('zalogowano')==0 && 'logowanie'!=$this->router->fetch_class() && $this->acl_library->czy_wymaga_logowania($this->router->fetch_class().'/'.$this->router->fetch_method())){
            redirect('/logowanie','location');
        }else{
		    return true;
        }
	}
}