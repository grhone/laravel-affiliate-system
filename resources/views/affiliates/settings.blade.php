{{-- resources/views/affiliates/settings.blade.php --}}

<x-app-layout>

<h1>Affiliate Settings</h1>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('affiliate.updateSettings', $affiliate->id) }}" method="POST">
    @csrf
    @method('PUT')

    @php
    $notificationNewSales = $affiliate->settings->where('key', 'notification_new_sales')->first();
    $notificationNewSalesChecked = $notificationNewSales && $notificationNewSales->value == 1;
    @endphp

    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="notification_new_sales" id="notificationNewSales"
               value="1" {{ $notificationNewSalesChecked ? 'checked' : '' }}>
        <label class="form-check-label" for="notificationNewSales">
            Receive notifications for new sales/leads
        </label>
    </div>

    @php
    $notificationDailyReport = $affiliate->settings->where('key', 'notification_daily_report')->first();
    $notificationDailyReportChecked = $notificationDailyReport && $notificationDailyReport->value == 1;
    @endphp

    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="notification_daily_report" id="notificationDailyReport"
               value="1" {{ $notificationDailyReportChecked ? 'checked' : '' }}>
        <label class="form-check-label" for="notificationDailyReport">
            Receive daily reports
        </label>
    </div>

    @php
    $notificationWeeklyReport = $affiliate->settings->where('key', 'notification_weekly_report')->first();
    $notificationWeeklyReportChecked = $notificationWeeklyReport && $notificationWeeklyReport->value == 1;
    @endphp

    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="notification_weekly_report" id="notificationWeeklyReport"
               value="1" {{ $notificationWeeklyReportChecked ? 'checked' : '' }}>
        <label class="form-check-label" for="notificationWeeklyReport">
            Receive weekly reports
        </label>
    </div>

    @php
    $notificationMonthlyReport = $affiliate->settings->where('key', 'notification_monthly_report')->first();
    $notificationMonthlyReportChecked = $notificationMonthlyReport && $notificationMonthlyReport->value == 1;
    @endphp

    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="notification_monthly_report" id="notificationMonthlyReport"
               value="1" {{ $notificationMonthlyReportChecked ? 'checked' : '' }}>
        <label class="form-check-label" for="notificationMonthlyReport">
            Receive monthly reports
        </label>
    </div>

    <button type="submit" class="btn btn-primary">Save Settings</button>
</form>

</x-app-layout>
