<?php

namespace Saeedvir\LaravelProjectMarkdown\Tests\Commands;

use Saeedvir\LaravelProjectMarkdown\Commands\ProjectFilesMarkdownCommand;
use Saeedvir\LaravelProjectMarkdown\Tests\TestCase;
use Illuminate\Support\Facades\File;

class ProjectFilesMarkdownCommandTest extends TestCase
{
    public function test_command_can_be_called()
    {
        $this->artisan(ProjectFilesMarkdownCommand::class)
            ->assertExitCode(0);
    }

    public function test_command_generates_markdown_file()
    {
        $outputPath = 'test-files-documentation.md';
        
        $this->artisan(ProjectFilesMarkdownCommand::class, [
            '--output' => $outputPath
        ])->assertExitCode(0);

        $this->assertTrue(File::exists($outputPath));
        
        $content = File::get($outputPath);
        $this->assertStringContainsString('# Project Files Documentation', $content);
        
        // Clean up
        File::delete($outputPath);
    }

    public function test_command_respects_exclude_option()
    {
        $outputPath = 'test-exclude-documentation.md';
        
        $this->artisan(ProjectFilesMarkdownCommand::class, [
            '--output' => $outputPath,
            '--exclude' => '*.php'
        ])->assertExitCode(0);

        $this->assertTrue(File::exists($outputPath));
        
        $content = File::get($outputPath);
        $this->assertStringContainsString('# Project Files Documentation', $content);
        
        // Clean up
        File::delete($outputPath);
    }
}
