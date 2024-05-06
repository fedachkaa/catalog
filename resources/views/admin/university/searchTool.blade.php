<div class="university-search-tool">
    <div class="search-tool-title">Шукати університет</div>
    <div class="search-tool-body">
        <div class="search-field">
            <label for="title">Назва:</label>
            <input type="text" id="title" name="title">
        </div>

        <div class="search-field">
            <label for="city">Місто:</label>
            <input type="text" id="city" name="city">
        </div>

        <div class="search-field">
            <label for="createdAt">Зареєстрований:</label>
            <input type="date" id="createdAt" name="createdAt">
        </div>

        <div class="search-field">
            <label for="accLevel">Рівень акредитації:</label>
            <select name="accLevel">
                <option value=""></option>
                @foreach(\App\Models\University::AVAILABLE_ACCREDITATION_LEVELS as $key => $level)
                     <option value="{{ $key }}">{{ $level }}</option>
                 @endforeach
            </select>
        </div>

        <div class="search-field">
            <label for="email">Пошта:</label>
            <input type="email" id="email" name="email">
        </div>

        <div class="search-field">
            <label for="status">Статус:</label>
            <select name="status">
                <option value=""></option>
                @foreach(\App\Models\University::AVAILABLE_STATUSES as $key => $status)
                    <option value="{{ $key }}">{{ $status }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="search-tool-button">
        <button type="button" class="add-user-btn js-search-university">Пошук</button>
    </div>
</div>
