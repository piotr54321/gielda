<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mail_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        //$this->load->database();
        $this->load->library('email');

    }
    //SELECT uzytkownicy.id, uzytkownicy.nazwa_uzytkownika, uzytkownicy_portfel.id_waluty, uzytkownicy_portfel.ilosc, waluty.nazwa FROM (((waluty INNER JOIN uzytkownicy_portfel ON waluty.id=uzytkownicy_portfel.id_waluty) INNER JOIN panstwa ON panstwa.id=waluty.id_panstwo_wydajace) INNER JOIN uzytkownicy ON uzytkownicy_portfel.id_uzytkownika=uzytkownicy.id)

    /**
     * Wysyła mail rejestracyjny
     * @param $email
     * @param $hash
     */
    public function rejestracja($email, $hash){
        $config = array(
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'testowymail213@gmail.com',
            'smtp_pass' => 'Jozef987654321',
            'mailtype'  => 'html',
            'charset'   => 'utf-8'
        );
        $this->email->initialize($config);
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");
        $this->email->from('testowymail213@gmail.com', 'Gielda');
        $this->email->to($email);

        $this->email->subject('Gielda Rejestracja');
        $this->email->message('Cześć, aby móc skorzystać z giełdy musisz ustawić swoje hasło, zrobisz to klikając w ten link: <a href="http://77.55.192.255/app/rejestracja/ustaw_haslo/'.$hash.'">link</a>');

        $this->email->send();
    }

}