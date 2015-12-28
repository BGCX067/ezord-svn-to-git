<?php 

class PlacesController extends ApiAppController {

    public $uses = array ('Place', 'ApiSession');
    
    public function beforeFilter () {
        $this->Auth->allow('getPlaces');
    }

    public function getPlaces () {
        $session_id = $this->request->data['session_id'];
        $cUser = $this->authVerify($session_id);
        if ($this->request->is('post') && !empty($cUser)) {
            $places = $this->Place->find('all', array (
                'joins' => array (
                    array (
                        'table' => $this->Place->tablePrefix.'places_users',
                        'alias' => 'PlacesUser',
                        'type' => 'inner',
                        'conditions' => array(
                            'Place.id = PlacesUser.place_id'
                        )
                    )
                ),
                'conditions' => array (
                    'PlacesUser.user_id' => $cUser->id,
                    'Place.active' => 1
                )
            ));
            $this->renderJson($places);
        } else {
            $data = array (
                'Error' => 1,
                'ErrorMessage' => 'Required parrams are missing'
            );
            $this->renderJson($data);
        }
    }
}