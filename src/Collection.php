<?php
/*
 * Queue assets and manage dependencies
 *
 * Copyright (c) 2015 Valéry Ambroise <vambroise@matilis.mu>

 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:

 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
 
 namespace MatilisLabs\AssetQueue;
 
 use MatilisLabs\AssetQueue\Asset\AssetInterface;
 use MatilisLabs\AssetQueue\Asset\Asset;
 
 class Collection implements \SeekableIterator{
	 
	 private $assets = array();
	 private $collection_id;
	 
	 private $position;
	 
	 public function __construct($collection_id){
	 	 $this->position = 0;
		 $this->collection_id = $collection_id;
	 }
	 
	 public function getAssets(){
		 return $this->assets;
	 }
	 
	 public function resolve(){
		 $raw = $this->getAssets();

		 $resolved = array();
		 
		 foreach($raw as $key => $asset){
		 	 if($asset instanceof AssetInterface){
				$resolved[$key] = $asset->getLocation(); 
				$asset->resolved = true;
		 	 } 
		 }
		 
		 return $resolved;
	 }	 

	 
	 public function addAsset(AssetInterface $asset){
		 if(!property_exists($this, $asset->getKey())){
		 	 $this->assets[] = $asset;
		 	 $this->{$asset->getKey()} = $asset;
		 }else{
			 $this->{$asset->getKey()}->setLocation($asset->getLocation());
		 }	 	 
		 
	 }
	 
	 public function pushToPosition(AssetInterface $asset, $position){
	 	 $position_normalized = ($position < 0)? 0: $position;
	 	 
	 	 foreach($this->assets as $k => $current_asset){
			 if($current_asset->getLocation() === $asset->getLocation()){
				 unset($this->assets[$k]);
			 }
	 	 }	 	 
	 	 
	 	 array_insert($this->assets, $asset, $position_normalized);
	 	 
	 }
	 
	 public function add($location, $key){
		 $asset = new Asset($this);
		 $asset->setKey($key);
		 $asset->setLocation($location);
		 
		 $this->addAsset($asset);
		 
		 return $asset;
	 }
	 
	 public function get($key){
	 	 foreach($this->assets as $asset){
			 if($asset instanceof AssetInterface && $asset->getKey() === $key){
				 return $asset;
			 }			 
	 	 }
	 }
	 
	 public function __get($name){
		 return $this->get($name);
	 }
	 
	 public function current(){
		 return $this->assets[$this->position];
	 } 
	 
	 public function key(){
		 return $this->position;
	 } 
	 
	 public function next(){
		 ++$this->position;
	 } 
	 
	 public function rewind(){
		 $this->position = 0;
	 }
	 
	 public function seek($position = 0){
		 return $this->assets[$position];
	 } 
	 
	 public function valid(){
		 return isset($this->assets[$this->position]);
	 }	

	 public function __toString(){
		 return $this->assets;
	 }		 
	  
 }  
 
function array_insert(&$array, $element, $position=null) {
	if (count($array) == 0) {
		$array[] = $element;
	}
	
	elseif (is_numeric($position) && $position < 0) {
		if((count($array)+$position) < 0) {
			$array = array_insert($array,$element,0);
		}
		else {
			$array[count($array)+$position] = $element;
		}
	}
	
	elseif (is_numeric($position) && isset($array[$position])) {
		$part1 = array_slice($array,0,$position,true);
		$part2 = array_slice($array,$position,null,true);
		$array = array_merge($part1,array($position=>$element),$part2);
		foreach($array as $key=>$item) {
			if (is_null($item)) {
				unset($array[$key]);
			}
		}
	}
	
	elseif (is_null($position)) {
		$array[] = $element;
	} 
	 
	elseif (!isset($array[$position])) {
		$array[$position] = $element;
	}
	
	$array = array_merge($array);
	return $array;
}

?>
