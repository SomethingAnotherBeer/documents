<?php
namespace App\Service;

class PaginationService
{
	private int $current_page;
	private int $rows_count;



	public function setCurrentPage(int $current_page):self
	{
		$this->current_page = $current_page;

		return $this;
	}

	public function getCurrentPage():int
	{
		return $this->current_page;
	}



	public function setRowsCount(int $rows_count):self
	{
		$this->rows_count = $rows_count;

		return $this;
	}


	public function getRowsCount():int
	{
		return $this->rows_count;
	}


	public function getOffset():int
	{

		return ( $this->current_page - 1 ) * $this->current_page;
	}

}