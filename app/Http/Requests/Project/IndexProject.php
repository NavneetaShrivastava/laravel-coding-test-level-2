<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project as ModelProject;


class IndexProject extends FormRequest
{
    const DEFAULT_LIMIT = 3;

    public function __construct(

    ) {
        parent::__construct();

    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['nullable'],
            'page' => ['nullable', 'numeric'],
            'limit' => ['nullable', 'numeric'],
            'sort_by' => ['nullable'],
        ];
    }

    public function data()
    {
        $query = ModelProject::query();
        if($this->q){
            $query = $query->where('name', 'Like', $this->q.'%');
        }
        if($this->sortBy){
            $sortDirection = $this->sortDirection ?? 'ASC';
            $query = $query->orderBy('name', $sortDirection);
        }
        $pageIndex = $this->pageIndex ?? 0;
        $pageSize = $this->pageSize ?? 3;
        return $query->paginate($pageSize, ['*'], 'p', $pageIndex);
    }
}