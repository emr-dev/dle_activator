<?php

/**
 * emrdev
 * @author        Mohammed Emomaliev
 * @web-site        https://www.emrdev.ru
 *  Strictly for educational purposes only. The author assumes no responsibility for the use of this script.
 */

$dleKey = new KeyGen();
$dleKey->filePath = 'engine/inc/include/init.php';
$dleKey->file_config = 'engine/data/config.php';
$dleKey->setKey();


class  KeyGen
{

    public $filePath;
    public $file_config;


    private function getDinversion()
    {
        if (file_exists($this->filePath)) {
            $data = file($this->filePath);
            foreach ($data as $row) {
                if (strpos($row, 'define') !== false and strpos($row, 'DINITVERSION') !== false) {
                    $dv_id = str_replace(['define', 'DINITVERSION', ' ', "'", ' ', '(', ')', ';', ','], '', $row);
                    return $dv_id;
                    break;
                }
            }
        } else {
            echo "File engine/inc/include/init.php does not exist.";
            die();
        }
    }


    public function getKey()
    {
        $domen_md5 = explode('.', $_SERVER['HTTP_HOST']);
        $count_key = count($domen_md5) - 1;
        unset($domen_md5[$count_key]);
        if (end($domen_md5) == "com" OR end($domen_md5) == "net") $count_key--;
        $domen_md5 = $domen_md5[$count_key - 1];
        $domen_md5 = md5(md5($domen_md5 . "780918"));
        $dv = $this->getDinversion();
        if (trim($dv)) {
            return md5($domen_md5 . trim($dv));
        } else {
            echo "defind DINITVERSION not found in file: engine/inc/include/init.php";
            die();
        }
    }

    public function setKey()
    {
        if (file_exists($this->file_config)) {
            $data = file($this->file_config, FILE_IGNORE_NEW_LINES);
            $text = '';
            foreach ($data as $row) {
                if (strpos($row, "'key'") !== false) {
                    $row = "'key' => '" . $this->getKey() . "',";
                }
                $text .= $row . PHP_EOL;
            }
            file_put_contents($this->file_config, $text);
            echo 'Key installed successfully';
        } else {
            echo "File engine/inc/include/init.php does not exist.";
            die();
        }
    }

}
