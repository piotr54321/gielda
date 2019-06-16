<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rejestracja extends CI_Controller {
    function __construct(){
        parent::__construct();
        if($this->acl_library->funkcjav2() == false) show_404();
        //$this->czy_zalogowano();
    }

    /**
     * Wyświetla formularz zmiany hasła
     * @param string $hash
     */
    public function ustaw_haslo($hash=''){
        $data=array();
        if(!$this->rejestracja_model->sprawdz_hash($hash) || $hash == ''){
            $data['blad']='Podana strona nie istnieje';
        }
        $this->load->view('rejestracja/zmianahasla', $data);
    }

    /**
     *Sprawdza czy dane podane w formularzu zmiany hasła są poprawne
     */
    public function ustaw_haslo_check(){
        $data=array();
        $this->form_validation->set_rules('inputEmail', 'inputEmail', 'required|trim|xss_clean');
        $this->form_validation->set_rules('inputPassword', 'inputPassword', 'required|trim|xss_clean');
        $data['inputPassword2']=$this->form_validation->set_rules('inputPassword2', 'inputPassword2', 'required|trim|xss_clean');
        $data['inputHash']=$this->form_validation->set_rules('inputHash', 'inputHash', 'required|trim|xss_clean');
        if ($this->form_validation->run() == FALSE){
            $data['blad']='Wprowadzono nieprawidłowe dane';
        }else{
            $data['inputEmail']=$this->input->post('inputEmail');
            $data['inputPassword']=$this->input->post('inputPassword');
            $data['inputPassword2']=$this->input->post('inputPassword2');
            $data['inputHash']=$this->input->post('inputHash');
            //var_dump($data);
            //$this->rejestracja_model->ustaw_haslo($data);
            if($this->rejestracja_model->ustaw_haslo($data)){
                echo'poprawnie zmieniono hasło';
                redirect('logowanie', 'location');
            }else{
                echo'niepoprawna zmiana hasłą';
            }
        }
    }

    /**
     *
     * @param $email
     */
    public function resetuj_haslo($email){
        
    }

    /**
     *Wyświetla formularz rejestracji
     */
    public function index(){
        $data['panstwa'] = $this->strona_model->panstwa();

        $this->load->view('head');
        $this->load->view('rejestracja/formularzrejestracji', $data);
        $this->load->view('footer');
    }

    /**
     *Sprawdza czy dane podane w formularzu rejestracji są poprawne
     */
    public function rejestracja_check(){
        $this->form_validation->set_rules('FormImie', 'FormImie', 'required|trim|xss_clean');
        $this->form_validation->set_rules('FormNazwisko', 'FormNazwisko', 'required|trim|xss_clean');
        $this->form_validation->set_rules('FormNazwaUzytkownika', 'FormNazwaUzytkownika', 'required|trim|xss_clean|is_unique[uzytkownicy.nazwa_uzytkownika]alpha_numeric');
        $this->form_validation->set_rules('FormDataUrodzenia', 'FormDataUrodzenia', 'required|trim|xss_clean');
        //$this->form_validation->set_rules('FormTypUzytkownika[]', 'FormTypUzytkownika', 'required|trim|xss_clean');
        $this->form_validation->set_rules('FormPanstwo', 'FormPanstwo', 'required|trim|xss_clean');
        $this->form_validation->set_rules('FormKodPocztowy', 'FormKodPocztowy', 'required|trim|xss_clean');
        $this->form_validation->set_rules('FormMiejscowosc', 'FormMiejscowosc', 'required|trim|xss_cleanalpha_numeric');
        $this->form_validation->set_rules('FormNrDomu', 'FormNrDomu', 'required|trim|xss_cleanalpha_numeric');
        $this->form_validation->set_rules('FormEmail', 'FormEmail', 'required|trim|xss_clean|valid_email|is_unique[uzytkownicy.email]');


        if ($this->form_validation->run() == FALSE) {
            //$this->load->view('myform');
            //redirect('/rejestracja','location');
            $data['info']='Pola uzupełnione niepoprawnie lub nazwa użytkownika, adres email istnieje już w bazie';
        }else{
            $dane=array();
            //var_dump(isset($dane));

            $dane['FormImie']=$this->input->post('FormImie');
            $dane['FormNazwisko']=$this->input->post('FormNazwisko');
            $dane['FormNazwaUzytkownika']=$this->input->post('FormNazwaUzytkownika');
            $dane['FormDataUrodzenia']=$this->input->post('FormDataUrodzenia');
            //$dane['FormTypUzytkownika']=$this->input->post('FormTypUzytkownika');
            $dane['FormPanstwo']=$this->input->post('FormPanstwo');
            $dane['FormKodPocztowy']=$this->input->post('FormKodPocztowy');
            $dane['FormMiejscowosc']=$this->input->post('FormMiejscowosc');
            $dane['FormNrDomu']=$this->input->post('FormNrDomu');
            $dane['FormNrLokalu']=$this->input->post('FormNrLokalu');
            $dane['idUzytkownika']=$this->input->post('idUzytkownika');
            $dane['FormEmail']=$this->input->post('FormEmail');

            if($this->uzytkownik_model->uzytkownik_dodaj($dane, 1)){
                $data['info']='Potwierdź link rejestracyjny który został wysłany na Twój adres email';
            }else{

                $data['info']='błąd dodawania';
            }
        }

        $data['panstwa'] = $this->strona_model->panstwa();
        $this->load->view('head');
        $this->load->view('rejestracja/formularzrejestracji', $data);
        $this->load->view('footer');
    }
}
