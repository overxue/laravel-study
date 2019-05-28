<?php

class ShopProduct 
{
	public $title = 'default product';
	public $producerMainName = 'main name';
	public $producerFirstName = 'first name';
	public $price = 0;

	public function __construct($title, $firstName, $mainName, $price)
	{
		$this->title = $title;
		$this->producerFirstName = $firstName;
		$this->producerMainName = $mainName;
		$this->price = $price;
	}

	public function getProducer() {
		return $this->producerMainName.$this->producerFirstName;
	}
}

$product1 = new ShopProduct('my name', 'xue', 'cong', 10);
var_dump($product1);
var_dump($product1->getProducer());