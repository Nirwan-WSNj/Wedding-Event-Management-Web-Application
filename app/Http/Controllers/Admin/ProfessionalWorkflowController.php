<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BanquetEventOrder;
use App\Models\EventContract;
use App\Models\EventInvoice;
use App\Models\EventLead;
use App\Models\EventProposal;
use App\Models\EventTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProfessionalWorkflowController extends Controller
{
    public function stats()
    {
        return response()->json([
            'success' => true,
            'stats' => [
                'leads_total' => $this->countTable('event_leads'),
                'leads_new' => $this->countWhere('event_leads', 'status', 'new'),
                'leads_converted' => $this->countWhere('event_leads', 'status', 'converted'),
                'proposals_sent' => $this->countWhere('event_proposals', 'status', 'sent'),
                'proposals_accepted' => $this->countWhere('event_proposals', 'status', 'accepted'),
                'contracts_signed' => $this->countWhere('event_contracts', 'status', 'signed'),
                'invoices_pending' => DB::table('event_invoices')->whereIn('status', ['sent', 'partially_paid', 'overdue'])->count(),
                'invoice_outstanding' => $this->invoiceOutstanding(),
                'calendar_holds_active' => $this->countWhere('calendar_holds', 'status', 'active'),
                'beos_pending' => DB::table('banquet_event_orders')->whereIn('status', ['draft', 'approved'])->count(),
                'tasks_open' => DB::table('event_tasks')->whereIn('status', ['todo', 'in_progress'])->count(),
                'tasks_overdue' => Schema::hasTable('event_tasks') ? DB::table('event_tasks')->whereIn('status', ['todo', 'in_progress'])->whereDate('due_date', '<', now()->toDateString())->count() : 0,
            ],
        ]);
    }

    public function leads(Request $request)
    {
        $query = EventLead::query()->with(['customer', 'assignedManager'])->latest();
        $this->applyStatusFilter($query, $request);
        return response()->json(['success' => true, 'data' => $query->paginate($request->integer('per_page', 15))]);
    }

    public function proposals(Request $request)
    {
        $query = EventProposal::query()->with(['lead', 'booking', 'customer'])->latest();
        $this->applyStatusFilter($query, $request);
        return response()->json(['success' => true, 'data' => $query->paginate($request->integer('per_page', 15))]);
    }

    public function contracts(Request $request)
    {
        $query = EventContract::query()->with(['proposal', 'booking', 'customer'])->latest();
        $this->applyStatusFilter($query, $request);
        return response()->json(['success' => true, 'data' => $query->paginate($request->integer('per_page', 15))]);
    }

    public function invoices(Request $request)
    {
        $query = EventInvoice::query()->with(['booking', 'proposal', 'customer', 'installments'])->latest();
        $this->applyStatusFilter($query, $request);
        return response()->json(['success' => true, 'data' => $query->paginate($request->integer('per_page', 15))]);
    }

    public function calendarHolds(Request $request)
    {
        $query = DB::table('calendar_holds')
            ->leftJoin('halls', 'calendar_holds.hall_id', '=', 'halls.id')
            ->select('calendar_holds.*', 'halls.name as hall_name')
            ->orderByDesc('calendar_holds.created_at');

        if ($request->filled('status')) {
            $query->where('calendar_holds.status', $request->string('status'));
        }

        return response()->json(['success' => true, 'data' => $query->paginate($request->integer('per_page', 15))]);
    }

    public function beos(Request $request)
    {
        $query = BanquetEventOrder::query()->with(['booking', 'timelineItems'])->latest();
        $this->applyStatusFilter($query, $request);
        return response()->json(['success' => true, 'data' => $query->paginate($request->integer('per_page', 15))]);
    }

    public function tasks(Request $request)
    {
        $query = EventTask::query()->with(['booking', 'assignee'])->latest();
        $this->applyStatusFilter($query, $request);
        return response()->json(['success' => true, 'data' => $query->paginate($request->integer('per_page', 15))]);
    }

    private function applyStatusFilter($query, Request $request): void
    {
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
    }

    private function countTable(string $table): int
    {
        return Schema::hasTable($table) ? DB::table($table)->count() : 0;
    }

    private function countWhere(string $table, string $column, string $value): int
    {
        return Schema::hasTable($table) && Schema::hasColumn($table, $column)
            ? DB::table($table)->where($column, $value)->count()
            : 0;
    }

    private function invoiceOutstanding(): float
    {
        if (!Schema::hasTable('event_invoices')) {
            return 0;
        }

        return (float) DB::table('event_invoices')
            ->selectRaw('COALESCE(SUM(total_amount - paid_amount), 0) as outstanding')
            ->value('outstanding');
    }
}
