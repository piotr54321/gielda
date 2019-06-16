<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Logowanie_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    /**
     * Generuje hash
     * @param $string
     */
    public function hashgenerator($string){
		$options = ['cost' => 10,];
		$hash = password_hash($string, PASSWORD_DEFAULT, $options);
		echo $hash;
		echo "</br>";
		if (password_verify('test', $hash)) {
			echo 'Password is valid!';
		} else {
			echo 'Invalid password.';
		}
	}

    /**
     * Dodaje historię logowania
     * @param $id_uzytkownika
     */
    public function zalogowano_info($id_uzytkownika){
		$data=array(
			'id_uzytkownika'=>$id_uzytkownika,
			'czas'=>date("Y-m-d H:i:s"),
			'adres_ip'=>$_SERVER['REMOTE_ADDR'],
			'przegladarka'=>$_SERVER ['HTTP_USER_AGENT']
		);
		$this->db->insert('historia_logowan',$data);
	}

    /**
     * Sprawdza poprawność logowania danego użytkownika
     * @param $nick_lub_email
     * @return array|bool
     */
    public function uzytkownik_hash($nick_lub_email){
		//$q = "nazwa_uzytkownika =" . "'" . $nazwa_uzytkownika . "'";

        $this->db->select('id, haslo');
        $this->db->from('uzytkownicy');
        if(filter_var($nick_lub_email, FILTER_VALIDATE_EMAIL)){
            $this->db->where('email', $nick_lub_email);
        }else{
            if(!preg_match("/^[0-9a-zA-Z]*$/", $nick_lub_email)){
                return FALSE;
            }else{
                $this->db->where('nazwa_uzytkownika', $nick_lub_email);
            }
        }
		$this->db->limit(1);
		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			//return $query->result();
            return $query->result_array();
		} else {
			return false;
		}
		
	}

}