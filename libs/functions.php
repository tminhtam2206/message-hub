<?php  
    function DateToTime($date){
        $to_time = strtotime($date);
        $from_time = strtotime(date("Y-m-d H:i:s"));
        $second = abs($to_time - $from_time);

        $time = 0;

        if($second < 60){
                $time = $second;
                return $time." giây trước";
        }
        else if($second > 60 && $second < 3600){  //minute
                $time = floor($second/60);
                return $time." phút trước";
        }
        else if($second > 3600 && $second < 86400){//hour
                $time = floor($second/3600);
                return $time." giờ trước";
        }
        else if($second > 86400 && $second < 2678400){ //day
                $time = floor($second/86400);
                return $time." ngày trước";
        }
        else if($second > 2678400 && $second < 32140800){//month
                $time = floor($second/2678400);
                return $time." tháng trước";
        }
        else{//year
                $arr = explode('-', $date);
            $truoc = $arr[0];
            $curr = date("Y");
            $time = $curr - $truoc;
            return $time." năm trước";
        }
    }

    function ConvertDate($date){
        $myDate = date_create($date);
        return date_format($myDate,"g:ia d/m/Y");
    }

    //insert to database
    function FormatMessage($data){
        $data = utf8_encode(ClearCode($data));
        $data = base64_encode($data);
        
        return $data;
    }

    function ClearCode($data){
        $data = str_replace('`', "'", $data);
        $data = str_replace('"', "&quot;", $data);
        $data = str_replace("\n", "&lt;br/&gt;", $data);

        return $data;
    }

    //base view
    function ConvertMessage($data){
        $data = base64_decode($data);
        $data = utf8_decode($data);

        return str_replace("&lt;br/&gt;", "<br/>", $data);
    }

    function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    function RandomCode(){
        $len = 32;
        return substr(md5(rand()), 0, $len);
    }

    function CheckUrl($url){
        if(isset($_GET['url'])){
            if($_GET['url'] == $url) echo 'active';
        }
    }

    function getAvatar($link){
        require_once './libs/middleware.php';

        $link = str_replace("./", "", $link);
        return server_post.$link;
    }
?>