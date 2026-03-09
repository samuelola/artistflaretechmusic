 @php
    $sn = 1;
    @endphp
    @foreach($payments as $payment)
        <tr>
            <td>{{$sn++}}</td>
            <td>{{$payment->user->first_name ?? ''}}</td>
            <td>
                {{$payment->amount ?? ''}}
            </td>
            <td>
                {{$payment->gateway ?? ''}}
            </td>
            <td class="text-center"> 
                @if($payment?->status == 'success')
                <span class="bg-success-focus text-success-main px-24 py-4 rounded-pill fw-medium text-sm">Successful</span>
                @endif 
            </td>
            <td>
               {{\Carbon\Carbon::parse($payment?->created_at)->format('d/m/Y')}}
            </td>
            
        </tr>
    @endforeach