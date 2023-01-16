<?php
namespace App\Data;

class DocumentItemList
{
	private array $document_items;

	public function __construct(array $document_items)
	{
		$this->document_items = $document_items;
	}


	public function getDocumentItems():array
	{
		return $this->document_items;
	}

}