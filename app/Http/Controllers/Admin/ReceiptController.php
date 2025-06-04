<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ReceiptExport;
use App\Http\Controllers\Controller;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Receipt::query()->with(['user', 'subscriptionUser']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('receipt_number', 'like', '%' . $search . '%')
                    ->orWhere('amount', 'like', '%' . $search . '%')
                    ->orWhere('payment_method', 'like', '%' . $search . '%')
                    ->orWhere('id', $search)
                    ->orWhereHas('user', function($query) use ($search) {
                        $query->where('email', 'like', '%' . $search . '%')
                            ->orWhere('username', 'like', '%' . $search . '%')
                            ->orWhere('first_name', 'like', '%' . $search . '%')
                            ->orWhere('last_name', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $receipts = $query->latest()->paginate(10);

        if (request()->hasHeader('HX-Request')) {
            return view('admin.receipts.all', compact('receipts'))->fragment('table-section');
        }

        return view('admin.receipts.all', compact('receipts'));
    }

    /**
     * Export receipts to Excel
     */
    public function export(Request $request)
    {
        $query = Receipt::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('receipt_number', 'like', '%' . $search . '%')
                    ->orWhere('amount', 'like', '%' . $search . '%')
                    ->orWhere('payment_method', 'like', '%' . $search . '%')
                    ->orWhere('id', $search)
                    ->orWhereHas('user', function($query) use ($search) {
                        $query->where('email', 'like', '%' . $search . '%')
                            ->orWhere('username', 'like', '%' . $search . '%')
                            ->orWhere('first_name', 'like', '%' . $search . '%')
                            ->orWhere('last_name', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $receipts = $query->latest()->get();

        return Excel::download(new ReceiptExport($receipts), 'transactions-' . now()->format('Y-m-d') . '.xlsx');
    }

    /**
     * Display the specified resource.
     */
    public function show(Receipt $receipt)
    {
        return view('admin.receipts.show', compact('receipt'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Receipt $receipt)
    {
        $receipt->delete();
    }
}
