@echo off
REM Laravel Project Markdown - Git Setup Script for Windows
REM This script helps you set up git and prepare for GitHub publishing

echo 🚀 Setting up Git for Laravel Project Markdown package...

REM Check if git is initialized
if not exist ".git" (
    echo 📁 Initializing Git repository...
    git init
) else (
    echo ✅ Git repository already initialized
)

REM Add all files
echo 📝 Adding all files to git...
git add .

REM Check if there are changes to commit
git diff --staged --quiet
if %errorlevel% equ 0 (
    echo ℹ️  No changes to commit
) else (
    echo 💾 Creating initial commit...
    git commit -m "Initial commit: Laravel Project Markdown package v1.0.0"
)

REM Check if remote origin exists
git remote get-url origin >nul 2>&1
if %errorlevel% equ 0 (
    echo ✅ Remote origin already configured
    echo 📍 Current remote:
    git remote get-url origin
) else (
    echo 🔗 Please add your GitHub repository as remote origin:
    echo    git remote add origin https://github.com/saeedvir/laravel-project-markdown.git
)

echo.
echo 📋 Next steps:
echo 1. Create GitHub repository: https://github.com/new
echo 2. Add remote origin: git remote add origin ^<your-repo-url^>
echo 3. Push to GitHub: git push -u origin main
echo 4. Create release tag: git tag -a v1.0.0 -m "Release version 1.0.0"
echo 5. Push tag: git push origin v1.0.0
echo 6. Submit to Packagist: https://packagist.org/packages/submit
echo.
echo 📚 See docs/PUBLISHING.md for complete instructions
pause
