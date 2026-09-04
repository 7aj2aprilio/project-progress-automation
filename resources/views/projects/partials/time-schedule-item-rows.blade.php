{{-- Item Row 1: Realisasi (Hijau) --}}
<tr class="hover:bg-slate-50/50 transition-colors text-slate-800">
    <td class="py-1.5 px-2 text-center text-slate-400 sticky left-0 bg-white z-20"></td>
    <td class="py-1.5 px-3 sticky left-12 bg-white z-20 {{ $indent ?? 'pl-8' }}">
        <div class="flex items-center space-x-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-slate-300 flex-shrink-0"></span>
            <span class="truncate max-w-[240px]" title="{{ $item->name }}">{{ $item->name }}</span>
        </div>
    </td>
    <td class="py-1.5 px-2 text-right sticky left-[328px] bg-white z-20 text-slate-600">
        {{ number_format($item->base_bobot, 2) }}
    </td>

    {{-- Kolom Realisasi per Minggu (Hijau) --}}
    @foreach($weeks as $w)
        <td class="py-1 px-1 text-center bg-emerald-50/60 text-emerald-900 border-l border-slate-200">
            <template x-if="getItemRealisasi({{ $item->id }}, {{ $w->id }}) !== null">
                <span class="font-medium" x-text="formatDec(getItemRealisasi({{ $item->id }}, {{ $w->id }}))"></span>
            </template>
            <template x-if="getItemRealisasi({{ $item->id }}, {{ $w->id }}) === null">
                <span class="text-slate-300">-</span>
            </template>
        </td>
    @endforeach
</tr>

{{-- Item Row 2: Rencana / Plan (Biru - Input Editable) --}}
<tr class="border-b border-slate-200 bg-sky-50/20">
    <td class="py-1 px-2 sticky left-0 bg-white z-20"></td>
    <td class="py-1 px-3 text-[10px] text-slate-400 italic sticky left-12 bg-white z-20 {{ $indent ?? 'pl-8' }} pl-12">
        ↳ Rencana (Plan)
    </td>
    <td class="py-1 px-2 sticky left-[328px] bg-white z-20"></td>

    {{-- Kolom Plan per Minggu (Biru) --}}
    @foreach($weeks as $w)
        <td class="py-1 px-1 text-center bg-sky-50/60 border-l border-slate-200">
            @if(auth()->user()->canEdit())
                <input type="text" inputmode="decimal" placeholder="0.00"
                       class="w-full text-center text-xs py-0.5 px-1 border border-sky-200 rounded-md bg-white hover:border-sky-400 focus:border-sky-600 focus:ring-1 focus:ring-sky-500 font-semibold text-sky-950 transition-all shadow-xs"
                       :value="getItemPlan({{ $item->id }}, {{ $w->id }})"
                       @input="setItemPlan({{ $item->id }}, {{ $w->id }}, $event.target.value)">
            @else
                <span class="font-semibold text-sky-900" x-text="formatDec(getItemPlan({{ $item->id }}, {{ $w->id }}))"></span>
            @endif
        </td>
    @endforeach
</tr>
