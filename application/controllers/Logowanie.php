<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Logowanie extends MY_Controller {
	function __construct(){
		parent::__construct();
        if($this->acl_library->funkcjav2() == false) show_404();
        //$this->czy_zalogowano();
		$this->load->database();
	}

    /**
     *Wyświetla formularz logowania
     */
    public function index(){
		$this->czy_zalogowano();
		$this->load->view('head');
		$this->load->view('logowanie/logowanie');
	}

    /**
     *Loguje użytkownika do systemu jeśli dane podane w formularzu logowania są poprawne
     */
    public function zaloguj_uzytkownika(){
		//echo var_dump($_REQUEST);
		//$this->czy_zalogowano();
		$this->form_validation->set_rules('inputUser_ID', 'inputUser_ID', 'required|trim|required|xss_clean');
		$this->form_validation->set_rules('inputPassword','inputPassword', 'required|trim|required|xss_clean');
		/*if(!empty($this->input->post('inputUser_ID')) && !empty($this->input->post('inputPassword'))){
			
		}*/
		
		if ($this->form_validation->run() == FALSE){
                        //$this->load->view('myform');
			$this->czy_zalogowano();
		}else{
			$nazwa_uzytkownika = $this->input->post('inputUser_ID');
			$haslo = $this->input->post('inputPassword');
			
			if($result=$this->logowanie_model->uzytkownik_hash($nazwa_uzytkownika)){
			    //var_dump($result);
				$hash=$result[0]['haslo'];
				//var_dump($result);
				//$nazwa_uzytkownika=$result[0]->nazwa_uzytkownika;
				//$id_uzytkownika=$result[0]->id;
				//echo $hash;
				if(password_verify($haslo, $hash)) {
					//echo 'Password is valid!';
                    //var_dump($result);
                    //exit;
                    $user_id=$result[0]['id'];
					$dane_uzytkownika=$this->uzytkownik_model->uzytkownik_id($user_id);
					//var_dump($dane_uzytkownika);
					//exit;
                    $this->logowanie_model->zalogowano_info($dane_uzytkownika[0]['id_uzytkownika']);
					$this->session->set_userdata(array('zalogowano'=>1,'nazwa_uzytkownika'=>$dane_uzytkownika[0]['nazwa_uzytkownika'],'id_uzytkownika'=>$dane_uzytkownika[0]['id_uzytkownika'],'typ_uzytkownika'=>$dane_uzytkownika[0]['typ_uzytkownika']));
                    //$this->session->sess_expire_on_close = 'true';
					$this->czy_zalogowano();
					//header('Location: /home');
					redirect('/home','location');
				} else {
					//echo 'Invalid password.';
                    $this->load->view('head');
					$this->load->view('logowanie/logowanie',array('error_message'=>'Wprowadzono błędne dane'));
				}
			}else{
                $this->load->view('head');
				$this->load->view('logowanie/logowanie',array('error_message'=>'Wprowadzono błędne dane'));
				
			}
			
		}
		
		
	}

    /**
     *Wylogowuwyje użytkownika z systemu
     */
    public function wyloguj_uzytkownika(){
		$this->czy_zalogowano(1);
		$this->session->sess_destroy();
        $this->load->view('head');
		$this->load->view('logowanie/logowanie',array('logout_message'=>'Zostałeś wylogowany'));
	}

}