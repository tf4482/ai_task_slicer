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
                ->title('AI Service Unavailable')
                ->body('The AI service is currently unavailable. Please try again later.')
                ->danger()
                ->send();
            return;
        }

        try {
            $subtasks = $ollamaService->generateSubtasks($todo->title, 5);

            if (empty($subtasks)) {
                Notification::make()
                    ->title('No Subtasks Generated')
                    ->body('Could not generate subtasks for this task. Please try a different task or add subtasks manually.')
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

            // Check if AI was actually used or fallback was used
            $isAiAvailable = $ollamaService->isAvailable();

            if ($isAiAvailable) {
                Notification::make()
                    ->title('AI Subtasks Generated')
                    ->body("Successfully created {$createdCount} AI-powered subtasks.")
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Smart Subtasks Generated')
                    ->body("Created {$createdCount} suggested subtasks. (AI service unavailable - using smart fallbacks)")
                    ->info()
                    ->send();
            }

            $this->dispatch('subtasks-generated');

        } catch (\Exception $e) {
            Notification::make()
                ->title('Generation Failed')
                ->body('Failed to generate subtasks. Please try again or add them manually.')
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
                ->title('AI Service Unavailable')
                ->body('The AI service is currently unavailable. Please try again later.')
                ->danger()
                ->send();
            return;
        }

        try {
            $improvedTitle = $ollamaService->improveTask($todo->title);

            if ($improvedTitle && trim($improvedTitle) !== $todo->title) {
                $todo->update(['title' => trim($improvedTitle)]);

                Notification::make()
                    ->title('Task Improved')
                    ->body('The task has been improved using AI.')
                    ->success()
                    ->send();

                $this->dispatch('task-improved');
            } else {
                Notification::make()
                    ->title('No Improvement Needed')
                    ->body('The AI found no improvements needed for this task.')
                    ->info()
                    ->send();
            }

        } catch (\Exception $e) {
            Notification::make()
                ->title('AI Improvement Failed')
                ->body('Failed to improve the task. Please try again.')
                ->danger()
                ->send();
        }
    }
}
