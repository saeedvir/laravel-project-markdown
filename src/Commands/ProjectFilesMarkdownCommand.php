<?php

namespace Saeedvir\LaravelProjectMarkdown\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ProjectFilesMarkdownCommand extends Command
{
    protected $signature = 'project:files-markdown
    {path? : project path (defaults to base_path())}
    {--o|output= : output markdown file (defaults to storage/app/project-structure.md)}
    {--exclude=* : additional names to exclude (repeatable)}
    {--depth= : max depth (integer)}';

    protected $description = 'Generate a markdown + JSON file documenting this Laravel project (meta + structure).';

    public function handle()
    {
        $path = $this->argument('path') ?? base_path();
        $output = $this->option('output') ?? storage_path('app/project-structure.md');
        $excludes = $this->option('exclude') ?? [];
        $depth = $this->option('depth') !== null ? (int)$this->option('depth') : PHP_INT_MAX;

        if (!is_dir($path)) {
            $this->error("Path not found or not a directory: {$path}");
            return 1;
        }

        try {
            [$count, $meta] = $this->generateDocs($path, $output, $excludes, $depth);
        } catch (InvalidArgumentException $ex) {
            $this->error($ex->getMessage());
            return 1;
        }

        $this->info("Wrote markdown to: {$output}");
        if (config('laravel-project-markdown.json.enabled', true)) {
            $this->info("Wrote JSON to: " . preg_replace('/\.md$/', '.json', $output));
        }
        $this->info("Entries written: {$count}");

        return 0;
    }

    protected function generateDocs(string $path, string $outputFile, array $excludes, int $maxDepth): array
    {
        $defaultExcludes = config(
            'laravel-project-markdown.files.exclude_directories',
            ['vendor', 'storage', 'node_modules', 'tests', '.git']
        );
        $excludes = array_unique(array_merge($defaultExcludes, $excludes));

        $projectName = basename($path);
        $laravelVersion = \Illuminate\Foundation\Application::VERSION ?? 'Unknown';
        $phpVersion = PHP_VERSION;

        // نسخه دیتابیس
        $dbVersion = 'Unknown';
        try {
            $ver = DB::select('select version() as v');
            if (!empty($ver[0]->v)) {
                $dbVersion = $ver[0]->v;
            }
        } catch (\Throwable $e) {
            // اگر اتصال به دیتابیس برقرار نیست، خطا ندهد
            $dbVersion = 'Unavailable';
        }

        // پکیج‌ها
        $packages = [];
        $discoverable = [];
        if (config('laravel-project-markdown.markdown.include_package_info', true)) {
            $composerLockPath = $path . DIRECTORY_SEPARATOR . 'composer.lock';
            if (file_exists($composerLockPath)) {
                $lock = json_decode(@file_get_contents($composerLockPath), true);
                foreach ($lock['packages'] ?? [] as $pkg) {
                    $packages[] = [
                        'name' => $pkg['name'] ?? '',
                        'version' => $pkg['version'] ?? ''
                    ];
                    if (!empty($pkg['extra']['laravel']['providers']) || !empty($pkg['extra']['laravel']['aliases'])) {
                        $discoverable[] = [
                            'name' => $pkg['name'],
                            'version' => $pkg['version'],
                            'providers' => $pkg['extra']['laravel']['providers'] ?? [],
                            'aliases' => $pkg['extra']['laravel']['aliases'] ?? []
                        ];
                    }
                }
            }
        }


        // درخت فایل‌ها
        $sizeCache = [];
        $tree = [];
        $count = 0;

        $computeDirSize = function (string $dir, int $currentDepth) use (&$computeDirSize, &$sizeCache, $excludes, $maxDepth): int {
            $real = @realpath($dir) ?: $dir;
            if (isset($sizeCache[$real])) return $sizeCache[$real];
            if ($currentDepth > $maxDepth) return $sizeCache[$real] = 0;

            $total = 0;
            $items = @scandir($dir);
            if ($items === false) return 0;
            foreach ($items as $item) {
                if ($item === '.' || $item === '..' || in_array($item, $excludes, true)) continue;
                $full = $dir . DIRECTORY_SEPARATOR . $item;
                if (!@is_readable($full)) continue;
                if (is_dir($full)) {
                    $total += $computeDirSize($full, $currentDepth + 1);
                } else {
                    $size = @filesize($full);
                    if ($size !== false) $total += $size;
                }
            }
            return $sizeCache[$real] = $total;
        };

        $iterator = function (string $dir, int $depth) use (&$iterator, &$tree, &$count, $excludes, $path, $maxDepth, $computeDirSize) {
            if ($depth > $maxDepth) return;
            $items = @scandir($dir);
            if ($items === false) return;

            $dirs = [];
            $files = [];
            foreach ($items as $item) {
                if ($item === '.' || $item === '..' || in_array($item, $excludes, true)) continue;
                $full = $dir . DIRECTORY_SEPARATOR . $item;
                if (is_dir($full)) $dirs[] = $item;
                else $files[] = $item;
            }
            sort($dirs, SORT_NATURAL | SORT_FLAG_CASE);
            sort($files, SORT_NATURAL | SORT_FLAG_CASE);

            foreach ($dirs as $d) {
                $full = $dir . DIRECTORY_SEPARATOR . $d;
                $bytes = $computeDirSize($full, $depth);
                $mtime = @filemtime($full);
                $tree[] = [
                    'type' => 'dir',
                    'path' => str_replace($path . DIRECTORY_SEPARATOR, '', $full),
                    'size' => $bytes,
                    'modified' => $mtime ? date('Y-m-d H:i', $mtime) : null
                ];
                $count++;
                $iterator($full, $depth + 1);
            }

            foreach ($files as $f) {
                $full = $dir . DIRECTORY_SEPARATOR . $f;
                $size = @filesize($full);
                $mtime = @filemtime($full);
                $tree[] = [
                    'type' => 'file',
                    'path' => str_replace($path . DIRECTORY_SEPARATOR, '', $full),
                    'size' => $size ?: 0,
                    'modified' => $mtime ? date('Y-m-d H:i', $mtime) : null
                ];
                $count++;
            }
        };

        $iterator($path, 0);

        // ساخت مارک‌داون
        $lines = [];
        $lines[] = "# Project structure for `{$projectName}`";
        $lines[] = '';
        $lines[] = '> Project Type: Laravel PHP Web Application';
        $lines[] = '> Generated: ' . date('Y-m-d H:i:s');
        $lines[] = '> Generated By: https://github.com/saeedvir/laravel-project-markdown';

        $lines[] = '';

        $lines[] = "## Versions";
        $lines[] = "- Laravel: **{$laravelVersion}**";
        $lines[] = "- PHP: **{$phpVersion}**";
        $lines[] = "- Database: **{$dbVersion}**";
        $lines[] = '';

        if (!empty($packages)) {
            $lines[] = "## Composer Packages";
            $lines[] = "| Package | Version |";
            $lines[] = "|---------|---------|";
            foreach ($packages as $pkg) {
                $lines[] = "| {$pkg['name']} | {$pkg['version']} |";
            }
            $lines[] = '';

            if (!empty($discoverable)) {
                $lines[] = "## Discoverable Laravel Packages";
                foreach ($discoverable as $d) {
                    $lines[] = "- **{$d['name']}** `{$d['version']}`";
                    if (!empty($d['providers'])) {
                        $lines[] = "  - Providers:";
                        foreach ($d['providers'] as $p) {
                            $lines[] = "    - `{$p}`";
                        }
                    }
                    if (!empty($d['aliases'])) {
                        $lines[] = "  - Aliases:";
                        foreach ($d['aliases'] as $alias => $class) {
                            $lines[] = "    - `{$alias}` → `{$class}`";
                        }
                    }
                }
                $lines[] = '';
            }
        }


        $lines[] = "## Project Tree";
        foreach ($tree as $item) {
            $name = basename($item['path']);
            $size = $item['size'] ? $this->formatBytes($item['size']) : '?';
            $time = $item['modified'] ?? '?';
            $lines[] = "- " . ($item['type'] === 'dir' ? "**{$name}/**" : $name) . " — `{$size}` — _modified: {$time}_";
        }

        file_put_contents($outputFile, implode(PHP_EOL, $lines));

        if (config('laravel-project-markdown.json.enabled', true)) {
            // ساخت فایل JSON
            $jsonFile = preg_replace('/\.md$/', '.json', $outputFile);
        } else {
            $jsonFile = false;
        }

        $meta = [
            'project' => $projectName,
            'path' => $path,
            'type' => 'Laravel PHP Web Application',
            'generated' => date('Y-m-d H:i:s'),
            'by' => 'https://github.com/saeedvir/laravel-project-markdown',
            'versions' => [
                'laravel' => $laravelVersion,
                'php' => $phpVersion,
                'database' => $dbVersion
            ],
            'packages' => $packages,
            'discoverable' => $discoverable,
            'files' => $tree
        ];
        if ($jsonFile !== false) {
            file_put_contents($jsonFile, json_encode($meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }


        return [$count, $meta];
    }

    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $bytes >= 1024 && $i < 4; $i++) $bytes /= 1024;
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
