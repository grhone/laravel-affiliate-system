<x-mail::message>
    # Affiliate Registration

    A new affiliate account has been been created.

    Please log in to approve or deny the account.

    <a href="{{ route('admin.manage_affiliates') }}">View Affiliates</a>

    Best Regards,
    {{ config('app.name') }}
</x-mail::message>