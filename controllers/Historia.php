<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Historia extends MY_Controller {
    function __construct(){
        parent::__construct();
        $this->czy_zalogowano();
        if($this->acl_library->funkcjav2() == false) show_404();
    }

    /**
     * Wyświetla historię akcji użytkownika
     * @param int $user_id
     * @param int $page
     */
    public function akcje($user_id=0, $page=1){
        $this->load->view('head');
        $this->load->view('navbar');

        if($this->acl_library->funkcjav2('historia_podglad_moderator') == false || $user_id==0){
            $user_id=$this->session->id_uzytkownika;
        }
        $page = $this->uri->segment(4, 1);
        $data['page'] = $page;
        $ilosc_na_stronie=15;

        $dane_akcje=$this->uzytkownik_historia->uzytkownik_akcje($user_id, true, $page, $ilosc_na_stronie);
        $data['akcje'] = $dane_akcje['akcje'];
        $data['liczba'] = $dane_akcje['liczba'];
        if($data['liczba'] == 0){
            $data['info'] = 'Brak historii logowań';
        }
        if (!$dane_akcje){
            $data['info'] = 'Brak takiej strony w historii akcji';
        }
        //var_dump($data['info']);


        $str_links=$this->pagination_model->config($data['liczba'], $ilosc_na_stronie, 'historia/akcje/'.$user_id.'/', 4);

        $data["links"] = explode('&nbsp;',$str_links );
        $data['uzytkownik']=$this->uzytkownik_model->uzytkownik_id($user_id);
        $this->load->view('historia/akcje.php', $data);
        $this->load->view('footer');
    }

    /**
     * Wyświetla historię walut użytkownika
     * @param int $user_id
     * @param int $page
     */
    public function waluty($user_id=0, $page=1){
        $this->load->view('head');
        $this->load->view('navbar');

        if($this->acl_library->funkcjav2('historia_podglad_moderator') == false || $user_id==0){
            $user_id=$this->session->id_uzytkownika;
        }
        $page = $this->uri->segment(4, 1);
        $data['page'] = $page;
        $ilosc_na_stronie=15;

        $dane_akcje=$this->uzytkownik_historia->uzytkownik_waluty($user_id, true, $page, $ilosc_na_stronie);
        $data['waluty'] = $dane_akcje['waluty'];
        $data['liczba'] = $dane_akcje['liczba'];
        if($data['liczba'] == 0){
            $data['info'] = 'Brak historii walut';
        }
        if (!$dane_akcje){
            $data['info'] = 'Brak takiej strony w historii walut';
        }
        //var_dump($data['info']);


        $str_links=$this->pagination_model->config($data['liczba'], $ilosc_na_stronie, 'historia/waluty/'.$user_id.'/', 4);

        $data["links"] = explode('&nbsp;',$str_links );
        $data['uzytkownik']=$this->uzytkownik_model->uzytkownik_id($user_id);
        $this->load->view('historia/waluty', $data);
        $this->load->view('footer');
    }

    /**
     * Wyświetla historię logowań użytkownika
     * @param int $user_id
     * @param int $page
     */
    public function logowania($user_id=0, $page=1){
        $this->load->view('head');
        $this->load->view('navbar');
        if($this->acl_library->funkcjav2('historia_podglad_moderator') == false || $user_id==0){
            $user_id=$this->session->id_uzytkownika;
        }
        $page = $this->uri->segment(4, 1);
        $data['page'] = $page;
        $ilosc_na_stronie=15;
        $danelogowania=$this->uzytkownik_historia->uzytkownik_logowania($user_id, true, $page, $ilosc_na_stronie);
        $data['logowania']=$danelogowania['query'];
        $data['liczba']=$danelogowania['liczba'];
        if($data['liczba'] == 0){
            $data['info'] = 'Brak historii logowań';
        }elseif (!$data['logowania']){
            $data['info'] = 'Brak takiej strony w historii logowań';
        }
        $str_links=$this->pagination_model->config($data['liczba'], $ilosc_na_stronie, 'historia/logowania/'.$user_id.'/', 4);

        $data["links"] = explode('&nbsp;',$str_links );
        $data['uzytkownik']=$this->uzytkownik_model->uzytkownik_id($user_id);
        //var_dump($data['uzytkownik']);

        //var_dump($this->uzytkownik_historia->uzytkownik_logowania($this->session->id_uzytkownika)['liczba']);
        $this->load->view('historia/logowania', $data);
        $this->load->view('footer');
    }

    /*public function akcje_podsumowanie(){
        $this->load->view('head');
        $this->load->view('navbar');
        $data['uzytkownik']=$this->uzytkownik_model->uzytkownik_id($this->session->id_uzytkownika);
        $data['akcje']=$this->uzytkownik_historia->uzytkownik_akcje($this->session->id_uzytkownika);
        $this->load->view('historia/akcje.php', $data);
        $this->load->view('footer');
    }*/

    /**
     *Wyświetla historię walut wszystkich użytkowników
     */
    public function waluty_wszystkie(){
        $this->load->view('head');
        $this->load->view('navbar');
        $page = $this->uri->segment(3, 1);
        $data['page'] = $page;
        $ilosc_na_stronie=15;

        $dane_akcje=$this->uzytkownik_historia->uzytkownik_waluty(0, true, $page, $ilosc_na_stronie);
        $data['waluty'] = $dane_akcje['waluty'];
        $data['liczba'] = $dane_akcje['liczba'];
        if($data['liczba'] == 0){
            $data['info'] = 'Brak historii walut';
        }
        if (!$dane_akcje){
            $data['info'] = 'Brak takiej strony w historii akcji';
        }
        //var_dump($data['info']);


        $str_links=$this->pagination_model->config($data['liczba'], $ilosc_na_stronie, 'historia/waluty_wszystkie/', 3);

        $data["links"] = explode('&nbsp;',$str_links );
        //$data['uzytkownik']=$this->uzytkownik_model->uzytkownik_id($user_id);
        $this->load->view('historia/waluty.php', $data);
        $this->load->view('footer');
    }

    /**
     *Wyświetla historię akcji wszystkich użytkowników
     */
    public function akcje_wszystkie(){
        $this->load->view('head');
        $this->load->view('navbar');
        $page = $this->uri->segment(3, 1);
        $data['page'] = $page;
        $ilosc_na_stronie=15;

        $dane_akcje=$this->uzytkownik_historia->uzytkownik_akcje(0, true, $page, $ilosc_na_stronie);
        $data['akcje'] = $dane_akcje['akcje'];
        $data['liczba'] = $dane_akcje['liczba'];
        if($data['liczba'] == 0){
            $data['info'] = 'Brak historii logowań';
        }
        if (!$dane_akcje){
            $data['info'] = 'Brak takiej strony w historii akcji';
        }
        //var_dump($data['info']);


        $str_links=$this->pagination_model->config($data['liczba'], $ilosc_na_stronie, 'historia/akcje_wszystkie/', 3);

        $data["links"] = explode('&nbsp;',$str_links );
        //$data['uzytkownik']=$this->uzytkownik_model->uzytkownik_id($user_id);
        $this->load->view('historia/akcje.php', $data);
        $this->load->view('footer');
    }

    /**
     *Wyświetla historię logowań wszystkich użytkowników
     */
    public function logowania_wszystkie(){
        $this->load->view('head');
        $this->load->view('navbar');
        $page = $this->uri->segment(3, 1);
        $data['page'] = $page;
        $ilosc_na_stronie=15;
        $danelogowania=$this->uzytkownik_historia->uzytkownik_logowania(0, true, $page, $ilosc_na_stronie);
        $data['logowania']=$danelogowania['query'];
        $data['liczba']=$danelogowania['liczba'];
        if($data['liczba'] == 0){
            $data['info'] = 'Brak historii logowań';
        }elseif (!$data['logowania']){
            $data['info'] = 'Brak takiej strony w historii logowań';
        }
        $str_links=$this->pagination_model->config($data['liczba'], $ilosc_na_stronie, 'historia/logowania_wszystkie/', 3);

        $data["links"] = explode('&nbsp;',$str_links );
        //$data['uzytkownik']=$this->uzytkownik_model->uzytkownik_id($user_id);
        //var_dump($data['uzytkownik']);

        //var_dump($this->uzytkownik_historia->uzytkownik_logowania($this->session->id_uzytkownika)['liczba']);
        $this->load->view('historia/logowania', $data);
        $this->load->view('footer');
    }
}
