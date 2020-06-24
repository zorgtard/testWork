<?php


class CsvData
{
    const API_KEY = 'd9f000dbc0237078dfb39bf8033d244c';
    public $arResult;
    public function __construct ($tmp_name) { //construct
        $csvAsArray = $this->getData($tmp_name);
        if (is_array($csvAsArray) && !empty($csvAsArray)) {
            $rebildCsvArray = $this->rebuildData($csvAsArray);
            $newArr = $this->getContinentFromIp($rebildCsvArray);
            $newArrPhones = $this->getContinentFromPhone($newArr);
            $updateArr = $this->updateArr($newArrPhones);
            $result = $this->resultArr($updateArr);
            $this-> arResult = $result;
            return true;
        } else {
            return false;
        }
    }
    private function getData ($tmp_name) { //get array from csv file
        $tmpName = $tmp_name;
        $csvAsArray = array_map('str_getcsv', file($tmpName));
        return $csvAsArray;
    }
    private function rebuildData ($csvAsArray) { // array rebuild
        if (is_array($csvAsArray)) {
            $newCsvArray = [];
            foreach ($csvAsArray as $csvItem) {
                $newCsvArray[$csvItem[0]][]=[
                    'DATE' => $csvItem[1],
                    'DURATION' => $csvItem[2],
                    'PHONE' => $csvItem[3],
                    'IP' => $csvItem[4]
                ];
            }
            return $newCsvArray;
        } else {
            return false;
        }
    }
    private function getContinentFromIp ($dataArr) { //getting continent code by IP
        foreach ($dataArr as $k => $dataItem) {
            foreach ($dataItem as $kk => $item) {
                $continentCode = $this->curlForContinent($item['IP']);
                $dataArr[$k][$kk]['CONTINENT_CODE_IP'] = $continentCode;
            }
        }
        return $dataArr;
    }
    private function curlForContinent ($ip) { // curl for getting continent code by IP
        $url = "http://api.ipstack.com/".$ip."?access_key=".self::API_KEY;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $returned = curl_exec ($ch);
        curl_close($ch);
        $returnedOb = json_decode($returned);
        return $returnedOb->continent_code;
    }
    private function getContinentFromPhone ($dataArr) { //getting continent code by Phone code
        foreach ($dataArr as $k => $dataItem) {
            foreach ($dataItem as $kk => $item) {
                $continentCode = $this->continentFromPhone($item['PHONE']);
                $dataArr[$k][$kk]['CONTINENT_CODE_PHONE'] = $continentCode;
            }
        }
        return $dataArr;
    }
    private function continentFromPhone ($phone) { // help function for getting continent code by Phone code
        $continent_code = '';
        for ($i=6; $i > 0; $i--) {
            $phone_code = substr($phone, 0 , $i);
            $continent_code = $this->phonesContinentsArr()[$phone_code];
            if (!empty($continent_code)) {
                break;
            }
        }
        return $continent_code;
    }
    private function updateArr ($dataArr) { //updating array
        $newArr = [];
        foreach ($dataArr as $k => $dataItem) {
            foreach ($dataItem as $kk => $item) {
                if ($item['CONTINENT_CODE_IP'] == $item['CONTINENT_CODE_PHONE']) {
                    $newArr[$k]['SAME'][] = $item['DURATION'];
                }
                $newArr[$k]['ALL'][] = $item['DURATION'];
            }
        }
        return $newArr;
    }
    private function resultArr ($dataArr) { // result array rebuild
        $newArr = [];
        foreach ($dataArr as $k => $dataItem) {
            $newArr[$k]['ALL_DURATION']= array_sum($dataItem['ALL']);
            $newArr[$k]['ALL_COUNT']= count($dataItem['ALL']);
            $newArr[$k]['SAME_DURATION']= array_sum($dataItem['SAME']);
            $newArr[$k]['SAME_COUNT']= count($dataItem['SAME']);
        }
        return $newArr;
    }
    private function phonesContinentsArr () { // array with phone codes and continent codes
        $arrPhonesCodes = [
            '376'=> 'EU',
            '971'=> 'AS',
            '93'=> 'AS',
            '1268'=> 'NA' ,
            '1264'=> 'NA',
            '355'=> 'EU',
            '374'=> 'AS',
            '244'=> 'AF',
            '54'=> 'SA',
            '1684'=> 'OC',
            '43'=> 'EU',
            '61'=> 'OC',
            '297'=> 'NA',
            '+358-18'=> 'EU',
            '994'=> 'AS',
            '387'=> 'EU',
            '1246'=> 'NA',
            '880'=> 'AS',
            '32'=>'EU' ,
            '226'=> 'AF',
            '359'=> 'EU',
            '973'=> 'AS',
            '257'=> 'AF',
            '229'=> 'AF',
            '590'=> 'NA',
            '1441'=> 'NA',
            '673'=> 'AS',
            '591'=> 'SA',
            '599'=> 'NA',
            '55'=> 'SA',
            '1242'=> 'NA',
            '975'=> 'AS',
            '267'=> 'AF',
            '375'=> 'EU',
            '501'=> 'NA',
            '1'=> 'NA',
            '243'=> 'AF',
            '236'=> 'AF',
            '242'=> 'AF',
            '41'=> 'EU',
            '225'=> 'AF',
            '682'=> 'OC',
            '56'=> 'SA',
            '237'=> 'AF',
            '86'=> 'AS',
            '57'=>'SA' ,
            '506'=>'NA' ,
            '53'=> 'NA',
            '238'=> 'AF',
            '357'=> 'EU',
            '420'=> 'EU',
            '49'=> 'EU',
            '253'=> 'AF',
            '45' => 'EU',
            '1767'=> 'NA',
            '1809'=>'NA' ,
            '1829'=> 'NA',
            '213'=> 'AF',
            '593'=> 'SA',
            '372'=> 'EU',
            '20'=> 'AF',
            '212'=> 'AF',
            '291'=> 'AF',
            '34'=> 'EU',
            '251'=> 'AF',
            '358'=> 'EU',
            '679'=> 'OC',
            '500'=> 'SA',
            '691'=> 'OC',
            '298'=> 'EU',
            '33'=> 'EU',
            '241'=> 'AF',
            '44'=> 'EU',
            '1473'=> 'NA',
            '995'=> 'AS',
            '594'=> 'SA',
            '+44-1481'=> 'EU',
            '233'=> 'AF',
            '350'=> 'EU',
            '299'=> 'NA',
            '220'=> 'AF',
            '224'=> 'AF',
            '240'=> 'AF',
            '30'=> 'EU',
            '502'=> 'NA',
            '1671'=> 'OC',
            '245'=>'AF' ,
            '592'=> 'SA',
            '852'=> 'AS',
            '504'=>'NA' ,
            '385'=>'EU',
            '509'=> 'NA',
            '36'=> 'EU',
            '62'=> 'AS',
            '353'=> 'EU',
            '972'=> 'AS',
            '441624'=> 'EU',
            '91'=> 'AS',
            '246'=> 'AS',
            '964'=> 'AS',
            '98'=> 'AS',
            '354'=> 'EU',
            '39'=> 'EU',
            '441534'=> 'EU',
            '1876'=> 'NA',
            '962'=>'AS' ,
            '81'=> 'AS',
            '254'=> 'AF',
            '996'=> 'AS',
            '855'=> 'AS',
            '686'=> 'OC',
            '269'=> 'AF',
            '1869'=> 'NA',
            '850'=> 'AS',
            '82'=>'AS' ,
            '965'=>'AS' ,
            '1345'=> 'NA',
            '856'=> 'AS',
            '961'=> 'AS',
            '1758'=>'NA' ,
            '423'=> 'EU',
            '94'=> 'AS',
            '231'=>'AF',
            '266'=>'AF',
            '370'=>'EU',
            '352'=>'EU',
            '371'=>'EU',
            '218'=>'AF',
            '377'=>'EU',
            '373'=>'EU',
            '382'=>'EU',
            '261'=> 'AF',
            '692'=> 'OC',
            '389'=> 'EU',
            '223'=> 'AF',
            '95'=> 'AS',
            '976'=> 'AS',
            '853'=> 'AS',
            '1670'=> 'OC',
            '596'=> 'NA',
            '222'=> 'AF',
            '1664'=> 'NA',
            '356'=> 'EU',
            '230'=> 'AF',
            '960'=> 'AS',
            '265'=> 'AF',
            '52'=> 'NA',
            '60'=> 'AS',
            '258'=> 'AF',
            '264'=> 'AF',
            '687'=> 'OC',
            '227'=> 'AF',
            '672'=> 'OC',
            '234'=> 'AF',
            '505'=> 'NA',
            '31'=> 'EU',
            '47'=> 'EU',
            '977'=> 'AS',
            '674'=> 'OC',
            '683'=> 'OC',
            '64'=> 'OC',
            '968'=> 'AS',
            '507'=> 'NA',
            '51'=> 'SA',
            '689'=> 'OC',
            '675'=> 'OC',
            '63'=> 'AS',
            '92'=> 'AS',
            '48'=> 'EU',
            '508'=> 'NA',
            '870'=> 'OC',
            '+1-787'=> 'NA',
            '1-939'=> 'NA',
            '970'=> 'AS',
            '351'=> 'EU',
            '680'=> 'OC',
            '595'=> 'SA',
            '974'=> 'AS',
            '262'=> 'AF',
            '40'=> 'EU',
            '381'=> 'EU',
            '7'=> 'EU',
            '250'=> 'AF',
            '966'=> 'AS',
            '677'=> 'OC',
            '248'=> 'AF',
            '249'=> 'AF',
            '211'=> 'AF',
            '46'=> 'EU',
            '65'=> 'AS',
            '290'=> 'AF',
            '386'=> 'EU',
            '421'=> 'EU',
            '232'=> 'AF',
            '378'=> 'EU',
            '221'=> 'AF',
            '252'=> 'AF',
            '597'=> 'SA',
            '239'=> 'AF',
            '503'=>'NA',
            '963'=> 'AS',
            '268'=> 'AF',
            '1649'=> 'NA',
            '235'=> 'AF',
            '228'=> 'AF',
            '66'=> 'AS',
            '992'=> 'AS',
            '690'=> 'OC',
            '670'=> 'OC',
            '993'=> 'AS',
            '216'=> 'AF',
            '676'=> 'OC',
            '90'=> 'AS',
            '+1-868'=> 'NA',
            '688'=> 'OC',
            '886'=> 'AS',
            '255'=> 'AF',
            '380'=> 'EU',
            '256'=> 'AF',
            '598'=> 'SA',
            '998'=> 'AS',
            '379'=> 'EU',
            '+1-784'=> 'NA',
            '58'=> 'SA',
            '+1-284'=> 'NA',
            '+1-340'=> 'NA',
            '84'=> 'AS',
            '678'=> 'OC',
            '681'=> 'OC',
            '685'=> 'OC',
            '967'=> 'AS',
            '27'=> 'AF',
            '260'=> 'AF',
            '263'=> 'AF',
        ];
        return $arrPhonesCodes;
    }

}