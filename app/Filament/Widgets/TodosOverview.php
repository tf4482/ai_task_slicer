<?php

namespace App\Filament\Widgets;

use App\Models\Todo;
use App\Services\OllamaService;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Widgets\Widget;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;

class TodosOverview extends Widget implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    protected static string $view = 'filament.widgets.todos-overview';

    protected int | string | array $columnSpan = 'full';

    public function getTodos()
    {
        return auth()->user()->todos()
            ->whereNull('parent_id') // Only show main tasks
            ->with('subtasks') // Eager load subtasks
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function createAction(): Action
    {
        return Action::make('create')
            ->label(__('app.add_new_todo'))
            ->icon('heroicon-m-plus')
            ->form([
                TextInput::make('title')
                    ->label(__('app.title'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('app.enter_todo_title')),
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

    public $subtaskTitle = [];

    public function createSubtask($parentId, $title = null): void
    {
        $title = $title ?? ($this->subtaskTitle[$parentId] ?? '');

        if (empty($title)) {
            return;
        }

        Todo::create([
            'user_id' => auth()->id(),
            'title' => $title,
            'parent_id' => $parentId,
            'completed' => false,
        ]);

        // Clear the input
        $this->subtaskTitle[$parentId] = '';

        $this->dispatch('subtask-created');
    }

    public function toggleSubtask($subtaskId): void
    {
        $subtask = Todo::where('user_id', auth()->id())->findOrFail($subtaskId);
        $subtask->update(['completed' => !$subtask->completed]);

        $this->dispatch('subtask-updated');
    }

    public function deleteSubtask($subtaskId): void
    {
        Todo::where('user_id', auth()->id())->findOrFail($subtaskId)->delete();

        $this->dispatch('subtask-deleted');
    }

    public function generateSubtasks($todoId): void
    {
        $todo = Todo::where('user_id', auth()->id())->findOrFail($todoId);
        $ollamaService = app(OllamaService::class);

        if (!$ollamaService->isAvailable()) {
            Notification::make()
                ->title(__('app.ai_service_unavailable'))
                ->body(__('app.ai_service_unavailable_message'))
                ->danger()
                ->send();
            return;
        }

        try {
            $subtasks = $ollamaService->generateSubtasks($todo->title, 5);

            if (empty($subtasks)) {
                Notification::make()
                    ->title(__('app.no_subtasks_generated'))
                    ->body(__('app.no_subtasks_generated_message'))
                    ->warning()
                    ->send();
                return;
            }

            $createdCount = 0;
            foreach ($subtasks as $subtaskTitle) {
                if (!empty(trim($subtaskTitle))) {
                    Todo::create([
                        'user_id' => auth()->id(),
                        'title' => trim($subtaskTitle),
                        'parent_id' => $todoId,
                        'completed' => false,
                    ]);
                    $createdCount++;
                }
            }

            $this->dispatch('subtasks-generated');

        } catch (\Exception $e) {
            Notification::make()
                ->title(__('app.generation_failed'))
                ->body(__('app.generation_failed_message'))
                ->danger()
                ->send();
        }
    }

    public function improveTask($todoId): void
    {
        $todo = Todo::where('user_id', auth()->id())->findOrFail($todoId);
        $ollamaService = app(OllamaService::class);

        if (!$ollamaService->isAvailable()) {
            Notification::make()
                ->title(__('app.ai_service_unavailable'))
                ->body(__('app.ai_service_unavailable_message'))
                ->danger()
                ->send();
            return;
        }

        try {
            $improvedTitle = $ollamaService->improveTask($todo->title);

            if ($improvedTitle && trim($improvedTitle) !== $todo->title) {
                $todo->update(['title' => trim($improvedTitle)]);

                Notification::make()
                    ->title(__('app.task_improved'))
                    ->body(__('app.task_improved_message'))
                    ->success()
                    ->send();

                $this->dispatch('task-improved');
            } else {
                Notification::make()
                    ->title(__('app.no_improvement_needed'))
                    ->body(__('app.no_improvement_needed_message'))
                    ->info()
                    ->send();
            }

        } catch (\Exception $e) {
            Notification::make()
                ->title(__('app.ai_improvement_failed'))
                ->body(__('app.ai_improvement_failed_message'))
                ->danger()
                ->send();
        }
    }
}
