<form role="search">
    <div class="form-group">
        <input type="text" class="form-control" placeholder="Search">
    </div>
</form>

@section('search')
    <script>
        function search(search, url, type) {
            $.ajax({
                type: type,
                url: url,
                data: { 'search' : search },
                success: function(response){
                    $('#merchandises').html(response);
                    console.log(response);
                }
            });
        }
    </script>
@endsection