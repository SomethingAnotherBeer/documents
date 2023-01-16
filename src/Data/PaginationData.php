<?php
namespace App\Data;

class PaginationData
{
	private $current_page;
	private $per_page;

	public function __construct(array $pagination_params)
	{
		$this->current_page = (array_key_exists('page', $pagination_params)) ? $pagination_params['page'] : 1;
		$this->per_page = (array_key_exists('perPage', $pagination_params)) ? $pagination_params['perPage'] : $this->current_page + 1;
	}


	public function getCurrentPage():int
	{
		return $this->current_page;
	}

	public function getPerPage():int
	{
		return $this->per_page;
	}

	

}