<?php

namespace App\Livewire;

use App\Models\Member;
use Livewire\Component;
use Livewire\WithPagination;

class PublicMemberList extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $members = Member::with(['user', 'trainingPackage'])
            ->whereHas('user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.public-member-list', [
            'members' => $members
        ]);
    }
}
