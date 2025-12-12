<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ReviewPR extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pr:review 
                            {--notes= : Path to PR notes file or notes text}
                            {--diff= : Path to git diff file or git diff output}
                            {--output= : Output file path (default: PR_REVIEW.md)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a structured PR review document from PR notes and git diff';

    /**
     * Domain categories for organizing changes
     */
    private const DOMAINS = [
        'Rentals' => ['rental', 'tenant', 'property', 'lease', 'contract'],
        'Money/Currency' => ['payment', 'currency', 'money', 'price', 'cost', 'invoice', 'receipt'],
        'Settings' => ['setting', 'config', 'preference', 'option'],
        'Validation' => ['validation', 'validator', 'request', 'rule'],
        'Inventory/GRN' => ['inventory', 'stock', 'product', 'warehouse', 'grn', 'goods', 'receiving'],
        'UI Helpers' => ['component', 'icon', 'blade', 'view', 'livewire', 'ui', 'frontend'],
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔍 Analyzing PR for review...');
        $this->newLine();

        // Get input
        $notes = $this->getInput('notes', 'Please provide PR notes (file path or text): ');
        $diff = $this->getInput('diff', 'Please provide git diff (file path or text): ');

        if (empty($notes) && empty($diff)) {
            $this->error('❌ No input provided. Please provide either notes or diff.');
            return 1;
        }

        // Parse the inputs
        $parsedNotes = $this->parseNotes($notes);
        $parsedDiff = $this->parseDiff($diff);

        // Generate review
        $review = $this->generateReview($parsedNotes, $parsedDiff);

        // Output
        $outputPath = $this->option('output') ?: 'PR_REVIEW.md';
        $outputPath = base_path($outputPath);

        file_put_contents($outputPath, $review);

        $this->newLine();
        $this->info("✅ PR review generated successfully!");
        $this->line("📄 Output: {$outputPath}");

        return 0;
    }

    /**
     * Get input from option or prompt user
     */
    private function getInput(string $option, string $prompt): string
    {
        $value = $this->option($option);

        if ($value) {
            // Check if it's a file path
            if (file_exists($value)) {
                return file_get_contents($value);
            }
            return $value;
        }

        // Prompt user
        $input = $this->ask($prompt);
        if (!$input) {
            return '';
        }

        // Check if it's a file path
        if (file_exists($input)) {
            return file_get_contents($input);
        }

        return $input;
    }

    /**
     * Parse PR notes into structured data
     */
    private function parseNotes(string $notes): array
    {
        return [
            'raw' => $notes,
            'lines' => explode("\n", $notes),
        ];
    }

    /**
     * Parse git diff into structured data
     */
    private function parseDiff(string $diff): array
    {
        $files = [];
        $currentFile = null;
        $additions = 0;
        $deletions = 0;

        foreach (explode("\n", $diff) as $line) {
            if (str_starts_with($line, 'diff --git')) {
                // New file
                if ($currentFile) {
                    $files[] = $currentFile;
                }
                preg_match('/diff --git a\/(.*) b\/(.*)/', $line, $matches);
                $currentFile = [
                    'path' => $matches[2] ?? 'unknown',
                    'changes' => [],
                    'additions' => 0,
                    'deletions' => 0,
                ];
            } elseif ($currentFile) {
                if (str_starts_with($line, '+') && !str_starts_with($line, '+++')) {
                    $currentFile['additions']++;
                    $additions++;
                    $currentFile['changes'][] = $line;
                } elseif (str_starts_with($line, '-') && !str_starts_with($line, '---')) {
                    $currentFile['deletions']++;
                    $deletions++;
                    $currentFile['changes'][] = $line;
                }
            }
        }

        if ($currentFile) {
            $files[] = $currentFile;
        }

        return [
            'files' => $files,
            'total_additions' => $additions,
            'total_deletions' => $deletions,
            'total_files' => count($files),
        ];
    }

    /**
     * Categorize files by domain
     */
    private function categorizeByDomain(array $files): array
    {
        $categorized = [];

        foreach ($files as $file) {
            $path = strtolower($file['path']);
            $matched = false;

            foreach (self::DOMAINS as $domain => $keywords) {
                foreach ($keywords as $keyword) {
                    if (str_contains($path, $keyword)) {
                        $categorized[$domain][] = $file;
                        $matched = true;
                        break 2;
                    }
                }
            }

            if (!$matched) {
                $categorized['Other'][] = $file;
            }
        }

        return $categorized;
    }

    /**
     * Generate the PR review document
     */
    private function generateReview(array $notes, array $diff): string
    {
        $title = $this->generateTitle($notes, $diff);
        $summary = $this->generateSummary($notes, $diff);
        $detailedChanges = $this->generateDetailedChanges($diff);
        $behaviorChanges = $this->generateBehaviorChanges($notes, $diff);
        $testPlan = $this->generateTestPlan($diff);
        $riskNotes = $this->generateRiskNotes($diff);
        $checklist = $this->generateChecklist();

        return <<<MARKDOWN
# {$title}

## Summary

{$summary}

## Detailed Changes

{$detailedChanges}

## Behavior Changes / Potential Breaking Changes

{$behaviorChanges}

## Test Plan

{$testPlan}

## Risk / Rollback Notes

{$riskNotes}

## Checklist

{$checklist}

---

**Generated:** {$this->getCurrentDateTime()}  
**Files Changed:** {$diff['total_files']}  
**Lines Added:** {$diff['total_additions']}  
**Lines Removed:** {$diff['total_deletions']}
MARKDOWN;
    }

    /**
     * Generate PR title
     */
    private function generateTitle(array $notes, array $diff): string
    {
        // Try to extract title from notes
        $firstLine = $notes['lines'][0] ?? '';
        
        if (str_starts_with($firstLine, '#')) {
            $title = trim(ltrim($firstLine, '#'));
        } else {
            // Generate from files changed
            $domains = $this->categorizeByDomain($diff['files']);
            $mainDomains = array_slice(array_keys($domains), 0, 2);
            $title = 'Update ' . implode(' and ', $mainDomains);
        }

        // Ensure title is <= 72 chars
        if (strlen($title) > 72) {
            $title = substr($title, 0, 69) . '...';
        }

        return $title;
    }

    /**
     * Generate summary section
     */
    private function generateSummary(array $notes, array $diff): string
    {
        $domains = $this->categorizeByDomain($diff['files']);
        $bullets = [];

        foreach ($domains as $domain => $files) {
            $fileCount = count($files);
            $bullets[] = "- Updated {$fileCount} file(s) in **{$domain}** domain";
        }

        // Add note about impact
        if ($diff['total_additions'] > 100 || $diff['total_deletions'] > 100) {
            $bullets[] = "- Significant changes: +{$diff['total_additions']} / -{$diff['total_deletions']} lines";
        }

        return implode("\n", array_slice($bullets, 0, 4));
    }

    /**
     * Generate detailed changes section
     */
    private function generateDetailedChanges(array $diff): string
    {
        $domains = $this->categorizeByDomain($diff['files']);
        $output = [];

        foreach ($domains as $domain => $files) {
            $output[] = "### {$domain}";
            $output[] = '';

            foreach ($files as $file) {
                $adds = $file['additions'];
                $dels = $file['deletions'];
                $output[] = "**{$file['path']}** (+{$adds} / -{$dels})";
                
                // Add a brief description based on file type
                $description = $this->describeFileChanges($file);
                if ($description) {
                    $output[] = "- {$description}";
                }
                $output[] = '';
            }
        }

        return implode("\n", $output);
    }

    /**
     * Describe changes in a file
     */
    private function describeFileChanges(array $file): string
    {
        $path = $file['path'];
        $adds = $file['additions'];
        $dels = $file['deletions'];

        if (str_ends_with($path, '.php')) {
            if ($adds > $dels * 2) {
                return "Added new functionality";
            } elseif ($dels > $adds * 2) {
                return "Removed or refactored code";
            } else {
                return "Modified implementation";
            }
        } elseif (str_ends_with($path, '.blade.php')) {
            return "Updated UI template";
        } elseif (str_contains($path, 'migration')) {
            return "Database schema changes";
        } elseif (str_contains($path, 'test')) {
            return "Updated tests";
        }

        return "Modified file";
    }

    /**
     * Generate behavior changes section
     */
    private function generateBehaviorChanges(array $notes, array $diff): string
    {
        $output = [];

        // Check for potential breaking changes
        $hasModelChanges = false;
        $hasValidationChanges = false;
        $hasDatabaseChanges = false;

        foreach ($diff['files'] as $file) {
            $path = strtolower($file['path']);
            
            if (str_contains($path, 'model')) {
                $hasModelChanges = true;
            }
            if (str_contains($path, 'request') || str_contains($path, 'validation')) {
                $hasValidationChanges = true;
            }
            if (str_contains($path, 'migration')) {
                $hasDatabaseChanges = true;
            }
        }

        if ($hasDatabaseChanges) {
            $output[] = "⚠️ **Database Schema Changes**";
            $output[] = "- Review migrations carefully before deploying";
            $output[] = "- Ensure backups are in place";
            $output[] = "";
        }

        if ($hasModelChanges) {
            $output[] = "⚠️ **Model Changes**";
            $output[] = "- May affect data access patterns";
            $output[] = "- Verify relationships and queries";
            $output[] = "";
        }

        if ($hasValidationChanges) {
            $output[] = "⚠️ **Validation Changes**";
            $output[] = "- May affect data acceptance criteria";
            $output[] = "- Test with existing data formats";
            $output[] = "";
        }

        if (empty($output)) {
            return "No breaking changes identified. Changes appear to be backward compatible.";
        }

        return implode("\n", $output);
    }

    /**
     * Generate test plan section
     */
    private function generateTestPlan(array $diff): string
    {
        $output = [];
        $output[] = "### Automated Tests";
        $output[] = "";
        $output[] = "```bash";
        $output[] = "# Run PHPUnit tests";
        $output[] = "php artisan test";
        $output[] = "";
        $output[] = "# Run specific test suite";
        $output[] = "php artisan test --testsuite=Feature";
        $output[] = "```";
        $output[] = "";
        $output[] = "### Manual Testing";
        $output[] = "";

        // Generate manual test flows based on domains
        $domains = $this->categorizeByDomain($diff['files']);
        
        foreach ($domains as $domain => $files) {
            if ($domain === 'Other') {
                continue;
            }

            $output[] = "**{$domain}:**";
            $output[] = $this->getManualTestFlow($domain);
            $output[] = "";
        }

        return implode("\n", $output);
    }

    /**
     * Get manual test flow for a domain
     */
    private function getManualTestFlow(string $domain): string
    {
        return match($domain) {
            'Rentals' => "1. Navigate to Rental module\n2. Test tenant/property CRUD operations\n3. Verify contract generation",
            'Money/Currency' => "1. Process a test payment\n2. Verify currency conversion\n3. Check invoice generation",
            'Settings' => "1. Access settings page\n2. Modify and save configuration\n3. Verify changes take effect",
            'Validation' => "1. Submit forms with valid data\n2. Submit forms with invalid data\n3. Verify error messages",
            'Inventory/GRN' => "1. Test product CRUD operations\n2. Verify stock movements\n3. Check GRN processing",
            'UI Helpers' => "1. Review UI components visually\n2. Test responsive behavior\n3. Verify icon rendering",
            default => "1. Test core functionality\n2. Verify data integrity\n3. Check error handling",
        };
    }

    /**
     * Generate risk and rollback notes
     */
    private function generateRiskNotes(array $diff): string
    {
        $output = [];
        
        $output[] = "### Risk Assessment";
        $output[] = "";
        
        $riskLevel = "Low";
        if ($diff['total_additions'] > 500 || $diff['total_deletions'] > 200) {
            $riskLevel = "High";
        } elseif ($diff['total_additions'] > 100 || $diff['total_deletions'] > 50) {
            $riskLevel = "Medium";
        }

        $output[] = "**Risk Level:** {$riskLevel}";
        $output[] = "";
        $output[] = "**Factors:**";
        $output[] = "- Number of files changed: {$diff['total_files']}";
        $output[] = "- Lines of code changed: " . ($diff['total_additions'] + $diff['total_deletions']);
        $output[] = "";
        $output[] = "### Rollback Plan";
        $output[] = "";
        $output[] = "```bash";
        $output[] = "# If issues arise, revert the changes";
        $output[] = "git revert HEAD";
        $output[] = "";
        $output[] = "# Clear caches";
        $output[] = "php artisan config:clear";
        $output[] = "php artisan route:clear";
        $output[] = "php artisan view:clear";
        $output[] = "```";

        return implode("\n", $output);
    }

    /**
     * Generate checklist section
     */
    private function generateChecklist(): string
    {
        return <<<'CHECKLIST'
- [ ] Code review completed
- [ ] All tests passing
- [ ] No new security vulnerabilities
- [ ] Documentation updated (if needed)
- [ ] Database migrations tested (if applicable)
- [ ] Backward compatibility verified
- [ ] Performance impact assessed
- [ ] Error handling tested
- [ ] Manual testing completed
- [ ] Ready for production deployment
CHECKLIST;
    }

    /**
     * Get current date time formatted
     */
    private function getCurrentDateTime(): string
    {
        return now()->format('Y-m-d H:i:s T');
    }
}
