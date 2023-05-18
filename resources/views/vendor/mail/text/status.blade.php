<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td align="center">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td align="center">
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation">
                            <tr>
                                <td style="text-align: center; padding: 20px 10px;">
                                    @if( $status == '0')
                                        <img src="{{ url('img/icon_plus.png') }}" alt="">
                                    @elseif($status == '1' || $status == '2')
                                        <img src="{{ url('img/icon_handle.png') }}" alt="">
                                    @elseif($status == '3')
                                        <img src="{{ url('img/icon_success.png') }}" alt="">
                                    @elseif($status == '4')
                                        <img src="{{url('img/icon_fail.png')}}" alt="">
                                    @elseif($status == '5')
                                        <img src="{{ url('img/icon_success.png') }}" alt="">
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p style="text-align: center;font-size: 22px;font-weight: bold; padding-top: 20px; margin-bottom: 0;">{{ $subject }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p style="text-align: center;font-size: 18px;font-weight: bold; padding-top: 20px; margin-bottom: 0;">{{ $statusList[$status] }}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
