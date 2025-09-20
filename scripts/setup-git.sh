#!/bin/bash

# Laravel Project Markdown - Git Setup Script
# This script helps you set up git and prepare for GitHub publishing

echo "🚀 Setting up Git for Laravel Project Markdown package..."

# Check if git is initialized
if [ ! -d ".git" ]; then
    echo "📁 Initializing Git repository..."
    git init
else
    echo "✅ Git repository already initialized"
fi

# Add all files
echo "📝 Adding all files to git..."
git add .

# Check if there are changes to commit
if git diff --staged --quiet; then
    echo "ℹ️  No changes to commit"
else
    echo "💾 Creating initial commit..."
    git commit -m "Initial commit: Laravel Project Markdown package v1.0.0"
fi

# Check if remote origin exists
if git remote get-url origin >/dev/null 2>&1; then
    echo "✅ Remote origin already configured"
    echo "📍 Current remote: $(git remote get-url origin)"
else
    echo "🔗 Please add your GitHub repository as remote origin:"
    echo "   git remote add origin https://github.com/saeedvir/laravel-project-markdown.git"
fi

echo ""
echo "📋 Next steps:"
echo "1. Create GitHub repository: https://github.com/new"
echo "2. Add remote origin: git remote add origin <your-repo-url>"
echo "3. Push to GitHub: git push -u origin main"
echo "4. Create release tag: git tag -a v1.0.0 -m 'Release version 1.0.0'"
echo "5. Push tag: git push origin v1.0.0"
echo "6. Submit to Packagist: https://packagist.org/packages/submit"
echo ""
echo "📚 See docs/PUBLISHING.md for complete instructions"
