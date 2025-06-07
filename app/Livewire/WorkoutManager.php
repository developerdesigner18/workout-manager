<?php

namespace App\Livewire;

use App\Models\Workout;
use Livewire\Component;
use Livewire\WithPagination;

class WorkoutManager extends Component
{
    use WithPagination;

    public $search = '';
    public $trainerFilter = '';
    public $showModal = false;
    public $editingWorkout = null;

    public $title = '';
    public $description = '';
    public $trainer = '';
    public $date = '';
    public $slots = 1;
    public $is_active = true;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'trainer' => 'required|string|max:255',
        'date' => 'required|date|after:now',
        'slots' => 'required|integer|min:1|max:100',
        'is_active' => 'boolean',
    ];

    public function render()
    {
        $workouts = Workout::byUser(auth()->id())
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->when($this->trainerFilter, function ($query) {
                $query->where('trainer', 'like', '%' . $this->trainerFilter . '%');
            })
            ->orderBy('date', 'asc')
            ->paginate(10);

        return view('livewire.workout-manager', compact('workouts'));
    }

    public function openModal($workoutId = null)
    {
        if ($workoutId) {
            $this->editingWorkout = Workout::findOrFail($workoutId);
            $this->fill($this->editingWorkout->toArray());
        } else {
            $this->resetForm();
        }
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function save()
    {
        $this->validate();

        if ($this->editingWorkout) {
            $this->editingWorkout->update([
                'title' => $this->title,
                'description' => $this->description,
                'trainer' => $this->trainer,
                'date' => $this->date,
                'slots' => $this->slots,
                'is_active' => $this->is_active,
            ]);
            session()->flash('message', 'Workout updated successfully!');
        } else {
            Workout::create([
                'title' => $this->title,
                'description' => $this->description,
                'trainer' => $this->trainer,
                'date' => $this->date,
                'slots' => $this->slots,
                'is_active' => $this->is_active,
                'user_id' => auth()->id(),
            ]);
            session()->flash('message', 'Workout created successfully!');
        }

        $this->closeModal();
    }

    public function delete($workoutId)
    {
        $workout = Workout::findOrFail($workoutId);
        $workout->delete();
        session()->flash('message', 'Workout deleted successfully!');
    }

    private function resetForm()
    {
        $this->editingWorkout = null;
        $this->title = '';
        $this->description = '';
        $this->trainer = '';
        $this->date = '';
        $this->slots = 1;
        $this->is_active = true;
    }
}
