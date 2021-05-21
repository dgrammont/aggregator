<?php

/** fichier		 : api/ThingHTTP.class.php
  description  : deux Classes ThingHTTP & ThingHTTPException pour les objets ThingHTTP
  Ces objets permettent de faire des requetes http
  La classe thingHTTPException hérite de la classe Exception
  author       : Lycée Touchard Le Mans

 * */

namespace Aggregator\Support;

class ThingHTTPException extends \Exception {
    
}

class ThingHTTP {

    // Constructeur
    function __construct($bdd, $id) {

        try {
            $sql = "SELECT * FROM thinghttps WHERE id = " . $id;
            $stmt = $bdd->query($sql);
            if ($request = $stmt->fetchObject()) {
                $this->curl = curl_init();

                curl_setopt_array($this->curl,
                        array(
                            CURLOPT_URL => $request->url,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_CUSTOMREQUEST => $request->method,
                            CURLOPT_HTTPHEADER => array(
                                "cache-control: no-cache"
                            ),
                        )
                );
                if ($request->http_version == "1.1")
                    curl_setopt($this->curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                if ($request->http_version == "1.0")
                    curl_setopt($this->curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);

                if ($request->method == "POST")
                    curl_setopt($this->curl, CURLOPT_POSTFIELDS, $request->body);

                // une authentification HTTP
                if ($request->auth_name != "" && $request->auth_pass != "") {
                    curl_setopt($this->curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                    curl_setopt($this->curl, CURLOPT_USERPWD, "$request->auth_name:$request->auth_pass");
                    curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
                }
            } else {
                // La requète retourne un objet vide !! id n'existe pas
                $this->curl = NULL;
                throw new thingHTTPException("Constructor thingHTTP Error", 1);
            }
        } catch (\PDOException $ex) {
            throw new thingHTTPException($ex->getMessage(), 2);
        }
    }

    /**
     * destructeur ferme la connexion réseau
     */
    function __destruct() {
        //print "Destroying " . __CLASS__ . "\n";
        if ($this->curl != NULL) {
            curl_close($this->curl);
        }
    }

    // déclaration des propriétés
    private $curl;
    private $response;
    private $err;

    /**
     * envoie la requête http sur le réseau
     *
     * @return string $response  la réponse obtenue
     */
    public function send_request() {
        if ($this->curl != NULL) {
            $this->response = curl_exec($this->curl);
            $this->err = curl_error($this->curl);
            if ($this->err != "")
                throw new thingHTTPException($this->err, 2);
            return $this->response;
        }
    }

    // Méthode pour obtenir l'erreur
    public function get_error() {
        return $this->err;
    }

}
