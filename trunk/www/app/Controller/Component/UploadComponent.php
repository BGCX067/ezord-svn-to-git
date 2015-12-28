<?php

class UploadComponent extends Component { 
    
    public $name = 'Upload';
    public $controller = null;

    private function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
                }
            }
            reset($objects);
            @rmdir($dir);
        }
    }
    
    public function initialize(&$controller) {
        $this->controller = $controller;
    }

    public function getUploadDir ($uid, $iid = null) {
        $user_path = getcwd().DS.'uploads'.DS.$uid;
        $path = $user_path;
        if (!is_null($iid)) {
            $path = $path.DS.'item'.DS.$iid;
        }
        return $path;
    }

    public function rmItemDir ($uid, $iid = null) {
        $user_path = getcwd().DS.'uploads'.DS.$uid;
        if (!is_null($iid)) {
            $paths[] = $user_path.DS.'item'.DS.$iid.DS.'medium';
            $paths[] = $user_path.DS.'item'.DS.$iid.DS.'small';
            $paths[] = $user_path.DS.'item'.DS.$iid;
        }
        // Create user dir
        foreach ($paths as $path) {
            if (is_dir($path)) {
                $this->rrmdir($path);
            }
        }
    }
    
    public function rmAvatarDir ($uid) {
        $user_path = getcwd().DS.'uploads'.DS.$uid;
        if (!is_null($uid)) {
            $paths[] = $user_path.DS.'avatar'.DS.'medium';
            $paths[] = $user_path.DS.'avatar'.DS.'small';
            $paths[] = $user_path.DS.'avatar';
        }
        foreach ($paths as $path) {
            if (is_dir($path)) {
                $this->rrmdir($path);
            }
        }
    }
    
    public function mkUploadDir ($uid, $iid = null) {
        $user_path = getcwd().DS.'uploads'.DS.$uid;
        $paths[] = $user_path;
        $paths[] = $user_path.DS.'avatar';
        $paths[] = $user_path.DS.'avatar'.DS.'medium';
        $paths[] = $user_path.DS.'avatar'.DS.'small';
        if (!is_null($iid)) {
            $paths[] = $user_path.DS.'item';
            $paths[] = $user_path.DS.'item'.DS.$iid;
            $paths[] = $user_path.DS.'item'.DS.$iid.DS.'medium';
            $paths[] = $user_path.DS.'item'.DS.$iid.DS.'small';
        }
        foreach ($paths as $path) {
            if (!is_dir($path)) {
                @mkdir($path, 755);
            } 
        }
    }

    public function getUploadUrlPath ($type = '', $uid, $iid = null) {
        $url = '/uploads/'.$uid;
        if (!is_null($iid)) {
            $url = $url.'/item/'.$iid;
        }
        $url = $url.'/'.$type;
        return $url;
    }
    
    public function fileUpload($file, $destination, &$error = '') {
        $error = null;
        $allowed_types = array ('image/png', 'image/gif', 'image/jpeg', 'image/jpg');
        if (isset($file['type']) 
                    && in_array($file['type'], $allowed_types) 
                    && (intval($file['size']) < 1048576))  {
            if ($file['error']>0) {
                $error = 'Return Code: ' . $file['error'] . '<br />';
                return false;
            }
            else {
                $number = 1;
                while(file_exists($destination.DS.$file['name'])) {
                    $file['name']=$number.'_'.$file['name'];
                    $number++;
                    if ($number>5) break;
                }
                @move_uploaded_file($file['tmp_name'], $destination.DS.$file['name']);
                return $file;
            }
        }
        else {
            $error = 'Invalid file';
            return false;
        }
    }
    
}