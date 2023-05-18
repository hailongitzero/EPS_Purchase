@component('mail::message')

{{-- Intro Lines --}}
@foreach ($introLines as $line)
    {!! $line !!}
@endforeach
{{-- Outro Lines --}}
@isset($outroLines)
<table class="action"width="100%" cellpadding="0" cellspacing="0" style="margin: 15px 0;">
    <tr>
        <td>
            <table width="100%"cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td>
                        <table cellpadding="0" cellspacing="0" width="100%" style="background: #e8edf5;">
                            @foreach ($outroLines as $key=>$line)
                                <tr>
                                    <td style="text-align: left; color: #00204d; padding: 10px 20px; border-bottom: 1px solid #d1dcec;">
                                        {!! $line !!}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endisset

{{-- Action Button --}}
@isset($actionText)
@component('mail::buttonCustom', ['url' => $actionUrl])
{{ $actionText }}
@endcomponent
@endisset

@component('mail::subcopyCustom')
@endcomponent
@endcomponent
