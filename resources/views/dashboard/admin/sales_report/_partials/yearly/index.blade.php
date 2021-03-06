@if($sales->count())
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>Month</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($sales as $sale)
                <tr>
                    <td>{{ date('F', mktime(0, 0, 0, $sale['_month'])) }}</td>
                    <td><strong>P{{ number_format($sale['_total'], 2, '.', ',') }}</strong></td>
                </tr>
            @endforeach
            <tr class="grand-total">
                <td class="text-right">
                    <strong>Total:</strong>
                </td>
                <td>
                    <strong>P{{ number_format(collect($sales)->pluck('_total')->sum(), 2, '.', ',') }}</strong>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
@else
    @include('_partials.empty')
@endif
