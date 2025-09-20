<?php

namespace Saeedvir\LaravelProjectMarkdown\Tests\Commands;

use Saeedvir\LaravelProjectMarkdown\Commands\ProjectDbMarkdownCommand;
use Saeedvir\LaravelProjectMarkdown\Tests\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class ProjectDbMarkdownCommandTest extends TestCase
{
    public function test_command_can_be_called()
    {
        $this->artisan(ProjectDbMarkdownCommand::class)
            ->assertExitCode(0);
    }

    public function test_command_generates_markdown_file()
    {
        $outputPath = 'test-database-documentation.md';
        
        $this->artisan(ProjectDbMarkdownCommand::class, [
            '--output' => $outputPath
        ])->assertExitCode(0);

        $this->assertTrue(File::exists($outputPath));
        
        $content = File::get($outputPath);
        $this->assertStringContainsString('# Database Documentation', $content);
        
        // Clean up
        File::delete($outputPath);
    }

    public function test_command_with_test_table()
    {
        // Create a test table
        Schema::create('test_table', function ($table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        $outputPath = 'test-database-with-table.md';
        
        $this->artisan(ProjectDbMarkdownCommand::class, [
            '--output' => $outputPath,
            '--tables' => 'test_table'
        ])->assertExitCode(0);

        $this->assertTrue(File::exists($outputPath));
        
        $content = File::get($outputPath);
        $this->assertStringContainsString('test_table', $content);
        
        // Clean up
        File::delete($outputPath);
        Schema::dropIfExists('test_table');
    }
}
