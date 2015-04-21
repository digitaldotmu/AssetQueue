<?php
/*
 * Queue assets and manage dependencies
 *
 * Copyright (c) 2004-2013 Valéry Ambroise <vambroise@matilis.mu>

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
 
 namespace MatilisLabs\AssetQueue\Asset;
 
 use MatilisLabs\AssetQueue\Asset\AssetInterface;
 use MatilisLabs\AssetQueue\Collection;
 
 class Asset implements AssetInterface{
 	 private $key;
 	 private $location;
 	 private $dependencies = array();
 	 private $dependents = array();
 	 private $collection;
 	 
 	 public $resolved = false;
 	 
 	 public function __construct(Collection $collection){
		 $this->collection = $collection;
 	 }
	 
	 public function setKey($key){
		 $this->key = $key;
	 }
	 
	 public function getKey(){
		 return $this->key;
	 }
	 
	 public function setLocation($location){
		 $this->location = $location;
	 }
	 
	 public function getLocation(){
		 return $this->location;
	 }
	 
	 public function dependsOn($key){
	 	 //Check if dependency has already been added
	 	 if(isset($this->collection->{$key}) && $this->collection->{$key} instanceof AssetInterface){
		 	$this->collection->{$key}->addDependent($this);
		 	$this->addDependency($this->collection->{$key});			 
	 	 }else{
			 $dependency = $this->collection->add('', $key);
			 $this->collection->{$key}->addDependent($this);
			 $this->addDependency($this->collection->{$key});
	 	 }

		 
		 return $this;
	 }
	 
	 public function resolveDependencies(){
		 return $this->dependencies;
	 }
	 
	 public function resolveDependents(){
		 return $this->dependents;
	 }
	 
	 public function addDependent(AssetInterface $asset){
		 $this->dependents[] = $asset;
	 }
	 
	 public function addDependency(AssetInterface $asset){
	 	 $current_position = $this->getPosition();
	 	 $dependency_position = $asset->getPosition();
	 	 
	 	 if($dependency_position >= $current_position){
			 $this->collection->pushToPosition($asset, $current_position - 1);
	 	 }
	 	 
		 $this->dependencies[] = $asset;
	 }	

	 public function __toString(){
		 return $this->location;
	 }	
	 
	 public function getPosition(){
	 	 foreach($this->collection->getAssets() as $k => $asset){
			 if($asset instanceof AssetInterface && $asset->getKey() === $this->getKey()){
				 return $k;
			 }			 
	 	 }		 
	 }	  	 	 
 }  
?>
