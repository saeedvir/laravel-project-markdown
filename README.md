# Laravel Project Markdown

[![Latest Version](https://img.shields.io/badge/version-1.0.0-blue.svg)](https://github.com/saeedvir/laravel-project-markdown)
[![Laravel](https://img.shields.io/badge/Laravel-10%2B-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1%2B-purple.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

A comprehensive Laravel package that provides artisan commands to generate detailed markdown documentation for your project files and database schema. Perfect for developers, teams, and AI systems that need to understand your Laravel project structure.

## 🚀 Features

### 📁 Project Files Documentation
- **Complete Project Structure**: Generate hierarchical tree view of your entire project
- **File Metadata**: Size, modification dates, and file types
- **Package Discovery**: Automatically detect Laravel packages and their providers/aliases
- **Version Information**: Laravel, PHP, and database version detection
- **Composer Analysis**: Parse and display all installed packages
- **Smart Filtering**: Exclude unnecessary directories and files
- **Dual Output**: Generate both human-readable Markdown and machine-readable JSON

### 🗄️ Database Documentation
- **Schema Analysis**: Complete database structure documentation
- **ER Diagrams**: Generate Mermaid diagrams for visual database relationships
- **Table Details**: Columns, types, constraints, indexes, and foreign keys
- **Relationship Mapping**: Visual connections between tables
- **Color-coded Tables**: Beautiful, organized database diagrams
- **Sample Data**: Include sample queries for better understanding
- **Multi-database Support**: MySQL, PostgreSQL, and SQLite

### 🤖 AI Readable Documentation
- **Structured JSON Output**: Machine-readable format for AI systems
- **Comprehensive Metadata**: All project information in structured format
- **API Documentation**: Clear command signatures and options
- **Consistent Formatting**: Standardized output for AI processing
- **Version Tracking**: Complete version information for compatibility

## 📦 Installation

### Via Composer (Recommended)

```bash
composer require saeedvir/laravel-project-markdown
```

### Local Development Installation

1. **Add path repository** to your Laravel project's `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "../packages"
        }
    ],
    "require": {
        "saeedvir/laravel-project-markdown": "1.0.0"
    }
}
```

2. **Install the package**:
```bash
composer update
```

3. **Publish configuration** (optional):
```bash
php artisan vendor:publish --provider="Saeedvir\LaravelProjectMarkdown\LaravelProjectMarkdownServiceProvider" --tag="config"
```

## ⚙️ Configuration

The package includes a comprehensive configuration file at `config/laravel-project-markdown.php`:

```php
return [
    'files' => [
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

## 🎯 Usage

### Project Files Documentation

Generate comprehensive documentation for your project files:

```bash
php artisan project:files-markdown
```

#### Command Options

| Option | Description | Default |
|--------|-------------|---------|
| `path` | Project path to analyze | `base_path()` |
| `--output` | Output markdown file path | `storage/app/project-structure.md` |
| `--exclude` | Additional directories to exclude | `[]` |
| `--depth` | Maximum directory depth | `PHP_INT_MAX` |

#### Examples

```bash
# Basic usage - analyze current project
php artisan project:files-markdown

# Analyze specific directory
php artisan project:files-markdown /path/to/project

# Custom output location
php artisan project:files-markdown --output=docs/project-overview.md

# Exclude specific directories
php artisan project:files-markdown --exclude=tests --exclude=storage

# Limit directory depth
php artisan project:files-markdown --depth=3
```

### Database Documentation

Generate comprehensive database schema documentation:

```bash
php artisan project:db-markdown
```

#### Command Options

| Option | Description | Default |
|--------|-------------|---------|
| `--path` | Output file path | `database.md` |

#### Examples

```bash
# Basic usage
php artisan project:db-markdown

# Custom output file
php artisan project:db-markdown --path=docs/database-schema.md
```

## 📊 Generated Documentation

### Files Documentation Output

The files documentation includes:

1. **Project Overview**
   - Project name and type
   - Generation timestamp
   - Version information (Laravel, PHP, Database)

2. **Composer Packages**
   - All installed packages with versions
   - Laravel discoverable packages
   - Service providers and aliases

3. **Project Structure**
   - Hierarchical file tree
   - File sizes and modification dates
   - Directory organization

4. **JSON Metadata**
   - Machine-readable project information
   - Complete package list
   - File structure data

### Database Documentation Output

The database documentation includes:

1. **Database Overview**
   - Database name and type
   - Version information
   - Generation timestamp

2. **ER Diagram**
   - Mermaid-compatible diagram
   - Color-coded tables
   - Relationship connections

3. **Table Details**
   - Column specifications
   - Data types and constraints
   - Indexes and foreign keys
   - Sample data queries

4. **JSON Schema**
   - Complete database structure
   - Relationship mappings
   - Metadata for AI processing

## 🤖 AI Readable Documentation

This package is specifically designed to generate documentation that is both human-readable and AI-processable:

### Structured JSON Output

Every command generates a corresponding JSON file with structured data:

```json
{
    "project": "my-laravel-app",
    "type": "Laravel PHP Web Application",
    "generated": "2024-01-15 10:30:00",
    "versions": {
        "laravel": "10.0.0",
        "php": "8.1.0",
        "database": "MySQL 8.0.0"
    },
    "packages": [
        {
            "name": "laravel/framework",
            "version": "10.0.0"
        }
    ],
    "files": [
        {
            "type": "file",
            "path": "app/Http/Controllers/HomeController.php",
            "size": 1024,
            "modified": "2024-01-15 09:00"
        }
    ]
}
```

### AI Processing Benefits

- **Consistent Format**: Standardized JSON structure for easy parsing
- **Complete Metadata**: All project information in one place
- **Version Tracking**: Clear version information for compatibility
- **Relationship Data**: Database relationships in structured format
- **File Analysis**: Complete project structure for AI understanding

### Use Cases for AI Systems

1. **Code Analysis**: AI can understand project structure and dependencies
2. **Documentation Generation**: Automated documentation creation
3. **Migration Planning**: Database schema analysis for migrations
4. **Dependency Management**: Package analysis and updates
5. **Project Onboarding**: Quick project understanding for new developers

## 🔧 Advanced Configuration

### Custom Exclude Patterns

```php
// config/laravel-project-markdown.php
'files' => [
    'exclude_directories' => [
        'vendor',
        'storage',
        'node_modules',
        'tests',
        '.git',
        'build',
        'dist',
        'coverage'
    ]
],
```

### Database Connection Settings

The database command automatically uses your Laravel database configuration. Ensure your `.env` file has proper database settings:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## 📋 Requirements

- **PHP**: 8.1 or higher
- **Laravel**: 10.0 or higher
- **Database**: MySQL, PostgreSQL, or SQLite (for database documentation)

## 🧪 Testing

Run the test suite:

```bash
composer test
# or
./vendor/bin/phpunit
```

## 📈 Examples

### Example 1: Basic Project Analysis

```bash
# Generate complete project documentation
php artisan project:files-markdown

# Output files:
# - storage/app/project-structure.md
# - storage/app/project-structure.json
```

### Example 2: Database Schema Documentation

```bash
# Generate database documentation
php artisan project:db-markdown --path=docs/db-schema.md

# Output files:
# - storage/app/docs/db-schema.md
# - storage/app/docs/db-schema.json
```

### Example 3: Custom Analysis

```bash
# Analyze specific directory with custom settings
php artisan project:files-markdown /path/to/project \
    --output=docs/custom-analysis.md \
    --exclude=tests \
    --exclude=storage \
    --depth=2
```


## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 Changelog

### [1.0.0] - 2025-09-20

#### Added
- Initial release of Laravel Project Markdown package
- `project:files-markdown` command for comprehensive project analysis
- `project:db-markdown` command for database schema documentation
- AI-readable JSON output for both commands
- Mermaid ER diagram generation
- Laravel package discovery and analysis
- Comprehensive configuration system
- Support for Laravel 10, 11, and 12
- Support for MySQL, PostgreSQL, and SQLite databases
- File structure tree visualization
- Database relationship mapping
- Sample data generation
- Customizable exclude/include patterns
- File content previews
- Database statistics and overview

#### Features
- **Dual Output**: Both Markdown and JSON formats
- **AI Integration**: Structured data for AI processing
- **Visual Diagrams**: Mermaid-compatible ER diagrams
- **Package Analysis**: Complete Composer package information
- **Version Detection**: Automatic version information gathering
- **Smart Filtering**: Configurable file and directory exclusions

## 📄 License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👨‍💻 Author

**Saeedvir**
- Email: saeed.es91@gmail.com
- GitHub: [@saeedvir](https://github.com/saeedvir)

**Made with ❤️ for the Laravel community**
