<?php

class ApiAppController extends AppController {
	
	public function renderJson ($result) {
		echo json_encode ($result);
		exit;
	}
    
    public function authVerify($session_id) {
        $user = $this->ApiSession->find('first', array(
            'conditions' => array ('ApiSession.id' => $session_id)
        ));
        
        if (!empty($user)) {
            $user = json_decode($user['ApiSession']['value']);
            return $user;
        }
        return null;
    }
	
}