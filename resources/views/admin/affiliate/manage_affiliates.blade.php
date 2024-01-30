{{-- Manage Affiliates View --}}

<x-admin-layout>

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

    <table class="table">
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
                        <a href="{{ route('admin.affiliates.show', $affiliate->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('admin.affiliates.edit', $affiliate->id) }}" class="btn btn-primary">Edit</a>
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
                        <form action="{{ route('admin.affiliates.destroy', $affiliate->id) }}" method="POST" style="display:inline-block;">
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
