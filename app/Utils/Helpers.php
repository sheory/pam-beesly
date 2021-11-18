<?php

namespace App\Utils;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;

class Helpers
{
    public static function indexQueryBuilder (Request $request, array $relationships, $model, $order_by = 'asc', $fields = ['*'])
    {
        $limit = $request->all()['limit'] ?? 20;

        $order = $request->all()['order'] ?? null;
        if ($order !== null) {
            $order = explode(',', $order);
        }
        $order[0] = $order[0] ?? 'id';
        $order[1] = $order[1] ?? $order_by;

        $wheres   = $request->all()['where'] ?? [];
        $betweens = $request->all()['between'] ?? [];

        $likes   = $request->all()['like'] ?? null;
        $searchs = $request->all()['search'] ?? null;

        $result = $model->orderBy($order[0], $order[1])
            ->where(function ($query) use ($wheres, $likes, $betweens) {
                if (gettype($likes) == 'array') {
                    foreach ($likes as $like) {
                        $like = explode(',', $like);
                        if (count($like) != 2) throw new \Exception('Invalid "like" parameters, expected 2 passes ' . count($like));

                        $like[1] = '%' . $like[1] . '%';

                        if (strpos($like[0], '.') !== false) {
                            $relation = explode('.', $like[0]);
                            $query->whereHas($relation[0], function ($query) use ($relation, $like) {
                                $query->where($relation[1], 'like', $like[1]);
                            });
                        } else {
                            $query->where($like[0], 'like', $like[1]);
                        }
                    }
                }


                if (gettype($wheres) == 'array') {
                    foreach ($wheres as $where) {
                        $where = explode(',', $where);
                        if (count($where) < 2 || count($where) > 3) throw new \Exception('Invalid "where" parameters, expected 3 passes ' . count($where));

                        if (strpos($where[0], '.') !== false) {
                            $relations = explode('.', $where[0]);

                            $query->whereHas($relations[0], function ($query) use ($relations, $where) {
                                $values = array_slice($where, 1);

                                if (count($relations) > 2) {
                                    $query->whereHas($relations[1], function ($query) use ($relations, $values) {
                                        foreach ($values as $value) {
                                            $query->where($relations[2], $value);
                                        }
                                    });
                                } else {
                                    foreach ($values as $value) {
                                        $query->where($relations[1], $value);
                                    }
                                }
                            });
                        } else {
                            $query->where($where[0], $where[1]);
                        }
                    }
                }

                if (gettype($betweens) == 'array') {
                    foreach ($betweens as $between) {
                        $between = explode(',', $between);
                        if (count($between) != 3) throw new \Exception('Invalid "between" parameters, expected 3 passes ' . count($between));

                        $query->whereBetween($between[0], ["{$between[1]} 00:00:00", "{$between[2]} 23:59:59"]);
                    }
                }
//                dd($query->toSql(), $query->getBindings());
                return $query;
            })
            ->where(function ($query) use ($searchs) {
                if (gettype($searchs) == 'array') {
                    foreach ($searchs as $item) {
                        $item = explode(',', $item);
                        if (count($item) != 2) throw new \Exception('Invalid "search" parameters, expected 2 passes ' . count($item));
                        $item[1] = '%' . $item[1] . '%';

                        if (strpos($item[0], '.') !== false) {
                            $relation = explode('.', $item[0]);

                            $query->whereHas($relation[0], function ($query) use ($relation, $item) {
                                $query->orWhere($relation[1], 'like', $item[1]);
                            });
                        } else {
                            $query->orWhere($item[0], 'like', $item[1]);
                        }
                    }
                }
//                dd($query->toSql(), $query->getBindings());
            })
            ->with($relationships);

        if ($request->get('paginate', true)) {
            $result = $result->paginate($limit, $fields);
        } else {
            $result = $result->get($fields);
        }

        return $result;
    }

    public static function paginateCollection ($collection, $perPage = 20, $pageName = 'page', $fragment = null)
    {
        $currentPage      = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage($pageName);
        $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage);
        parse_str(request()->getQueryString(), $query);
        unset($query[$pageName]);
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $currentPage,
            [
                'pageName' => $pageName,
                'path'     => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
                'query'    => $query,
                'fragment' => $fragment
            ]
        );
    }

    public static function legalEntity ($document)
    {
        $cpf_cnpj = preg_replace('/[^a-zA-Z0-9_ -]/s', ' ', $document);
        return strlen($cpf_cnpj) == 14 ? 'PJ' : 'PF';
    }

    public static function cardNumberGenerator ()
    {
        $number = array();

        array_push(
            $number,
            9999,
            rand(1000, 9999),
            rand(1000, 9999),
            rand(1000, 9999),
        );

        return implode(" ", $number);
    }

    public static function cardHashGenerator ()
    {
        return (string)rand(1000000000000000, 9999999999999999);
    }

    public static function centsToMoney ($cents, $pattern = 'BRL')
    {
        return number_format(($cents / 100), 2, $pattern == 'BRL' ? ',' : '.', '');
    }

    public static function cardPasswordGenerator ()
    {
        return rand(1000, 9999);
    }

    public static function userPasswordGenerator ($length = 6)
    {
        return strtolower(substr(md5(uniqid()), 0, $length));
    }

    public static function userRevokeTokens ($user)
    {
        $tokens = $user->tokens->where('revoked', 0)->pluck('id');
        error_log("TOKENS: ".$tokens->count());
        if ($tokens->count()){
            Token::whereIn('id', $tokens)->update(['revoked' => true]);
            RefreshToken::whereIn('access_token_id', $tokens)->update(['revoked' => true]);
        }
    }


    public static function sanitizeString ($str)
    {
        return preg_replace('/[^0-9]/', '', $str);
    }

    public static function sanitizeStringWithLetters ($str)
    {
        return preg_replace("~[^A-Za-z0-9]~", '', $str);
    }
}
