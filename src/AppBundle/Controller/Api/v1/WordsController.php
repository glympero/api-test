<?php

namespace AppBundle\Controller\Api\v1;

use AppBundle\Controller\BaseController;
use AppBundle\Service\Logger;
use AppBundle\Service\Translator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class WordsController extends BaseController
{
    private $input;
    /**
     * @Route("most_words", name="most_words")
     * @Method("POST")
     */
    public function wordsAction(Request $request)
    {
        $newLogger = new Logger();
        $start = microtime(true);
        $this->getJsonInput($request->getContent());

        $translator = new Translator();

        $translatedSentence = @$translator->detectLanguageAndTranslate($this->input['sentence'], $this->input['output_language']);
        if($translatedSentence === null) {
            $this->throw400("Cannot translate to same language.");
        }

        $final_string = @$translator->packPhrase($translatedSentence, $this->input['max_characters']);
        if($final_string === "") {
            $this->throw400("Max chars number is too low");
        }

        $final_string_translated = $translator->detectLanguageAndTranslate($final_string, $translator->getTargetLanguage());
        if($final_string_translated === null) {
            $this->throw400("Cannot translate to same language");
        }

        $time_elapsed_secs = microtime(true) - $start;
        $data = $this->serializeResponse($final_string, $time_elapsed_secs, $final_string_translated);

        $newLogger->logAPI($this->input, $data, 'most_words');

        $response = $this->createApiResponse($data, 200);

        return $response;
    }

    /**
     * @param $sentenceQuery
     * @param $output_languageQuery
     * @param $max_characters
     */
    private function validateInputs($sentenceQuery, $output_languageQuery, $max_characters)
    {
        if (!$sentenceQuery || $sentenceQuery === '' || !is_string($sentenceQuery) || is_numeric(($sentenceQuery))) {
            $this->throw422();
        }

        if (!$output_languageQuery || $output_languageQuery === '' || !is_string($output_languageQuery) || is_numeric(($output_languageQuery))) {
            $this->throw422();
        }

        if (!$max_characters || $max_characters < 1 || !is_int($max_characters)) {
            $this->throw422();
        }
    }

    /**
     * @param $body
     */
    private function getJsonInput($body)
    {
        $inputs = json_decode($body, true);
        $this->input = $inputs;

        if(!isset($this->input['sentence']) || !isset($this->input['output_language']) || !isset($this->input['max_characters'])) {
            $this->throw400();
        }

        $this->validateInputs($this->input['sentence'], $this->input['output_language'], $this->input['max_characters']);
    }

    /**
     * @param string $final_string
     * @param float $time_elapsed_secs
     * @param string $final_string_translated
     * @return array
     */
    private function serializeResponse(string $final_string, float $time_elapsed_secs, string $final_string_translated) : array
    {
        return $data = [
            'original_sentence' => $this->input['sentence'],
            'final_string' => $final_string,
            'final_string_translated' => $final_string_translated,
            'duration_ms' => $time_elapsed_secs
        ];
    }
}