<?php  

class ImageComponent extends Component { 
    
    public $name = 'Image'; 
    
    public function resize($cType = 'resize', $imgPath, $newImgPath = false, $newWidth=false, $newHeight=false, $quality = 75, $bgcolor = false) { 
        list($oldWidth, $oldHeight, $type) = @ getimagesize($imgPath);
        
        $ext = $this->image_type_to_extension($type); 
         
        //check to make sure that the file is writeable, if so, create destination image (temp image) 
        $imgFolder = dirname($imgPath);
        if (is_writeable($imgFolder)) { 
            $dest = $newImgPath;
        } 
        else { 
            //if not let developer know 
            $imgFolder = substr($imgFolder, 0, strlen($imgFolder) -1); 
            $imgFolder = substr($imgFolder, strrpos($imgFolder, '\\') + 1, 20); 
            echo("You must allow proper permissions for image processing. And the folder has to be writable."); 
            echo("Run \"chmod 777 on '$imgFolder' folder\""); 
            exit(); 
        } 
         
        if (($oldWidth>0 && $oldHeight>0) && ($newWidth || $newHeight)) { 
            switch ($cType) { 
                default: 
                case 'resize': 
                    $widthScale = 2; 
                    $heightScale = 2; 
                     
                    if($newWidth) $widthScale =     $newWidth / $oldWidth; 
                    if($newHeight) $heightScale = $newHeight / $oldHeight; 
                    
                    if($widthScale < $heightScale) { 
                        $maxWidth = $newWidth; 
                        $maxHeight = false;                             
                    } 
                    elseif ($widthScale > $heightScale ) { 
                        $maxHeight = $newHeight; 
                        $maxWidth = false; 
                    } 
                    else { 
                        $maxHeight = $newHeight; 
                        $maxWidth = $newWidth; 
                    } 
                     
                    if($maxWidth > $maxHeight){ 
                        $applyWidth = $maxWidth; 
                        $applyHeight = ($oldHeight*$applyWidth)/$oldWidth; 
                    } 
                    elseif ($maxHeight > $maxWidth) { 
                        $applyHeight = $maxHeight; 
                        $applyWidth = ($applyHeight*$oldWidth)/$oldHeight; 
                    } 
                    else { 
                        $applyWidth = $maxWidth;  
                            $applyHeight = $maxHeight; 
                    } 
                    
                    $startX = 0; 
                    $startY = 0; 
                    
                    break; 
                case 'resizeCrop': 
                    echo $oldWidth.'-'.$oldHeight; 
                    $ratioX = $newWidth / $oldWidth; 
                    $ratioY = $newHeight / $oldHeight; 
 
                    if ($ratioX < $ratioY) {  
                        $startX = round(($oldWidth - ($newWidth / $ratioY))/2); 
                        $startY = 0; 
                        $oldWidth = round($newWidth / $ratioY); 
                        $oldHeight = $oldHeight; 
                    } 
                    else {  
                        $startX = 0; 
                        $startY = round(($oldHeight - ($newHeight / $ratioX))/2); 
                        $oldWidth = $oldWidth; 
                        $oldHeight = round($newHeight / $ratioX); 
                    } 
                    $applyWidth = $newWidth; 
                    $applyHeight = $newHeight; 
                    break; 
                case 'crop': 
                    $startY = ($oldHeight - $newHeight)/2; 
                    $startX = ($oldWidth - $newWidth)/2; 
                    $oldHeight = $newHeight; 
                    $applyHeight = $newHeight; 
                    $oldWidth = $newWidth;  
                    $applyWidth = $newWidth; 
                    break; 
            } 
            switch($ext) { 
                case 'gif' : 
                    $oldImage = imagecreatefromgif($imgPath); 
                    break; 
                case 'png' : 
                    $oldImage = imagecreatefrompng($imgPath); 
                    break; 
                case 'jpg' : 
                case 'jpeg' : 
                    $oldImage = imagecreatefromjpeg($imgPath); 
                    break; 
                default : 
                    return false; 
                    break; 
            } 
            //create new image 
            $newImage = imagecreatetruecolor($applyWidth, $applyHeight); 
            if($bgcolor) { 
                //set up background color for new image 
                sscanf($bgcolor, "%2x%2x%2x", $red, $green, $blue); 
                $newColor = ImageColorAllocate($newImage, $red, $green, $blue);  
                imagefill($newImage,0,0,$newColor); 
            }
            //put old image on top of new image 
            imagecopyresampled($newImage, $oldImage, 0,0 , $startX, $startY, $applyWidth, $applyHeight, $oldWidth, $oldHeight);
            switch($ext) { 
                case 'gif' : 
                    imagegif($newImage, $dest, $quality); 
                    break; 
                case 'png' : 
                    imagepng($newImage, $dest, $quality); 
                    break; 
                case 'jpg' : 
                case 'jpeg' : 
                    imagejpeg($newImage, $dest, $quality); 
                    break; 
                default : 
                    return false; 
                    break; 
            } 
            imagedestroy($newImage); 
            imagedestroy($oldImage); 
            return true; 
        } 
        else { 
            echo ("Invalid file size!");
            return false; 
        } 
    } 
    function image_type_to_extension($imagetype) { 
        if(empty($imagetype)) return false; 
        switch($imagetype) { 
            case IMAGETYPE_GIF    : return 'gif'; 
            case IMAGETYPE_JPEG    : return 'jpg'; 
            case IMAGETYPE_PNG    : return 'png'; 
            case IMAGETYPE_SWF    : return 'swf'; 
            case IMAGETYPE_PSD    : return 'psd'; 
            case IMAGETYPE_BMP    : return 'bmp'; 
            case IMAGETYPE_TIFF_II : return 'tiff'; 
            case IMAGETYPE_TIFF_MM : return 'tiff'; 
            case IMAGETYPE_JPC    : return 'jpc'; 
            case IMAGETYPE_JP2    : return 'jp2'; 
            case IMAGETYPE_JPX    : return 'jpf'; 
            case IMAGETYPE_JB2    : return 'jb2'; 
            case IMAGETYPE_SWC    : return 'swc'; 
            case IMAGETYPE_IFF    : return 'aiff'; 
            case IMAGETYPE_WBMP    : return 'wbmp'; 
            case IMAGETYPE_XBM    : return 'xbm'; 
            default                : return false; 
        } 
    }        
} 
