<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pagination_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        //$this->load->database();
        $this->load->library('pagination');
    }
    //SELECT uzytkownicy.id, uzytkownicy.nazwa_uzytkownika, uzytkownicy_portfel.id_waluty, uzytkownicy_portfel.ilosc, waluty.nazwa FROM (((waluty INNER JOIN uzytkownicy_portfel ON waluty.id=uzytkownicy_portfel.id_waluty) INNER JOIN panstwa ON panstwa.id=waluty.id_panstwo_wydajace) INNER JOIN uzytkownicy ON uzytkownicy_portfel.id_uzytkownika=uzytkownicy.id)

    /**
     * Konfiguracja Biblioteki Pagination
     * @param $rows
     * @param int $ilosc
     * @param string $url
     * @param int $segment
     * @return string
     */
    public  function config($rows, $ilosc=10, $url='', $segment=0){
        $config = array();
        //$data['url_keyword'] = url_title($keyword, '_');
        $config['base_url'] = base_url().$url;
        $config["total_rows"] = $rows;
        $config["per_page"] = $ilosc;
        $config['uri_segment'] = $segment==0?'3':$segment;
        $config['first_url'] = base_url().$url.'1';
        //$config['page_query_string'] = TRUE;
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = $rows;
        //$config['query_string_segment'] = 'page';

        $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination">';
        $config['full_tag_close']   = '</ul></nav></div>';
        $config['num_tag_open']     = '<li class="page-item"><span class="page-link" style="color: #0b2e13">';
        $config['num_tag_close']    = '</span></li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link" style="color: #0b2e13">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open']    = '<li class="page-item"><span class="page-link" style="color: #0b2e13">';
        $config['next_tag_close']  = '<span aria-hidden="true"></span></span></li>';
        $config['prev_tag_open']    = '<li class="page-item"><span class="page-link" style="color: #0b2e13">';
        $config['prev_tag_close']  = '</span></li>';
        $config['first_tag_open']   = '<li class="page-item"><span class="page-link" style="color: #0b2e13">';
        $config['first_tag_close'] = '</span></li>';
        $config['last_tag_open']    = '<li class="page-item"><span class="page-link" style="color: #0b2e13">';
        $config['last_tag_close']  = '</span></li>';


        $this->pagination->initialize($config);
        $str_links = $this->pagination->create_links();
        //$page = $this->uri->segment(3, 0);


        return $str_links;
    }

}