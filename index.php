<?php
require_once("Assets/library/LibreTranslate.php");
require_once ("Assets/library/GoogleTranslate.php");

use Jefs42\LibreTranslate;
use \Statickidz\GoogleTranslate;

$translator = new LibreTranslate();
$trans = new GoogleTranslate();

// Docs : https://github.com/jefs42/libretranslate

//$translator->setTarget('en');

$GlobalLanguage = "en";
$TextConverted = "fr";


// check language and translate in english
if (isset($_POST['text'])) {

    // faire un appel curl pour savoir la langue

    /*$source = 'es';
    $text = $_POST['text'];

    $resultLang = $trans->getTextLanguage($text, $text);
    $result = $trans->translate($source, $GlobalLanguage, $text);*/

// Good morning
    echo "<p class='avalifont'>".$_POST['text']."</p>";
	

    /*$curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://google-translate1.p.rapidapi.com/language/translate/v2/detect",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "q=". $_POST['text'],
        CURLOPT_HTTPHEADER => [
            "Accept-Encoding: application/gzip",
            "X-RapidAPI-Host: google-translate1.p.rapidapi.com",
            "X-RapidAPI-Key: 9fcafa241bmsh9ef7be5f7345bdap163994jsn1f27327b8419",
            "content-type: application/x-www-form-urlencoded"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        // get the result in json format and decode it
        $result = json_decode($response, true);
        // get the language
        $language = $result['data']['detections'][0][0]['language'];

        $GlobalLanguage = $language;
        echo $language."<br>";
    }



    // translate the text
    $curlTR = curl_init();

    curl_setopt_array($curlTR, [
        CURLOPT_URL => "https://google-translate1.p.rapidapi.com/language/translate/v2",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "q=". $_POST['text'] ."&source=".$GlobalLanguage."&target=en",
        CURLOPT_HTTPHEADER => [
            "Accept-Encoding: application/gzip",
            "X-RapidAPI-Host: google-translate1.p.rapidapi.com",
            "X-RapidAPI-Key: ",
            "content-type: application/x-www-form-urlencoded"
        ],
    ]);

    $responseTR = curl_exec($curlTR);
    $errTR = curl_error($curlTR);

    curl_close($curlTR);

    if ($errTR) {
        echo "cURL Error #:" . $errTR;
    } else {
        // get the result in json format and decode it
        $resultTR = json_decode($responseTR, true);
        // get the language
        $text = $resultTR['data']['translations'][0]['translatedText'];

        $TextConverted = $text;
        echo $text;
    }*/

}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Avali Scratch Translator</title>
    <!-- Made by Maxime6610 -->
    <link rel="stylesheet" type="text/css" href="Assets/css/main.css">
</head>
<body>

	<br>
	
    <!-- Made a formular with text -->
    <form action="index.php" method="post">
        <textarea name="text" rows="10" cols="30"></textarea>
		<br><br>
        <input type="submit" value="Translate">
    </form>
	
	<br>

    <p class='avalifont'><?php if (isset($_POST['text'])) {echo $_POST['text'];} ?></p>
</body>
</html>

