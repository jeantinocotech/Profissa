<x-app-layout>

@php
    // Grava um log inicial
    \Illuminate\Support\Facades\Log::info('Log inicial profile');
@endphp

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Find an Advisor</h1>
    
    <div class="bg-white p-6 rounded-lg shadow-lg">
       
    <form action="{{ route('advisor.search.results') }}" method="POST">
            @csrf

                <!-- Hidden inputs to store selected IDs -->
                <div id="selectedCourseInputs"></div>
                <div id="selectedSkillInputs"></div>

                <div class="mb-4">
                    <label for="courseSearch" class="block text-sm font-medium text-gray-700 mb-2">Select Areas of Interest (Courses)</label>
                    <input type="text" id="courseSearch" placeholder="Type to search..." class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mb-2">
                    <div class="flex">
                        <select id="courseList" multiple class="w-1/2 p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 h-40 overflow-y-auto">
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" 
                                    {{ in_array($course->id, $selectedCourses ?? []) ? 'selected' : '' }}>
                                    {{ $course->courses_name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="w-1/2 p-2 border border-gray-300 rounded-md shadow-sm ml-4 h-40 overflow-y-auto bg-gray-50" id="selectedCourses">
                            @foreach($courses as $course)
                                @if(in_array($course->id, $selectedCourses ?? []))
                                    <div class="p-2 bg-indigo-100 rounded mb-1 cursor-pointer selected-item" data-value="{{ $course->id }}">
                                        {{ $course->courses_name }}
                                        <input type="hidden" name="courses[]" value="{{ $course->id }}">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            
            <div class="mb-4">
                <label for="skillSearch" class="block text-sm font-medium text-gray-700 mb-2">Select Skills</label>
                <input type="text" id="skillSearch" placeholder="Type to search..." class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mb-2">
                <div class="flex">
                     <select id="skillList" multiple class="w-1/2 p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 h-40 overflow-y-auto">
                            @foreach($skills as $skill)
                                <option value="{{ $skill->id }}" 
                                    {{ in_array($skill->id, $selectedSkills ?? []) ? 'selected' : '' }}>
                                    {{ $skill->name }}
                                </option>
                            @endforeach
                     </select>
                     <div class="w-1/2 p-2 border border-gray-300 rounded-md shadow-sm ml-4 h-40 overflow-y-auto bg-gray-50" id="selectedSkills">
                            @foreach($skills as $skill)
                                @if(in_array($skill->id, $selectedSkills ?? []))
                                <div class="p-2 bg-indigo-100 rounded mb-1 cursor-pointer selected-item" data-value="{{ $skill->id }}">
                                    {{ $skill->name }}
                                    <input type="hidden" name="skills[]" value="{{ $skill->id }}">
                                </div>
                                @endif
                            @endforeach
                        </div>
                </div>
                   
            </div>

            <div class="flex gap-6">

                <button type="button"
                        onclick="clearSelections()"
                        class="inline-flex px-4 py-2 bg-red-600 text-white rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        clearSelections
                </button>
                
                <button type="submit"
                        class="inline-flex px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Search Advisors
                </button>
            
            </div>

        </form>
        
        @if(isset($matchingAdvisors))

        <div class="top-matching gap-6 mb-4 p-4 border border-gray-300 dark:border-gray-700 rounded-md">
        <div class="gap-6 mb-4">
       
            <h2 class="text-xl font-semibold mb-4">Top Matching Advisors</h2>

            @if(count($matchingAdvisors) > 0)
            <ul class="flex-1  space-y-1">

                @foreach($matchingAdvisors as $advisor)
                    <li class="p-6 bg-white rounded shadow flex items-start gap-6 max-w-xl ml-0 text-left">

                        {{-- Foto do Advisor --}}
                        <div class="flex-shrink-0">
                            @if(!empty($advisor->profile_picture))
                                <img src="{{ asset('storage/' . $advisor->profile_picture) }}"
                                    alt="{{ $advisor->full_name }}"
                                    class="w-20 h-20 object-cover rounded-full border border-gray-300 shadow">
                                    @php
                                        // Grava um log inicial
                                        \Illuminate\Support\Facades\Log::info('Com picture', ['com picture' => $advisor->profile_picture]);
                                    @endphp
                            @else

                                @php
                                    // Grava um log inicial
                                    \Illuminate\Support\Facades\Log::info('Sem picture', ['sem picture' => $advisor->profile_picture]);
                                @endphp
                              
                                <img src="{{ asset('storage/profiles/profile-image.png') }}"
                                alt="Default profile image"
                                class="w-20 h-20 object-cover rounded-full border border-gray-300 shadow-sm">
                            @endif
                        </div>

                        {{-- Conteúdo textual --}}
                        <div class="flex-1 space-y-1">
                            <div class="font-bold text-lg text-gray-800">
                                {{ $advisor->full_name ?? 'Advisor #' . $advisor->id }}
                            </div>

                            @if(isset($maxScore) && $maxScore > 0)

                                @php
                                    $percentage = isset($maxScore) && $maxScore > 0
                                        ? round(($advisor->matching_score / $maxScore) * 100)
                                        : 0;

                                    $barStyle = "width: {$percentage}%;";

                                    $barColor = match(true) {
                                    $percentage >= 75 => 'green',
                                    $percentage >= 50 => 'orange',
                                    default => 'red',
                                    };

                                    \Illuminate\Support\Facades\Log::info('Barra', ['Barra' => $advisor->matching_score]);
                                @endphp

                                <div class="mt-4">

                                    <div class="text-sm text-gray-600 mb-1">
                                        Match Score: <strong>{{ $advisor->matching_score }}</strong> of {{ $maxScore }} ({{ $percentage }}%)
                                    </div>

                                    <div class="w-full bg-gray-200 rounded-full h-5 overflow-hidden">
                                        <div class="h-5 rounded-full transition-all duration-700 ease-out"
                                            style="width: {{ $percentage }}%; background-color: {{ $barColor }};">
                                        </div>
                                    </div>

                                </div>

                            @endif

                            @if(!empty($advisor->linkedin_url))
                                <div class="text-sm">
                                    <a href="{{ $advisor->linkedin_url }}" target="_blank" class="text-blue-600 hover:underline">
                                        LinkedIn Profile
                                    </a>
                                </div>
                            @endif

                            @if(!empty($advisor->instagram_url))
                                <div class="text-sm">
                                    <a href="{{ $advisor->instagram_url }}" target="_blank" class="text-pink-600 hover:underline">
                                        Instagram Profile
                                    </a>
                                </div>
                            @endif

                            @if(!empty($advisor->overview))
                                <div class="text-sm text-gray-700">
                                    <strong>Overview:</strong> {{ $advisor->overview }}
                                </div>
                            @endif

                            @if(isset($advisor->courses))
                                <div class="text-sm mt-2">
                                    <strong>Matched Courses:</strong>
                                    <div class="flex flex-wrap gap-2 mt-1">
                                        <div id="selectedCourses">
                                            @foreach($courses as $course)
                                                @if(in_array($course->id, $selectedCourses ?? []))
                                                    <div class="p-2 bg-indigo-100 rounded mb-1 cursor-pointer selected-course"
                                                        data-value="{{ $course->id }}">
                                                        {{ $course->courses_name }}
                                                        <input type="hidden" name="courses[]" value="{{ $course->id }}">
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @else
                                @php
                                    // Grava um log inicial
                                    \Illuminate\Support\Facades\Log::info('Sem courses', ['sem courses' => $advisor]);
                                @endphp
                            @endif

                            @if(isset($advisor->skills))
                                <div class="text-sm mt-2">
                                    <strong>Matched Skills:</strong>
                                    <div class="flex flex-wrap gap-2 mt-1">
                                        <div id="selectedSkills">
                                            @foreach($skills as $skill)
                                                @if(in_array($skill->id, $selectedSkills ?? []))
                                                    <div class="p-2 bg-indigo-100 rounded mb-1 cursor-pointer selected-skill"
                                                        data-value="{{ $skill->id }}">
                                                        {{ $skill->name }}
                                                        <input type="hidden" name="skills[]" value="{{ $skill->id }}">
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @else
                                @php
                                    // Grava um log inicial
                                    \Illuminate\Support\Facades\Log::info('Sem skills', ['sem skills' => $advisor]);
                                @endphp    
                            @endif
                        </div>
                        <!-- Adicionar no arquivo finder-search.blade.php -->
                        <!-- Inserir dentro do loop foreach($matchingAdvisors as $advisor) -->
                        <!-- Colocar logo antes do fechamento do </li> -->

                        <a href="{{ route('requests.create', ['advisorId' => $advisor->id]) }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Request Connection
                        </a>

                    </li>
                @endforeach

            </ul>
            @else
                <div class="text-gray-500">No advisors matched your search criteria.</div>
            @endif
        </div>
        </div>
        @endif

    </div>
</div>
</x-app-layout>

<script>

    document.querySelectorAll('.selected-course').forEach(function(el) {
        el.addEventListener('click', function() {
            el.remove();
        });
    });

    document.querySelectorAll('.selected-skill').forEach(function(el) {
        el.addEventListener('click', function() {
            el.remove();
        });
    });


        // Função que aplica clique para remover em qualquer item com .selected-item
    function enableItemRemoval() {
        document.querySelectorAll('.selected-item').forEach(function (el) {
            el.addEventListener('click', function () {
                el.remove();
            });
        });
    }

    // Ativa os eventos após o carregamento inicial da página
    document.addEventListener('DOMContentLoaded', function () {
        enableItemRemoval();
    });

    function clearSelections() {
        document.getElementById('selectedCourses').innerHTML = '';
        document.getElementById('selectedSkills').innerHTML = '';
        document.getElementById('selectedCourseInputs').innerHTML = '';
        document.getElementById('selectedSkillInputs').innerHTML = '';
    }

    function filterOptions(inputId, listId) {
        document.getElementById(inputId).addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let options = document.getElementById(listId).options;
            for (let option of options) {
                option.style.display = option.text.toLowerCase().includes(filter) ? '' : 'none';
            }
        });
    }
    
    function moveItem(listId, targetId, hiddenInputContainerId, inputName) {
    const list = document.getElementById(listId);
    const target = document.getElementById(targetId);
    const hiddenContainer = document.getElementById(hiddenInputContainerId);

    list.addEventListener("click", function (event) {
        if (event.target.tagName === "OPTION") {
            const selectedValue = event.target.value;

            // Prevent duplicates
            const exists = [...target.children].some(div => div.dataset.value === selectedValue);
            if (exists) return;

            // Visual badge
            const div = document.createElement("div");
            div.textContent = event.target.text;
            div.classList.add("p-2", "bg-indigo-100", "rounded", "mb-1", "cursor-pointer");
            div.dataset.value = selectedValue;

            // Hidden input
            const hiddenInput = document.createElement("input");
            hiddenInput.type = "hidden";
            hiddenInput.name = `${inputName}[]`;
            hiddenInput.value = selectedValue;

            div.addEventListener("click", function () {
                div.remove();
                hiddenInput.remove();
            });

            target.appendChild(div);
            hiddenContainer.appendChild(hiddenInput);
        }
    });
}

// Apply function to course and skill lists
moveItem("courseList", "selectedCourses", "selectedCourseInputs", "courses");
moveItem("skillList", "selectedSkills", "selectedSkillInputs", "skills");

filterOptions("courseSearch", "courseList");
filterOptions("skillSearch", "skillList");

</script>

