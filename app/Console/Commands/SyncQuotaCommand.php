<?php

namespace App\Console\Commands;

use App\Services\QuotaService;
use Illuminate\Console\Command;

class SyncQuotaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quota:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi alokasi kuota bidang dan otomatisasi status pendaftar selesai magang';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Memulai sinkronisasi kuota bidang...');

        try {
            QuotaService::syncDepartmentQuotas();
            $this->info('Sinkronisasi kuota bidang Diskominfo Garut berhasil dilakukan.');
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Terjadi kesalahan saat sinkronisasi kuota: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
