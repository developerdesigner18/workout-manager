<?php

namespace App\Livewire;

use App\Models\Workout;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public $search = '';
    public $trainerFilter = '';
    public $showModal = false;
    public $editingWorkout = null;

    // Form fields
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

    protected $messages = [
        'title.required' => 'The workout title is required.',
        'description.required' => 'Please provide a workout description.',
        'trainer.required' => 'The trainer name is required.',
        'date.required' => 'Please select a workout date.',
        'date.after' => 'The workout date must be in the future.',
        'slots.required' => 'Please specify the number of available slots.',
        'slots.min' => 'There must be at least 1 slot available.',
        'slots.max' => 'Maximum 100 slots allowed.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTrainerFilter()
    {
        $this->resetPage();
    }

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
            ->paginate(6);

        $stats = [
            'total' => Workout::byUser(auth()->id())->count(),
            'active' => Workout::byUser(auth()->id())->active()->count(),
            'upcoming' => Workout::byUser(auth()->id())->where('date', '>', now())->count(),
        ];

        return view('livewire.dashboard', compact('workouts', 'stats'));
    }

    public function openModal($workoutId = null)
    {
        if ($workoutId) {
            $this->editingWorkout = Workout::findOrFail($workoutId);
            $this->fill([
                'title' => $this->editingWorkout->title,
                'description' => $this->editingWorkout->description,
                'trainer' => $this->editingWorkout->trainer,
                'date' => $this->editingWorkout->date->format('Y-m-d\TH:i'),
                'slots' => $this->editingWorkout->slots,
                'is_active' => $this->editingWorkout->is_active,
            ]);
        } else {
            $this->resetForm();
        }
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
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

        if ($workout->user_id !== auth()->id()) {
            session()->flash('error', 'Unauthorized action.');
            return;
        }

        $workout->delete();
        session()->flash('message', 'Workout deleted successfully!');
    }

    public function logout()
    {
        auth()->logout();
        return redirect('/login');
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
