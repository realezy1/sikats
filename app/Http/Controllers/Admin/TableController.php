<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TableController extends Controller
{
    /**
     * Display a listing of the tables.
     */
    public function index()
    {
        $title = 'Hapus Meja!';
        $text = "Apakah Anda yakin ingin menghapus meja ini beserta QR Code-nya?";
        confirmDelete($title, $text);

        $tables = Table::orderBy('table_number')->get();
        return view('admin.tables.index', compact('tables'));
    }

    /**
     * Store a newly created table in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|integer|unique:tables,table_number',
        ]);

        Table::create([
            'table_number' => $request->table_number,
            'qr_token' => Str::random(64),
            'is_active' => true,
        ]);

        return redirect()->route('admin.tables.index')->with('success', 'Meja baru berhasil ditambahkan.');
    }

    /**
     * Remove the specified table from storage.
     */
    public function destroy(Table $table)
    {
        $table->delete();
        return redirect()->route('admin.tables.index')->with('success', 'Meja berhasil dihapus.');
    }

    /**
     * Reset the QR token for the specified table.
     */
    public function resetToken(Table $table)
    {
        $table->update([
            'qr_token' => Str::random(64),
        ]);

        return redirect()->route('admin.tables.index')->with('success', 'Token QR untuk Meja ' . $table->table_number . ' berhasil direset.');
    }

    /**
     * Print the QR code for the specified table.
     */
    public function printQr(Table $table)
    {
        // For development, we might use ngrok URL if env variable is set, otherwise default to APP_URL
        $baseUrl = config('app.url');
        // Generate the URL for self-ordering
        $qrUrl = $baseUrl . '/order?table=' . $table->id . '&token=' . $table->qr_token;

        return view('admin.tables.print', compact('table', 'qrUrl'));
    }
}
