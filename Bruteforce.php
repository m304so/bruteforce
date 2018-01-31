<?php

namespace Bruteforce;

require_once 'Curl.php';
require_once 'MultiCurl.php';

use Curl\Curl;
use Curl\MultiCurl;

class Bruteforce
{

    public $answer = null;
    public $checkStr;
    public $flag = false;
    public $result = null;
    public $threadsAmount = 1;
    public $url;
    public $wrongAnswer;

    /**
     * 
     * @param string $url
     * @param string $wrongStringAnswer
     */
    public function __construct($url = null, $wrongStringAnswer)
    {
        $this->_setTargetUrl($url);
        $this->_setWrongAnswerPattern($wrongStringAnswer);
    }

    /**
     * Return true when answer is wrong
     * 
     * @param string $haystack
     * @param string $needle
     * @return boolean
     */
    private function _checkWrong($haystack, $needle)
    {
        if (strpos($haystack, $needle) === false) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
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
     * @param string $string
     * @return void
     */
    private function _setWrongAnswerPattern($pattern)
    {
        $this->wrongAnswer = $pattern;
    }

    /**
     * 
     * @param integer $amount
     * @return void
     */
    public function setThreadsAmount($amount)
    {
        $this->threadsAmount = (int) $amount;
    }

    public function start()
    {
        $cnt = 0;
        do {
            $multiCurl = new MultiCurl($this->url, $cnt, $this->threadsAmount);
            $multiCurl->run();

            foreach ($multiCurl->answers as $key => $answer) {
                if (!$this->_checkWrong($answer, $this->wrongAnswer)) {
                    $this->flag = true;
                    $this->result = $key;
                    $this->answer = $answer;
                }
            }

            $cnt = $multiCurl->target;
        } while ($this->flag == false);
    }

}
