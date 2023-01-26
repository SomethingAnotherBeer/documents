<?php
namespace App\Data;



class DocumentsData
{
	private string $documents_status;
	private int $current_page;


	public function __construct(array $documents_params)
	{
		$this->documents_status = (array_key_exists('document_status', $documents_params)) ? $documents_params['document_status'] : '';
		$this->current_page = (array_key_exists('current_page', $documents_params)) ? $documents_params['current_page'] : 1;	
	}


	public function getDocumentsStatus():string
	{
		return $this->documents_status;
	}

	public function getCurrentPage():int
	{
		return ($this->current_page < 0) ? -$this->current_page : $this->current_page; 
	}

}