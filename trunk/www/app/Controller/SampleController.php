<?php

class SampleController extends AppController
{
	public $name = 'Sample';
	public $uses = array('User', 'Place', 'PlacesUser', 'Table', 'MenuItemGroup', 'MenuItem', 'MenuItemPrice', 'ItemImage',
            'SampleMenuItemGroup', 'SampleMenuItem', 'SampleMenuItemPrice', 'SampleItemImage');
    public $components = array('Upload');
    
    private function recurseCopy($src, $dst) { 
        $dir = opendir($src); 
        @mkdir($dst); 
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    $this->recurseCopy($src . '/' . $file,$dst . '/' . $file); 
                } 
                else { 
                    copy($src . '/' . $file,$dst . '/' . $file); 
                } 
            } 
        } 
        closedir($dir); 
    }

    private function addItem($uid, $prefix) {
        // Find sample groups
        $this->SampleMenuItemGroup->tablePrefix = '';
        $groups = $this->SampleMenuItemGroup->find('all');
        foreach ($groups as $g => $group) {
            // Add sample groups
            $group_data['MenuItemGroup'] = array(
                'user_id' => $uid,
                'name' => $group['SampleMenuItemGroup']['name'],
                'description' => $group['SampleMenuItemGroup']['description']
            );
            $this->MenuItemGroup->tablePrefix = $prefix;
            $this->MenuItemGroup->create();
            $this->MenuItemGroup->save($group_data);
            $gid = $this->MenuItemGroup->getLastInsertID();
            // Find sample items
            $this->SampleMenuItem->tablePrefix = '';
            $items = $this->SampleMenuItem->find('all', array(
                'conditions' => array('SampleMenuItem.menu_item_group_id' => $group['SampleMenuItemGroup']['id'])
            ));
            foreach ($items as $i => $item) {
                // Add sample items
                $item_data['MenuItem'] = array(
                    'user_id' => $uid,
                    'menu_item_group_id' => $gid,
                    'name' => $item['SampleMenuItem']['name'],
                    'price' => $item['SampleMenuItem']['price'],
                    'description' => $item['SampleMenuItem']['description']
                );
                $this->MenuItem->tablePrefix = $prefix;
                $this->MenuItem->create();
                $this->MenuItem->save($item_data);
                $iid = $this->MenuItem->getLastInsertID();
                // Find Sample Price
                $this->SampleMenuItemPrice->tablePrefix = '';
                $prices = $this->SampleMenuItemPrice->find('all', array(
                    'conditions' => array('SampleMenuItemPrice.menu_item_id' => $item['SampleMenuItem']['id'])
                ));
                foreach ($prices as $p => $price) {
                    $price_data = array(
                        'menu_item_id' => $iid,
                        'price' => $price['SampleMenuItemPrice']['price'],
                        'time_from' => $price['SampleMenuItemPrice']['time_from'],
                        'time_to' => $price['SampleMenuItemPrice']['time_to']
                    );
                    $this->MenuItemPrice->tablePrefix = $prefix;
                    $this->MenuItemPrice->create();
                    $this->MenuItemPrice->save($price_data);
                }
                // Find sample images
                $this->SampleItemImage->tablePrefix = '';
                $images = $this->SampleItemImage->find('all', array(
                    'conditions' => array('SampleItemImage.menu_item_id' => $item['SampleMenuItem']['id'])
                ));
                foreach ($images as $j => $image) {
                    // Add sample images
                    $image_data['ItemImage'] = array(
                        'menu_item_id' => $iid,
                        'name' => $image['SampleItemImage']['name'],
                        'caption' => $image['SampleItemImage']['caption'],
                        'type' => $image['SampleItemImage']['type'],
                        'size' => $image['SampleItemImage']['size']
                    );
                    $this->ItemImage->tablePrefix = $prefix;
                    $this->ItemImage->create();
                    $this->ItemImage->save($image_data);
                }
                // Copy sample images
                $sample_path = getcwd().DS.'uploads'.DS.'sample'.DS.'item'.DS.$item['SampleMenuItem']['id'];
                $this->Upload->mkUploadDir($uid, $iid);
                $user_path = $this->Upload->getUploadDir($uid, $iid);
                $this->recurseCopy($sample_path, $user_path);
            }
        }
    }

    private function addTable($uid, $pid, $prefix) {
        for ($i=1; $i<=12; $i++) {
            $table['Table'] = array (
                'place_id' => $pid,
                'name' => 'Table #'.$i,
                'active' => 1
            );
            $this->Table->tablePrefix = $prefix;
            $this->Table->create();
            $this->Table->save($table);
        }
    }

    public function beforeFilter () {
        $this->Auth->allow('addPlace');
    }

	public function addPlace($uid, $prefix) {
        $place['Place'] = array (
            'name' => 'Sample Place',
            'slogan' => 'See a place with sample data',
            'description' => 'A place with 10 tables only to test'
        );
        $place['Place']['alias'] = str_replace(' ', '_', $place['Place']['name']);
        $this->Place->tablePrefix = $prefix;
        $this->Place->save($place);
        $pid = $this->Place->getLastInsertID();
        $places_user = array (
            'user_id' => $uid,
            'place_id' => $pid
        );
        $this->PlacesUser->tablePrefix = $prefix;
        $this->PlacesUser->save($places_user);

        // Add 12 tables
        $this->addTable($uid, $pid, $prefix);

        // Add sample foods & drinks
        $this->addItem($uid, $prefix);
    }

    public function beforeRender() {
        exit();
    }
}
