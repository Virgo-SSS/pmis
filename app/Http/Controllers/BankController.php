<?php

namespace App\Http\Controllers;

use App\Actions\Banks\CreateBankAction;
use App\Actions\Banks\DeleteBankAction;
use App\Actions\Banks\UpdateBankAction;
use App\DataTransferObjects\CreateBankData;
use App\Http\Requests\Banks\BankStoreRequest;
use App\Http\Requests\Banks\BankUpdateRequest;
use App\Models\Bank;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BankController extends Controller
{
    /**
     * Display a listing of the banks.
     *
     * @return View
     */
    public function index(): View
    {
        $banks = Bank::query()->get();

        return view('banks.index', compact('banks'));
    }

    /**
     * Store a newly created bank in storage.
     *
     * @param BankStoreRequest $request
     * @param CreateBankAction $action
     * @return RedirectResponse
     */
    public function store(BankStoreRequest $request, CreateBankAction $action): RedirectResponse
    {
        $action->run(
            CreateBankData::fromArray($request->validated())
        );

        return redirect()->route('bank')->with('success-swal', 'Bank created successfully.');
    }

    /**
     * Update the bank in storage.
     *
     * @param BankUpdateRequest $request
     * @param Bank $bank
     * @param UpdateBankAction $action
     * @return RedirectResponse
     */
    public function update(BankUpdateRequest $request, Bank $bank, UpdateBankAction $action): RedirectResponse
    {
        $action->run(
            $bank,
            CreateBankData::fromArray($request->validated())
        );

        return redirect()->route('bank')->with('success-swal', 'Bank updated successfully.');
    }

    /**
     * Remove the bank from storage.
     *
     * @param Bank $bank
     * @param DeleteBankAction $action
     * @return RedirectResponse
     */
    public function delete(Bank $bank, DeleteBankAction $action): RedirectResponse
    {
        $action->run($bank);

        return redirect()->route('bank')->with('success-swal', 'Bank deleted successfully.');
    }
}
