<?php

namespace App\Livewire\Utils;

use Livewire\Component;

class SearchableSelect extends Component
{
    public string $keyword = "";
    public string | int $selectedItem = "";
    public string $id = 'searchableSelect';
    public string $placeholder = "Ketikkan sesuatu";
    public string $model = "App\Models\Polyclinic";
    public string $labelKey = "";
    public string $valueKey = "";
    public $results;

    public function updated(): void
    {
        if($this->keyword){
            $model = new $this->model();
            $this->results = $model->where($this->labelKey, "LIKE", "%{$this->keyword}%")->get();
        }else{
            $this->results = null;
        }
    }

    public function clearSearch() : void
    {
        $this->results = null;
        $this->keyword = "";
    }

    public function selected($data): void
    {
        $this->selectedItem = $data;
        $this->keyword = $data;
        $this->results = null;
    }

    public function render()
    {
        return view('livewire.utils.searchable-select');
    }

    public function select()
    {

    }


}
