<x-app-layout>
    <div class="mb-6">
        <a href="{{ route('admin.affiliates.index') }}" class="text-blue-600 hover:text-blue-800">
            ← Back to Affiliates
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Admin Affiliate Reports</h1>
        
        <form method="GET" action="{{ route('admin.affiliates.reports.generate') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block font-medium text-gray-700">Affiliate</label>
                    <select name="affiliate_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">All Affiliates</option>
                        @foreach($affiliates as $affiliate)
                        <option value="{{ $affiliate->id }}" {{ request('affiliate_id') == $affiliate->id ? 'selected' : '' }}>
                            {{ $affiliate->user->name }} ({{ $affiliate->referral_code }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block font-medium text-gray-700">Transaction Type</label>
                    <select name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">All Transactions</option>
                        <option value="sale">Sales</option>
                        <option value="refund">Refunds</option>
                        <option value="chargeback">Chargebacks</option>
                    </select>
                </div>
                <div>
                    <label class="block font-medium text-gray-700">Group By</label>
                    <select name="group_by" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="day">Daily</option>
                        <option value="week">Weekly</option>
                        <option value="month">Monthly</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                Generate Report
            </button>
        </form>

        @isset($reportData)
        <div class="mt-8">
            <h2 class="text-xl font-bold mb-4">Report Results</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @if(!request('affiliate_id'))
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Affiliate</th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transactions</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sales Value</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Earnings</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reportData as $row)
                        <tr>
                            @if(!request('affiliate_id'))
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $row->affiliate->user->name ?? 'N/A' }}
                            </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap">{{ $row->period }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $row->count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ config('affiliate.currency') }}{{ number_format($row->sales, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ config('affiliate.currency') }}{{ number_format($row->earnings, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endisset
    </div>
</x-app-layout> 