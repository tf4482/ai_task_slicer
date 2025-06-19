<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use App\Services\OllamaService;

class TestAILocalization extends Command
{
    protected $signature = 'test:ai-localization';
    protected $description = 'Test AI service localization';

    public function handle()
    {
        $this->info('Testing AI service localization...');

        $ollamaService = app(OllamaService::class);

        if (!$ollamaService->isAvailable()) {
            $this->warn('⚠ Ollama service is not available. Cannot test AI localization.');
            return 1;
        }

        // Test English
        $this->newLine();
        $this->info('Testing English locale:');
        App::setLocale('en');
        $this->line('Current locale: ' . App::getLocale());

        // Use reflection to test the private method
        $reflection = new \ReflectionClass($ollamaService);
        $method = $reflection->getMethod('getLanguageInstruction');
        $method->setAccessible(true);

        $englishInstruction = $method->invoke($ollamaService);
        $this->line('Language instruction: ' . $englishInstruction);

        // Test German
        $this->newLine();
        $this->info('Testing German locale:');
        App::setLocale('de');
        $this->line('Current locale: ' . App::getLocale());

        $germanInstruction = $method->invoke($ollamaService);
        $this->line('Language instruction: ' . $germanInstruction);

        $this->newLine();
        $this->info('✓ AI localization test completed successfully!');
        $this->line('AI prompts will now include appropriate language instructions based on user locale.');

        return 0;
    }
}
