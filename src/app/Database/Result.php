<?php
namespace App\Database;

class Result extends \ArrayObject
{
    public $currentPage = 0;
    public $prevPage = 0;
    public $nextPage = 0;
    public $itemPerPage = 0;
    public $totalItems = 0;
    public $firstPage = 0;
    public $lastPage = 0;
    public $totalPages = 0;

    /**
     * Undocumented function
     *
     * @param array $input
     * @param int? $page
     * @param int? $itemPerPage
     * @param int? $totalItems
     */
    public function __construct($input, $page = null, $itemPerPage = null, $totalItems = null)
    {
        if ($page !== null && $itemPerPage !== null && $totalItems !== null) {
            $page = intval($page) <= 0 ? 1 : intval($page);
            $this->currentPage = $page;
            $this->nextPage = $page + 1;
            $this->prevPage = ($page - 1) <= 0 ? 0 : $page - 1;
            $this->itemPerPage = intval($itemPerPage);
            $this->totalItems = intval($totalItems);
            $this->firstPage = 1;
            $this->lastPage = floor($this->totalItems / $this->itemPerPage) + 1;
            $this->totalPages = floor($this->totalItems / $this->itemPerPage) + 1;
        }
        parent::__construct($input);
    }

    /**
     * Convert to array
     *
     * @return array
     */
    public function getResult()
    {
        return $this->getArrayCopy();
    }
}
