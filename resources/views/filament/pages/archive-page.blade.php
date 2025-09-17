<x-filament-panels::page>
    <div class="space-y-6">
        

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Kode Klasifikasi') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Uraian Berkas') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Tanggal') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Kurun Waktu') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Jumlah Berkas') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('No Item Arsip') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Segment') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Akun') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Uraian Item Arsip') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('File/Folder') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Box File') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Keterangan') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Retensi Arsip Aktif') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Retensi Arsip Inaktif') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Nasib Akhir Arsip') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($folders as $folder)
                            @forelse($folder->documents as $index => $document)
                                <tr>
                                    @if($index === 0)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" rowspan="{{ $folder->documents->count() }}">
                                            {{ $folder->classification->code ?? '' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500" rowspan="{{ $folder->documents->count() }}">
                                            {{ $folder->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" rowspan="{{ $folder->documents->count() }}">
                                            @php
                                                $latestDate = $folder->documents->max('published_at');
                                                echo $latestDate ? $latestDate->format('d/m/Y') : '-';
                                            @endphp
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" rowspan="{{ $folder->documents->count() }}">
                                            @php
                                                $latestDate = $folder->documents->max('published_at');
                                                echo $latestDate ? $latestDate->format('Y') : '-';
                                            @endphp
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" rowspan="{{ $folder->documents->count() }}">
                                            {{ $folder->documents->count() }} {{ $folder->type === 'lembar' ? 'lembar' : 'berkas' }}
                                        </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $document->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $document->segment->name ?? '' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @foreach($document->accounts as $account)
                                            {{ $account->code }}@if(!$loop->last), @endif
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $document->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @php
                                            // Get the sub-child (grandchild) of the location
                                            $location = $folder->location;
                                            $fileFolder = '-';
                                            if ($location && $location->children->count() > 0) {
                                                $firstChild = $location->children->first();
                                                if ($firstChild->children->count() > 0) {
                                                    $fileFolder = $firstChild->children->first()->code ?? '-';
                                                }
                                            }
                                        @endphp
                                        {{ $fileFolder }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @php
                                            // Get the child of the location
                                            $location = $folder->location;
                                            $boxFile = '-';
                                            if ($location && $location->children->count() > 0) {
                                                $boxFile = $location->children->first()->code ?? '-';
                                            }
                                        @endphp
                                        {{ $boxFile }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $document->information ?? '' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $document->active_retention ?? '' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $document->inactive_retention ?? '' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @php
                                            if ($document->condition == '0') {
                                                echo 'Musnah';
                                            } elseif ($document->condition == '1') {
                                                echo 'Tidak Musnah';
                                            } else {
                                                echo $document->condition ?? '';
                                            }
                                        @endphp
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $folder->classification->code ?? '' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $folder->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @php
                                            $latestDate = $folder->documents->max('published_at');
                                            echo $latestDate ? $latestDate->format('d/m/Y') : '-';
                                        @endphp
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @php
                                            $latestDate = $folder->documents->max('published_at');
                                            echo $latestDate ? $latestDate->format('Y') : '-';
                                        @endphp
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        0 {{ $folder->type === 'lembar' ? 'lembar' : 'berkas' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500" colspan="10">
                                        {{ __('Tidak ada dokumen dalam folder ini.') }}
                                    </td>
                                </tr>
                            @endforelse
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>