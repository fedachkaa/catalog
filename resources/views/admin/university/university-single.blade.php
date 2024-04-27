<?php
/**
 * @var array $universityData
 */
?>

<style>
    #faculties-table_wrapper .dt-layout-row:first-child {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }
    #faculties-table_wrapper .dt-layout-row:last-child {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }

    #faculties-table_wrapper .dt-search input {
        border: 2px #2d3748;
    }
</style>
@extends('layouts.admin')

@section('title', 'UniSpace | AdminPanel')

@section('content')
    <div class="js-university-single" data-universityid="{{ $universityData['id'] }}">
        <div class="text-center text-3xl font-semibold flex items-center justify-center">
            {{ $universityData['name'] }}
            <span class="{{ $universityData['activated_at'] ? 'status-active' : 'status-inactive'}} action-icon" title="{{ $universityData['activated_at'] ? 'Активний з ' .  date('d-m-Y', strtotime($universityData['activated_at'])) : 'Не активний'}}"></span>
        </div>

        <div class="flex flex-row text-xl p-4 gap-3">
            <div class="flex flex-col gap-3 w-1/2 border-2 border-gray-700 p-4">
                <div><span class="font-bold">Місто: </span>{{ $universityData['city'] }} </div>
                <div><span class="font-bold">Адреса: </span>{{ $universityData['address'] }} </div>
                <div><span class="font-bold">Номер телефону: </span>{{ $universityData['phone_number'] }} </div>
                <div><span class="font-bold">Електронна пошта: </span><a href="mailto:{{ $universityData['email'] }}">{{ $universityData['email'] }}</a></div>
                <div><span class="font-bold">Заснований: </span>{{ date('d M Y', strtotime($universityData['founded_at'])) }} </div>
                <div><span class="font-bold">Веб-сайт: </span><a href="{{ $universityData['website'] }}" target="_blank">{{ $universityData['website'] }}</a></div>
            </div>
            <div class="flex flex-col gap-3 w-1/2 border-2 border-gray-700 p-4">
                Адміністратор:
                <div><span class="font-bold">ПІБ: </span>{{ $universityData['universityAdmin']['full_name'] }}</div>
                <div><span class="font-bold">Електронна пошта: </span><a href="mailto:{{ $universityData['universityAdmin']['email'] }}">{{ $universityData['universityAdmin']['email'] }}</a></div>
                <div><span class="font-bold">Номер телефону: </span>{{ $universityData['universityAdmin']['phone_number'] }}</div>
            </div>
        </div>
        @if (!$universityData['activated_at'])
            <div class="flex flex-row justify-end gap-4 m-4" data-token="{{ csrf_token() }}">
                <a class="save-btn js-approve-university" data-approved="1">Approve</a>
                <a class="remove-btn js-reject-university" data-approved="0">Decline</a>
            </div>
        @endif

        <div class="js-accrodion">
            <div class="js-accoridon-tab">
                <div class="js-accordion-title accordion-title">Факультети</div>
                <div class="js-accordion-body hidden">
                    <table id="faculties-table" class="table-block">
                        <thead>
                            <tr>
                                <th>№</th>
                                <th>Факультет</th>
                                <th>Курси</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($universityData['faculties'] as $faculty)
                                <tr>
                                    <td>{{ $faculty['id'] }}</td>
                                    <td>{{ $faculty['title'] }}</td>
                                    <td>
                                        <ul>
                                            @foreach($faculty['courses'] as $course)
                                                <li> {{ $course['course'] }} курс</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="js-accoridon-tab">
                <div class="js-accordion-title accordion-title">Викладачі</div>
                <div class="js-accordion-body hidden">
                    <table id="teachers-table" class="table-block">
                        <thead>
                            <tr>
                                <th>№</th>
                                <th>ПІБ</th>
                                <th>Факультет</th>
                                <th>Предмети</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($universityData['teachers'] as $teacher)
                                <tr data-userid="{{ $teacher['user_id'] }}">
                                    <td>{{ $teacher['user_id'] }}</td>
                                    <td class="js-show-user-info action-icon">{{ $teacher['user']['full_name'] }}</td>
                                    <td>{{ $teacher['faculty']['title'] }}</td>
                                    <td>
                                        <ul>
                                            @foreach($teacher['subjects'] as $subject)
                                                <li> {{ $subject['title'] }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="js-accoridon-tab">
                <div class="js-accordion-title accordion-title">Студенти</div>
                <div class="js-accordion-body hidden">
                    <table id="students-table" class="table-block">
                        <thead>
                            <tr>
                                <th>№</th>
                                <th>ПІБ</th>
                                <th>Факультет</th>
                                <th>Курс</th>
                                <th>Група</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($universityData['students'] as $student)
                                <tr data-userid="{{ $student['user_id'] }}">
                                    <td>{{ $student['user_id'] }}</td>
                                    <td class="js-show-user-info action-icon">{{ $student['user']['full_name'] }}</td>
                                    <td>{{ $student['faculty']['title'] }}</td>
                                    <td>{{ $student['course']['course'] }}</td>
                                    <td>{{ $student['group']['title'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="js-accoridon-tab">
                <div class="js-accordion-title accordion-title">Каталоги</div>
                <div class="js-accordion-body hidden">
                    <table id="catalog-table" class="table-block">
                        <thead>
                            <tr>
                                <th>№</th>
                                <th>Тип</th>
                                <th>Факультет</th>
                                <th>Курс</th>
                                <th>Групи</th>
                                <th>Наукові керівники</th>
                                <th>Створено</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($universityData['catalogs'] as $catalog)
                                <tr>
                                    <td>{{ $catalog['id'] }}</td>
                                    <td>{{ \App\Models\Catalog::AVAILABLE_CATALOG_TYPES[$catalog['type']] }}</td>
                                    <td>{{ $catalog['faculty']['title'] }}</td>
                                    <td>{{ $catalog['course']['course'] }}</td>
                                    <td>
                                        <ul>
                                            @foreach($catalog['supervisors'] as $supervisor)
                                                <li> {{ $supervisor['user']['full_name'] }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            @foreach($catalog['groups'] as $group)
                                                <li> {{ $group['title'] }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>{{ $catalog['created_at'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('general.user-info-modal')

@endsection

@push('scripts')
    <script src="{{ asset('js/admin/universitySingle.js')}}"></script>
@endpush
