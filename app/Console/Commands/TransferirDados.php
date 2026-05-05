<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferirDados extends Command
{
    protected $signature   = 'transferir:dados';
    protected $description = 'Transfere dados do MySQL local para Clever Cloud';

    public function handle()
{
    $this->info('A transferir beneficiários...');

    $total = DB::connection('mysql_local')->table('beneficiarios')->count();
    $bar   = $this->output->createProgressBar($total);

    DB::connection('mysql_local')->table('beneficiarios')->orderBy('id')->chunk(500, function($beneficiarios) use ($bar) {
        $lote = collect($beneficiarios)
            ->groupBy('social_id')
            ->map(fn($grupo) => $grupo->last())
            ->values()
            ->map(fn($b) => (array) $b)
            ->toArray();

        DB::connection('mysql_clever')->table('beneficiarios')->upsert(
            $lote,
            ['social_id'],
            array_keys($lote[0])
        );

        $bar->advance(count($lote));
    });

    $bar->finish();
    $this->info("\nTransferência concluída!");
}
}