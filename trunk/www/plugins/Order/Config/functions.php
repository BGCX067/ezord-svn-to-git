<?

    function get_time_passed ($date) {
        $t1=strtotime($date); 
        $t2=strtotime(date('Y:m:d H:i:s')); 
        if($t2>$t1) { 
            $t3=$t2-$t1; 
            $time=$t3/3600; 
            $time=floor($time); 
            $dif=$t3%3600; 
            $min=$dif/60; 
            $min=round($min); 
            return array ('h' => $time, 'i' => $min);
        }
        return null;
    }