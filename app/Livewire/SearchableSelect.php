<?php
// app/Livewire/SearchableSelect.php
namespace App\Livewire;

use Livewire\Component;

class SearchableSelect extends Component
{
    public $options = [];
    public $selected = null;
    public $search = '';
    public $placeholder = 'Seleccione una opción...';
    public $name = '';
    
    public function updatedSearch()
    {
        // Filtrar opciones
    }
    
    public function selectOption($value)
    {
        $this->selected = $value;
        $this->search = '';
        $this->dispatch('selected', $this->name, $value);
    }
    
    public function render()
    {
        $filteredOptions = $this->options;
        if ($this->search) {
            $filteredOptions = array_filter($this->options, function($opt) {
                return stripos($opt['text'], $this->search) !== false;
            });
        }
        
        return view('livewire.searchable-select', [
            'filteredOptions' => $filteredOptions
        ]);
    }
}