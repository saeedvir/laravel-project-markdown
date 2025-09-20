# Quick Publishing Steps

Follow these steps to publish your Laravel Project Markdown package to GitHub and Packagist.

## 🚀 Quick Start (5 minutes)

### Step 1: Create GitHub Repository

1. Go to [GitHub.com](https://github.com) and sign in
2. Click **"New Repository"** (green button)
3. Fill in:
   - **Repository name**: `laravel-project-markdown`
   - **Description**: `Laravel package to generate comprehensive markdown documentation for project files and database schema with AI-readable output`
   - **Visibility**: Public ✅
   - **Initialize**: Don't check any boxes (you already have files)
4. Click **"Create repository"**

### Step 2: Setup Git (Windows)

```bash
# Run the setup script
scripts\setup-git.bat

# Or manually:
git init
git add .
git commit -m "Initial commit: Laravel Project Markdown package v1.0.0"
git remote add origin https://github.com/saeedvir/laravel-project-markdown.git
git push -u origin main
```

### Step 3: Create Release

```bash
# Create and push version tag
git tag -a v1.0.0 -m "Release version 1.0.0"
git push origin v1.0.0
```

### Step 4: Submit to Packagist

1. Go to [Packagist.org](https://packagist.org)
2. Sign up with your GitHub account
3. Click **"Submit"**
4. Enter: `https://github.com/saeedvir/laravel-project-markdown`
5. Click **"Check"** then **"Submit"**

### Step 5: Enable Auto-Update

1. Go to your package page on Packagist
2. Click **"Settings"** tab
3. Enable **"GitHub Service Hook"**

## ✅ Verification

Test your published package:

```bash
# In a new Laravel project
composer require saeedvir/laravel-project-markdown

# Test the commands
php artisan project:files-markdown
php artisan project:db-markdown
```

## 📋 Complete Checklist

- [ ] GitHub repository created and public
- [ ] All files pushed to GitHub
- [ ] Version tag v1.0.0 created and pushed
- [ ] Packagist account created
- [ ] Package submitted to Packagist
- [ ] GitHub integration enabled on Packagist
- [ ] Package installs via Composer
- [ ] Commands work correctly

## 🎉 Success!

Your package is now published and available worldwide! Users can install it with:

```bash
composer require saeedvir/laravel-project-markdown
```

## 📚 Detailed Guide

For complete instructions, see [docs/PUBLISHING.md](docs/PUBLISHING.md)

## 🔧 Troubleshooting

**Package not found on Packagist?**
- Check repository URL is correct
- Ensure repository is public
- Wait a few minutes for Packagist to process

**Auto-update not working?**
- Verify GitHub service hook is enabled
- Check repository permissions
- Test with a new commit

**Need help?**
- Check [docs/PUBLISHING.md](docs/PUBLISHING.md) for detailed instructions
- Review the troubleshooting section
