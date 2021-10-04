<?php # Item.php

// This is a sample Item class. 
// This class could be extended by individual applications.
class Item {
	
	// Item attributes are all protected:
	protected $id;
	protected $tipology;
	protected $category;
	protected $name;
	protected $price;
	protected $discounted;
	
	// Constructor populates the attributes:
	public function __construct($id, $tipology, $category, $name, $price, $discounted) {
	//private function __construct()	{
		$this->id = $id;
		$this->tipology = $tipology;
		$this->category = $category;
		$this->name = $name;
		$this->price = $price;
		$this->discounted = $discounted;
	}

	// Method that returns the ID:
	public function getId()	{
		return $this->id;
	}

	// Method that returns the category:
	public function getCategory() {
		return $this->category;
	}

	// Method that returns the category:
	public function getTipology() {
		return $this->tipology;
	}

	// Method that returns the name:
	public function getName() {
		return $this->name;
	}

	// Method that returns the price:
	public function getPrice() {
		return $this->price;
	}

	// Method that returns if the product 
	// is discounted:
	public function getDiscounted() {
		return $this->discounted;
	}

} // End of Item class.
