# Examples and Use Cases

This document provides comprehensive examples and use cases for the Laravel Project Markdown package.

## Table of Contents

1. [Basic Usage Examples](#basic-usage-examples)
2. [Advanced Configuration Examples](#advanced-configuration-examples)
3. [Integration Examples](#integration-examples)
4. [CI/CD Integration](#cicd-integration)
5. [AI Integration Examples](#ai-integration-examples)
6. [Real-world Use Cases](#real-world-use-cases)
7. [Troubleshooting Examples](#troubleshooting-examples)

## Basic Usage Examples

### Example 1: Generate Basic Project Documentation

```bash
# Generate documentation for current project
php artisan project:files-markdown

# Output files:
# - storage/app/project-structure.md
# - storage/app/project-structure.json
```

**Generated Markdown Preview:**
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

### Example 2: Generate Database Documentation

```bash
# Generate database documentation
php artisan project:db-markdown

# Output files:
# - storage/app/database.md
# - storage/app/database.json
```

**Generated Database Documentation Preview:**
```markdown
# Database Documentation for `my_laravel_app`

> Database Type: MySQL/MariaDB | Generated: 2024-01-15 10:30:00
> PHP: **8.1.0** | DB: **MySQL 8.0.0**

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

## Advanced Configuration Examples

### Example 3: Custom Exclude Patterns

```bash
# Exclude specific directories and file types
php artisan project:files-markdown \
    --exclude=tests \
    --exclude=storage \
    --exclude=node_modules \
    --exclude=*.log \
    --output=docs/clean-project.md
```

### Example 4: Limited Depth Analysis

```bash
# Analyze only top 2 levels of directory structure
php artisan project:files-markdown \
    --depth=2 \
    --output=docs/overview.md
```

### Example 5: Custom Database Documentation

```bash
# Generate database documentation with custom path
php artisan project:db-markdown \
    --path=docs/database-schema.md
```

## Integration Examples

### Example 6: Custom Artisan Command

Create a custom command that uses the package:

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateProjectDocs extends Command
{
    protected $signature = 'docs:generate {--type=all : Type of documentation (all, files, database)}';
    protected $description = 'Generate comprehensive project documentation';

    public function handle()
    {
        $type = $this->option('type');

        if ($type === 'all' || $type === 'files') {
            $this->info('Generating project files documentation...');
            $this->call('project:files-markdown', [
                '--output' => 'docs/project-structure.md'
            ]);
        }

        if ($type === 'all' || $type === 'database') {
            $this->info('Generating database documentation...');
            $this->call('project:db-markdown', [
                '--path' => 'docs/database-schema.md'
            ]);
        }

        $this->info('Documentation generated successfully!');
    }
}
```

### Example 7: Programmatic Usage

```php
<?php

use Saeedvir\LaravelProjectMarkdown\Commands\ProjectFilesMarkdownCommand;
use Saeedvir\LaravelProjectMarkdown\Commands\ProjectDbMarkdownCommand;

class DocumentationService
{
    public function generateProjectDocs(string $outputPath = null): array
    {
        $command = new ProjectFilesMarkdownCommand();
        $command->setLaravel(app());
        
        $outputPath = $outputPath ?? 'docs/project-structure.md';
        
        // Simulate command execution
        $exitCode = $command->handle();
        
        return [
            'success' => $exitCode === 0,
            'output_path' => $outputPath,
            'json_path' => str_replace('.md', '.json', $outputPath)
        ];
    }

    public function generateDatabaseDocs(string $outputPath = null): array
    {
        $command = new ProjectDbMarkdownCommand();
        $command->setLaravel(app());
        
        $outputPath = $outputPath ?? 'docs/database-schema.md';
        
        // Simulate command execution
        $exitCode = $command->handle();
        
        return [
            'success' => $exitCode === 0,
            'output_path' => $outputPath,
            'json_path' => str_replace('.md', '.json', $outputPath)
        ];
    }
}
```

## CI/CD Integration

### Example 8: GitHub Actions Workflow

```yaml
name: Generate Documentation
on:
  push:
    branches: [ main, develop ]
  pull_request:
    branches: [ main ]

jobs:
  documentation:
    runs-on: ubuntu-latest
    
    steps:
    - name: Checkout code
      uses: actions/checkout@v3
      
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.1'
        extensions: mbstring, dom, fileinfo, mysql
        
    - name: Copy .env
      run: php -r "file_exists('.env') || copy('.env.example', '.env');"
      
    - name: Install dependencies
      run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist
      
    - name: Generate project documentation
      run: php artisan project:files-markdown --output=docs/project-structure.md
      
    - name: Generate database documentation
      run: php artisan project:db-markdown --path=docs/database-schema.md
      
    - name: Upload documentation
      uses: actions/upload-artifact@v3
      with:
        name: documentation
        path: docs/
        
    - name: Comment PR with documentation
      if: github.event_name == 'pull_request'
      uses: actions/github-script@v6
      with:
        script: |
          const fs = require('fs');
          const path = require('path');
          
          const projectDoc = fs.readFileSync('docs/project-structure.md', 'utf8');
          const dbDoc = fs.readFileSync('docs/database-schema.md', 'utf8');
          
          const comment = `## 📚 Documentation Generated
          
          ### Project Structure
          \`\`\`markdown
          ${projectDoc.substring(0, 1000)}...
          \`\`\`
          
          ### Database Schema
          \`\`\`markdown
          ${dbDoc.substring(0, 1000)}...
          \`\`\`
          `;
          
          github.rest.issues.createComment({
            issue_number: context.issue.number,
            owner: context.repo.owner,
            repo: context.repo.repo,
            body: comment
          });
```

### Example 9: GitLab CI Pipeline

```yaml
stages:
  - documentation

generate_docs:
  stage: documentation
  image: php:8.1-cli
  before_script:
    - apt-get update -yqq
    - apt-get install -yqq git curl libmcrypt-dev libjpeg-dev libpng-dev libfreetype6-dev libbz2-dev
    - docker-php-ext-install pdo_mysql
    - curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
    - composer install --no-dev --optimize-autoloader
  script:
    - php artisan project:files-markdown --output=docs/project-structure.md
    - php artisan project:db-markdown --path=docs/database-schema.md
  artifacts:
    paths:
      - docs/
    expire_in: 1 week
  only:
    - main
    - develop
```

## AI Integration Examples

### Example 10: AI Code Analysis

```python
import json
import requests
from typing import Dict, List

class LaravelProjectAnalyzer:
    def __init__(self, project_path: str):
        self.project_path = project_path
        
    def analyze_project_structure(self) -> Dict:
        """Analyze Laravel project structure using generated JSON"""
        with open(f"{self.project_path}/docs/project-structure.json", 'r') as f:
            data = json.load(f)
            
        analysis = {
            'total_files': len(data['files']),
            'laravel_version': data['versions']['laravel'],
            'php_version': data['versions']['php'],
            'packages': len(data['packages']),
            'discoverable_packages': len(data['discoverable']),
            'file_types': self._analyze_file_types(data['files']),
            'largest_directories': self._find_largest_directories(data['files'])
        }
        
        return analysis
        
    def _analyze_file_types(self, files: List[Dict]) -> Dict:
        """Analyze file types distribution"""
        file_types = {}
        for file in files:
            if file['type'] == 'file':
                ext = file['path'].split('.')[-1] if '.' in file['path'] else 'no_extension'
                file_types[ext] = file_types.get(ext, 0) + 1
        return file_types
        
    def _find_largest_directories(self, files: List[Dict]) -> List[Dict]:
        """Find largest directories by size"""
        dir_sizes = {}
        for file in files:
            if file['type'] == 'dir':
                dir_sizes[file['path']] = file['size']
                
        return sorted(dir_sizes.items(), key=lambda x: x[1], reverse=True)[:5]

# Usage
analyzer = LaravelProjectAnalyzer('/path/to/laravel/project')
analysis = analyzer.analyze_project_structure()
print(json.dumps(analysis, indent=2))
```

### Example 11: Database Schema Analysis

```python
import json
from typing import Dict, List

class DatabaseSchemaAnalyzer:
    def __init__(self, schema_path: str):
        self.schema_path = schema_path
        
    def analyze_schema(self) -> Dict:
        """Analyze database schema using generated JSON"""
        with open(f"{self.schema_path}/docs/database-schema.json", 'r') as f:
            data = json.load(f)
            
        analysis = {
            'total_tables': len(data['tables']),
            'total_columns': sum(len(table['columns']) for table in data['tables']),
            'total_foreign_keys': sum(len(table['foreign_keys']) for table in data['tables']),
            'total_indexes': sum(len(table['indexes']) for table in data['tables']),
            'largest_tables': self._find_largest_tables(data['tables']),
            'relationship_complexity': self._analyze_relationships(data['tables']),
            'data_types': self._analyze_data_types(data['tables'])
        }
        
        return analysis
        
    def _find_largest_tables(self, tables: List[Dict]) -> List[Dict]:
        """Find tables with most rows"""
        return sorted(tables, key=lambda x: x['rows'], reverse=True)[:5]
        
    def _analyze_relationships(self, tables: List[Dict]) -> Dict:
        """Analyze relationship complexity"""
        total_relationships = sum(len(table['foreign_keys']) for table in tables)
        avg_relationships = total_relationships / len(tables) if tables else 0
        
        return {
            'total_relationships': total_relationships,
            'average_relationships_per_table': round(avg_relationships, 2),
            'most_connected_table': max(tables, key=lambda x: len(x['foreign_keys']))['name'] if tables else None
        }
        
    def _analyze_data_types(self, tables: List[Dict]) -> Dict:
        """Analyze data type distribution"""
        data_types = {}
        for table in tables:
            for column in table['columns']:
                data_type = column['type'].split('(')[0]  # Remove size constraints
                data_types[data_type] = data_types.get(data_type, 0) + 1
        return data_types

# Usage
analyzer = DatabaseSchemaAnalyzer('/path/to/laravel/project')
analysis = analyzer.analyze_schema()
print(json.dumps(analysis, indent=2))
```

## Real-world Use Cases

### Example 12: Project Onboarding

```bash
#!/bin/bash
# onboarding-docs.sh - Generate documentation for new team members

echo "🚀 Generating project documentation for onboarding..."

# Generate comprehensive project documentation
php artisan project:files-markdown \
    --output=docs/onboarding/project-overview.md \
    --exclude=tests \
    --exclude=storage \
    --depth=3

# Generate database documentation
php artisan project:db-markdown \
    --path=docs/onboarding/database-schema.md

# Create onboarding README
cat > docs/onboarding/README.md << EOF
# Project Onboarding Documentation

This documentation was generated automatically to help new team members understand the project structure.

## Files to Review

1. [Project Overview](project-overview.md) - Complete project structure and dependencies
2. [Database Schema](database-schema.md) - Database structure and relationships

## Quick Start

1. Review the project structure to understand the codebase organization
2. Check the database schema to understand data relationships
3. Look at the Composer packages to understand dependencies
4. Review the Laravel discoverable packages for service providers

## Next Steps

- Set up your development environment
- Review the main application files
- Understand the database relationships
- Check the configuration files
EOF

echo "✅ Onboarding documentation generated successfully!"
echo "📁 Check the docs/onboarding/ directory"
```

### Example 13: Code Review Preparation

```bash
#!/bin/bash
# pre-review-docs.sh - Generate documentation before code review

echo "📋 Generating documentation for code review..."

# Generate focused documentation for review
php artisan project:files-markdown \
    --output=docs/review/project-changes.md \
    --exclude=vendor \
    --exclude=node_modules \
    --exclude=storage \
    --exclude=tests \
    --depth=2

# Generate database documentation if schema changed
php artisan project:db-markdown \
    --path=docs/review/database-changes.md

echo "✅ Review documentation generated!"
echo "📁 Check the docs/review/ directory"
```

### Example 14: Documentation Website

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DocumentationController extends Controller
{
    public function index()
    {
        // Generate fresh documentation
        $this->generateDocumentation();
        
        // Load generated files
        $projectDoc = File::get(storage_path('app/project-structure.md'));
        $dbDoc = File::get(storage_path('app/database.md'));
        
        return view('documentation.index', [
            'projectDoc' => $projectDoc,
            'dbDoc' => $dbDoc
        ]);
    }
    
    private function generateDocumentation()
    {
        // Generate project documentation
        \Artisan::call('project:files-markdown', [
            '--output' => 'project-structure.md'
        ]);
        
        // Generate database documentation
        \Artisan::call('project:db-markdown', [
            '--path' => 'database.md'
        ]);
    }
}
```

## Troubleshooting Examples

### Example 15: Common Issues and Solutions

```bash
#!/bin/bash
# troubleshoot.sh - Common troubleshooting scenarios

echo "🔧 Laravel Project Markdown Troubleshooting"

# Check if package is installed
if ! php artisan list | grep -q "project:files-markdown"; then
    echo "❌ Package not found. Installing..."
    composer require saeedvir/laravel-project-markdown
fi

# Check if configuration is published
if [ ! -f "config/laravel-project-markdown.php" ]; then
    echo "❌ Configuration not published. Publishing..."
    php artisan vendor:publish --provider="Saeedvir\LaravelProjectMarkdown\LaravelProjectMarkdownServiceProvider" --tag="config"
fi

# Check database connection
echo "🔍 Checking database connection..."
php artisan tinker --execute="echo 'Database: ' . config('database.default') . PHP_EOL;"

# Test commands with verbose output
echo "🧪 Testing commands..."
php artisan project:files-markdown --verbose
php artisan project:db-markdown --verbose

echo "✅ Troubleshooting complete!"
```

### Example 16: Performance Optimization

```bash
#!/bin/bash
# optimize-performance.sh - Optimize for large projects

echo "⚡ Optimizing for large project analysis..."

# Increase PHP memory limit
export PHP_MEMORY_LIMIT=1024M

# Generate documentation with limited depth
php artisan project:files-markdown \
    --output=docs/optimized/project-overview.md \
    --depth=2 \
    --exclude=vendor \
    --exclude=node_modules \
    --exclude=storage \
    --exclude=tests \
    --exclude=.git

echo "✅ Optimized documentation generated!"
```

## Best Practices

### Example 17: Documentation Workflow

```bash
#!/bin/bash
# docs-workflow.sh - Best practices workflow

echo "📚 Documentation Workflow - Best Practices"

# 1. Generate comprehensive documentation
echo "1️⃣ Generating comprehensive documentation..."
php artisan project:files-markdown \
    --output=docs/comprehensive/project-structure.md

php artisan project:db-markdown \
    --path=docs/comprehensive/database-schema.md

# 2. Generate focused documentation for specific use cases
echo "2️⃣ Generating focused documentation..."

# For developers
php artisan project:files-markdown \
    --output=docs/developers/code-structure.md \
    --exclude=tests \
    --exclude=storage \
    --depth=3

# For database administrators
php artisan project:db-markdown \
    --path=docs/dba/database-schema.md

# 3. Generate API documentation
echo "3️⃣ Generating API documentation..."
php artisan project:files-markdown \
    --output=docs/api/project-api.md \
    --exclude=tests \
    --exclude=storage \
    --exclude=resources/views

echo "✅ Documentation workflow complete!"
echo "📁 Check the docs/ directory for all generated files"
```

This comprehensive examples document provides real-world scenarios and practical implementations for using the Laravel Project Markdown package effectively.
