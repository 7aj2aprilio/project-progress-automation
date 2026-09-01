<div x-show="activeTab === 'boq'" x-cloak x-data="{ 
    showModal: false, 
    isEdit: false,
    formAction: '{{ route('work-items.store', $project) }}',
    itemType: 'main', 
    parentId: null, 
    itemName: '',
    itemVolume: '',
    itemUnit: '',
    itemPrice: '',
    modalTitle: 'Tambah Pekerjaan Utama',
    
    openAdd(type, parent = null, title = '') {
        this.isEdit = false;
        this.formAction = '{{ route('work-items.store', $project) }}';
        this.itemType = type;
        this.parentId = parent;
        this.itemName = '';
        this.itemVolume = '';
        this.itemUnit = '';
        this.itemPrice = '';
        this.modalTitle = title;
        this.showModal = true;
    },
    
    openEdit(item, updateUrl) {
        this.isEdit = true;
        this.formAction = updateUrl;
        this.itemType = item.type;
        this.parentId = item.parent_id;
        this.itemName = item.name;
        this.itemVolume = item.volume;
        this.itemUnit = item.unit;
        this.itemPrice = item.unit_price;
        this.modalTitle = 'Edit ' + item.name;
        this.showModal = true;
    }
}">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-slate-800">Rincian Pekerjaan (BoQ)</h3>
        @if(auth()->user()->canEdit())
            <button @click="openAdd('main', null, 'Tambah Pekerjaan Utama')" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700">
                + Tambah Pekerjaan Utama
            </button>
        @endif
    </div>

    <!-- Modal Form (Tambah/Edit) -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" @click="showModal = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showModal" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form :action="formAction" method="POST">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    <input type="hidden" name="type" x-model="itemType">
                    <input type="hidden" name="parent_id" x-model="parentId">
                    
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" x-text="modalTitle"></h3>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nama Pekerjaan</label>
                            <input type="text" name="name" x-model="itemName" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <template x-if="itemType === 'item'">
                            <div class="grid grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Volume</label>
                                    <input type="number" step="0.01" name="volume" x-model="itemVolume" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Satuan</label>
                                    <input type="text" name="unit" x-model="itemUnit" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Harga Satuan</label>
                                    <input type="number" name="unit_price" x-model="itemPrice" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan
                        </button>
                        <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="bg-white border rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Pekerjaan</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Volume</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Satuan</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Harga</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot (%)</th>
                    @if(auth()->user()->canEdit())
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($project->workItems()->whereNull('parent_id')->get() as $mainIndex => $mainItem)
                    <tr class="bg-gray-50 font-semibold">
                        <td class="px-4 py-3">{{ App\Helpers\FormatHelper::romawi($mainIndex + 1) }}</td>
                        <td class="px-4 py-3">{{ $mainItem->name }}</td>
                        <td class="px-4 py-3 text-right">-</td>
                        <td class="px-4 py-3">-</td>
                        <td class="px-4 py-3 text-right">-</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($mainItem->total_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($mainItem->base_bobot, 2, ',', '.') }}%</td>
                        @if(auth()->user()->canEdit())
                            <td class="px-4 py-3 text-right flex justify-end gap-2">
                                <button @click="openAdd('sub', {{ $mainItem->id }}, 'Tambah Sub Pekerjaan')" class="text-xs text-indigo-600 hover:text-indigo-900">+ Sub</button>
                                <button @click="openAdd('item', {{ $mainItem->id }}, 'Tambah Item Detail')" class="text-xs text-emerald-600 hover:text-emerald-900">+ Item</button>
                                <form action="{{ route('work-items.destroy', $mainItem->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pekerjaan ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                    @foreach($mainItem->children as $subIndex => $subItem)
                        @if($subItem->type === 'sub')
                            <tr class="bg-white font-medium">
                                <td class="px-4 py-3 pl-8">{{ App\Helpers\FormatHelper::romawi($mainIndex + 1) }}.{{ chr(65 + $subIndex) }}</td>
                                <td class="px-4 py-3 pl-8">{{ $subItem->name }}</td>
                                <td class="px-4 py-3 text-right">-</td>
                                <td class="px-4 py-3">-</td>
                                <td class="px-4 py-3 text-right">-</td>
                                <td class="px-4 py-3 text-right">Rp {{ number_format($subItem->total_price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right">{{ number_format($subItem->base_bobot, 2, ',', '.') }}%</td>
                                @if(auth()->user()->canEdit())
                                    <td class="px-4 py-3 text-right flex justify-end gap-2">
                                        <button @click="openAdd('item', {{ $subItem->id }}, 'Tambah Item Detail')" class="text-xs text-emerald-600 hover:text-emerald-900">+ Item</button>
                                        <form action="{{ route('work-items.destroy', $subItem->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pekerjaan ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                            @foreach($subItem->children as $itemIndex => $item)
                                <tr class="bg-white text-sm text-gray-600">
                                    <td class="px-4 py-2 pl-12">{{ $itemIndex + 1 }}</td>
                                    <td class="px-4 py-2 pl-12">{{ $item->name }}</td>
                                    <td class="px-4 py-2 text-right">{{ number_format($item->volume, 2, ',', '.') }}</td>
                                    <td class="px-4 py-2">{{ $item->unit }}</td>
                                    <td class="px-4 py-2 text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-right">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-right">{{ number_format($item->base_bobot, 2, ',', '.') }}%</td>
                                    @if(auth()->user()->canEdit())
                                        <td class="px-4 py-2 text-right flex justify-end gap-2">
                                            <button @click="openEdit({{ $item }}, '{{ route('work-items.update', $item->id) }}')" class="text-xs text-blue-600 hover:text-blue-900">Edit</button>
                                            <form action="{{ route('work-items.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus item ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-xs text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr class="bg-white text-sm text-gray-600">
                                <td class="px-4 py-2 pl-8">{{ $subIndex + 1 }}</td>
                                <td class="px-4 py-2 pl-8">{{ $subItem->name }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($subItem->volume, 2, ',', '.') }}</td>
                                <td class="px-4 py-2">{{ $subItem->unit }}</td>
                                <td class="px-4 py-2 text-right">Rp {{ number_format($subItem->unit_price, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-right">Rp {{ number_format($subItem->total_price, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-right">{{ number_format($subItem->base_bobot, 2, ',', '.') }}%</td>
                                @if(auth()->user()->canEdit())
                                    <td class="px-4 py-2 text-right flex justify-end gap-2">
                                        <button @click="openEdit({{ $subItem }}, '{{ route('work-items.update', $subItem->id) }}')" class="text-xs text-blue-600 hover:text-blue-900">Edit</button>
                                        <form action="{{ route('work-items.destroy', $subItem->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus item ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endif
                    @endforeach
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-4 text-center text-gray-500 italic">Belum ada rincian pekerjaan (BoQ).</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-100 font-bold border-t-2 border-gray-300">
                <tr>
                    <td colspan="5" class="px-4 py-4 text-right uppercase tracking-wider text-slate-800">Total Keseluruhan Pekerjaan</td>
                    <td class="px-4 py-4 text-right text-indigo-700 text-lg">Rp {{ number_format($project->workItems()->where('type', 'main')->get()->sum('total_price'), 0, ',', '.') }}</td>
                    <td class="px-4 py-4 text-right text-indigo-700 text-lg">100,00%</td>
                    @if(auth()->user()->canEdit())
                        <td></td>
                    @endif
                </tr>
            </tfoot>
        </table>
    </div>
</div>
