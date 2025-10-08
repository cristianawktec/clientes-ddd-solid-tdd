<?php

/**
 * Implementa o CURL e configura o endereço do webservice 
 * de consulta o CEP via API viacep.
 *
 * author Cristian Marques
 * data 02/06/2020
 */
class Curl {

    // endereco do webservice
    private $endereco_ws = "http://viacep.com.br/ws";
    private $url_json;
    private $url_xml;

    public function consulta($cep) {

        /*conforme api da viacep 
        * viacep.com.br/ws/01001000/json/
        * para retornar um JSON */
        $this->url_json = $this->endereco_ws . '/' . $cep . '/json/';

        //retorno em xml
        $this->url_xml = $this->endereco_ws . '/' . $cep . '/xml/';

        // abre a conexão
        $ch = curl_init();

        // define url
        curl_setopt($ch, CURLOPT_URL, $this->url_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // executa o post
        $resultado = curl_exec($ch);

        if (curl_error($ch)) {
            echo 'Erro:' . curl_error($ch);
            return false;
        }
        return $resultado;

        // fecha a conexão
        curl_close($ch);

    }

}
