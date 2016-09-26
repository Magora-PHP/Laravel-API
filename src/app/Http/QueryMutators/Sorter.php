<?php


namespace App\Http\QueryMutators;


use App\Exceptions\SorterHttpException;
use Illuminate\Database\Eloquent\Builder;

class Sorter
{

    /**
     * @var Builder
     */
    protected $query;

    /**
     * @var array
     */
    protected $sorts = [];

    public function __construct($sorts)
    {
        $this->parseSorts($sorts);
        return $this;
    }

    public function apply(/*Builder*/ $query)
    {
        //todo instanceof
        $this->query = $query;
        if (count($this->sorts) === 0) {
            return;
        }
        try {
            foreach ($this->sorts as $sort) {
                $direction = 'ASC';
                if ($sort[0] == '!') {
                    $direction = 'DESC';
                    $sort = substr($sort, 1);
                }
                $this->query->orderBy($sort, $direction);
            }
        } catch (\Exception $e) {
            throw new SorterHttpException();
        }
    }

    protected function parseSorts($sorts)
    {
        if (!is_string($sorts)) {
            return;
        }

        if ($sorts[0] === "'") {
            $sorts[0] = " ";
        }
        if ($sorts[strlen($sorts) - 1] === "'") {
            $sorts[strlen($sorts) - 1] = " ";
        }

        $sorts = explode('|', $sorts);
        foreach ($sorts as $sort) {
            $sort = trim($sort);
            array_push($this->sorts, $sort);
        }
    }

}
