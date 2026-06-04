<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadImagesToR2 extends Command
{
    protected $signature = 'storage:upload-to-r2
                            {--dry-run : List files without uploading}
                            {--disk=r2 : Target cloud disk}';

    protected $description = 'Upload all local public storage images to Cloudflare R2 (or another cloud disk)';

    public function handle(): int
    {
        $targetDisk = $this->option('disk');
        $dryRun = $this->option('dry-run');

        $localDisk = Storage::disk('public');
        $cloudDisk = Storage::disk($targetDisk);

        $files = $localDisk->allFiles();

        if (empty($files)) {
            $this->warn('No files found in public storage disk.');
            return self::SUCCESS;
        }

        $this->info(sprintf('Found %d files in local public disk.', count($files)));

        if ($dryRun) {
            foreach ($files as $file) {
                $this->line("  [dry-run] Would upload: {$file}");
            }
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar(count($files));
        $bar->start();

        $uploaded = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($files as $file) {
            try {
                if ($cloudDisk->exists($file)) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                $stream = $localDisk->readStream($file);
                $cloudDisk->put($file, $stream, 'public');
                fclose($stream);

                $uploaded++;
            } catch (\Throwable $e) {
                $failed++;
                $this->newLine();
                $this->error("Failed: {$file} — {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Done. Uploaded: {$uploaded} | Skipped (already exists): {$skipped} | Failed: {$failed}");

        return self::SUCCESS;
    }
}
