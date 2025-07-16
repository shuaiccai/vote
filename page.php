<?php
if(!function_exists('paging')){
    function paging($total, $displayPG = 20, $url = ''){

        global $page,$firstCount,$pageNav,$_SERVER;

        $GLOBALS["displayPG"] = $displayPG;
        $page = $_GET['page'] ?? 1;

        if(!$url){
            $url =$_SERVER["REQUEST_URI"];
        }
        $parse_url = parse_url($url);
        $url_query = $parse_url["query"] ?? '';

        if($url_query){
            $url_query = preg_replace("/(^|&)page=$page/", "", $url_query);
            $url=str_replace($parse_url["query"],$url_query,$url);
            if($url_query) $url.="&page"; else $url.="page";
        }else{
            $url.="?page";
        }
        $lastpg=ceil($total/$displayPG);
        $page=min($lastpg,$page);
        $prepg=$page-1;
        $nextpg=($page == $lastpg)? 0 :$page+1;
        $firstCount=($page-1)*$displayPG;

        $pageNav="第 <B>".($total?($firstCount+1):0)."</B>-<B>".min($firstCount+$displayPG,$total)."</B> 条，共 <B> $total </B>条记录";
        if($lastpg<=1) return false;
        $pageNav.=" <a href=$url=1 mce_href=$url=1>首页</a>";
        if($prepg) $pageNav.=" <a href=$url=$prepg mce_href=$url=$prepg>上页</a>"; else $pageNav.="上页";
        if($nextpg) $pageNav.=" <a href=$url=$nextpg mce_href=$url=$nextpg>下页</a>"; else $pageNav.="下页";
        $pageNav.=" <a href=$url=$lastpg mce_href=$url=$lastpg>尾页</a>";

        $pageNav.=" 到第 <select name = 'topage' size = '1' style='font-size:12px' mce_style ='font-size:12px' onchange='window.location=\"$url=\"+this.value'>\n";

        for($i=1;$i<=$displayPG;$i++){
            if($i==$page) $pageNav.="<option value='$i' selected>$i</option>\n";
            else $pageNav.="<option value='$i'>$i</option>\n";
        }
        $pageNav.="</select> 页，共 $lastpg 页";



    }
}
?>