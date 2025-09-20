# Documentation Index

Welcome to the comprehensive documentation for the Laravel Project Markdown package. This documentation provides everything you need to understand, install, configure, and use the package effectively.

## 📚 Documentation Structure

### Core Documentation

- **[Main README](../README.md)** - Complete package overview, installation, and usage guide
- **[API Documentation](API.md)** - Detailed API reference for commands, configuration, and integration
- **[Examples & Use Cases](EXAMPLES.md)** - Comprehensive examples and real-world use cases
- **[AI Readable Documentation](AI_READABLE.md)** - AI integration guide and structured data format

### Quick Start

1. **[Installation Guide](#installation)** - Get started quickly
2. **[Basic Usage](#basic-usage)** - Run your first commands
3. **[Configuration](#configuration)** - Customize the package
4. **[Advanced Features](#advanced-features)** - Explore advanced capabilities

## 🚀 Installation

### Via Composer (Recommended)

```bash
composer require saeedvir/laravel-project-markdown
```

### Local Development

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "../projector"
        }
    ],
    "require": {
        "saeedvir/laravel-project-markdown": "1.0.0"
    }
}
```

## 🎯 Basic Usage

### Generate Project Files Documentation

```bash
php artisan project:files-markdown
```

### Generate Database Documentation

```bash
php artisan project:db-markdown
```

## ⚙️ Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Saeedvir\LaravelProjectMarkdown\LaravelProjectMarkdownServiceProvider" --tag="config"
```

## 🔧 Advanced Features

### AI Integration

The package generates both human-readable Markdown and machine-readable JSON for AI processing:

```bash
# Generate documentation with AI-readable JSON
php artisan project:files-markdown --output=docs/project.md
# Creates: docs/project.md and docs/project.json

php artisan project:db-markdown --path=docs/database.md
# Creates: docs/database.md and docs/database.json
```

### Custom Configuration

```php
// config/laravel-project-markdown.php
return [
    'files' => [
        'exclude_directories' => [
            'vendor',
            'storage',
            'node_modules',
            'tests',
            '.git',
        ],
        'preview' => [
            'max_lines' => 10,
            'max_file_size' => 1024 * 1024, // 1MB
        ],
    ],
    'database' => [
        'sample_data' => [
            'enabled' => true,
            'max_rows' => 3,
        ],
        'er_diagram' => [
            'enabled' => true,
            'format' => 'mermaid',
        ],
    ],
];
```

## 📊 Output Examples

### Project Files Documentation

```markdown
# Project structure for `my-laravel-app`

> Project Type: Laravel PHP Web Application
> Generated: 2024-01-15 10:30:00

## Versions
- Laravel: **10.0.0**
- PHP: **8.1.0**
- Database: **MySQL 8.0.0**

## Composer Packages
| Package | Version |
|---------|---------|
| laravel/framework | 10.0.0 |
| guzzlehttp/guzzle | 7.5.0 |

## Project Tree
- **app/** — `2.5 MB` — _modified: 2024-01-15 09:00_
  - **Http/** — `1.2 MB` — _modified: 2024-01-15 08:30_
    - Controllers/HomeController.php — `15 KB` — _modified: 2024-01-15 08:00_
```

### Database Documentation

```markdown
# Database Documentation for `my_laravel_app`

> Database Type: MySQL/MariaDB | Generated: 2024-01-15 10:30:00

## ER Diagram

```mermaid
erDiagram
  users {
    BIGINT id PK
    VARCHAR name
    VARCHAR email UNIQUE
    TIMESTAMP created_at
    TIMESTAMP updated_at
  }
  posts {
    BIGINT id PK
    BIGINT user_id FK
    VARCHAR title
    TEXT content
    TIMESTAMP created_at
    TIMESTAMP updated_at
  }
  users }o--|| posts : "user_id"
```

## users

_Rows: 150_

| Column | Type | Nullable | Default | Key | Comment |
|--------|------|----------|---------|-----|---------|
| id | bigint(20) unsigned | NO | NULL | PRI | Primary key |
| name | varchar(255) | NO | NULL | | User name |
| email | varchar(255) | NO | NULL | UNI | User email |
```

## 🤖 AI Integration

### JSON Output Structure

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

### AI Processing Example

```python
import json

def analyze_laravel_project(project_json_path):
    with open(project_json_path, 'r') as f:
        data = json.load(f)
    
    analysis = {
        'project_type': data['type'],
        'laravel_version': data['versions']['laravel'],
        'total_files': len(data['files']),
        'package_count': len(data['packages']),
    }
    
    return analysis
```

## 🔄 CI/CD Integration

### GitHub Actions

```yaml
name: Generate Documentation
on: [push, pull_request]

jobs:
  documentation:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
      - name: Install dependencies
        run: composer install
      - name: Generate project documentation
        run: php artisan project:files-markdown --output=docs/project.md
      - name: Generate database documentation
        run: php artisan project:db-markdown --path=docs/database.md
      - name: Upload documentation
        uses: actions/upload-artifact@v3
        with:
          name: documentation
          path: docs/
```

## 🧪 Testing

Run the test suite:

```bash
composer test
# or
./vendor/bin/phpunit
```

## 📋 Requirements

- **PHP**: 8.1 or higher
- **Laravel**: 10.0 or higher
- **Database**: MySQL, PostgreSQL, or SQLite (for database documentation)

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👨‍💻 Author

**Saeedvir**
- Email: saeed.es91@gmail.com
- GitHub: [@saeedvir](https://github.com/saeedvir)

## 📞 Support

- **GitHub Issues**: [Create an issue](https://github.com/saeedvir/laravel-project-markdown/issues)
- **Email**: saeed.es91@gmail.com
- **Documentation**: [Full Documentation](README.md)

---

**Made with ❤️ for the Laravel community**
