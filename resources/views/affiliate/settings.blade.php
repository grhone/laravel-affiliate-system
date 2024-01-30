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

    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="notification_new_sales" id="notificationNewSales"
               value="1" {{ $affiliate->settings->notification_new_sales ? 'checked' : '' }}>
        <label class="form-check-label" for="notificationNewSales">
            Receive notifications for new sales/leads
        </label>
    </div>

    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="notification_daily_report" id="notificationDailyReport"
               value="1" {{ $affiliate->settings->notification_daily_report ? 'checked' : '' }}>
        <label class="form-check-label" for="notificationDailyReport">
            Receive daily reports
        </label>
    </div>

    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="notification_weekly_report" id="notificationWeeklyReport"
               value="1" {{ $affiliate->settings->notification_weekly_report ? 'checked' : '' }}>
        <label class="form-check-label" for="notificationWeeklyReport">
            Receive weekly reports
        </label>
    </div>

    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="notification_monthly_report" id="notificationMonthlyReport"
               value="1" {{ $affiliate->settings->notification_monthly_report ? 'checked' : '' }}>
        <label class="form-check-label" for="notificationMonthlyReport">
            Receive monthly reports
        </label>
    </div>

    <button type="submit" class="btn btn-primary">Save Settings</button>
</form>

</x-app-layout>
