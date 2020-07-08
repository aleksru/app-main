<?php


namespace App\Http\Requests;

use Yajra\DataTables\Utilities\Request;

class DataTableRequest extends Request
{
    /**
     * @param string $name
     * @return int|null
     */
    public function getColumnIndexByName(string $name): ?int
    {
        foreach ($columns = $this->columns() as $index => $column){
            if($this->columnName($index) === $name){
                return $index;
            }
        }

        return null;
    }
}