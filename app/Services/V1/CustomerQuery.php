<?php

namespace App\Services\V1;

use Illuminate\Http\Request;

class CustomerQuery {
    protected array $safeParams = [
        'name' => ['eq'],
        'type' => ['eq'],
        'email' => ['eq'],
        'address' => ['eq'],
        'city' => ['eq'],
        'state' => ['eq'],
        'postalCode' => ['eq', 'gt', 'lt'],
    ];

    protected array $columnMap = [
        'postalCode' => 'postal_code',
    ];

    protected array $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'gt' => '>',
        'lte' => '<=',
        'gte' => '>=',
    ];

    public function transform(Request $request) {
        $eloQuery = [];
        foreach($this->safeParams as $param => $operators) {
            $query = $request->query($param);
            if(!isset($query)) {
                continue;
            }
            $column = $this->columnMap[$param] ?? $param;
            foreach($operators as $operator) {
                if(isset($query[$operator])) {
                    $eloQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }

        return $eloQuery;
    }
}
