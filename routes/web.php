<?php

use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\Legatra\GenerateNumberController;
use App\Http\Controllers\Legatra\QRDocumentController;
use App\Http\Controllers\Legatra\RequestDocumentQRController;
use App\Http\Controllers\Legatra\User\RequestDocumentQRController as UserRequestDocumentQRController;
use App\Http\Controllers\Legatra\TSP\RequestDocumentController as TSPRequestDocumentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
//     return Redirect::to($actual_link . '/satria');
//     // return env('SATRIA_URL');
// })->name('index');
Route::get('/welcome', [App\Http\Controllers\HomeController::class, 'index'])->name('welcome');
Route::get('/satria-profile', [App\Http\Controllers\HomeController::class, 'profile'])->name('profile');


Auth::routes();

Route::middleware('auth')->group(function () {

// Route::middleware('token.login')->group(function() {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'home'])->name('index');
    Route::get('home', [App\Http\Controllers\HomeController::class, 'home'])->name('home');
    Route::get('logout', [App\Http\Controllers\Legatra\User\HomeController::class, 'logout'])->name('logout');
    Route::get('download-template/{id}', [App\Http\Controllers\Legatra\User\HomeController::class, 'download'])->name('download.template');

    // Main
    // Contract //
    Route::resource('contract', App\Http\Controllers\Legatra\DocumentController::class);
    Route::get('contract/download/{id}', [App\Http\Controllers\Legatra\DocumentController::class, 'download'])->name('contract.download');
    Route::get('contract/get-data/{id}', [App\Http\Controllers\Legatra\DocumentController::class, 'viewerDocument'])->name('contract.get-data');
    Route::resource('extended-contract', App\Http\Controllers\Legatra\ExtendedDocumentController::class);
    Route::get('extended-contract-show/{id}', [App\Http\Controllers\Legatra\ExtendedDocumentController::class, 'showRingkasan'])->name('extended-contract-show');
    Route::get('extended-contract/get-data/{id}', [App\Http\Controllers\Legatra\ExtendedDocumentController::class, 'viewerExtendDocument'])->name('extended-contract.get-data');
    Route::resource('contract-alert', App\Http\Controllers\Legatra\AlertController::class);
    Route::get('contract-alert/email/{id}', [App\Http\Controllers\Legatra\AlertController::class, 'sendEmail'])->name('contract-alert.email');
    Route::get('contract-alert/get-data/{id}', [App\Http\Controllers\Legatra\AlertController::class, 'historyEmail'])->name('contract-alert.get-data');
    Route::get('contract/detail/{id}', [App\Http\Controllers\Legatra\DocumentController::class, 'detail'])->name('contract.detail');
    Route::get('contract/edit-document/{id}', [App\Http\Controllers\Legatra\DocumentController::class, 'editDocument'])->name('contract.edit-document');
    Route::PUT('contract/update-document/{id}', [App\Http\Controllers\Legatra\DocumentController::class, 'updateDocument'])->name('contract.update-document');
    Route::get('contract/export-document/gas', [App\Http\Controllers\Legatra\DocumentController::class, 'export'])->name('contract.export-document-gas');
    Route::get('contract/restore/{id}', [App\Http\Controllers\Legatra\DocumentController::class, 'restore'])->name('contract.restore');
    Route::get('contract/delete-edit-file/{id}', [App\Http\Controllers\Legatra\DocumentController::class, 'deleteFinal'])->name('contract-delete-edit-file');
    Route::POST('contract/update-edit-file', [App\Http\Controllers\Legatra\DocumentController::class, 'addUpload'])->name('contract-update-edit-file');


    // Tracking Contract
    Route::resource('tracking', App\Http\Controllers\Legatra\TrackingController::class);
    Route::get('tracking/download/{id}', [App\Http\Controllers\Legatra\TrackingController::class, 'download'])->name('tracking.download');
    Route::get('tracking-drafting/{id}', [App\Http\Controllers\Legatra\TrackingController::class, 'legalDrafting'])->name('tracking-drafting');
    Route::post('tracking-drafting/store', [App\Http\Controllers\Legatra\TrackingController::class, 'storeLegalDrafting'])->name('tracking-drafting.store');
    Route::get('tracking-drafting/edit/{id}', [App\Http\Controllers\Legatra\TrackingController::class, 'editLegalDrafting'])->name('tracking-drafting.edit');
    Route::post('tracking-drafting/update', [App\Http\Controllers\Legatra\TrackingController::class, 'updateLegalDrafting'])->name('tracking-drafting.update');
    Route::get('tracking-drafting/download/{id}', [App\Http\Controllers\Legatra\TrackingController::class, 'downloadDocument'])->name('tracking-drafting.download');
    Route::get('tracking-drafting/download-history/{id}', [App\Http\Controllers\Legatra\TrackingController::class, 'downloadDocumentHistory'])->name('tracking-drafting.download-history');
    Route::post('tracking-drafting/send-draft/{id}', [App\Http\Controllers\Legatra\TrackingController::class, 'sendDraft'])->name('tracking-drafting.send-draft');
    Route::post('tracking-drafting/approve-draft/{id}', [App\Http\Controllers\Legatra\TrackingController::class, 'approveDraft'])->name('tracking-drafting.approve-draft');
    Route::post('tracking-drafting/feedback-draft', [App\Http\Controllers\Legatra\TrackingController::class, 'feedbackDraft'])->name('tracking-drafting.feedback-draft');
    Route::get('tracking-drafting/feedback-download/{id}', [App\Http\Controllers\Legatra\TrackingController::class, 'feedbackDownload'])->name('tracking-drafting.feedback-download');
    Route::get('tracking-drafting/revisi/{id}', [App\Http\Controllers\Legatra\TrackingController::class, 'revisiDraft'])->name('tracking-drafting.revisi');
    Route::post('tracking-drafting/revisi-update', [App\Http\Controllers\Legatra\TrackingController::class, 'revisiUpdate'])->name('tracking-drafting.revisi-update');
    Route::post('tracking-drafting/send-rekanan/{id}', [App\Http\Controllers\Legatra\TrackingController::class, 'sendRekanan'])->name('tracking-drafting.send-rekanan');
    Route::post('tracking-drafting/negotiation/{id}', [App\Http\Controllers\Legatra\TrackingController::class, 'negotiation'])->name('tracking-drafting.negotiation');
    Route::get('tracking-drafting/document-filing/{id}', [App\Http\Controllers\Legatra\TrackingController::class, 'documentFiling'])->name('tracking-drafting.document-filing');
    Route::post('tracking-drafting/document-filing-store', [App\Http\Controllers\Legatra\TrackingController::class, 'storeDocumentFiling'])->name('tracking-drafting.document-filing-store');
    Route::get('tracking/get-data/{id}', [App\Http\Controllers\Legatra\TrackingController::class, 'historyProcess'])->name('tracking.get-data');
    Route::post('tracking-drafting/negotiation-draft', [App\Http\Controllers\Legatra\TrackingController::class, 'negotiationDraft'])->name('tracking-drafting.negotiation-draft');
    Route::get('tracking-drafting/negotiation-download/{id}', [App\Http\Controllers\Legatra\TrackingController::class, 'negotiationDownload'])->name('tracking-drafting.negotiation-download');
    Route::get('tracking-drafting/attachment-download/{id}', [App\Http\Controllers\Legatra\TrackingController::class, 'downloadAttachment'])->name('tracking-drafting.attachment-download');
    Route::get('tracking-drafting/revisi-negotiation/{id}', [App\Http\Controllers\Legatra\TrackingController::class, 'revisiNegotiation'])->name('tracking-drafting.revisi-negotiation');
    Route::post('tracking-drafting/revisi-negotiation-update', [App\Http\Controllers\Legatra\TrackingController::class, 'revisiNegotiationUpdate'])->name('tracking-drafting.revisi-negotiation-update');
    Route::get('tracking-drafting/final-download/{id}', [App\Http\Controllers\Legatra\TrackingController::class, 'finalDownload'])->name('tracking-drafting.final-download');
    Route::post('tracking-drafting/cancel', [App\Http\Controllers\Legatra\TrackingController::class, 'cancel'])->name('tracking-drafting.cancel');
    Route::post('tracking-drafting/negotiation-approve/{id}', [App\Http\Controllers\Legatra\TrackingController::class, 'negotiationApprove'])->name('tracking-drafting.negotiation-approve');
    
    Route::get('contract-request-export-document', [App\Http\Controllers\Legatra\TrackingController::class, 'export'])->name('contract-request-export-document');


    // email reminder
    Route::get('tracking-drafting/email-reminder/{id}', [App\Http\Controllers\Legatra\TrackingController::class, 'sendReminderEmail'])->name('tracking-drafting.email-reminder');

    // License //
    Route::resource('license', App\Http\Controllers\Legatra\LicenseController::class);
    Route::resource('extended-license', App\Http\Controllers\Legatra\ExtendedLicenseController::class);
    Route::resource('license-alert', App\Http\Controllers\Legatra\AlertLicenseController::class);
    Route::post('license-alert/email', [App\Http\Controllers\Legatra\AlertLicenseController::class, 'sendEmail'])->name('license-alert.email');
    Route::get('license/detail/{id}', [App\Http\Controllers\Legatra\LicenseController::class, 'detail'])->name('license.detail');
    Route::get('license/edit-document/{id}', [App\Http\Controllers\Legatra\LicenseController::class, 'editDocument'])->name('license.edit-document');
    Route::PUT('license/update-document/{id}', [App\Http\Controllers\Legatra\LicenseController::class, 'updateDocument'])->name('license.update-document');
    Route::get('license-export-document', [App\Http\Controllers\Legatra\LicenseController::class, 'export'])->name('license-export-document');

    Route::resource('tracking-license', App\Http\Controllers\Legatra\TrackingLicenseController::class);
    Route::get('tracking-license/download/{id}', [App\Http\Controllers\Legatra\TrackingLicenseController::class, 'download'])->name('tracking-license.download');
    Route::get('tracking-license-drafting/{id}', [App\Http\Controllers\Legatra\TrackingLicenseController::class, 'legalDrafting'])->name('tracking-license-drafting');
    Route::post('tracking-license-drafting/store', [App\Http\Controllers\Legatra\TrackingLicenseController::class, 'storeLegalDrafting'])->name('tracking-license-drafting.store');
    Route::get('tracking-license-drafting/edit/{id}', [App\Http\Controllers\Legatra\TrackingLicenseController::class, 'editLegalDrafting'])->name('tracking-license-drafting.edit');
    Route::post('tracking-license-drafting/update', [App\Http\Controllers\Legatra\TrackingLicenseController::class, 'updateLegalDrafting'])->name('tracking-license-drafting.update');
    Route::get('tracking-license-drafting/download/{id}', [App\Http\Controllers\Legatra\TrackingLicenseController::class, 'downloadDocument'])->name('tracking-license-drafting.download');
    Route::get('tracking-license-drafting/download-history/{id}', [App\Http\Controllers\Legatra\TrackingLicenseController::class, 'downloadDocumentHistory'])->name('tracking-license-drafting.download-history');
    Route::post('tracking-license-drafting/send-draft/{id}', [App\Http\Controllers\Legatra\TrackingLicenseController::class, 'sendDraft'])->name('tracking-license-drafting.send-draft');
    Route::post('tracking-license-drafting/feedback-draft', [App\Http\Controllers\Legatra\TrackingLicenseController::class, 'feedbackDraft'])->name('tracking-license-drafting.feedback-draft');
    Route::get('tracking-license-drafting/feedback-download/{id}', [App\Http\Controllers\Legatra\TrackingLicenseController::class, 'feedbackDownload'])->name('tracking-license-drafting.feedback-download');
    Route::post('tracking-license-drafting/registration/{id}', [App\Http\Controllers\Legatra\TrackingLicenseController::class, 'registration'])->name('tracking-license-drafting.registration');
    Route::post('tracking-license-drafting/complete/{id}', [App\Http\Controllers\Legatra\TrackingLicenseController::class, 'complete'])->name('tracking-license-drafting.complete');
    Route::post('tracking-license-drafting/filing', [App\Http\Controllers\Legatra\TrackingLicenseController::class, 'filing'])->name('tracking-license-drafting.filing');
    Route::get('tracking-license-drafting/document-filing/{id}', [App\Http\Controllers\Legatra\TrackingLicenseController::class, 'documentFiling'])->name('tracking-license-drafting.document-filing');

    Route::get('lisence-request-export-document', [App\Http\Controllers\Legatra\TrackingLicenseController::class, 'export'])->name('lisence-request-export-document');

    // HAKI //
    Route::resource('haki', App\Http\Controllers\Legatra\HakiController::class);
    Route::get('haki/download/{id}', [App\Http\Controllers\Legatra\HakiController::class, 'download'])->name('haki.download');
    Route::get('haki/get-data/{id}', [App\Http\Controllers\Legatra\HakiController::class, 'viewerDocument'])->name('haki.get-data');
    Route::get('haki/detail/{id}', [App\Http\Controllers\Legatra\HakiController::class, 'detail'])->name('haki.detail');
    Route::resource('extended-haki', App\Http\Controllers\Legatra\HakiExtendedController::class);
    Route::resource('alert-haki', App\Http\Controllers\Legatra\HakiAlertController::class);
    Route::get('alert-haki/email/{id}', [App\Http\Controllers\Legatra\HakiAlertController::class, 'sendEmail'])->name('alert-haki.email');
    Route::get('haki/edit-document/{id}', [App\Http\Controllers\Legatra\HakiController::class, 'editDocument'])->name('haki.edit-document');
    Route::PUT('haki/update-document/{id}', [App\Http\Controllers\Legatra\HakiController::class, 'updateDocument'])->name('haki.update-document');
    Route::get('haki-export-document', [App\Http\Controllers\Legatra\HakiController::class, 'export'])->name('haki-export-document');

    Route::resource('tracking-haki', App\Http\Controllers\Legatra\HakiTrackingController::class);
    Route::get('tracking-haki/download/{id}', [App\Http\Controllers\Legatra\HakiTrackingController::class, 'download'])->name('tracking-haki.download');
    Route::get('tracking-haki-drafting/{id}', [App\Http\Controllers\Legatra\HakiTrackingController::class, 'legalDrafting'])->name('tracking-haki-drafting');
    Route::post('tracking-haki-drafting/store', [App\Http\Controllers\Legatra\HakiTrackingController::class, 'storeLegalDrafting'])->name('tracking-haki-drafting.store');
    Route::get('tracking-haki-drafting/edit/{id}', [App\Http\Controllers\Legatra\HakiTrackingController::class, 'editLegalDrafting'])->name('tracking-haki-drafting.edit');
    Route::post('tracking-haki-drafting/update', [App\Http\Controllers\Legatra\HakiTrackingController::class, 'updateLegalDrafting'])->name('tracking-haki-drafting.update');
    Route::get('tracking-haki-drafting/download/{id}', [App\Http\Controllers\Legatra\HakiTrackingController::class, 'downloadDocument'])->name('tracking-haki-drafting.download');
    Route::get('tracking-haki-drafting/download-history/{id}', [App\Http\Controllers\Legatra\HakiTrackingController::class, 'downloadDocumentHistory'])->name('tracking-haki-drafting.download-history');
    Route::post('tracking-haki-drafting/send-draft/{id}', [App\Http\Controllers\Legatra\HakiTrackingController::class, 'sendDraft'])->name('tracking-haki-drafting.send-draft');
    Route::post('tracking-haki-drafting/feedback-draft', [App\Http\Controllers\Legatra\HakiTrackingController::class, 'feedbackDraft'])->name('tracking-haki-drafting.feedback-draft');
    Route::get('tracking-haki-drafting/feedback-download/{id}', [App\Http\Controllers\Legatra\HakiTrackingController::class, 'feedbackDownload'])->name('tracking-haki-drafting.feedback-download');
    Route::post('tracking-haki-drafting/registration/{id}', [App\Http\Controllers\Legatra\HakiTrackingController::class, 'registration'])->name('tracking-haki-drafting.registration');
    Route::post('tracking-haki-drafting/complete/{id}', [App\Http\Controllers\Legatra\HakiTrackingController::class, 'complete'])->name('tracking-haki-drafting.complete');
    Route::post('tracking-haki-drafting/filing', [App\Http\Controllers\Legatra\HakiTrackingController::class, 'filing'])->name('tracking-haki-drafting.filing');
    Route::get('tracking-haki-drafting/document-filing/{id}', [App\Http\Controllers\Legatra\HakiTrackingController::class, 'documentFiling'])->name('tracking-haki-drafting.document-filing');
    Route::get('haki-request-export-document', [App\Http\Controllers\Legatra\HakiTrackingController::class, 'export'])->name('haki-request-export-document');

    // Tracking Existing
    Route::resource('tracking-existing', App\Http\Controllers\Legatra\TrackingExistingController::class);
    Route::get('tracking-existing/get-data/{id}', [App\Http\Controllers\Legatra\TrackingExistingController::class, 'historyProcess'])->name('tracking-existing.get-data');
    Route::post('tracking-existing/cancel', [App\Http\Controllers\Legatra\TrackingExistingController::class, 'cancel'])->name('tracking-existing.cancel');
    Route::post('tracking-existing/step-process', [App\Http\Controllers\Legatra\TrackingExistingController::class, 'processStep'])->name('tracking-existing.step-process');
    Route::post('tracking-existing/step-process-requester', [App\Http\Controllers\Legatra\TrackingExistingController::class, 'processStepRequester'])->name('tracking-existing.step-process-requester');

    Route::get('existing-request-export-document', [App\Http\Controllers\Legatra\TrackingExistingController::class, 'export'])->name('existing-request-export-document');

    // Master
    Route::resource('master-alert', App\Http\Controllers\Legatra\Master\AlertController::class);
    Route::PUT('master-update-alert', [App\Http\Controllers\Legatra\Master\AlertController::class, 'updateAlert'])->name('master-update-alert');
    Route::resource('master-company', App\Http\Controllers\Legatra\Master\CompanyController::class);
    Route::PUT('master-company-update', [App\Http\Controllers\Legatra\Master\CompanyController::class, 'updateCompany'])->name('master-company-update');
    Route::resource('master-template', App\Http\Controllers\Legatra\Master\TemplateController::class);
    Route::PUT('master-template-update', [App\Http\Controllers\Legatra\Master\TemplateController::class, 'updateTemplate'])->name('master-template-update');
    Route::get('master-template-download/{id}', [App\Http\Controllers\Legatra\Master\TemplateController::class, 'download'])->name('master-template.download');
    Route::resource('master-pic', App\Http\Controllers\Legatra\Master\PicController::class);
    Route::get('master-pic-email', [App\Http\Controllers\Legatra\Master\PicController::class, 'updateEmail'])->name('master-pic-email');
    Route::get('master-pic-email-false', [App\Http\Controllers\Legatra\Master\PicController::class, 'updateEmailFalse'])->name('master-pic-email-false');
    Route::resource('master-title', App\Http\Controllers\Legatra\Master\TitleController::class);
    Route::PUT('master-title-update', [App\Http\Controllers\Legatra\Master\TitleController::class, 'updateTitle'])->name('master-title-update');
    Route::resource('master-haki-type', App\Http\Controllers\Legatra\Master\HakiTypeController::class);
    Route::PUT('master-haki-type-update', [App\Http\Controllers\Legatra\Master\HakiTypeController::class, 'updateHaki'])->name('master-haki-type-update');
    Route::resource('master-duty', App\Http\Controllers\Legatra\Master\DutyController::class);
    Route::PUT('master-duty-update', [App\Http\Controllers\Legatra\Master\DutyController::class, 'updateduty'])->name('master-duty-update');
    Route::resource('contract-number', App\Http\Controllers\Legatra\Master\ContractNumberController::class);
    Route::resource('pic-email', App\Http\Controllers\Legatra\Master\PicEmailController::class);


    //Report Admin
    Route::resource('log-error-legatra', App\Http\Controllers\Legatra\Master\LogErrorController::class);



    ////////////// USER /////////////////
    Route::resource('request-document', App\Http\Controllers\Legatra\User\RequestDocumentController::class);
    Route::get('request-document/download/{id}', [App\Http\Controllers\Legatra\User\RequestDocumentController::class, 'download'])->name('request-document.download');
    Route::get('request-document/show-license/{id}', [App\Http\Controllers\Legatra\User\RequestDocumentController::class, 'showDocument'])->name('request-document.show-license');
    Route::get('request-document/show-haki/{id}', [App\Http\Controllers\Legatra\User\RequestDocumentController::class, 'showHaki'])->name('request-document.show-haki');
    Route::post('request-document/feedback', [App\Http\Controllers\Legatra\User\RequestDocumentController::class, 'feedbackDraft'])->name('request-document.feedback');
    Route::get('request-document/feedback-download/{id}', [App\Http\Controllers\Legatra\User\RequestDocumentController::class, 'feedbackDownload'])->name('request-document.feedback-download');
    Route::get('request-document-drafting/download/{id}', [App\Http\Controllers\Legatra\User\RequestDocumentController::class, 'downloadDocument'])->name('request-document-drafting.download');
    Route::get('request-document-drafting/download-attachment/{id}', [App\Http\Controllers\Legatra\User\RequestDocumentController::class, 'downloadAttachment'])->name('request-document-drafting.download-attachment');
    Route::get('request-document-drafting/download-history/{id}', [App\Http\Controllers\Legatra\User\RequestDocumentController::class, 'downloadDocumentHistory'])->name('request-document-drafting.download-history');
    Route::post('request-document-drafting/approve-draft/{id}', [App\Http\Controllers\Legatra\User\RequestDocumentController::class, 'approveDraft'])->name('request-document-drafting.approve-draft');
    Route::post('request-document/feedback-contract', [App\Http\Controllers\Legatra\User\RequestDocumentController::class, 'feedbackDraftContract'])->name('request-document.feedback-contract');
    Route::get('request-document/get-data/{id}', [App\Http\Controllers\Legatra\User\RequestDocumentController::class, 'historyProcess'])->name('request-document.get-data');
    Route::get('request-document/download-upload/{id}', [App\Http\Controllers\Legatra\User\RequestDocumentController::class, 'downloadUpload'])->name('request-document.download-upload');
    Route::get('request-document/destroy-upload/{id}', [App\Http\Controllers\Legatra\User\RequestDocumentController::class, 'destroyUpload'])->name('request-document.destroy-upload');
    Route::post('request-document/add-upload', [App\Http\Controllers\Legatra\User\RequestDocumentController::class, 'addUpload'])->name('request-document.add-upload');
    Route::post('request-document/add-negotiation/{id}', [App\Http\Controllers\Legatra\User\RequestDocumentController::class, 'negotiation'])->name('request-document.add-negotiation');
    Route::get('request-document/negotiation-download/{id}', [App\Http\Controllers\Legatra\User\RequestDocumentController::class, 'negotiationDownload'])->name('request-document.negotiation-download');
    Route::get('request-document/negotiation-approve/{id}', [App\Http\Controllers\Legatra\User\RequestDocumentController::class, 'negotiationApprove'])->name('request-document.negotiation-approve');
    Route::get('request-document/feedback-approve/{id}', [App\Http\Controllers\Legatra\User\RequestDocumentController::class, 'feedbackApprove'])->name('request-document.feedback-approve');
    Route::post('request-document/upload-final', [App\Http\Controllers\Legatra\User\RequestDocumentController::class, 'uploadFinal'])->name('request-document.upload-final');
    Route::get('request-document/download-final/{id}', [App\Http\Controllers\Legatra\User\RequestDocumentController::class, 'downloadFinal'])->name('request-document.download-final');
    Route::post('request-document/cancel', [App\Http\Controllers\Legatra\User\RequestDocumentController::class, 'cancel'])->name('request-document.cancel');
    Route::get('request-document-download-all-attachment/{id}', [App\Http\Controllers\Legatra\User\RequestDocumentController::class, 'allAttachment'])->name('request-document.download-attachment.all');

    Route::resource('request-extend', App\Http\Controllers\Legatra\User\RequestExtendController::class);
    Route::get('request-extend/download/{id}', [App\Http\Controllers\Legatra\User\RequestExtendController::class, 'download'])->name('request-extend.download');
    Route::get('request-extend/show-license/{id}', [App\Http\Controllers\Legatra\User\RequestExtendController::class, 'showDocument'])->name('request-extend.show-license');
    Route::get('request-extend/show-haki/{id}', [App\Http\Controllers\Legatra\User\RequestExtendController::class, 'showHaki'])->name('request-extend.show-haki');
    Route::post('request-extend/feedback', [App\Http\Controllers\Legatra\User\RequestExtendController::class, 'feedbackDraft'])->name('request-extend.feedback');
    Route::get('request-extend/feedback-download/{id}', [App\Http\Controllers\Legatra\User\RequestExtendController::class, 'feedbackDownload'])->name('request-extend.feedback-download');
    Route::get('request-extend-drafting/download/{id}', [App\Http\Controllers\Legatra\User\RequestExtendController::class, 'downloadDocument'])->name('request-extend-drafting.download');
    Route::get('request-extend-drafting/download-history/{id}', [App\Http\Controllers\Legatra\User\RequestExtendController::class, 'downloadDocumentHistory'])->name('request-extend-drafting.download-history');
    Route::post('request-extend-drafting/approve-draft/{id}', [App\Http\Controllers\Legatra\User\RequestExtendController::class, 'approveDraft'])->name('request-extend-drafting.approve-draft');
    Route::post('request-extend/feedback-contract', [App\Http\Controllers\Legatra\User\RequestExtendController::class, 'feedbackDraftContract'])->name('request-extend.feedback-contract');
    Route::get('request-extend/get-data/{id}', [App\Http\Controllers\Legatra\User\RequestExtendController::class, 'historyProcess'])->name('request-extend.get-data');
    Route::get('request-extend/download-upload/{id}', [App\Http\Controllers\Legatra\User\RequestExtendController::class, 'downloadUpload'])->name('request-extend.download-upload');
    Route::get('request-extend/destroy-upload/{id}', [App\Http\Controllers\Legatra\User\RequestExtendController::class, 'destroyUpload'])->name('request-extend.destroy-upload');
    Route::post('request-extend/add-upload', [App\Http\Controllers\Legatra\User\RequestExtendController::class, 'addUpload'])->name('request-extend.add-upload');
    Route::get('request-extend-get-item', [App\Http\Controllers\Legatra\User\RequestExtendController::class, 'getItem'])->name('request-extend.get-item');
    Route::get('request-extend/negotiation-approve/{id}', [App\Http\Controllers\Legatra\User\RequestExtendController::class, 'negotiationApprove'])->name('request-extend.negotiation-approve');
    Route::post('request-extend/cancel', [App\Http\Controllers\Legatra\User\RequestExtendController::class, 'cancel'])->name('request-extend.cancel');
    Route::get('request-extend/feedback-approve/{id}', [App\Http\Controllers\Legatra\User\RequestExtendController::class, 'feedbackApprove'])->name('request-extend.feedback-approve');

    Route::resource('request-existing', App\Http\Controllers\Legatra\User\RequestExistingController::class);
    Route::get('request-existing/download/{id}', [App\Http\Controllers\Legatra\User\RequestExistingController::class, 'download'])->name('request-existing.download');
    Route::post('request-existing/feedback', [App\Http\Controllers\Legatra\User\RequestExistingController::class, 'feedback'])->name('request-existing.feedback');
    Route::get('request-existing/get-data/{id}', [App\Http\Controllers\Legatra\User\RequestExistingController::class, 'historyProcess'])->name('request-existing.get-data');
    Route::put('request-existing/update-data/{id}', [App\Http\Controllers\Legatra\User\RequestExistingController::class, 'updateRequest'])->name('request-existing.update-data');

    Route::resource('contract-user', App\Http\Controllers\Legatra\User\ContractController::class);
    Route::get('contract-user/detail/{id}', [App\Http\Controllers\Legatra\User\ContractController::class, 'detail'])->name('contract-user.detail');
    Route::resource('extend-contract-user', App\Http\Controllers\Legatra\User\ExtendContractController::class);
    Route::get('extend-contract-user/show-ringkasan/{id}', [App\Http\Controllers\Legatra\User\ExtendContractController::class, 'showRingkasan'])->name('extend-contract-user.show-ringkasan');
    Route::get('contract-user/download/{id}', [App\Http\Controllers\Legatra\User\ContractController::class, 'download'])->name('contract-user.download');

    Route::resource('license-user', App\Http\Controllers\Legatra\User\LicenseController::class);
    Route::get('license-user/detail/{id}', [App\Http\Controllers\Legatra\User\LicenseController::class, 'detail'])->name('license-user.detail');
    Route::resource('extend-license-user', App\Http\Controllers\Legatra\User\ExtendLicenseController::class);
    Route::get('extend-license-user/show-ringkasan/{id}', [App\Http\Controllers\Legatra\User\ExtendLicenseController::class, 'showRingkasan'])->name('extend-license-user.show-ringkasan');

    Route::resource('haki-user', App\Http\Controllers\Legatra\User\HakiController::class);
    Route::get('haki-user/detail/{id}', [App\Http\Controllers\Legatra\User\HakiController::class, 'detail'])->name('haki-user.detail');
    Route::resource('extend-haki-user', App\Http\Controllers\Legatra\User\ExtendHakiController::class);
    Route::get('extend-haki-user/show-ringkasan/{id}', [App\Http\Controllers\Legatra\User\ExtendHakiController::class, 'showRingkasan'])->name('extend-haki-user.show-ringkasan');

    // Bagian Request Document QR User
    Route::get('request-qr-user', [UserRequestDocumentQRController::class, 'index'])->name('request-qr-user');
    Route::get('request-qr-user/create', [UserRequestDocumentQRController::class, 'create'])->name('request-qr-user-create');
    Route::post('request-qr-user', [UserRequestDocumentQRController::class, 'store'])->name('request-qr-user-store');
    Route::delete('request-qr-user/{id}', [UserRequestDocumentQRController::class, 'destroy'])->name('request-qr-user-destroy');

    Route::resource('alert-user', App\Http\Controllers\Legatra\User\AlertController::class);

    Route::resource('home-user', App\Http\Controllers\Legatra\User\HomeController::class);

    ///////////////// Notification ///////////////
    Route::resource('notification', App\Http\Controllers\Legatra\User\NotificationController::class);
    Route::get('mark-as-read-all', [App\Http\Controllers\Legatra\User\NotificationController::class, 'readAll'])->name('notification.read-all');


    // Sychronize
    // Route::get('generate-pic', [App\Http\Controllers\Legatra\User\RequestDocumentController::class, 'getPic'])->name('request-document.generate-pic');
    // Route::get('generate-duration-days', [App\Http\Controllers\Legatra\TrackingController::class, 'syncrhonizeDocument'])->name('tracking.generate-duration-days');
    // Route::get('generate-base-duration-days', [App\Http\Controllers\Legatra\TrackingController::class, 'syncrhonizeBaseDocument'])->name('tracking.generate-base-duration-days');
    Route::get('synchronize-base-document-file', [App\Http\Controllers\Legatra\TrackingController::class, 'syncrhonizeBaseDocumentFile'])->name('tracking.synchronize-base-document-file');
    Route::get('synchronize-document-file', [App\Http\Controllers\Legatra\TrackingController::class, 'syncrhonizeDocumentFile'])->name('tracking.synchronize-document-file');


    // QR Generate
    Route::resource('qr-document', QRDocumentController::class);
    Route::get('qr-document/search', [QRDocumentController::class, 'searchDocument'])->name('qr-document.search');
    Route::get('qr-document/coba', [QRDocumentController::class, 'coba']);
    Route::get('/qr-document/document/{idGenerateNumber}', [QRDocumentController::class, 'getDocumentGenerateNumber']);

    Route::get('/qr-document/stamp/{idDocumentQR}', [QRDocumentController::class, 'stamp'])->name('qr-document.stamp');

    // Untuk Scan Link QR Code
    Route::get('/document/look', [QRDocumentController::class, 'checkDocument']);

    // Request QR
    Route::resource('request-qr', RequestDocumentQRController::class);

    // Generate Number
    Route::resource('generate-number', GenerateNumberController::class);
    Route::get('/generate-number/departments/{companyCode}', [GenerateNumberController::class, 'getDepartments']);
    Route::delete('generate-number/force/{generate_number}', [GenerateNumberController::class, 'deleteForce'])->name('generate-number-force');
    Route::put('generate-number/restore/{generate_number}', [GenerateNumberController::class, 'restore'])->name('generate-number.restore');


    //TSP Route
    Route::prefix('tsp/request-document')
    ->name('tsp.request-document.')
    ->group(function () {

        Route::get('/', [TSPRequestDocumentController::class, 'index'])
            ->name('index');

        Route::get('/data', [TSPRequestDocumentController::class, 'getData'])
            ->name('data');

    });
});
