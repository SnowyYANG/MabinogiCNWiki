<?php
$title = '魔法释放卷轴';
$START = strlen('_LT[xml.optionset.');
$NO_EXISTS = [2,10,104,108,109,110,203,204,209,210,1401,1402,1403,1408,1409,1410,20315,20421,20423,20515,20629,20634,20642,20752,20753,20754,20847,20916,21107,21113,21415,21416,21418,21419,21420,21421,30322,30533,30665,30666,30667,31513,31514,31515,31516,31517,31522];

if (filemtime(__DIR__.'/cache/options.json') > filemtime(__DIR__.'/package/optionset.china.txt'))
    $options = json_decode(file_get_contents(__DIR__.'/cache/options.json'), true);
else {
    $xml = simplexml_load_file(__DIR__.'/package/optionset.xml');
    $txt = [];
    foreach(file(__DIR__.'/package/optionset.china.txt',FILE_IGNORE_NEW_LINES) as $l) {
        $kv = explode("\t",$l);
        $txt[$kv[0]]=$kv[1];
    }
    function getlocalstr($str) {
        global $START,$txt;
        return $str?$txt[substr($str,$START,strlen($str)-$START-1)]:'';
    }
    foreach($xml->OptionSetList->OptionSet as $o) {
        $o=$o[0];
        if (($id=(int)$o['ID']) > 39999) break;
        if (in_array($id,$NO_EXISTS)) continue;
        $option = [];
        $option['id']=$id;
        $option['ename']=(string)$o['Name'];
        $s=strlen('_LT[xml.optionset.');
        $option['name']=getlocalstr((string)$o['LocalName']);
        $name2=getlocalstr((string)$o['LocalName2']);
        if ($name2 !== $option['name']) $option['name2']=$name2;
        $option['usage']=((int)$o['Usage'])?'接尾':'接头';
        $option['level']="0FEDCBA987654321"[(int)$o['Level']];
        //$option['desc']=getlocalstr((string)$o['OptionDesc']);
        $option['desc']=str_replace(['\n','[',']'],['<br>','<span class="option-red">','</span>'],getlocalstr((string)$o['OptionDesc']));
        $options[$id]=$option;
    }
    $fp = fopen(__DIR__.'/cache/options.json', 'a');
    if (flock($fp, LOCK_EX | LOCK_NB)) {
        ftruncate($fp, 0);
        fwrite($fp, json_encode($options));
        flock($fp, LOCK_UN);
    }
    fclose($fp);
}
require_once 'option_attach.php';

function view() {
    global $options,$option_attach;
    foreach($options as $o) {
        $id=$o['id'];
        $name2=$o['name2'];
        echo "<div><b>$o[name]";
        if ($name2) echo ' '.$name2;
        echo '</b>';
        $usage=$o['usage']?'接尾':'接头';
        echo " ($o[ename]) $usage 等级$o[level]<div class=\"option-blue\">$o[desc]</div>";
        if ($pre=$option_attach[$id]['pre']) echo "垫卷：$pre<br>";
        if ($get=$option_attach[$id]['get']) {
            $get=str_replace(['\n'],['；'],$get);
            echo "获得途径：$get<br>";
        }
        echo '<br></div>';
    }
}
