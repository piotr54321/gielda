<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ustawienia extends MY_Controller {
    function __construct(){
        parent::__construct();
        $this->czy_zalogowano();
        if($this->acl_library->funkcjav2() == false) show_404();
    }


    /**
     *Wyświetla formularz edycji adresu
     */
    public function adresy(){
        $this->load->view('head');
        $this->load->view('navbar');

        $dane_uzytkownika = $this->uzytkownik_model->uzytkownik_id($this->session->id_uzytkownika);
        $data['dane_uzytkownika'] = $dane_uzytkownika;

       // foreach($dane_uzytkownika as $wiersz){
            //echo $wiersz['nazwisko'].'</br>';
      //  }
       // exit();

        $data['panstwa'] = $this->strona_model->panstwa();
        //var_dump($data['panstwa']);

        //foreach ($data['panstwa'] as $wiersz){
         //   echo $wiersz['name'].' - '.$wiersz['id'].'</br>';
        //}

        //exit();


        $this->load->view('ustawienia/adresformularz', $data);
        $this->load->view('footer');
    }

    /**
     *Sprawdza dane z formularza i aktualizuje adres
     */
    public function edytuj_adres(){

        $this->load->view('head');
        $this->load->view('navbar');


        $this->form_validation->set_rules('panstwo', 'panstwo', 'trim|xss_clean|required');
        $this->form_validation->set_rules('kod_pocztowy', 'kod_pocztowy', 'trim|xss_clean|required');
        $this->form_validation->set_rules('miejscowosc', 'miejscowosc', 'trim|xss_clean|required');
        $this->form_validation->set_rules('numer_domu', 'numer_domu', 'trim|xss_clean|required');
        $this->form_validation->set_rules('numer_lokalu', 'numer_lokalu', 'trim|xss_clean|required');
        $this->form_validation->set_rules('idAdres', 'idAdres', 'trim|xss_clean|required');
        if ($this->form_validation->run() == FALSE) {
            $data['info']='Błąd, złe dane';
        }else{
            $daneformularz['panstwo']=$this->input->post('panstwo');
            $daneformularz['kod']=$this->input->post('kod_pocztowy');
            $daneformularz['miasto']=$this->input->post('miejscowosc');
            $daneformularz['numer_domu']=$this->input->post('numer_domu');
            $daneformularz['numer_lokalu']=$this->input->post('numer_lokalu');
            $id_adresu=$this->input->post('idAdres');



            if($this->ustawienia_model->aktualizuj_adres($this->session->id_uzytkownika, $id_adresu, $daneformularz)){
                $data['info']='Pomyślnie zaaktualizowano Twój adres';
            }else{

                $data['info']='Coś poszło nie tak';
            }
        }
        $dane_uzytkownika = $this->uzytkownik_model->uzytkownik_id($this->session->id_uzytkownika);
        $data['dane_uzytkownika'] = $dane_uzytkownika;
        $data['panstwa'] = $this->strona_model->panstwa();
        $this->load->view('ustawienia/adresformularz', $data);

        $this->load->view('footer');
    }

    /**
     *Wyświetla formularz edycji danych logowania
     */
    public function dane_logowania(){
        $this->load->view('head');
        $this->load->view('navbar');

        $dane_uzytkownika = $this->uzytkownik_model->uzytkownik_id($this->session->id_uzytkownika);
        $data['dane_uzytkownika'] = $dane_uzytkownika;

        $this->load->view('ustawienia/danelogowaniaformularz', $data);
        $this->load->view('footer');
    }

    /**
     *Sprawdza dane z formularza i aktualizuje dane logowania
     */
    public function edytuj_danelog(){

        $this->load->view('head');
        $this->load->view('navbar');


        $this->form_validation->set_rules('nazwa_uzytkownika', 'nazwa_uzytkownika', 'trim|xss_clean|required|alpha_numeric|is_unique[uzytkownicy.nazwa_uzytkownika]');
        $this->form_validation->set_rules('email', 'email', 'trim|xss_clean|required|valid_email|is_unique[uzytkownicy.email]');
        if ($this->form_validation->run() == FALSE) {
            $data['info']='Błąd, złe dane';
        }else{
            $daneformularza1['nazwa_uzytkownika']=$this->input->post('nazwa_uzytkownika');
            $daneformularza1['email']=$this->input->post('email');
            //$id_adresu=$this->input->post('idAdres');

            if($this->ustawienia_model->aktalizuj_danelogo($this->session->id_uzytkownika, $daneformularza1)){
                $data['info']='Pomyślnie zaaktualizowano Twoje dane';
            }else{

                $data['info']='Coś poszło nie tak';
            }
        }
        $dane_uzytkownika = $this->uzytkownik_model->uzytkownik_id($this->session->id_uzytkownika);
        $data['dane_uzytkownika'] = $dane_uzytkownika;
        $data['role_uzytkownikow'] = $this->strona_model->role_uzytkownikow();
        $this->load->view('ustawienia/danelogowaniaformularz', $data);

        $this->load->view('footer');
    }

    /**
     *Wyświetla formularz edycji danych osobowych
     */
    public function dane_osobowe()
    {
        $this->load->view('head');
        $this->load->view('navbar');

        $dane_uzytkownika = $this->uzytkownik_model->uzytkownik_id($this->session->id_uzytkownika);
        $data['dane_uzytkownika'] = $dane_uzytkownika;

        $this->load->view('ustawienia/daneosoboweformularz', $data);
        $this->load->view('footer');

    }

    /**
     *Sprawdza dane z formularza i aktualizuje dane osobowe
     */
    public function edytuj_daneosob(){

        $this->load->view('head');
        $this->load->view('navbar');


        $this->form_validation->set_rules('imie', 'imie', 'trim|xss_clean|required');
        $this->form_validation->set_rules('nazwisko', 'nazwisko', 'trim|xss_clean|required');
        if ($this->form_validation->run() == FALSE) {
            $data['info']='Błąd, złe dane';
        }else{
            $danezformularza2['imie']=$this->input->post('imie');
            $danezformularza2['nazwisko']=$this->input->post('nazwisko');


            if($this->ustawienia_model->aktualizuj_daneosob($this->session->id_uzytkownika, $danezformularza2)){
                $data['info']='Pomyślnie zaaktualizowano Twoje dane';
            }else{

                $data['info']='Coś poszło nie tak';
            }
        }
        $dane_uzytkownika = $this->uzytkownik_model->uzytkownik_id($this->session->id_uzytkownika);

        $data['dane_uzytkownika'] = $dane_uzytkownika;
        $data['ustawienie_daneosob'] = $this->ustawienia_model->ustawienie_daneosob();
        $this->load->view('ustawienia/daneosoboweformularz', $data);
        $this->load->view('footer');

    }

    /**
     *Wyświetla formularz dodawnia nowego adresu
     */
    public function dodajadres(){
        $this->load->view('head');
        $this->load->view('navbar');

        $dane_uzytkownika = $this->uzytkownik_model->uzytkownik_id($this->session->id_uzytkownika);
        $data['dane_uzytkownika'] = $dane_uzytkownika;

        // foreach($dane_uzytkownika as $wiersz){
        //echo $wiersz['nazwisko'].'</br>';
        //  }
        // exit();

        $data['panstwa'] = $this->strona_model->panstwa();
        //var_dump($data['panstwa']);

        //foreach ($data['panstwa'] as $wiersz){
        //   echo $wiersz['name'].' - '.$wiersz['id'].'</br>';
        //}

        //exit();


        $this->load->view('ustawienia/adresformularzdodaj', $data);
        $this->load->view('footer');
    }

    /**
     *Sprawdza dane z formularza i dodaje nowy adres
     */
    public function dodaj_adres(){
        $this->load->view('head');
        $this->load->view('navbar');

        $dane_uzytkownika = $this->uzytkownik_model->uzytkownik_id($this->session->id_uzytkownika);
        $data['dane_uzytkownika'] = $dane_uzytkownika;

        $data['panstwa'] = $this->strona_model->panstwa();

        $this->form_validation->set_rules('panstwo', 'panstwo', 'trim|xss_clean|required');
        $this->form_validation->set_rules('kod_pocztowy', 'kod_pocztowy', 'trim|xss_clean|required');
        $this->form_validation->set_rules('miejscowosc', 'miejscowosc', 'trim|xss_clean|required');
        $this->form_validation->set_rules('numer_domu', 'numer_domu', 'trim|xss_clean|required');
        $this->form_validation->set_rules('numer_lokalu', 'numer_lokalu', 'trim|xss_clean|required');
        $this->form_validation->set_rules('czy_glowny', 'czy_glowny', 'trim|xss_clean|required');
        if(!$this->form_validation->run()){
            $data['info']='Błędne dane';
        }else{
            $daneformularz['panstwo_zamieszkania']=$this->input->post('panstwo');
            $daneformularz['kod_pocztowy']=$this->input->post('kod_pocztowy');
            $daneformularz['miejscowosc']=$this->input->post('miejscowosc');
            $daneformularz['numer_domu']=$this->input->post('numer_domu');
            $daneformularz['numer_lokalu']=$this->input->post('numer_lokalu');
            $daneformularz['adres_glowny']=$this->input->post('czy_glowny');
            $daneformularz['uzytkownicy_id']=$this->session->id_uzytkownika;
            if($this->ustawienia_model->dodaj_adres($daneformularz)){
                $data['info']='Poprawnie dodano adres';
            }else{
                $data['info']='Błąd';
            }
        }

        $this->load->view('ustawienia/adresformularzdodaj', $data);
        $this->load->view('footer');
    }

    /**
     *Wyświetla podgląd "moich" adresów
     */
    public function mojeadresy(){
        $this->load->view('head');
        $this->load->view('navbar');

        $dane_uzytkownika = $this->uzytkownik_model->uzytkownik_id($this->session->id_uzytkownika);
        $data['lista_adresow'] = $this->ustawienia_model->adresy_uzytkownika($this->session->id_uzytkownika);
        //var_dump($data['lista_adresow']);
        $data['dane_uzytkownika'] = $dane_uzytkownika;

        $this->load->view('uzytkownicy/mojeadresy', $data);
        $this->load->view('footer');
    }

    public function ustaw_adres_glowny(){
        $this->form_validation->set_rules('id_adresu','id_adresu','required');
        if($this->form_validation->run()){
            $id_adresu = $this->input->post('id_adresu');
            if($this->ustawienia_model->ustaw_adres_glowny($this->session->id_uzytkownika, $id_adresu)){
                $data['info'] = 'Ustawiono adres główny';
            }else{
                $data['info'] = 'Błąd w ustawieniu adresu głównego';
            }
        }else{
            $data['info'] = 'Błąd w ustawieniu adresu głównego';
        }

        $this->mojeadresy();
    }

}
