<?php

App::uses('AppHelper', 'View/Helper');

class UploadHelper extends AppHelper {
    
    public function getUploadUrlPath ($type = '', $uid, $iid = null) {
        $user_path = '/uploads/'.$uid;
        $path = $user_path;
        
        if (!is_null($iid)) {
            $path = $path.'/item/'.$iid;
        }
        return $path.'/'.$type;
    }
    
}