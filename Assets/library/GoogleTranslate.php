<?php

namespace Statickidz;

/**
 * GoogleTranslate.class.php
 *
 * Class to talk with Google Translator for free.
 *
 * @package PHP Google Translate Free;
 * @category Translation
 * @author Adrián Barrio Andrés
 * @author Paris N. Baltazar Salguero <sieg.sb@gmail.com>
 * @copyright 2016 Adrián Barrio Andrés
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License 3.0
 * @version 2.0
 * @link https://statickidz.com/
 */

/**
 * Main class GoogleTranslate
 *
 * @package GoogleTranslate
 *
 */
class GoogleTranslate
{
    /**
     * Retrieves the translation of a text
     *
     * @param string $source
     *            Original language of the text on notation xx. For example: es, en, it, fr...
     * @param string $target
     *            Language to which you want to translate the text in format xx. For example: es, en, it, fr...
     * @param string $text
     *            Text that you want to translate
     *
     * @return string a simple string with the translation of the text in the target language
     */
    public static function translate($source, $target, $text)
    {
        // Request translation
        $response = self::requestTranslation($source, $target, $text);

        // Clean translation
        $translation = self::getSentencesFromJSON($response);

        return $translation;
    }

    /**
     * Internal function to make the request to the translator service
     *
     * @internal
     *
     * @param string $source
     *            Original language taken from the 'translate' function
     * @param string $target
     *            Target language taken from the ' translate' function
     * @param string $text
     *            Text to translate taken from the 'translate' function
     *
     * @return object[] The response of the translation service in JSON format
     */
    protected static function requestTranslation($source, $target, $text)
    {

        if (strlen($text) >= 5000)
            throw new \Exception("Maximum number of characters exceeded: 5000");

        // Google translate URL
        $url = "https://translate.googleapis.com/translate_a/single?client=gtx&dt=t";

        $fields = array(
            'sl' => urlencode($source),
            'tl' => urlencode($target),
            'q' => urlencode($text)
        );

        // URL-ify the data for the POST
        $fields_string = "";
        foreach ($fields as $key => $value) {
            $fields_string .= '&' . $key . '=' . $value;
        }

        rtrim($fields_string, '&');

        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        // Execute post
        $result = curl_exec($ch);

        // Close connection
        curl_close($ch);

        return $result;
    }

    /**
     * Dump of the JSON's response in an array
     *
     * @param string $json
     *            The JSON object returned by the request function
     *
     * @return string A single string with the translation
     */
    protected static function getSentencesFromJSON($json)
    {
        $sentencesArray = json_decode($json, true);
        $sentences = "";

        if (!$sentencesArray || !isset($sentencesArray[0]))
            throw new \Exception("Google detected unusual traffic from your computer network, try again later (2 - 48 hours)");

        foreach ($sentencesArray[0] as $s) {
            $sentences .= isset($s[0]) ? $s[0] : '';
        }

        return $sentences;
    }

    function getTextLanguage($text, $default) {
        $supported_languages = array(
            'en',
            'de',
            'fr',
            'es',
        );
        // German word list
        // from http://wortschatz.uni-leipzig.de/Papers/top100de.txt
        $wordList['de'] = array ('der', 'die', 'und', 'in', 'den', 'von',
            'zu', 'das', 'mit', 'sich', 'des', 'auf', 'für', 'ist', 'im',
            'dem', 'nicht', 'ein', 'Die', 'eine');
        // English word list
        // from http://en.wikipedia.org/wiki/Most_common_words_in_English
        $wordList['en'] = array ('the', 'be', 'to', 'of', 'and', 'a', 'in',
            'that', 'have', 'I', 'it', 'for', 'not', 'on', 'with', 'he',
            'as', 'you', 'do', 'at');
        // French word list
        // from https://1000mostcommonwords.com/1000-most-common-french-words/
        $wordList['fr'] = array ('comme', 'que',  'tait',  'pour',  'sur',  'sont',  'avec',
            'tre',  'un',  'ce',  'par',  'mais',  'que',  'est',
            'il',  'eu',  'la', 'et', 'dans', 'mot');

        // Spanish word list
        // from https://spanishforyourjob.com/commonwords/
        $wordList['es'] = array ('que', 'no', 'a', 'la', 'el', 'es', 'y',
            'en', 'lo', 'un', 'por', 'qu', 'si', 'una',
            'los', 'con', 'para', 'est', 'eso', 'las');
        // clean out the input string - note we don't have any non-ASCII
        // characters in the word lists... change this if it is not the
        // case in your language wordlists!
        $text = preg_replace("/[^A-Za-z]/", ' ', $text);
        // count the occurrences of the most frequent words
        foreach ($supported_languages as $language) {
            $counter[$language]=0;
        }
        for ($i = 0; $i < 20; $i++) {
            foreach ($supported_languages as $language) {
                $counter[$language] = $counter[$language] +
                    // I believe this is way faster than fancy RegEx solutions
                    substr_count($text, ' ' .$wordList[$language][$i] . ' ');;
            }
        }
        // get max counter value
        // from http://stackoverflow.com/a/1461363
        $max = max($counter);
        $maxs = array_keys($counter, $max);
        // if there are two winners - fall back to default!
        if (count($maxs) == 1) {
            $winner = $maxs[0];
            $second = 0;
            // get runner-up (second place)
            foreach ($supported_languages as $language) {
                if ($language <> $winner) {
                    if ($counter[$language]>$second) {
                        $second = $counter[$language];
                    }
                }
            }
            // apply arbitrary threshold of 10%
            if (($second / $max) < 0.1) {
                return $winner;
            }
        }
        return $default;
    }
}
