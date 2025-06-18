<?php

namespace App\Console\Commands;

use App\Services\OllamaService;
use Illuminate\Console\Command;

class TestOllama extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ollama:test {--prompt= : Test prompt to send to Ollama}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test connection to Ollama server';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ollamaService = app(OllamaService::class);

        $this->info('Testing Ollama connection...');
        $this->info('Server URL: ' . config('services.ollama.url'));
        $this->info('Default Model: ' . config('services.ollama.default_model'));

        // Test availability
        if (!$ollamaService->isAvailable()) {
            $this->error('❌ Ollama server is not available at ' . config('services.ollama.url'));
            $this->info('Please ensure:');
            $this->info('1. Ollama is running on the server');
            $this->info('2. The server is accessible from this machine');
            $this->info('3. The URL in .env is correct: ' . config('services.ollama.url'));
            return 1;
        }

        $this->info('✅ Ollama server is available!');

        // Get available models
        $this->info('Fetching available models...');
        $models = $ollamaService->getModels();
        
        if (empty($models)) {
            $this->warn('⚠️  No models found or unable to fetch models');
        } else {
            $this->info('Available models:');
            foreach ($models as $model) {
                $this->line('  - ' . $model);
            }
        }

        // Test generation if prompt provided
        $prompt = $this->option('prompt');
        if ($prompt) {
            $this->info('Testing text generation...');
            $this->info('Prompt: ' . $prompt);
            
            $response = $ollamaService->generate($prompt);
            
            if ($response) {
                $this->info('✅ Generation successful!');
                $this->info('Response:');
                $this->line($response);
            } else {
                $this->error('❌ Generation failed');
            }
        }

        // Test subtask generation
        $this->info('Testing subtask generation...');
        $testTask = 'Plan a birthday party';
        $subtasks = $ollamaService->generateSubtasks($testTask, 3);
        
        if (!empty($subtasks)) {
            $this->info("✅ Subtask generation successful for: '{$testTask}'");
            $this->info('Generated subtasks:');
            foreach ($subtasks as $i => $subtask) {
                $this->line('  ' . ($i + 1) . '. ' . $subtask);
            }
        } else {
            $this->error('❌ Subtask generation failed');
        }

        $this->info('Test completed!');
        return 0;
    }
}
