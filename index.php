<?php
header("Content-type:text/html;charset=utf-8");
function GetCurl($url){
    $curl = curl_init();
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($curl,CURLOPT_URL, $url);
    curl_setopt($curl,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    $resp = curl_exec($curl);
    curl_close($curl);
    return $resp;
}
$bv = isset($_GET['bv'])?$_GET['bv']:'';
$av = isset($_GET['av'])?$_GET['av']:'';
class Bilibili
{
    protected $tr = "fZodR9XQDSUm21yCkr6zBqiveYah8bt4xsWpHnJE7jL5VG3guMTKNPAwcF";
    protected $xor = 177451812;
    protected $add = 8728348608;
    protected $s = [11, 10, 3, 8, 4, 6];

    /**
     * BV 转 AV
     *
     * @param $bv
     * @return int
     */
    public function dec($bv)
    {
        $r = 0;
        $tr = array_flip(str_split($this->tr));
        for ($i = 0; $i < 6; $i++) {
            $r += $tr[$bv[$this->s[$i]]] * (pow(58, $i));
        }
        return ($r - $this->add) ^ $this->xor;
    }

    /**
     *
     * AV 转 BV
     *
     * @param $av
     * @return string
     */
    public function enc($av)
    {
        $tr = str_split($this->tr);
        $bv = 'BV1  4 1 7  ';
        $av = ($av ^ $this->xor) + $this->add;
        for ($i = 0; $i < 6; $i++) {
            $bv[$this->s[$i]] = $tr[floor($av/pow(58,$i)%58)];
        }
        return $bv;
    }
}
$bilibili = new Bilibili;
if(!empty($bv)){
	$av = $bilibili -> dec($bv);
}elseif(!empty($av)){
	$bv = $bilibili -> enc($av);
}
$resp = GetCurl("https://api.bilibili.com/x/player/pagelist?bvid=".$bv."&jsonp=jsonp");
$resp = json_decode($resp,true);
$cid = $resp['data'][0]['cid'];
if(!empty($cid)){
	$out['code'] = 200;
	$out['aid'] = $av;
	$out['bvid'] = $bv;
	$out['cid'] = $cid;
}else{
	$out['code'] = 400;
}
echo json_encode($out);
exit;
?>
