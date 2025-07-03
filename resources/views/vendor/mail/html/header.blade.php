@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel' || trim($slot) === 'Wet Water Resort')
<div style="text-align: center; padding: 20px;">
    <div style="background: linear-gradient(135deg, #0891b2, #155e75); color: white; padding: 15px 30px; border-radius: 15px; display: inline-block; font-family: 'Arial', sans-serif;">
        <div style="font-size: 28px; font-weight: bold; margin-bottom: 5px;">🏨 Wet Water Resort</div>
        <div style="font-size: 14px; opacity: 0.9;">Your Perfect Wedding Destination</div>
    </div>
</div>
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
