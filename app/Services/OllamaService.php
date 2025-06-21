<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;

class OllamaService
{
    private string $baseUrl;
    private int $timeout;

    public function __construct()
    {
        $this->baseUrl = config('services.ollama.url');
        $this->timeout = config('services.ollama.timeout', 30);
    }

    /**
     * Generate text using Ollama
     */
    public function generate(string $prompt, ?string $model = null, array $options = []): ?string
    {
        try {
            // Use default model if none specified
            $model = $model ?? config('services.ollama.default_model');

            $response = Http::timeout($this->timeout)
                ->post("{$this->baseUrl}/api/generate", [
                    'model' => $model,
                    'prompt' => $prompt,
                    'stream' => false,
                    'options' => array_merge([
                        'num_ctx' => (int) config('services.ollama.token_limit', 12800),
                        'num_predict' => (int) config('services.ollama.response_tokens', 1000),
                    ], $options),
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['response'] ?? null;
            }

            Log::error('Ollama API error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'url' => "{$this->baseUrl}/api/generate",
                'model' => $model,
                'prompt' => substr($prompt, 0, 100) . '...',
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Ollama connection error', [
                'message' => $e->getMessage(),
                'url' => $this->baseUrl,
            ]);

            return null;
        }
    }

    /**
     * Generate subtasks for a given main task
     */
    public function generateSubtasks(string $mainTask, int $count = 5): array
    {
        $languageInstruction = $this->getLanguageInstruction();

        $prompt = "{$languageInstruction}

Task: {$mainTask}

Generate {$count} specific and actionable subtasks. Each subtask should be detailed and include concrete steps, numbers, or timeframes where relevant.

IMPORTANT: Do not include any thinking process, explanations, or <think> tags. Return only a numbered list, one subtask per line.

Example:
1. Research 5 car models under \$25,000 on automotive websites
2. Visit 3 local dealerships this weekend for test drives
3. Get insurance quotes from 3 different companies

Now generate {$count} subtasks for: {$mainTask}";

        // Use the default model directly
        $model = config('services.ollama.default_model');
        $response = $this->generate($prompt, $model);

        if ($response) {
            return $this->parseSubtasks($response, $count);
        }

        // If AI generation fails, return empty array or basic message
        return ["Unable to generate subtasks - AI service unavailable"];
    }


    /**
     * Parse subtasks from AI response
     */
    private function parseSubtasks(string $response, int $count): array
    {
        // Remove thinking tags and content
        $response = preg_replace('/<think>.*?<\/think>/s', '', $response);
        $response = preg_replace('/<think>.*$/s', '', $response);

        $lines = explode("\n", trim($response));
        $subtasks = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            // Skip lines that look like thinking process
            if (strpos($line, '<think>') !== false || strpos($line, '</think>') !== false) {
                continue;
            }

            // Remove numbering (1., 2., -, etc.)
            $line = preg_replace('/^\d+\.\s*/', '', $line);
            $line = preg_replace('/^-\s*/', '', $line);
            $line = trim($line);

            if (!empty($line) && strlen($line) > 3) {
                $subtasks[] = $line;
            }
        }

        return array_slice($subtasks, 0, $count);
    }

    /**
     * Get available models with fallbacks
     */
    private function getAvailableModels(): array
    {
        $models = $this->getModels();

        // If no models found, try common model names in order of preference
        if (empty($models)) {
            return [
                config('services.ollama.default_model')
            ];
        }

        // Prioritize the configured default model if it exists
        $defaultModel = config('services.ollama.default_model');
        if (in_array($defaultModel, $models)) {
            // Move default model to the front
            $models = array_diff($models, [$defaultModel]);
            array_unshift($models, $defaultModel);
        }

        return $models;
    }

    /**
     * Improve or expand a task description
     */
    public function improveTask(string $task): ?string
    {
        $languageInstruction = $this->getLanguageInstruction();

        $prompt = "{$languageInstruction}

IMPORTANT: Keep the original task '{$task}' as the main focus. DO NOT change the core task or replace it with something different. Only add specific details, timeframes, and methods to make it more actionable while preserving the original intent.

Add specific details to this task: '{$task}'

Enhance it by adding:
- Specific timeframes or deadlines
- Concrete methods, tools, or approaches
- Measurable quantities or targets
- Clear success criteria
- Specific resources or locations

Examples of proper enhancements (keeping original task intact):
- 'Buy a car' → 'Buy a car: Research 5 reliable models under \$25,000, visit 3 dealerships, and complete purchase within 4 weeks'
- 'Exercise more' → 'Exercise more: Go to gym 3 times per week for 45-minute sessions focusing on cardio and strength training'
- 'Learn programming' → 'Learn programming: Complete Python fundamentals course on Codecademy and build 2 practice projects within 8 weeks'

Return only the enhanced task description that starts with the original task, without any additional explanation:";

        // Use the default model directly
        $model = config('services.ollama.default_model');
        $response = $this->generate($prompt, $model);

        return $response;
    }

    /**
     * Check if Ollama server is available
     */
    public function isAvailable(): bool
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/api/tags");
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get available models
     */
    public function getModels(): array
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/api/tags");

            if ($response->successful()) {
                $data = $response->json();
                return collect($data['models'] ?? [])
                    ->pluck('name')
                    ->toArray();
            }

            return [];
        } catch (\Exception $e) {
            Log::error('Failed to fetch Ollama models', [
                'message' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Get language instruction for AI prompts based on current locale
     */
    private function getLanguageInstruction(): string
    {
        $locale = App::getLocale();

        switch ($locale) {
            case 'de':
                return "WICHTIG: Antworte ausschließlich auf Deutsch. Alle Aufgaben, Unteraufgaben und Verbesserungen müssen in deutscher Sprache formuliert werden. Verändere niemals die ursprüngliche Aufgabe, sondern erweitere sie nur mit spezifischen Details.";
            case 'en':
            default:
                return "IMPORTANT: Respond exclusively in English. All tasks, subtasks, and improvements must be formulated in English. Never change the original task, only enhance it with specific details.";
        }
    }
}
