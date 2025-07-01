<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class GenerateVoiceReminders extends Command
{
    protected $signature = 'reminders:generate-voice';
    protected $description = 'Generate voice reminder MP3 files using Python gTTS';

    public function handle()
    {
        $pythonScript = str_replace('/', '\\', base_path('scripts/generate_voice_reminders.py'));
        $outputDir = str_replace('/', '\\', public_path('sounds'));

        // Create sounds directory if it doesn't exist
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        // Build the command for Windows
        $command = [
            'python',
            $pythonScript,
            '--output',
            $outputDir
        ];

        $process = new Process($command);
        $process->setTimeout(300);

        try {
            $process->mustRun();
            $this->info($process->getOutput());
            $this->info('Voice reminders generated successfully!');
        } catch (ProcessFailedException $exception) {
            $this->error('Failed to generate voice reminders:');
            $this->error($exception->getMessage());
            $this->error('Command: ' . $process->getCommandLine());
            $this->error('Error Output: ' . $process->getErrorOutput());
        }
    }
}
