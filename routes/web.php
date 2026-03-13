<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DecisionController;
use App\Http\Controllers\ActionPlanController;
use App\Http\Controllers\StrategicAxisController;
use App\Http\Controllers\ActionController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ProgressUpdateController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ValidationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AiAnalysisController;
use App\Http\Controllers\GedController;
use App\Http\Controllers\DocumentValidationController;
use App\Http\Controllers\ManualController;

Route::get('/', function () {
    return view('welcome');
});

// Static Public Pages
Route::get('/plateforme', function () { return view('pages.plateforme'); })->name('public.plateforme');
Route::get('/modules', function () { return view('pages.modules'); })->name('public.modules');
Route::get('/instances', function () { return view('pages.instances'); })->name('public.instances');
Route::get('/rapports', function () { return view('pages.rapports'); })->name('public.rapports');
Route::get('/documentation', function () { return view('pages.documentation'); })->name('public.documentation');
Route::get('/a-propos', function () { return view('pages.about'); })->name('public.about');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('decisions/export', [DecisionController::class, 'export'])->name('decisions.export');
    Route::post('decisions/preview', [DecisionController::class, 'preview'])->name('decisions.preview');
    Route::post('decisions/autosave', [DecisionController::class, 'autosave'])->name('decisions.autosave');
    Route::resource('decisions', DecisionController::class);
    Route::resource('action-plans', ActionPlanController::class);
    Route::resource('strategic-axes', StrategicAxisController::class);
    Route::resource('actions', ActionController::class);
    Route::resource('activities', ActivityController::class);
    
    Route::get('progress-updates/{progress_update}/export-pdf', [ProgressUpdateController::class, 'exportPdf'])->name('progress-updates.export-pdf');
    Route::resource('progress-updates', ProgressUpdateController::class);
    
    // GED — Documents (store + update pour versioning, destroy pour suppression logique)
    Route::resource('documents', DocumentController::class)->only(['store', 'update', 'destroy']);
    Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    
    // Validations
    Route::get('validations', [ValidationController::class, 'index'])->name('validations.index');
    Route::post('validations', [ValidationController::class, 'store'])->name('validations.store');

    // Rapports Stratégiques
    Route::get('reports/strategic', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/strategic/pdf', [ReportController::class, 'strategicPdf'])->name('reports.strategic.pdf');

    // Module IA : Analyse de Décisions
    Route::get('ai-analyses/create', [AiAnalysisController::class, 'create'])->name('ai-analyses.create');
    // Throttle : max 5 analyses par utilisateur par heure (prévient le spam API OpenAI)
    Route::post('ai-analyses/analyze', [AiAnalysisController::class, 'analyze'])
        ->middleware('throttle:5,60')
        ->name('ai-analyses.analyze');
    Route::get('ai-analyses/{aiAnalysis}', [AiAnalysisController::class, 'show'])->name('ai-analyses.show');
    Route::post('ai-analyses/{aiAnalysis}/confirm', [AiAnalysisController::class, 'confirm'])->name('ai-analyses.confirm');

    // Missing Routes from previous features
    Route::resource('actor-assignments', ActorAssignmentController::class)->only(['store', 'destroy']);
    Route::resource('milestones', MilestoneController::class)->only(['store', 'destroy', 'update']);
    Route::resource('deliverables', DeliverableController::class)->only(['store', 'destroy', 'update']);
    Route::resource('indicators', \App\Http\Controllers\IndicatorController::class)->only(['store', 'destroy', 'update']);

    // Administration Hub (Paramétrage)
    Route::get('admin', function () { return view('admin.index'); })->name('admin.index');
    Route::resource('decision-types', \App\Http\Controllers\DecisionTypeController::class);
    Route::resource('decision-categories', \App\Http\Controllers\DecisionCategoryController::class);
    Route::resource('domains', \App\Http\Controllers\DomainController::class);
    Route::resource('sessions', \App\Http\Controllers\SessionController::class);

    // Organisation Institutionnelle
    Route::resource('countries', \App\Http\Controllers\CountryController::class);
    Route::resource('institutions', \App\Http\Controllers\InstitutionController::class);
    Route::resource('organs', \App\Http\Controllers\OrganController::class);
    Route::resource('departments', \App\Http\Controllers\DepartmentController::class);
    Route::resource('directions', \App\Http\Controllers\DirectionController::class);

    // Journal d'Audit
    Route::get('audit-logs', [\App\Http\Controllers\AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('audit-logs/{auditLog}', [\App\Http\Controllers\AuditLogController::class, 'show'])->name('audit-logs.show');

    // GED — Module principal
    Route::get('ged', [GedController::class, 'index'])->name('ged.index');
    Route::get('ged/export', [GedController::class, 'export'])->name('ged.export');
    Route::get('ged/documents/{document}', [GedController::class, 'show'])->name('ged.show');

    // GED — Workflow de validation documentaire
    Route::get('ged/validations', [DocumentValidationController::class, 'index'])->name('ged.validations.index');
    Route::post('ged/documents/{document}/start-validation', [DocumentValidationController::class, 'start'])->name('ged.validations.start');
    Route::post('ged/validations/{step}/approve', [DocumentValidationController::class, 'approve'])->name('ged.validations.approve');
    Route::post('ged/validations/{step}/reject', [DocumentValidationController::class, 'reject'])->name('ged.validations.reject');

    // Gestion des Utilisateurs et Rôles
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::resource('roles', \App\Http\Controllers\RoleController::class);

    // Manuel Utilisateur
    Route::get('manual/download', [ManualController::class, 'download'])->name('manual.download');
});
