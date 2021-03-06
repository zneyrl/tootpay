<div class="table-responsive">
    <table class="table table-hover table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Category</th>
            @if(\App\Models\OperationDay::find(date('w', strtotime(\Carbon\Carbon::now())))->has_operation)
                <th class="text-center">Make Available Today?</th>
            @endif
            <th class="text-center">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($merchandises as $merchandise)
            <tr>
                <td>
                    <a href="{{ route('merchandises.show', [$merchandise->id, 'redirect' => Request::fullUrl()]) }}">
                        <strong>{{ $merchandise->name }}</strong>
                    </a>
                </td>
                <td>P{{ number_format($merchandise->price, 2, '.', ',') }}</td>
                <td class="text-muted">
                    {{ is_null($merchandise->category) ? 'Not set' : $merchandise->category->name }}
                </td>
                @if(\App\Models\OperationDay::find(date('w', strtotime(\Carbon\Carbon::now())))->has_operation)
                    <td class="text-center">
                        {!! Form::open(['route' => ['merchandises.available.update', $merchandise->id], 'class' => '']) !!}
                        {!! Form::hidden('_method', 'PUT') !!}
                        <input type="hidden" name="available" value="off">
                        <input type="checkbox" onchange="this.form.submit();"
                               {!! in_array(date('w', strtotime(\Carbon\Carbon::now())), $merchandise->operationDays()->getRelatedIds()->all()) ? 'checked' : '' !!} id="available" name="available"
                               data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success"
                               data-offstyle="default" data-size="mini">
                        {!! Form::close() !!}
                    </td>
                @endif
                <td class="text-center">
                    {!! Form::open(['route' => ['merchandises.destroy', $merchandise->id], 'class' => '']) !!}
                    {!! Form::hidden('_method', 'DELETE') !!}
                    <a href="{{ route('merchandises.edit', $merchandise->id) }}"
                       class="btn btn-default btn-xs">Edit</a>
                    <button type="submit" class="btn btn-danger btn-xs">Delete</button>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="text-center">{{ $merchandises->links() }}</div>