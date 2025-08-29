@if(!is_null($get_transactions))
  
    @foreach($get_transactions as $value)
    <tr>
        <td>{{$value->user->first_name}} {{$value->user->last_name}}</td>    
        <td>{{$value->gateway ?? ''}}</td>
        <td>{{$value->remarks ?? ''}}</td>
        <td>&#8358;{{$value->amount ?? ''}}</td>
        <td>
            {{$value->subscription_name ?? 'Not Available'}}
        </td>
            <td>{{$value->reference ?? 'Not Available'}}</td>
        <td class="text-center"> 
            @if($value->status == 'success')
            <span class="bg-success-focus text-success-main px-24 py-4 rounded-pill fw-medium text-sm">Successful</span>
            @endif 
        </td>
        <td>
            {{\Carbon\Carbon::parse($value->created_at)->format('d/m/Y')}}
        </td>
    </tr>
   @endforeach


@else

    <p style="text-align:center">No Data avaliable</p

@endif


