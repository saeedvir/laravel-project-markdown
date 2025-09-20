# API Documentation

## Overview

This document provides comprehensive API documentation for the Laravel Project Markdown package, including command signatures, configuration options, and output formats.

## Commands

### 1. Project Files Markdown Command

**Command**: `project:files-markdown`

**Description**: Generates comprehensive markdown documentation for project files with structure overview, package information, and metadata.

#### Signature

```bash
php artisan project:files-markdown
    {path? : Project path to analyze (defaults to base_path())}
    {--o|output= : Output markdown file path (defaults to storage/app/project-structure.md)}
    {--exclude=* : Additional directories to exclude (repeatable)}
    {--depth= : Maximum directory depth (integer)}
```

#### Parameters

| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| `path` | string | No | `base_path()` | Path to the project directory to analyze |
| `--output` | string | No | `storage/app/project-structure.md` | Output file path for markdown documentation |
| `--exclude` | array | No | `[]` | Additional directories to exclude from analysis |
| `--depth` | integer | No | `PHP_INT_MAX` | Maximum directory depth to traverse |

#### Examples

```bash
# Basic usage
php artisan project:files-markdown

# Analyze specific directory
php artisan project:files-markdown /path/to/project

# Custom output with exclusions
php artisan project:files-markdown --output=docs/project.md --exclude=tests --exclude=storage

# Limit depth
php artisan project:files-markdown --depth=3
```

#### Output Files

1. **Markdown File**: Human-readable documentation
2. **JSON File**: Machine-readable metadata (same name with .json extension)

#### JSON Output Structure

```json
{
    "project": "string",
    "path": "string",
    "type": "string",
    "generated": "string",
    "versions": {
        "laravel": "string",
        "php": "string",
        "database": "string"
    },
    "packages": [
        {
            "name": "string",
            "version": "string"
        }
    ],
    "discoverable": [
        {
            "name": "string",
            "version": "string",
            "providers": ["string"],
            "aliases": {"string": "string"}
        }
    ],
    "files": [
        {
            "type": "string",
            "path": "string",
            "size": "integer",
            "modified": "string"
        }
    ]
}
```

### 2. Database Markdown Command

**Command**: `project:db-markdown`

**Description**: Generates comprehensive database schema documentation with ER diagrams, table details, and relationships.

#### Signature

```bash
php artisan project:db-markdown
    {--path=database.md : Output file path}
```

#### Parameters

| Parameter | Type | Required | Default | Description |
|-----------|------|----------|---------|-------------|
| `--path` | string | No | `database.md` | Output file path for database documentation |

#### Examples

```bash
# Basic usage
php artisan project:db-markdown

# Custom output path
php artisan project:db-markdown --path=docs/database-schema.md
```

#### Output Files

1. **Markdown File**: Human-readable documentation with Mermaid diagrams
2. **JSON File**: Machine-readable database schema (same name with .json extension)

#### JSON Output Structure

```json
{
    "database": "string",
    "generated": "string",
    "php_version": "string",
    "db_version": "string",
    "tables": [
        {
            "name": "string",
            "comment": "string",
            "rows": "integer",
            "color": "string",
            "columns": [
                {
                    "name": "string",
                    "type": "string",
                    "nullable": "boolean",
                    "default": "string",
                    "key": "string",
                    "comment": "string"
                }
            ],
            "indexes": [
                {
                    "name": "string",
                    "columns": ["string"],
                    "unique": "boolean",
                    "primary": "boolean"
                }
            ],
            "foreign_keys": [
                {
                    "name": "string",
                    "column": "string",
                    "references": {
                        "table": "string",
                        "column": "string"
                    }
                }
            ]
        }
    ]
}
```

## Configuration

### Configuration File

**Location**: `config/laravel-project-markdown.php`

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | File Documentation Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for the project:files-markdown command.
    |
    */
    'files' => [
        /*
        |--------------------------------------------------------------------------
        | Default Exclude Patterns
        |--------------------------------------------------------------------------
        |
        | Directories to exclude from file analysis by default.
        |
        */
        'exclude_directories' => [
            'vendor',
            'storage',
            'node_modules',
            'tests',
            '.git',
        ]
    ],
];
```

### Configuration Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `files.exclude_directories` | array | `['vendor', 'storage', 'node_modules', 'tests', '.git']` | Default directories to exclude from analysis |

## Service Provider

### LaravelProjectMarkdownServiceProvider

**Namespace**: `Saeedvir\LaravelProjectMarkdown\LaravelProjectMarkdownServiceProvider`

#### Methods

##### `register()`

Registers the service provider and merges configuration.

```php
public function register(): void
{
    $this->mergeConfigFrom(
        __DIR__.'/../config/laravel-project-markdown.php', 
        'laravel-project-markdown'
    );
}
```

##### `boot()`

Bootstraps the service provider and registers commands.

```php
public function boot(): void
{
    if ($this->app->runningInConsole()) {
        $this->commands([
            ProjectFilesMarkdownCommand::class,
            ProjectDbMarkdownCommand::class,
        ]);
    }

    $this->publishes([
        __DIR__.'/../config/laravel-project-markdown.php' => config_path('laravel-project-markdown.php'),
    ], 'config');
}
```

## Error Handling

### Common Errors

#### 1. Path Not Found

**Error**: `Path not found or not a directory: {path}`

**Cause**: The specified path doesn't exist or is not a directory.

**Solution**: Verify the path exists and is accessible.

#### 2. Database Connection Error

**Error**: Database connection issues in `project:db-markdown`

**Cause**: Invalid database configuration or connection.

**Solution**: Check your `.env` file and database configuration.

#### 3. Permission Denied

**Error**: Permission denied when writing output files

**Cause**: Insufficient permissions to write to the output directory.

**Solution**: Ensure the output directory is writable.

### Error Codes

| Code | Description |
|------|-------------|
| `0` | Success |
| `1` | General error |
| `2` | Invalid argument |

## Integration Examples

### Programmatic Usage

```php
use Saeedvir\LaravelProjectMarkdown\Commands\ProjectFilesMarkdownCommand;
use Saeedvir\LaravelProjectMarkdown\Commands\ProjectDbMarkdownCommand;

// Generate files documentation
$command = new ProjectFilesMarkdownCommand();
$command->setLaravel(app());
$exitCode = $command->handle();

// Generate database documentation
$command = new ProjectDbMarkdownCommand();
$command->setLaravel(app());
$exitCode = $command->handle();
```

### Custom Command Integration

```php
use Illuminate\Console\Command;
use Saeedvir\LaravelProjectMarkdown\Commands\ProjectFilesMarkdownCommand;

class CustomDocumentationCommand extends Command
{
    protected $signature = 'custom:docs';
    protected $description = 'Generate custom documentation';

    public function handle()
    {
        // Generate project documentation
        $this->call('project:files-markdown', [
            '--output' => 'docs/custom-project.md'
        ]);

        // Generate database documentation
        $this->call('project:db-markdown', [
            '--path' => 'docs/custom-database.md'
        ]);

        $this->info('Custom documentation generated successfully!');
    }
}
```

## Performance Considerations

### File Analysis

- **Large Projects**: Consider using `--depth` option to limit analysis depth
- **Exclude Directories**: Use `--exclude` to skip unnecessary directories
- **Memory Usage**: Large projects may require increased PHP memory limit

### Database Analysis

- **Large Databases**: Analysis time increases with number of tables
- **Connection Limits**: Ensure database connection limits are sufficient
- **Index Analysis**: Large indexes may slow down analysis

## Security Considerations

### File Access

- Commands only read files, never modify them
- Respects file system permissions
- Excludes sensitive directories by default

### Database Access

- Uses Laravel's database configuration
- Only reads schema information
- No data modification or deletion

## Troubleshooting

### Common Issues

1. **Command Not Found**
   - Ensure package is properly installed
   - Check service provider registration
   - Run `composer dump-autoload`

2. **Configuration Not Found**
   - Publish configuration: `php artisan vendor:publish --tag=config`
   - Check configuration file exists

3. **Output File Not Created**
   - Verify output directory exists and is writable
   - Check file permissions
   - Ensure sufficient disk space

### Debug Mode

Enable debug mode for detailed error information:

```bash
php artisan project:files-markdown --verbose
php artisan project:db-markdown --verbose
```

## Version Compatibility

| Laravel Version | Package Version | PHP Version |
|----------------|-----------------|-------------|
| 10.x | 1.0.0+ | 8.1+ |
| 11.x | 1.0.0+ | 8.1+ |
| 12.x | 1.0.0+ | 8.1+ |

## Support

For issues, questions, or contributions:

- **GitHub Issues**: [Create an issue](https://github.com/saeedvir/laravel-project-markdown/issues)
- **Email**: saeed.es91@gmail.com
- **Documentation**: [README.md](README.md)
