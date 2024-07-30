<table class="table table-hover custom-data-bs-table">
    <thead class="table-light">
        <tr>
            <th scope="col">Label</th>
            @foreach($languages as $language)
            <th scope="col">{{$language->name}}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($translations as $translation)
        <tr>

            <td data-label="User">
                <span class="sub sub-s2 sub-dtype">{{ $translation->name }}</span>
            </td>
            @foreach($translation->translations as $language)
            <td data-label="Doc Type">
                <span class="sub sub-s2 sub-dtype">{{ $language->value }}</span>
            </td>
            @endforeach

        </tr>
        @endforeach
    </tbody>
</table>