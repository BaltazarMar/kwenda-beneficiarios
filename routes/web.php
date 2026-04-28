<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstagiarioController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\FuncaoController;
use App\Http\Controllers\EfetividadeController;
use App\Http\Controllers\BeneficiarioController;

// ================= PÁGINA INICIAL =================
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/kwenda-dashboard');
    }
    return redirect()->route('login');
});

// ================= DASHBOARD =================
Route::get('/dashboard', function () {
    return redirect('/kwenda-dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ================= PERFIL =================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ================= ESTAGIÁRIOS =================
Route::get('/estagiarios', [EstagiarioController::class, 'index']);
Route::get('/estagiarios/create', [EstagiarioController::class, 'create']);
Route::post('/estagiarios', [EstagiarioController::class, 'store']);
Route::get('/estagiarios/{id}/edit', [EstagiarioController::class, 'edit']);
Route::put('/estagiarios/{id}', [EstagiarioController::class, 'update']);
Route::delete('/estagiarios/{id}', [EstagiarioController::class, 'destroy']);
Route::get('/estagiarios/pdf', [EstagiarioController::class, 'exportPdf']);
Route::get('/estagiarios/excel', [EstagiarioController::class, 'exportExcel'])->name('estagiarios.excel');

// ================= FUNCIONÁRIOS =================
Route::get('/funcionarios', [FuncionarioController::class, 'index']);
Route::get('/funcionarios/create', [FuncionarioController::class, 'create']);
Route::post('/funcionarios', [FuncionarioController::class, 'store']);
Route::get('/funcionarios/{id}/edit', [FuncionarioController::class, 'edit']);
Route::put('/funcionarios/{id}', [FuncionarioController::class, 'update']);
Route::delete('/funcionarios/{id}', [FuncionarioController::class, 'destroy']);

// ================= FUNÇÕES =================
Route::get('/funcoes', [FuncaoController::class, 'index']);
Route::get('/funcoes/create', [FuncaoController::class, 'create']);
Route::post('/funcoes', [FuncaoController::class, 'store']);
Route::get('/funcoes/{id}/edit', [FuncaoController::class, 'edit']);
Route::put('/funcoes/{id}', [FuncaoController::class, 'update']);
Route::delete('/funcoes/{id}', [FuncaoController::class, 'destroy']);

// ================= EFETIVIDADE =================
Route::get('/efetividade', [EfetividadeController::class, 'index']);
Route::post('/efetividade/salvar', [EfetividadeController::class, 'store']);
Route::post('/efetividade/pdf', [EfetividadeController::class, 'pdf']);
Route::get('/efetividades/csv', [EfetividadeController::class, 'csv']);

// ================= IMPORTAÇÃO =================
Route::post('/importar', [BeneficiarioController::class, 'importar']);
Route::get('/importar', function () {
    return view('importar');
});

// ================= KWENDA DASHBOARD =================
Route::get('/kwenda-dashboard', [BeneficiarioController::class, 'dashboard']);
Route::get('/dashboard-filtros', [BeneficiarioController::class, 'dashboardFiltros'])->name('dashboard.filtros');
Route::get('/dashboard-recorrencias', [BeneficiarioController::class, 'recorrenciasMunicipio'])->name('dashboard.recorrencias');

// ================= BENEFICIÁRIOS =================
Route::get('/beneficiarios/exportar', [BeneficiarioController::class, 'exportar'])->name('beneficiarios.exportar');
Route::get('/beneficiarios', [BeneficiarioController::class, 'index'])->name('beneficiarios.index');
Route::get('/beneficiarios/{beneficiario}', [BeneficiarioController::class, 'show'])->name('beneficiarios.show');
Route::get('/beneficiarios/{beneficiario}/edit', [BeneficiarioController::class, 'edit'])->name('beneficiarios.edit');
Route::put('/beneficiarios/{beneficiario}', [BeneficiarioController::class, 'update'])->name('beneficiarios.update');
Route::get('/bairros-por-municipio', [BeneficiarioController::class, 'bairrosPorMunicipio'])->name('bairros.por.municipio');

require __DIR__.'/auth.php';