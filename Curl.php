<?php

namespace Curl;

class Curl
{

    public $curl;
    public $postFields = null;
    public $url;

    /**
     * 
     * @param string $url
     */
    public function __construct($url = null)
    {
        $this->setUrl($url);
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_URL, $this->url);
        curl_setopt($this->curl, CURLOPT_HEADER, false);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
    }

    /**
     * 
     * @param array $data
     * @return void
     */
    public function addPostFields($data = null)
    {
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($data));
    }

    /**
     * 
     * @param string $url
     * @return void
     */
    public function setUrl($url = null)
    {
        $this->url = $url;
    }

}
