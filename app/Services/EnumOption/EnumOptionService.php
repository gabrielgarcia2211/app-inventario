<?php

namespace App\Services\EnumOption;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Configuration\EnumOption;
use App\Repositories\EnumOption\EnumOptionRepositoryInterface;

class EnumOptionService
{
    protected $enumRepository;

    public function __construct(EnumOptionRepositoryInterface $enumRepository)
    {
        $this->enumRepository = $enumRepository;
    }

    public function getEnumsQuery()
    {
        return EnumOption::query()
            ->where('is_father', '!=', true)
            ->where('name', '!=', 'master padre')
            ->distinct();
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

    public function storeEnum(array $data)
    {
        DB::beginTransaction();
        try {
            $parent = EnumOption::find($data['parent_id']);
            if (isset($data['value1']) && $parent->code == 'category') {
                $value1 = $data['value1'] / 100;
            }
            $enum = [
                'parent_id' => $data['parent_id'],
                'name' => $data['name'],
                'status' => true,
                'value1' => $value1 ?? NULL,
            ];
            $enum = $this->enumRepository->create($enum);
            DB::commit();
            return $enum;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function updateEnum(array $data, $id)
    {
        DB::beginTransaction();
        try {
            $parent = EnumOption::find($data['parent_id']);
            if (isset($data['value1']) && $parent->code == 'category') {
                $value1 = $data['value1'] / 100;
            }
            $enum = [
                'parent_id' => $data['parent_id'],
                'name' => $data['name'],
                'status' => true,
                'value1' => $value1 ?? NULL,
            ];
            $enum = $this->enumRepository->update($id, $enum);
            DB::commit();
            return $enum;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function deleteEnum($id)
    {
        try {
            $this->enumRepository->delete($id);
            return true;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }


    public static function getByWhere($where)
    {
        return EnumOption::where($where)->get();
    }
}
