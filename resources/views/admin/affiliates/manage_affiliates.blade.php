{{-- Manage Affiliates View --}}

<x-admin-layout>

    <div class="mb-6">
        <a href="{{ route('admin.affiliate.dashboard') }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="h-4 inline">
                <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                <path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/>
            </svg>
            Back
        </a>
    </div>

    <h1>Manage Affiliates</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <table class="w-full">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Commission Rate (%)</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($affiliates as $affiliate)
                <tr>
                    <td>{{ $affiliate->id }}</td>
                    <td>{{ $affiliate->first_name }} {{ $affiliate->last_name }}</td>
                    <td>{{ $affiliate->user->email }}</td>
                    <td>{{ $affiliate->commission_rate ?? 'Default' }}</td>
                    <td>{{ $affiliate->approved ? 'Approved' : 'Pending' }}</td>
                    <td>
                        <a href="{{ route('admin.affiliate.show', $affiliate->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('admin.affiliate.edit', $affiliate->id) }}" class="btn btn-primary">Edit</a>
                        @if(!$affiliate->approved)
                            <form action="{{ route('admin.approve.affiliate', $affiliate->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-success">Approve</button>
                            </form>
                            <form action="{{ route('admin.deny.affiliate', $affiliate->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-danger">Deny</button>
                            </form>
                        @endif
                        <form action="{{ route('admin.affiliate.destroy', $affiliate->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-warning">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
</x-admin-layout>
