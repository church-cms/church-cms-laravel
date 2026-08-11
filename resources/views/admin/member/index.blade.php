@extends('layouts.admin.layout')

@section('content')
    <div class="relative">
        <div class="flex flex-wrap lg:flex-row justify-between mb-4">
            <div class="">
                <h1 class="admin-h1">Members ( {{ $count }} )</h1>
            </div>
            <div class="w-full lg:w-2/4">
                <!-- Search Filter Component Portal Targets -->
                <portal-target name="search"></portal-target>
                <portal-target name="memberfilter"></portal-target>
                <search-filter url="{{ url('/') }}" searchquery="{{ $query }}"></search-filter>
            </div>
            <div  class="relative flex items-center w-full lg:w-2/5 md:w-2/5 lg:justify-end gap-2">
                <div class="flex items-center" dusk="add-button">
                    <a href="{{ url('/admin/member/add/') }}"
                        class="no-underline text-white px-4 flex items-center custom-green py-1 justify-center rounded whitespace-nowrap">
                        <span class="mx-1 text-sm font-semibold">Add</span>
                        <img src="{{ url('uploads/icons/plus.svg') }}" class="w-3 h-3">
                    </a>
                </div>
                <div class="">
                    <a href="{{ url('/admin/exportUsers/?' . $query . '&usergroup_id=5') }}" id="export-button"
                        class="no-underline text-white px-4 flex items-center custom-green py-1 rounded whitespace-nowrap">
                        <span class="mx-1 text-sm font-semibold">Export</span>
                    </a>
                </div>
                <div class="">
                    <a href="{{ url('/admin/import') }}" id="import-button"
                        class="no-underline text-white px-4 flex items-center custom-green py-1 rounded whitespace-nowrap">
                        <span class="mx-1 text-sm font-semibold">Import</span>
                    </a>
                </div>
                <div class="">
                    <a href="{{ url('/admin/membershipCard/create') }}" id="membership-card-button" class="no-underline text-white px-2 flex items-center custom-green py-1 whitespace-nowrap">
                        <span class="text-xs font-semibold">Membership Card</span>
                    </a>
                </div>
            </div>
        </div>

        @include('partials.message')

        <!-- A-Z Filter -->
        <div class="my-4 filter-alphabet bg-gradient-to-r from-gray-50 to-white p-2 rounded-lg border border-gray-200">
            <ul class="list-reset flex flex-wrap gap-0 items-center justify-center lg:justify-start">
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('A'); return false;" data-letter="A">A</a></li>
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('B'); return false;" data-letter="B">B</a></li>
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('C'); return false;" data-letter="C">C</a></li>
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('D'); return false;" data-letter="D">D</a></li>
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('E'); return false;" data-letter="E">E</a></li>
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('F'); return false;" data-letter="F">F</a></li>
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('G'); return false;" data-letter="G">G</a></li>
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('H'); return false;" data-letter="H">H</a></li>
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('I'); return false;" data-letter="I">I</a></li>
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('J'); return false;" data-letter="J">J</a></li>
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('K'); return false;" data-letter="K">K</a></li>
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('L'); return false;" data-letter="L">L</a></li>
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('M'); return false;" data-letter="M">M</a></li>
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('N'); return false;" data-letter="N">N</a></li>
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('O'); return false;" data-letter="O">O</a></li>
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('P'); return false;" data-letter="P">P</a></li>
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('Q'); return false;" data-letter="Q">Q</a></li>
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('R'); return false;" data-letter="R">R</a></li>
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('S'); return false;" data-letter="S">S</a></li>
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('T'); return false;" data-letter="T">T</a></li>
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('U'); return false;" data-letter="U">U</a></li>
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('V'); return false;" data-letter="V">V</a></li>
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('W'); return false;" data-letter="W">W</a></li>
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('X'); return false;" data-letter="X">X</a></li>
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('Y'); return false;" data-letter="Y">Y</a></li>
                <li><a href="#" class="alphabet-btn" onclick="filterByLetter('Z'); return false;" data-letter="Z">Z</a></li>
                <li class="ml-auto"><a href="{{ url('/admin/members') }}" class="clear-filter-btn">Clear All</a></li>
            </ul>
        </div>

        <!-- Members Table -->
        @include('admin.member.members-table')
    </div>

    <script>
        function filterByLetter(letter) {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('alphabet', letter);
            currentUrl.searchParams.delete('page'); // Reset to page 1 when filtering
            window.location.href = currentUrl.toString();
        }

        // Highlight active letter on page load
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const activeLetter = urlParams.get('alphabet');

            if (activeLetter) {
                const activeBtns = document.querySelectorAll(`a[data-letter="${activeLetter}"]`);
                activeBtns.forEach(btn => {
                    btn.classList.add('active');
                });
            }
        });
    </script>

    <style>
        .alphabet-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.0rem;
            height: 2.0rem;
            font-weight: 600;
            font-size: 0.75rem;
            border: 1.5px solid #d1d5db;
            border-radius: 0.375rem;
            background-color: white;
            color: #4b5563;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .alphabet-btn:hover {
            background-color: #eff6ff;
            border-color: #60a5fa;
            color: #3b82f6;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.15);
        }

        .alphabet-btn.active {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border-color: #1d4ed8;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
            font-weight: 700;
        }

        .clear-filter-btn {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 0.4rem;
            font-weight: 600;
            font-size: 0.875rem;
            border: 1.5px solid #f87171;
            border-radius: 0.375rem;
            background-color: white;
            color: #ef4444;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .clear-filter-btn:hover {
            background-color: #fee2e2;
            border-color: #dc2626;
            color: #991b1b;
            box-shadow: 0 4px 6px rgba(239, 68, 68, 0.15);
            transform: translateY(-1px);
        }
    </style>
@endsection
