<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstagiarioController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\FuncaoController;
use App\Http\Controllers\EfetividadeController;
use App\Http\Controllers\BeneficiarioController;
use App\Http\Controllers\KoboSyncController;

// ================= PÁGINA INICIAL =================
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/painel');
    }
    return redirect()->route('login');
});

// ================= LOGOUT MANUAL =================
Route::get('/sair', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
});

// ================= DASHBOARD LARAVEL (após login) =================
Route::get('/dashboard', function () {
    return redirect('/painel');
})->middleware(['auth', 'verified'])->name('dashboard');

// ================= ROTAS PROTEGIDAS =================
Route::middleware(['auth'])->group(function () {

    // Painel principal — todos os utilizadores autenticados
    Route::get('/painel', function () {
        return view('dashboard');
    });

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Kwenda Dashboard — director, assistente_dados, assistente_local
    Route::middleware(['role:director|assistente_dados|assistente_local'])->group(function () {
        Route::get('/kwenda-dashboard', [BeneficiarioController::class, 'dashboard']);
        Route::get('/dashboard-filtros', [BeneficiarioController::class, 'dashboardFiltros'])->name('dashboard.filtros');
        Route::get('/dashboard-recorrencias', [BeneficiarioController::class, 'recorrenciasMunicipio'])->name('dashboard.recorrencias');
        Route::get('/beneficiarios', [BeneficiarioController::class, 'index'])->name('beneficiarios.index');
        Route::get('/beneficiarios/{beneficiario}', [BeneficiarioController::class, 'show'])->name('beneficiarios.show');
        Route::get('/bairros-por-municipio', [BeneficiarioController::class, 'bairrosPorMunicipio'])->name('bairros.por.municipio');
        Route::get('/beneficiarios/exportar', [BeneficiarioController::class, 'exportar'])->name('beneficiarios.exportar');
    });

    // Beneficiários editar — director, assistente_dados
    Route::middleware(['role:director|assistente_dados'])->group(function () {
        Route::get('/beneficiarios/{beneficiario}/edit', [BeneficiarioController::class, 'edit'])->name('beneficiarios.edit');
        Route::put('/beneficiarios/{beneficiario}', [BeneficiarioController::class, 'update'])->name('beneficiarios.update');
        Route::post('/importar', [BeneficiarioController::class, 'importar']);
        Route::get('/importar', function () {
            return view('importar');
        });
    });

    // Funcionários — director, recepcionista
    Route::middleware(['role:director|recepcionista'])->group(function () {
        Route::get('/funcionarios', [FuncionarioController::class, 'index']);
        Route::get('/funcionarios/create', [FuncionarioController::class, 'create']);
        Route::post('/funcionarios', [FuncionarioController::class, 'store']);
        Route::get('/funcionarios/{id}/edit', [FuncionarioController::class, 'edit']);
        Route::put('/funcionarios/{id}', [FuncionarioController::class, 'update']);
        Route::delete('/funcionarios/{id}', [FuncionarioController::class, 'destroy']);
    });

    // Estagiários — director
    Route::middleware(['role:director|assistente_local'])->group(function () {
        Route::get('/estagiarios', [EstagiarioController::class, 'index']);
        Route::get('/estagiarios/create', [EstagiarioController::class, 'create']);
        Route::post('/estagiarios', [EstagiarioController::class, 'store']);
        Route::get('/estagiarios/{id}/edit', [EstagiarioController::class, 'edit']);
        Route::put('/estagiarios/{id}', [EstagiarioController::class, 'update']);
        Route::delete('/estagiarios/{id}', [EstagiarioController::class, 'destroy']);
        Route::get('/estagiarios/pdf', [EstagiarioController::class, 'exportPdf']);
        Route::get('/estagiarios/excel', [EstagiarioController::class, 'exportExcel'])->name('estagiarios.excel');
    });

    // Funções — director
    Route::middleware(['role:director'])->group(function () {
        Route::get('/funcoes', [FuncaoController::class, 'index']);
        Route::get('/funcoes/create', [FuncaoController::class, 'create']);
        Route::post('/funcoes', [FuncaoController::class, 'store']);
        Route::get('/funcoes/{id}/edit', [FuncaoController::class, 'edit']);
        Route::put('/funcoes/{id}', [FuncaoController::class, 'update']);
        Route::delete('/funcoes/{id}', [FuncaoController::class, 'destroy']);
    });

    // Efetividade — director, recepcionista
    Route::middleware(['role:director|recepcionista|motorista'])->group(function () {
        Route::get('/efetividade', [EfetividadeController::class, 'index']);
        Route::post('/efetividade/salvar', [EfetividadeController::class, 'store']);
        Route::post('/efetividade/pdf', [EfetividadeController::class, 'pdf']);
        Route::get('/efetividades/csv', [EfetividadeController::class, 'csv']);
    });

});



Route::prefix('kobo')->name('kobo.')->middleware(['auth'])->group(function () {
    Route::get('/sync',      [KoboSyncController::class, 'index'])             ->name('sync');
    Route::post('/importar', [KoboSyncController::class, 'importar'])          ->name('importar');
    Route::get('/json',      [KoboSyncController::class, 'json'])              ->name('json');
    Route::post('/eliminar', [KoboSyncController::class, 'eliminarDuplicados'])->name('eliminar');
    Route::get('/exportar', [KoboSyncController::class, 'exportarExcel'])->name('exportar');
    Route::post('/eliminar-individual', [KoboSyncController::class, 'eliminarIndividual'])->name('eliminar.individual');
    Route::post('/limpar-todos', [KoboSyncController::class, 'limparTodos'])->name('limpar.todos');
});

require __DIR__.'/auth.php';