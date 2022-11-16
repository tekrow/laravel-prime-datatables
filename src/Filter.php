<?php

namespace Tekrow\PrimevueDatatables;

use Illuminate\Database\Eloquent\Builder;

class Filter
{
    const STARTS_WITH = 'startsWith';
    const CONTAINS = 'contains';
    const NOT_CONTAINS = 'notContains';
    const ENDS_WITH = 'endsWith';
    const EQUALS = 'equals';
    const NOT_EQUALS = 'notEquals';
    const IN = 'in';
    const LESS_THAN = 'lt';
    const LESS_THAN_OR_EQUAL_TO = 'lte';
    const GREATER_THAN = 'gt';
    const GREATER_THAN_OR_EQUAL_TO = 'gte';
    const BETWEEN = 'between';
    const DATE_IS = 'dateIs';
    const DATE_IS_NOT = 'dateIsNot';
    const DATE_BEFORE = 'dateBefore';
    const DATE_AFTER = 'dateAfter';
    public function __construct(public string $field, public ?string $value = null, public ?string $matchMode = self::CONTAINS)
    {
    }

    public function buildWhere(Builder &$q, ?bool $or = false)
    {
        $searchParts = explode(".", $this->field);
        if (count($searchParts) <= 4) {
            switch (count($searchParts)) {
                case 1:
                    $this->applyWhere($q, $searchParts[0], $or);
                    break;
                case 2:
                    if ($or) {
                        $q->orWhereHas($searchParts[0], function ($q1) use ($searchParts) {
                            $this->applyWhere($q1, $searchParts[1]);
                        });
                    } else {
                        $q->whereHas($searchParts[0], function ($q1) use ($searchParts) {
                            $this->applyWhere($q1, $searchParts[1]);
                        });
                    }

                    break;
                case  3:
                    if ($or) {
                        $q->orWhereHas($searchParts[0], function ($q1) use ($searchParts) {
                            $q1->whereHas($searchParts[1], function ($q2) use ($searchParts) {
                                $this->applyWhere($q2, $searchParts[2]);
                            });
                        });
                    } else {
                        $q->whereHas($searchParts[0], function ($q1) use ($searchParts) {
                            $q1->whereHas($searchParts[1], function ($q2) use ($searchParts) {
                                $this->applyWhere($q2, $searchParts[2]);
                            });
                        });
                    }
                    break;
                case  4:
                    if ($or) {
                        $q->orWhereHas($searchParts[0], function ($q1) use ($searchParts) {
                            $q1->whereHas($searchParts[1], function ($q2) use ($searchParts) {
                                $q2->whereHas($searchParts[2], function ($q3) use ($searchParts) {
                                    $this->applyWhere($q3, $searchParts[3]);
                                });
                            });
                        });
                    } else {
                        $q->whereHas($searchParts[0], function ($q1) use ($searchParts) {
                            $q1->whereHas($searchParts[1], function ($q2) use ($searchParts) {
                                $q2->whereHas($searchParts[2], function ($q3) use ($searchParts) {
                                    $this->applyWhere($q3, $searchParts[3]);
                                });
                            });
                        });
                    }
                    break;
                default:
                    break;
            }
        }
    }
    private function applyWhere(Builder &$q, string $field, ?bool $or = false)
    {
        switch ($this->matchMode) {
            case self::STARTS_WITH:
                if ($or) {
                    $q->orWhere($field, "LIKE", $this->value . "%");
                } else {
                    $q->where($field, "LIKE", $this->value . "%");
                }
                break;
            case self::NOT_CONTAINS:
                if ($or) {
                    $q->orWhere($field, "NOT LIKE", "%" . $this->value . "%");
                } else {
                    $q->where($field, "NOT LIKE", "%" . $this->value . "%");
                }
                break;
            case self::ENDS_WITH:
                if ($or) {
                    $q->orWhere($field, "LIKE", "%" . $this->value);
                } else {
                    $q->where($field, "LIKE", "%" . $this->value);
                }
                break;
            case self::EQUALS:
                if ($or) {
                    $q->orWhere($field, "=", $this->value);
                } else {
                    $q->where($field, "=", $this->value);
                }
                break;
            case self::NOT_EQUALS:
                if ($or) {
                    $q->orWhere($field, "!=", $this->value);
                } else {
                    $q->where($field, "!=", $this->value);
                }
                break;
            case self::IN:
                //TODO: Implement
                break;
            case self::LESS_THAN:
                if ($or) {
                    $q->orWhere($field, "<", $this->value);
                } else {
                    $q->where($field, "<", $this->value);
                }
                break;
            case self::LESS_THAN_OR_EQUAL_TO:
                if ($or) {
                    $q->orWhere($field, "<=", $this->value);
                } else {
                    $q->where($field, "<=", $this->value);
                }
                break;
            case self::GREATER_THAN:
                if ($or) {
                    $q->orWhere($field, ">", $this->value);
                } else {
                    $q->where($field, ">", $this->value);
                }
                break;
            case self::GREATER_THAN_OR_EQUAL_TO:
                if ($or) {
                    $q->orWhere($field, ">=", $this->value);
                } else {
                    $q->where($field, ">=", $this->value);
                }
                break;
            case self::BETWEEN:
                //TODO: implement
                break;

            case self::DATE_IS:
                if ($or) {
                    $q->orWhereDate($field, "=", $this->value);
                } else {
                    $q->whereDate($field, "=", $this->value);
                }
                break;

            case self::DATE_IS_NOT:
                if ($or) {
                    $q->orWhereDate($field, "!=", $this->value);
                } else {
                    $q->whereDate($field, "!=", $this->value);
                }
                break;

            case self::DATE_BEFORE:
                if ($or) {
                    $q->orWhereDate($field, "<=", $this->value);
                } else {
                    $q->whereDate($field, "<=", $this->value);
                }
                break;
            case self::DATE_AFTER:
                if ($or) {
                    $q->orWhereDate($field, ">", $this->value);
                } else {
                    $q->whereDate($field, ">", $this->value);
                }
                break;

            case self::CONTAINS:
            default:
                if ($or) {
                    $q->orWhere($field, "LIKE", "%" . $this->value . "%");
                } else {
                    $q->where($field, "LIKE", "%" . $this->value . "%");
                }
                break;
        }
    }
}
