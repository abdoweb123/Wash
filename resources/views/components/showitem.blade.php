@props([
    'name'
])

<tr>
    <th style="width: 25%" scope="row">{{ $name }}</th>
    <td class="text-center">
        {{ $slot }}
    </td>
</tr>
