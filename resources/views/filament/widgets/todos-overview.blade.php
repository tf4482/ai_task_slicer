<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            My Todo List
        </x-slot>

        <x-slot name="headerEnd">
            {{ $this->createAction }}
        </x-slot>

        <div class="space-y-4">
            @if($this->getTodos()->isEmpty())
                <div class="text-center py-8">
                    <div class="text-gray-400 text-lg mb-2">📝</div>
                    <p class="text-gray-500">No todos yet. Create your first todo!</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($this->getTodos() as $todo)
                        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 shadow-sm hover:shadow-md transition-shadow duration-200 {{ $todo->completed ? 'opacity-75' : '' }}">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100 {{ $todo->completed ? 'line-through text-gray-500' : '' }}">
                                    {{ $todo->title }}
                                </h3>
                                <div class="flex items-center space-x-1 ml-2">
                                    <button 
                                        wire:click="toggleTodo({{ $todo->id }})"
                                        class="p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                        title="{{ $todo->completed ? 'Mark as pending' : 'Mark as completed' }}"
                                    >
                                        @if($todo->completed)
                                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @endif
                                    </button>
                                    <button 
                                        wire:click="deleteTodo({{ $todo->id }})"
                                        wire:confirm="Are you sure you want to delete this todo?"
                                        class="p-1 rounded-full hover:bg-red-100 dark:hover:bg-red-900 transition-colors"
                                        title="Delete todo"
                                    >
                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            @if($todo->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 {{ $todo->completed ? 'line-through' : '' }}">
                                    {{ $todo->description }}
                                </p>
                            @endif
                            
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span class="flex items-center">
                                    @if($todo->completed)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            ✓ Completed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            ⏳ Pending
                                        </span>
                                    @endif
                                </span>
                                <span>{{ $todo->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </x-filament::section>

    <x-filament-actions::modals />
</x-filament-widgets::widget>
