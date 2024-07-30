<?php

namespace App\Services\EnumOption;

use App\Models\Configuration\EnumOption;
use App\Repositories\EnumOption\EnumOptionRepositoryInterface;

class EnumOptionService
{
    protected $enumRepository;

    public function __construct(EnumOptionRepositoryInterface $enumRepository)
    {
        $this->enumRepository = $enumRepository;
    }

    public function listChildrens($names)
    {
        $names = explode(',', $names);
        $responses = EnumOption::whereIn('parent_id', function ($query) use ($names) {
            $query->select('id')
                ->from('enum_options')
                ->whereIn('code', $names);
        })
            ->where('status', true)
            ->get()
            ->groupBy('parent_id');

        $comboResponses = [];
        foreach ($responses as $index => $value) {
            $parent = EnumOption::find($index)->code;
            $comboResponses[$parent] = $value->toArray();
        }
        return $comboResponses;
    }
}
