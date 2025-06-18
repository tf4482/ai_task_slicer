<?php

namespace App\Filament\Widgets;

use App\Models\Todo;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Widgets\Widget;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class TodosOverview extends Widget implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    protected static string $view = 'filament.widgets.todos-overview';

    protected int | string | array $columnSpan = 'full';

    public function getTodos()
    {
        return auth()->user()->todos()->orderBy('created_at', 'desc')->get();
    }

    public function createAction(): Action
    {
        return Action::make('create')
            ->label('Add New Todo')
            ->icon('heroicon-m-plus')
            ->form([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter todo title...'),
            ])
            ->action(function (array $data): void {
                Todo::create([
                    'user_id' => auth()->id(),
                    'title' => $data['title'],
                    'completed' => false,
                ]);
                
                $this->dispatch('todo-created');
            });
    }

    public function toggleTodo($todoId): void
    {
        $todo = Todo::where('user_id', auth()->id())->findOrFail($todoId);
        $todo->update(['completed' => !$todo->completed]);
        
        $this->dispatch('todo-updated');
    }

    public function deleteTodo($todoId): void
    {
        Todo::where('user_id', auth()->id())->findOrFail($todoId)->delete();
        
        $this->dispatch('todo-deleted');
    }
}
