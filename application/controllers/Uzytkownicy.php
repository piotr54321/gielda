<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uzytkownicy extends MY_Controller {
    function __construct(){
        parent::__construct();
        $this->acl_library->session_update();
        $this->czy_zalogowano();
        if($this->acl_library->funkcjav2() == false) show_404();

    }

    /**
     *Wyświetla listę wszystkich użytkowników
     */
    public function index()
    {
        $this->load->view('head');
        $this->load->view('navbar');
        $liczba_uzytkownikow=$data['liczba_uzytkownikow']=$this->uzytkownicy_model->liczba_uzytkownikow();

        $ilosc_na_stronie=15;
        $str_links=$this->pagination_model->config($liczba_uzytkownikow, $ilosc_na_stronie, 'uzytkownicy/podglad/');
        //$this->pagination->initialize($config);

        $page = $this->uri->segment(3, 1);

        //var_dump($page);
        //$page=;
        //var_dump($this->uri->segment(3));
        //$str_links = $this->pagination->create_links();
        //var_dump($str_links);
        $data["links"] = explode('&nbsp;',$str_links );
        //var_dump($data['links']);
        //echo $this->uri->segment(3);
        $data['lista_uzytkownikow']=$this->uzytkownicy_model->lista_uzytkownikow(true ,$page, $ilosc_na_stronie);
        $this->load->view('uzytkownicy/podglad', $data);
        $this->load->view('footer');
    }

    /**
     *Wyświetla formularz szukania użytkowników
     */
    public function szukaj(){
        $this->load->view('head');
        $this->load->view('navbar');
        //$dane_uzytkownika = $this->uzytkownik_model->uzytkownik_id($id);
        //  $data['dane_uzytkownika'] = $dane_uzytkownika;
        $data['panstwa'] = $this->strona_model->panstwa();
        //var_dump($data['panstwa']);
        $data['role_uzytkownikow'] = $this->strona_model->role_uzytkownikow();
      //  var_dump($data['role_uzytkownikow']);
        $this->load->view('uzytkownicy/szukaj', $data);
        $this->load->view('footer');

    }

    /**
     *Jeśli formularz wypełniony poprawnie wyświetla listę wyszukanych użytkowników
     */
    public function szukaj_check(){
        $this->form_validation->set_rules('FormImie', 'FormImie', 'trim|xss_clean');
        $this->form_validation->set_rules('FormNazwisko', 'FormNazwisko', 'trim|xss_clean');
        $this->form_validation->set_rules('FormNazwaUzytkownika', 'FormNazwaUzytkownika', 'trim|xss_clean');
        $this->form_validation->set_rules('FormDataUrodzenia', 'FormDataUrodzenia', 'trim|xss_clean');
        $this->form_validation->set_rules('FormTypUzytkownika[]', 'FormTypUzytkownika', 'trim|xss_clean');
        $this->form_validation->set_rules('FormPanstwo', 'FormPanstwo', 'trim|xss_clean');
        $this->form_validation->set_rules('FormKodPocztowy', 'FormKodPocztowy', 'trim|xss_clean');
        $this->form_validation->set_rules('FormMiejscowosc', 'FormMiejscowosc', 'trim|xss_clean');
        $this->form_validation->set_rules('FormNrDomu', 'FormNrDomu', 'trim|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            //$this->load->view('myform');
            redirect('/uzytkownicy','location');
        }else{
            $dane=array();
            //var_dump(isset($dane));

            $dane['FormImie']=$this->input->post('FormImie');
            $dane['FormNazwisko']=$this->input->post('FormNazwisko');
            $dane['FormNazwaUzytkownika']=$this->input->post('FormNazwaUzytkownika');
            $dane['FormDataUrodzenia']=$this->input->post('FormDataUrodzenia');
            $dane['FormTypUzytkownika']=$this->input->post('FormTypUzytkownika');
            $dane['FormPanstwo']=$this->input->post('FormPanstwo');
            $dane['FormKodPocztowy']=$this->input->post('FormKodPocztowy');
            $dane['FormMiejscowosc']=$this->input->post('FormMiejscowosc');
            $dane['FormNrDomu']=$this->input->post('FormNrDomu');
            $dane['FormNrLokalu']=$this->input->post('FormNrLokalu');
            $dane['idUzytkownika']=$this->input->post('idUzytkownika');

            //var_dump($dane);

            if($this->uzytkownik_model->uzytkownik_szukaj($dane) == false){
                $data['nie_znaleziono']=TRUE;
            }else{
                $data['lista_uzytkownikow']=$this->uzytkownik_model->uzytkownik_szukaj($dane);
            }
            $this->load->view('head');
            $this->load->view('navbar');
            $this->load->view('uzytkownicy/podglad', $data);
            $this->load->view('footer');
        }
    }

    /**
     *Wyświetla formularz dodawania nowego użytkownika
     */
    public function dodaj(){
        $this->load->view('head');
        $this->load->view('navbar');
        //$this->load->view('uzytkownicy/dodaj');
        $data['panstwa'] = $this->strona_model->panstwa();
        $data['role_uzytkownikow'] = $this->strona_model->role_uzytkownikow();
        $this->load->view('uzytkownicy/dodajformularz',$data);
        $this->load->view('footer');
    }

    /**
     *Jeśli wprowadzone dane w formularzu są poprawne to dodaje nowego użytkownika
     */
    public function dodaj_check(){
        $this->load->view('head');
        $this->load->view('navbar');
        $data=array();

        //var_dump($this->input->post('FormTypUzytkownika'));
       // $xd=$this->uzytkownik_model->strona_role_to_uzytkownicy_role($this->input->post('FormTypUzytkownika'), 4);
        //var_dump($xd);


        //xit;

        $this->form_validation->set_rules('FormImie', 'FormImie', 'required|trim|xss_clean');
        $this->form_validation->set_rules('FormNazwisko', 'FormNazwisko', 'required|trim|xss_clean');
        $this->form_validation->set_rules('FormNazwaUzytkownika', 'FormNazwaUzytkownika', 'required|trim|xss_clean|is_unique[uzytkownicy.nazwa_uzytkownika]|alpha_numeric');
        $this->form_validation->set_rules('FormDataUrodzenia', 'FormDataUrodzenia', 'required|trim|xss_clean');
        $this->form_validation->set_rules('FormTypUzytkownika[]', 'FormTypUzytkownika', 'required|trim|xss_clean');
        $this->form_validation->set_rules('FormPanstwo', 'FormPanstwo', 'required|trim|xss_clean');
        $this->form_validation->set_rules('FormKodPocztowy', 'FormKodPocztowy', 'required|trim|xss_clean');
        $this->form_validation->set_rules('FormMiejscowosc', 'FormMiejscowosc', 'required|trim|xss_clean|alpha_numeric');
        $this->form_validation->set_rules('FormNrDomu', 'FormNrDomu', 'required|trim|xss_clean|alpha_numeric');
        $this->form_validation->set_rules('FormEmail', 'FormEmail', 'required|trim|xss_clean|valid_email|is_unique[uzytkownicy.email]');


        if ($this->form_validation->run() == FALSE) {
            //$this->load->view('myform');
            $data['info']='Nie poprawnie uzupełnione pola';
        }else {
            $dane = array();
            //var_dump(isset($dane));

            $dane['FormImie'] = $this->input->post('FormImie');
            $dane['FormNazwisko'] = $this->input->post('FormNazwisko');
            $dane['FormNazwaUzytkownika'] = $this->input->post('FormNazwaUzytkownika');
            $dane['FormDataUrodzenia'] = $this->input->post('FormDataUrodzenia');
            $dane['FormTypUzytkownika'] = $this->input->post('FormTypUzytkownika');
            $dane['FormPanstwo'] = $this->input->post('FormPanstwo');
            $dane['FormKodPocztowy'] = $this->input->post('FormKodPocztowy');
            $dane['FormMiejscowosc'] = $this->input->post('FormMiejscowosc');
            $dane['FormNrDomu'] = $this->input->post('FormNrDomu');
            $dane['FormNrLokalu'] = $this->input->post('FormNrLokalu');
            $dane['idUzytkownika'] = $this->input->post('idUzytkownika');
            $dane['FormEmail'] = $this->input->post('FormEmail');

            if ($this->uzytkownik_model->uzytkownik_dodaj($dane)) {
                $data['info'] = 'poprawnie dodano';
            } else {

                $data['info'] = 'błąd';
            }
        }
        $this->load->view('uzytkownicy/info', $data);
        $this->load->view('footer');
    }

    /**
     * Wyświetla profil użytkownika
     * @param string $id_uzytkownika
     */
    public function uzytkownik($id_uzytkownika=''){
        $this->load->view('head');
        $this->load->view('navbar');
        $data=array();
        if(is_numeric($id_uzytkownika)){
            if($this->uzytkownik_model->uzytkownik_id($id_uzytkownika)){
                $data['uzytkownik_info']=$this->uzytkownik_model->uzytkownik_id($id_uzytkownika);
            }else{
                $data['info'] = 'Brak użytkownika o id: '.$id_uzytkownika;
            }
        }else{
            $data['info'] = 'Błąd';
        }



        $this->load->view('uzytkownicy/profil', $data);
        $this->load->view('footer');
    }

    /**
     * Blokuje dostęp do systemu dla danego użytkownika
     * @param string $id_uzytkownika
     * @param int $blokuj
     */
    public function zablokuj($id_uzytkownika='', $blokuj=1){
        $this->load->view('head');
        $this->load->view('navbar');
        $data=array();
        if(is_numeric($id_uzytkownika)){
            if($this->uzytkownik_model->uzytkownik_id($id_uzytkownika)){
                $data['uzytkownik_info']=$this->uzytkownik_model->uzytkownik_id($id_uzytkownika);
                $this->uzytkownik_model->zablokuj($id_uzytkownika, $blokuj);
            }else{
                $data['info'] = 'Brak użytkownika o id: '.$id_uzytkownika;
            }
        }else{
            $data['info'] = 'Błąd';
        }



        $this->load->view('uzytkownicy/profil', $data);
        $this->load->view('footer');
    }

    /**
     * Wyświetla formularz edycji użytkownika
     * @param $id
     */
    public function edytuj($id){

        $this->load->view('head');
        $this->load->view('navbar');
        $dane_uzytkownika = $this->uzytkownik_model->uzytkownik_id($id);
        $data['dane_uzytkownika'] = $dane_uzytkownika;
        $data['panstwa'] = $this->strona_model->panstwa();
        //var_dump($data['panstwa']);
        $data['role_uzytkownikow'] = $this->strona_model->role_uzytkownikow();
        //var_dump($data['role_uzytkownikow']);
        $this->load->view('uzytkownicy/edytuj', $data);

        $this->load->view('footer');
    }

    /**
     *Jeśli wprowadzone dane w formularzu są poprawne to je aktualizuje dla danego użytkownika
     */
    public function edytuj_check(){
        $this->load->view('head');
        $this->load->view('navbar');
        $data=array();
        $this->form_validation->set_rules('FormImie', 'FormImie', 'trim|xss_clean');
        $this->form_validation->set_rules('FormNazwisko', 'FormNazwisko', 'trim|xss_clean');
        $this->form_validation->set_rules('FormNazwaUzytkownika', 'FormNazwaUzytkownika', 'trim|xss_clean|is_unique[uzytkownicy.nazwa_uzytkownika]|alpha_numeric');
        $this->form_validation->set_rules('FormDataUrodzenia', 'FormDataUrodzenia', 'trim|xss_clean');
        $this->form_validation->set_rules('FormTypUzytkownika[]', 'FormTypUzytkownika', 'trim|xss_clean');
        $this->form_validation->set_rules('FormPanstwo', 'FormPanstwo', 'trim|xss_clean');
        $this->form_validation->set_rules('FormKodPocztowy', 'FormKodPocztowy', 'trim|xss_clean');
        $this->form_validation->set_rules('FormMiejscowosc', 'FormMiejscowosc', 'trim|xss_clean|alpha_numeric');
        $this->form_validation->set_rules('FormNrDomu', 'FormNrDomu', 'trim|xss_clean|alpha_numeric');
        $this->form_validation->set_rules('FormEmail', 'FormEmail', 'trim|xss_clean|valid_email|is_unique[uzytkownicy.email]');
        $this->form_validation->set_rules('idUzytkownika', 'idUzytkownika', 'required|trim|xss_clean|integer');
        if ($this->form_validation->run() == FALSE) {
            //$this->load->view('myform');

            redirect('/uzytkownicy','location');
            $data['info']='Wprowadzono niepoprawne dane';
        }else{
            $dane=array();
            //var_dump(isset($dane));

            $dane['FormImie']=$this->input->post('FormImie');
            $dane['FormNazwisko']=$this->input->post('FormNazwisko');
            $dane['FormNazwaUzytkownika']=$this->input->post('FormNazwaUzytkownika');
            $dane['FormDataUrodzenia']=$this->input->post('FormDataUrodzenia');
            $dane['FormTypUzytkownika']=$this->input->post('FormTypUzytkownika');
            $dane['FormPanstwo']=$this->input->post('FormPanstwo');
            $dane['FormKodPocztowy']=$this->input->post('FormKodPocztowy');
            $dane['FormMiejscowosc']=$this->input->post('FormMiejscowosc');
            $dane['FormNrDomu']=$this->input->post('FormNrDomu');
            $dane['FormNrLokalu']=$this->input->post('FormNrLokalu');
            $dane['idUzytkownika']=$this->input->post('idUzytkownika');

            if($this->uzytkownik_model->uzytkownik_aktualizuj($dane) == false){
                $data['info']='Wprowadzono niepoprawne dane';
            }else{
                $data['info']='Poprawnie zaaktualizowane dane';
            }

        }
        $this->load->view('uzytkownicy/info', $data);
        $this->load->view('footer');
        //echo $this->input->post('FormImie');
    }

    /**
     * akceptuje użytkowników którzy zarejestrowali się przy pomocy rejestracji
     * @param $id_uzytkownika
     */
    public function zaakceptuj($id_uzytkownika){
        //$this->info();

        $this->load->view('head');
        $this->load->view('navbar');

        if(is_numeric($id_uzytkownika)){
            if($this->uzytkownik_model->uzytkownik_id($id_uzytkownika)){
                if($this->uzytkownik_model->uzytkownik_czy_zaakceptowano($id_uzytkownika) || !$this->uzytkownik_model->uzytkownik_akceptuj($id_uzytkownika)){
                    $data['info']='Podany użytkownik został już zaakceptowany';
                }else{
                    $uzytkownik_nick=$this->uzytkownik_model->uzytkownik_id($id_uzytkownika)[0]['nazwa_uzytkownika'];
                    $data['info']='Zaakceptowano użytkownika '.$uzytkownik_nick;
                }
            }else{
                $data['info']='Użytkownik o podanym id nie istnieje';
            }
        }else{
            $data['info']='Błąd';
        }
        $this->load->view('uzytkownicy/info', $data);
        $this->load->view('footer');
    }

    /**
     * Wysyła mail z linkiem do resetowania hasła
     * @param int $email64
     * @param int $username
     */
    public function resetuj_haslo($email64=0, $username=0){
        if($this->uzytkownik_model->resetuj_haslo($email64, $username)){
            echo 'Link do zmiany hasła został wysłany na podany email';
        }else{
            echo'niepowodzenie';
        }
    }

    /**
     *Wyświetla podgląd zalogowanych użytkowników
     */
    public function zalogowani(){
        $this->load->view('head');
        $this->load->view('navbar');
        $liczba_uzytkownikow=$data['liczba_uzytkownikow']=$this->uzytkownicy_model->liczba_uzytkownikow(10);

        $ilosc_na_stronie=15;
        $str_links=$this->pagination_model->config($liczba_uzytkownikow, $ilosc_na_stronie, 'uzytkownicy/zalogowani/');
        $page = $this->uri->segment(3, 1);
        $data["links"] = explode('&nbsp;',$str_links );
        $data['lista_uzytkownikow']=$this->uzytkownicy_model->lista_uzytkownikow(true ,$page, $ilosc_na_stronie, 10);
        $this->load->view('uzytkownicy/podglad', $data);
        $this->load->view('footer');
    }

}
