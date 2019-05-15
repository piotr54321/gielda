<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portfel extends MY_Controller {
    function __construct(){
        parent::__construct();
        $this->czy_zalogowano();
        if($this->acl_library->funkcjav2() == false) show_404();
    }

    /**
     *Wyświetla zawartośc portfelu użytkownika
     */
    public function index(){
        $this->load->view('head');
        $this->load->view('navbar');
        if($this->portfel_model->uzytkownik_walutyv2($this->session->id_uzytkownika)==false) {
            $this->load->view('portfel/podglad', array('brak_walut' => 'Nie masz kupionych żadnych walut przejdź do zakładki waluty'));
        }
        else {
            $data['dane_portfela']=$this->portfel_model->uzytkownik_walutyv2($this->session->id_uzytkownika);
            //var_dump($data);
            $this->load->view('portfel/podglad', $data);
        }
        $this->load->view('footer');
    }

    /**
     *Wyświetla formularz wypłaty środków
     */
    public function wyplata(){
        $this->load->view('head');
        $this->load->view('navbar');
        $data=array();
        $id_waluty=$data['id_waluty']=$this->uri->segment('3');
        $data['dane_portfela']=$this->portfel_model->uzytkownik_walutyv2($this->session->id_uzytkownika, $id_waluty);
        if(!$data['dane_portfela'] || !is_numeric($id_waluty)){
            $data['info']='Nieprawidłowy link';
        }
        $this->load->view('portfel/wyplata',$data);
        $this->load->view('footer');
    }

    /**
     * Jeśli dane wprowadzone w formularzu środków są poprawne to je wypłaca
     * @param int $id_transakcji
     */
    public function wyplata_check($id_transakcji=0){
        $this->load->view('head');
        $this->load->view('navbar');
        $data=array();
        if(!$this->portfel_model->id_transakcji_sprawdz($id_transakcji, $this->session->id_uzytkownika)){
            $data['info'] = 'Brak takiej transakcji..';
        }else {
            $this->form_validation->set_rules('iloscWaluty', 'iloscWaluty', 'required|trim|xss_clean|integer');
            $this->form_validation->set_rules('idWaluty', 'idWaluty', 'required|trim|xss_clean|integer');
            if (!$this->form_validation->run()) {
                $data['info'] = 'Niepoprawna wartość pola';
            } else {
                $idWaluty = $this->input->post('idWaluty');
                $iloscWaluty = $this->input->post('iloscWaluty');
                if (!$this->portfel_model->uzytkownik_walutyv2($this->session->id_uzytkownika, $idWaluty)) {
                    $data['info'] = 'Nie posidasz takiej waluty';
                } elseif (!$this->portfel_model->uzytkownik_walutyv2($this->session->id_uzytkownika, $idWaluty, $iloscWaluty)) {
                    $data['info'] = 'Nie posiadasz takiej ilości tej waluty';
                } elseif ($this->portfel_model->uzytkownik_waluty_sprzedaj($this->session->id_uzytkownika, $idWaluty, $iloscWaluty)) {
                    $data['info'] = 'Środki wypłacone prawidłowo';
                } else {
                    $data['info'] = 'Niepowodzenie..';
                }
            }
        }

        $this->load->view('portfel/wyplata', $data);
        $this->load->view('footer');
    }

    /**
     *Wyświetla formularz wpłaty środków
     */
    public function wplata(){
        $this->load->view('head');
        $this->load->view('navbar');
        $data['dane_portfela']=$this->portfel_model->waluta_info(0,1);
        $this->load->view('portfel/wplata', $data);
        $this->load->view('footer');
    }

    /**
     *Czeka na odpowiedź z PayPal, jeśli jest poprawna to doładowywuje konto użytkownika
     */
    public function wplata_check_ipn(){
        $paypalInfo = $this->input->post();
        $data['user_id']     = $paypalInfo['custom'];
        $data['product_id']    = $paypalInfo["item_number"];
        $data['txn_id']    = $paypalInfo["txn_id"];
        $data['payment_gross'] = $paypalInfo["mc_gross"];
        $data['currency_code'] = $paypalInfo["mc_currency"];
        $data['payer_email'] = $paypalInfo["payer_email"];
        $data['payment_status']    = $paypalInfo["payment_status"];
        $data['amount']    = $paypalInfo["amount"];
        $paypalURL = $this->paypal_lib->paypal_url;
        $result     = $this->paypal_lib->curlPost($paypalURL,$paypalInfo);
        //file_put_contents('filename.txt', print_r($data, true));
        if(preg_match("/VERIFIED/i",$result)){
            if ($this->portfel_model->uzytkownik_waluty_kup($data['user_id'], $data['product_id'], $data['payment_gross'])) {
                //return TRUE;
                $data['info'] = 'Środki zostały wpłacone pomyślnie';
            } else {
                $data['info'] = 'Niepowodzenie..';
            }
        }

    }

    /**
     * Jeśli dane z formularza są poprawne to przekierowywuje do strony paypal
     * @param int $id_transakcji
     */
    public function wplata_check($id_transakcji=0){

        $this->load->view('head');
        $this->load->view('navbar');
        $data=array();
        $returnURL = base_url().'portfel/wplata_check/1'; //payment success url
        $cancelURL = base_url().'portfel/wplata_check/2'; //payment cancel url
        $notifyURL = base_url().'portfel/wplata_check_ipn'; //ipn url


        if($id_transakcji==1){
            $data['info'] = 'Transakcja zakończona sukcesem';
        }elseif($id_transakcji==2){
            $data['info'] = 'Transakcja nie powiodła się';
        }elseif(!$this->portfel_model->id_transakcji_sprawdz($id_transakcji, $this->session->id_uzytkownika)){
            $data['info'] = 'Brak takiej transakcji..';
        }else{

            $this->form_validation->set_rules('iloscWaluty', 'iloscWaluty', 'required|trim|xss_clean|integer');
            $this->form_validation->set_rules('id_waluty', 'id_waluty', 'required|trim|xss_clean|integer');
            if (!$this->form_validation->run()) {
                $data['info'] = 'Niepoprawna wartość pola';
            } else {

                $iloscWaluta = $this->input->post('iloscWaluty');
                $idWaluta = $this->input->post('id_waluty');

                $this->paypal_lib->add_field('return', $returnURL);
                $this->paypal_lib->add_field('cancel_return', $cancelURL);
                $this->paypal_lib->add_field('notify_url', $notifyURL);
                $this->paypal_lib->add_field('item_name', 'Wplata');
                $this->paypal_lib->add_field('custom', $this->session->id_uzytkownika);
                $this->paypal_lib->add_field('item_number', $idWaluta);
                $this->paypal_lib->add_field('amount', $iloscWaluta);
                $this->paypal_lib->paypal_auto_form();
            }
                /*
                 if ($this->portfel_model->uzytkownik_waluty_kup($this->session->id_uzytkownika, $idWaluta, $iloscWaluta)) {
                    //return TRUE;
                    $data['info'] = 'Środki zostały wpłacone pomyślnie';
                } else {
                    $data['info'] = 'Niepowodzenie..';
                }
                */

        }



        $data['dane_portfela']=$this->portfel_model->uzytkownik_walutyv2($this->session->id_uzytkownika);
        if(!$data['dane_portfela']){
            $data['brak_walut']='Nie masz kupionych żadnych walut przejdź do zakładki waluty';
        }
        $this->load->view('portfel/podglad', $data);
        $this->load->view('footer');
    }
}
