<?php
namespace App\Data;

class DocumentItem
{
	private string $document_key;
	private string $document_status;
	private mixed $document_payload;
	private string $createAt;
	private string $modifyAt;


	public function getDocumentKey():string
	{
		return $this->document_key;
	}

	public function setDocumentKey(string $document_key):self
	{
		$this->document_key = $document_key;

		return $this;
	}

	public function getDocumentStatus():string
	{
		return $this->document_status;
	}

	public function setDocumentStatus(string $document_status):self
	{
		$this->document_status = $document_status;

		return $this;
	}


	public function getDocumentPayload():mixed
	{
		return $this->document_payload;
	}

	public function setDocumentPayload(string $document_payload):self
	{
		$this->document_payload = json_decode($document_payload);

		return $this;
	}



	public function getCreateAt():string
	{
		return $this->createAt;
	}

	public function setCreateAt(string $createAt):self
	{
		$this->createAt = $createAt;

		return $this;
	}

	public function getModifyAt():string
	{
		return $this->modifyAt;
	}

	public function setModifyAt(string $modifyAt):self
	{
		$this->modifyAt = $modifyAt;

		return $this;
	}


}