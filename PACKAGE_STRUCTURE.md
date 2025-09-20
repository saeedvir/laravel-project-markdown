# Package Structure

This document outlines the complete structure of the Laravel Project Markdown package.

```
laravel-project-markdown/
├── composer.json                           # Package definition and dependencies
├── README.md                              # Main documentation
├── CHANGELOG.md                           # Version history
├── LICENSE                                # MIT License
├── phpunit.xml                            # PHPUnit configuration
├── .gitignore                             # Git ignore rules
├── .gitattributes                         # Git attributes
├── PACKAGE_STRUCTURE.md                   # This file
├── config/
│   └── laravel-project-markdown.php       # Enhanced package configuration
├── docs/                                  # Comprehensive documentation
│   ├── README.md                          # Documentation index
│   ├── API.md                             # API documentation
│   ├── EXAMPLES.md                        # Examples and use cases
│   └── AI_READABLE.md                     # AI integration guide
├── src/
│   ├── LaravelProjectMarkdownServiceProvider.php  # Service provider
│   └── Commands/
│       ├── ProjectFilesMarkdownCommand.php        # Enhanced files documentation command
│       └── ProjectDbMarkdownCommand.php           # Enhanced database documentation command
└── tests/
    ├── TestCase.php                       # Base test case
    └── Commands/
        ├── ProjectFilesMarkdownCommandTest.php    # Files command tests
        └── ProjectDbMarkdownCommandTest.php       # Database command tests
```

## Files Description

### Core Package Files

- **composer.json**: Defines package metadata, dependencies, and autoloading
- **README.md**: Comprehensive documentation with installation and usage instructions
- **CHANGELOG.md**: Version history and changes
- **LICENSE**: MIT license file
- **phpunit.xml**: PHPUnit testing configuration
- **.gitignore**: Git ignore patterns for common files
- **.gitattributes**: Git attributes for proper file handling

### Configuration

- **config/laravel-project-markdown.php**: Enhanced configuration file with comprehensive options for both commands, performance settings, security options, and logging configuration

### Documentation

- **docs/README.md**: Documentation index and quick start guide
- **docs/API.md**: Comprehensive API documentation with command signatures, parameters, and integration examples
- **docs/EXAMPLES.md**: Real-world examples, use cases, CI/CD integration, and best practices
- **docs/AI_READABLE.md**: AI integration guide with structured data formats and processing examples

### Source Code

- **src/LaravelProjectMarkdownServiceProvider.php**: Laravel service provider that registers commands and publishes config
- **src/Commands/ProjectFilesMarkdownCommand.php**: Enhanced Artisan command for generating comprehensive project files documentation with package analysis, version detection, and dual output (Markdown + JSON)
- **src/Commands/ProjectDbMarkdownCommand.php**: Enhanced Artisan command for generating database schema documentation with ER diagrams, relationship mapping, and structured JSON output

### Tests

- **tests/TestCase.php**: Base test case extending Orchestra Testbench
- **tests/Commands/ProjectFilesMarkdownCommandTest.php**: Unit tests for files command
- **tests/Commands/ProjectDbMarkdownCommandTest.php**: Unit tests for database command

## Installation Instructions

1. **Via Composer (when published)**:
   ```bash
   composer require your-vendor/laravel-project-markdown
   ```

2. **Local Development**:
   ```bash
   # Add to composer.json repositories
   {
       "repositories": [
           {
               "type": "path",
               "url": "./path/to/laravel-project-markdown"
           }
       ]
   }
   
   # Install
   composer require your-vendor/laravel-project-markdown
   ```

3. **Publish Configuration**:
   ```bash
   php artisan vendor:publish --provider="YourVendor\LaravelProjectMarkdown\LaravelProjectMarkdownServiceProvider" --tag="config"
   ```

## Usage

### Files Documentation
```bash
php artisan project:files-markdown [--output=path] [--exclude=patterns] [--include=extensions]
```

### Database Documentation
```bash
php artisan project:db-markdown [--output=path] [--connection=name] [--tables=list]
```

## Testing

Run the test suite:
```bash
composer test
# or
./vendor/bin/phpunit
```
