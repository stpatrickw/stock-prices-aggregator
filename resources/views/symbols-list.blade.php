@foreach($symbols as $symbol)
    <tr>
        <th scope="row">{{ $loop->index+1 }}</th>
        <td>{{ $symbol->name }}</td>
        <td>{{ $symbol->symbol }}</td>
        <td>
            <b style="color: {{$symbol->priceChange >= 0 ? 'green' : 'red'}};">{{ $symbol->price }}
                <i class="icon icon-2xl cil-arrow-thick-{{$symbol->priceChange >= 0 ? 'top' : 'bottom'}}"></i>
            </b></td>
    </tr>
@endforeach
