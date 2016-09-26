<?php


namespace App\Http\QueryMutators;


use App\Exceptions\FilterHttpException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Filter
{

    /**
     * @var Builder
     */
    protected $query;

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var array
     */
    protected $mapper = [
        'eq' => '=',
        'neq' => '!=',
        'lk' => 'like',
        'ilk' => 'ilike',
        'gt' => '>',
        'lt' => '<',
        'in' => 'in',
        //todo not null magic
//        'nnul' => 'not null'
    ];

    public function __construct($filter)
    {
        $this->parseFilter($filter);
        return $this;
    }

    public function apply(/*Builder*/ $query)
    {
        //todo instanceof
        $this->query = $query;
        if (count($this->filters) === 0) {
            return;
        }
        try {
            foreach ($this->filters as $filter) {
                $this->applyFilter($filter);
            }
        } catch (\Exception $e) {
            throw new FilterHttpException();
        }
    }

    protected function parseFilter($filter)
    {
        if ($filter === null || !is_string($filter)) {
            return;
        }

        if ($filter[0] === "'") {
            $filter[0] = " ";
        }
        if ($filter[strlen($filter) - 1] === "'") {
            $filter[strlen($filter) - 1] = " ";
        }

        $filters = explode('|', $filter);
        foreach ($filters as $filter) {
            $filter = trim($filter);
            array_push($this->filters, $filter);
        }
    }

    protected function applyFilter($filter)
    {
        $matches = $this->parseOne($filter);
        $this->applyOne($matches);
    }

    protected function parseOne($filter)
    {
        preg_match(
            '#^([a-z._]+) (' . implode('|', array_keys($this->mapper)) . ') (.+)$#',
            $filter,
            $matches,
            PREG_OFFSET_CAPTURE
        );

        return $matches;
    }

    protected function applyOne($matches)
    {

        if ($matches[2][0] == 'in') {
            $this->query->whereRaw($matches[0][0]);
            return;
        }

        if ($matches[3][0] == 'now()') {
            $matches[3][0] = Carbon::now();
        }
        $this->query->where($matches[1][0], $this->mapper[$matches[2][0]], $matches[3][0]);

    }

}
