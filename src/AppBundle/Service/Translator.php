<?php

namespace AppBundle\Service;
use Unirest;

class Translator
{
    const KEY = 'AIzaSyCWf-Hl9zC0FdmLpJShaXPCUgK8UbighfQ';
    private $targetLanguage;

    /**
     * @param string $sentence
     * @param string $outputLanguage
     * @return null|string
     */
    public function detectLanguageAndTranslate(string $sentence, string $outputLanguage) : ?string
    {
        $headers = array('Accept' => 'application/json');
        $query = array('q' => $sentence, 'key' => self::KEY);

        $response = Unirest\Request::get('https://translation.googleapis.com/language/translate/v2/detect',$headers,$query);

        $result = $response->body;

        foreach($result as $data) {
            foreach ($data as $detections) {
                foreach ($detections as $values) {
                    foreach ($values as $result) {
                        $this->targetLanguage = $result->language;
                        return $this->translate($sentence, $outputLanguage, $this->targetLanguage);
                    }
                }
            }
        }
    }

    /**
     * @param string $string
     * @param int $max_characters
     * @return string
     */
    public function packPhrase(string $string, int $max_characters) : string
    {
        $arr = explode(" ",$string);
        $result_arr = array();
        $return_array_chars_count = 0;

        usort($arr, function($a, $b) {
            return strlen($a) <=> strlen($b);
        });

        foreach ($arr as $value) {
            if(($return_array_chars_count + strlen($value)) <= $max_characters) {
                $result_arr[] = $value;
                $return_array_chars_count += strlen($value);
            }
        }

        return implode(" ",$result_arr);
    }

    /**
     * @param string $string
     * @param string $outputLanguage
     * @param string $sourceLanguage
     * @return null|string
     */
    private function translate(string $string, string $outputLanguage, string $sourceLanguage) : ?string
    {
        $headers = array('Accept' => 'application/json');
        $query = array('q' => $string, 'source' => $sourceLanguage ,'target' => $outputLanguage, 'format' => 'text' , 'key' => self::KEY);

        $response = Unirest\Request::post('https://translation.googleapis.com/language/translate/v2',$headers,$query);

        $result = $response->body;

        foreach($result as $data) {
            foreach ($data as $translations) {
                foreach ($translations as $array) {
                    return $array->translatedText;
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getTargetLanguage() : string
    {
        return $this->targetLanguage;
    }
}