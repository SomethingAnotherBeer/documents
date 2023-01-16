<?php
namespace App\Data;

class DocumentData
{
	private string $document_key;
	private string $document_payload;


	public function __construct(array $document_params)
	{
		$this->document_key = (array_key_exists('document_key', $document_params)) ? $document_params['document_key'] : '';
		$this->document_payload = (array_key_exists('document_payload', $document_params)) ? $document_params['document_payload'] : '';
	}



	public function getDocumentKey():string
	{
		return $this->document_key;
	}

	
	public function getDocumentPayload():string
	{
		return $this->document_payload;
	}

}