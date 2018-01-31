<?php

namespace Curl;

class MultiCurl
{

    public $active = null;
    public $answers = [];
    public $multiCurl;
    public $target = null;
    public $threads = [];
    public $threadsAmount;
    public $url;

    /**
     * 
     * @param string $url
     * @param integer $position
     * @param integer $threadsAmount
     */
    public function __construct($url = null, $position = null, $threadsAmount = 1)
    {
        $this->multiCurl = curl_multi_init();

        $this->_setTargetUrl($url);
        $this->_setTargetPosition((int) $position);
        $this->_setThreadsAmount((int) $threadsAmount);
    }

    /**
     * 
     * @param integer $position
     * @return void
     */
    private function _setTargetPosition($position)
    {
        $this->target = $position;
    }

    /**
     * 
     * @param string $url
     * @return void
     */
    private function _setTargetUrl($url)
    {
        $this->url = $url;
    }

    /**
     * 
     * @param integer $amount
     * @return void
     */
    private function _setThreadsAmount($amount)
    {
        $this->threadsAmount = $amount;
    }

    public function run()
    {
        $count = $this->target + $this->threadsAmount;
        for ($this->target; $this->target < $count; $this->target++) {
            $Curl = new Curl($this->url);
            $Curl->addPostFields(['code' => $this->target]);
            curl_multi_add_handle($this->multiCurl, $Curl->curl);
            $this->threads[$this->target] = $Curl->curl;
        }

        do {
            $mrc = curl_multi_exec($this->multiCurl, $this->active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($this->active && $mrc == CURLM_OK) {
            if (curl_multi_select($this->multiCurl) == -1) {
                usleep(100);
            }

            do {
                $mrc = curl_multi_exec($this->multiCurl, $this->active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }

        foreach ($this->threads as $key => $thread) {
            $this->answers[$key] = curl_multi_getcontent($thread);
            curl_multi_remove_handle($this->multiCurl, $thread);
        }

        curl_multi_close($this->multiCurl);
    }

}
