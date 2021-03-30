@extends('admin.layouts.admin')

@section('title')
    Providers &nbsp; <a class="btn btn-primary" href="{{ route('admin.providers.create') }}"><i class="fa fa-plus-circle"></i>
        Add</a>
@endsection

@section('content')
    <div class="row">

        <table class="table table-striped table-bordered dt-responsive nowrap jambo_table" cellspacing="0"
               width="100%">
            <thead>
            <tr>
                <th>User</th>
                <th>Category</th>
                <th>Is Verified</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($providers as $provider)
                <tr>
                    <td>{{ $provider->user->name }}</td>
                    <td>{{ $provider->primary_category->title }}</td>
                    <td>{{ $provider->is_verified }}</td>
                    <td>
                        <a class="btn btn-xs btn-info" href="{{ route('admin.providers.edit', [$provider->id]) }}" data-toggle="tooltip" data-placement="top">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a class="btn btn-xs btn-danger" href="{{ route('admin.providers.destroy', [$provider->id]) }}" data-toggle="tooltip" data-placement="top">
                            <i class="fa fa-remove"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pull-right">
            {{ $providers->links() }}
        </div>
    </div>
@endsection