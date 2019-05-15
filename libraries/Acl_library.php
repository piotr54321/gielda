<?php

class Acl_library {

    protected $CI;

    // We'll use a constructor, as you can't directly call a function
    // from a property definition.
    public function __construct()
    {
        // Assign the CodeIgniter super-object
        $this->CI =& get_instance();
        $this->CI->load->library('session');
        $this->CI->load->helper('url');
        $this->CI->load->database();
        $this->CI->load->model('uzytkownik_model');
    }

    public function url(){
        echo current_url();
    }

    /**
     * Aktualizuje sesję
     * @param int $url np. home/index
     * @return bool
     */

    public function session_update(){
       // var_dump($this->session->id_uzytkownika);
        if(empty($url)){
            $url = $this->CI->router->fetch_class().'/'.$this->CI->router->fetch_method();
        }
        $id_uzytkownika=$this->CI->session->userdata('id_uzytkownika');
        if($id_uzytkownika==NULL) $id_uzytkownika=0;
        if($id_uzytkownika == 0){
            $this->CI->session->set_userdata(array('typ_uzytkownika'=>0));
        }else{
            //$dane_uzytkownika = $this->CI->uzytkownik_model->uzytkownik_id($id_uzytkownika);
            //var_dump($dane_uzytkownika);
            $this->CI->session->set_userdata(array('typ_uzytkownika'=>$this->uzytkownik_role($url,$id_uzytkownika)));
            $this->CI->uzytkownik_model->uzytkownik_aktualizuj_kolumne($id_uzytkownika, "czas_dostepu", time());
        }

    }

    /**
     * Nie używane
     * @param int $url
     * @return bool
     */
    public function funkcja($url=0){
        //echo $url;
        if(empty($url)){
            $url = $this->CI->router->fetch_class().'/'.$this->CI->router->fetch_method();
        }
        //echo $url;
        //echo"test";
        //echo $this->CI->uri->ruri_string();
        //echo $this->CI->uri->segment('1');
        //$url = $this->CI->router->fetch_class().'/'.$this->CI->router->fetch_method();
        $sesja=$this->CI->session->userdata('zalogowano');
        $id_uzytkownika=$this->CI->session->userdata('id_uzytkownika');
        $typ_uzytkownika=$this->CI->session->userdata('typ_uzytkownika');
        if($sesja==NULL) $sesja=0;
        if($id_uzytkownika==NULL) $id_uzytkownika=0;
        if($typ_uzytkownika==NULL) $typ_uzytkownika=0;
        //echo $typ_uzytkownika;
        $this->CI->db->select('*');
        $this->CI->db->from('strona_funkcje');
        $this->CI->db->where('nazwa', $url);

        $this->CI->db->where('logowanie <=', $sesja);
        //$q = $this->CI->db->get();

        //if ($q->num_rows() > 0) {
        if ($this->CI->db->count_all_results() > 0){
            $this->CI->db->select('zezwol');
            $this->CI->db->from('uzytkownicy_funkcje');
            $this->CI->db->join('strona_funkcje','strona_funkcje.id=uzytkownicy_funkcje.id_funkcji');
            $this->CI->db->where('id_uzytkownika', $id_uzytkownika);
            $this->CI->db->where('strona_funkcje.nazwa', $url);
            $this->CI->db->limit(1);
            $query=$this->CI->db->get();
            if ($query->num_rows() == 1) {
                foreach ($query->result() as $row)
                {
                    $zezwol = $row->zezwol;
                }
                //echo $zezwol;
                if($zezwol==0){
                    //show_404();
                    return false;
                }else{
                    //Można korzystać
                    return true;
                }

            }else{
                $this->CI->db->select('*');
                $this->CI->db->from('strona_funkcje');
                $this->CI->db->where('nazwa', $url);
                $this->CI->db->where('logowanie <=', $sesja);
                $this->CI->db->where('ranga_domyslna <=', $typ_uzytkownika);
                //$query = $this->CI->db->get();

                //if($query->num_rows()==0){
                if($this->CI->db->count_all_results()==0){
                    //show_404();
                    //echo "y";
                    return false;
                }else{
                    //Można korzystać
                    //echo $url;
                    //echo "x";
                    return true;
                }

            }

            //if()
        }else{
            //show_404();
            return false;
        }
        /*if($this->session->userdata('zalogowano')==1){
            $db->select('');
        }*/

    }

    /**
     * Sprawdza czy dana funkcja wymaga logowania
     * @param $url
     * @return bool
     */
    public function czy_wymaga_logowania($url){
        $this->CI->db->select('logowanie');
        $this->CI->db->from('strona_funkcje');
        $this->CI->db->where('nazwa', $url);
        $this->CI->db->where('logowanie', 1);
        if($this->CI->db->count_all_results() > 0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /**
     * Sprawdza czy może zezwolić wykonać daną funkcję danemu użytkownikowi
     * @param $url
     * @param null $id_uzytkownika
     * @return bool
     */
    public function uzytkownik_funkcja_zezwol($url, $id_uzytkownika=NULL){
        if(empty($id_uzytkownika)){
            $id_uzytkownika=$this->CI->session->userdata('id_uzytkownika');
            if($id_uzytkownika==NULL) $id_uzytkownika=0;
        }
        $this->CI->db->select('zezwol');
        $this->CI->db->from('uzytkownicy_funkcje');
        $this->CI->db->join('strona_funkcje','strona_funkcje.id=uzytkownicy_funkcje.id_funkcji');
        $this->CI->db->where('id_uzytkownika', $id_uzytkownika);
        $this->CI->db->where('strona_funkcje.nazwa', $url);
        $query=$this->CI->db->get();
        if($query->num_rows() == 1){
            foreach ($query->result_array() as $row){
                $zezwol = $row['zezwol'];
            }
            if($zezwol==0){
                return FALSE;
            }else{
                return TRUE;
            }
        }else{
            return $this->uzytkownik_role($url, $id_uzytkownika=NULL);
        }
    }

    /**
     * Zwraca id ról użytkownika
     * @param $url
     * @param $id_uzytkownika
     * @return bool
     */
    public function uzytkownik_role($url, $id_uzytkownika){
        if(empty($id_uzytkownika)){
            $id_uzytkownika=$this->CI->session->userdata('id_uzytkownika');
            if($id_uzytkownika==NULL) $id_uzytkownika=0;
        }
        if($id_uzytkownika != 0) {
            //var_dump($id_uzytkownika);
            $this->CI->db->select('strona_role.typ_uzytkownika');
            $this->CI->db->from('uzytkownicy_role');
            $this->CI->db->join('strona_role', 'uzytkownicy_role.id_roli=strona_role.id');
            $this->CI->db->where('uzytkownicy_role.id_uzytkownika', $id_uzytkownika);
            $query = $this->CI->db->get();
            $uzytkownik_role = $query->result_array();
            foreach ($uzytkownik_role as $item) {
                if ($item['typ_uzytkownika'] == 0) {
                    show_error('Administrator musi potwierdzić Twoje konto');
                }
            }
            //print_r($uzytkownik_role);
            array_push($uzytkownik_role, array('typ_uzytkownika' => '1'));
            //print_r($uzytkownik_role);
            //array_column($uzytkownik_role, 'id_roli');
            //array_push($uzytkownik_role, $data['one'] = 1);
            //var_dump($uzytkownik_role);
            //$this->CI->db->where
            //exit;
        }else{
            $uzytkownik_role=array(array('typ_uzytkownika' => '0'));
        }
        //var_dump(array_column($uzytkownik_role, 'typ_uzytkownika'));
        //exit;

        $this->CI->db->select('ranga_domyslna');
        $this->CI->db->from('strona_funkcje');
        $this->CI->db->where('nazwa', $url);
        $this->CI->db->where_in('ranga_domyslna', array_column($uzytkownik_role, 'typ_uzytkownika'));
        if($this->CI->db->count_all_results() > 0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /**
     * Zwraca nazwy ról użytkownika
     * @param $id_uzytkownika
     * @return array
     */
    public function uzytkownik_role_nazwy($id_uzytkownika){
        $this->CI->db->select('strona_role.nazwa_typu');
        $this->CI->db->from('uzytkownicy_role');
        $this->CI->db->join('strona_role', 'uzytkownicy_role.id_roli=strona_role.id');
        $this->CI->db->where('uzytkownicy_role.id_uzytkownika', $id_uzytkownika);
        $query=$this->CI->db->get();
            $uzytkownik_role=$query->result_array();
            array_push($uzytkownik_role, array('nazwa_typu' => 'Inwestor'));
            return $uzytkownik_role;
    }

    /**
     * Sprawdza czy może zezwolić wykonać daną funkcję danemu użytkownikowi
     * @param int $url
     * @param null $id_uzytkownika
     * @return bool
     */
    public function funkcjav2($url=0, $id_uzytkownika=NULL){
        if(empty($url)){
            $url = $this->CI->router->fetch_class().'/'.$this->CI->router->fetch_method();
        }
        if(empty($id_uzytkownika)){
            $id_uzytkownika=$this->CI->session->userdata('id_uzytkownika');
            if($id_uzytkownika==NULL) $id_uzytkownika=0;
        }

        if ($this->czy_wymaga_logowania($url)){
            return $this->uzytkownik_funkcja_zezwol($url, $id_uzytkownika);
        }else{
            return TRUE;
        }
    }

}