@component('mail::message')
# Upcoming Bill Reminder

This is a reminder that you have a bill due soon.

**Description:** {{ $bill->description }}
**Amount:** ${{ number_format($bill->amount, 2) }}
**Due Date:** {{ $bill->due_date }}

Thanks,
{{ config('app.name') }}
@endcomponent
