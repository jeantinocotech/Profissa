<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('FInder Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            
            <div class="p-6">   

            
            <form action="{{ isset($profile) && is_object($profile) ? route('finder-profile.update', $profile->id) : route('finder-profile.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if (isset($profile))
                        @method('PUT') <!-- Use PUT method for updates -->
                    @endif
                   
                    @php
                        
                        $profilePicture = null;
                        $errorImage = 'storage/profiles/profile-image.png';
                        $debug = [];


                        if (isset($profile) && $profile->profile_picture) {
                            $debug['profile_picture_db'] = $profile->profile_picture;
                            // Construct the public storage path
                            $publicPath = 'storage/' . $profile->profile_picture;
                            $debug['public_path'] = $publicPath;
                            // Construct the full storage path for existence check
                            $fullPath = storage_path('app/public/' . $profile->profile_picture);
                            
                            $debug['full_path'] = $fullPath;
                            $debug['file_exists'] = file_exists($fullPath) ? 'Yes' : 'No';

                            if (file_exists($fullPath)) {
                                $profilePicture = $publicPath;
                                $debug['selected_path'] = $profilePicture;
                            }
                        }
                        $debug['error_image'] = $errorImage;
                        $debug['error_image_exists'] = file_exists(public_path($errorImage)) ? 'Yes' : 'No';
                        
                    @endphp


                     <!-- Profile Picture Display-->
                     <div class="mb-4">
                        @if ($profilePicture)

                            @php
                                //dd('Profile Picture', $profilePicture); 
                            @endphp

                            <div class="mb-4">
                                <div class="relative w-20 h-20 mb-4 flex items-center gap-4">
                                    <img src="{{ $profilePicture ? asset($profilePicture) : asset($errorImage) }}" 
                                        alt="{{ $profilePicture ? 'Profile Picture' : 'Default Profile Picture' }}" 
                                        class="rounded-full w-20 h-20 object-cover"
                                        id="profile-picture-preview"
                                        onerror="this.src='{{ asset($errorImage) }}'" />
                                </div>
                            </div>

                        @else


                            <div class="relative w-20 h-20 mb-4 flex items-center gap-4">
                                <img src="{{ asset($errorImage) }}" 
                                    alt="Default Profile Picture" 
                                    class="rounded-full w-20 h-20 object-cover"
                                    id="profile-picture-preview" />                            
                            </div>
                        @endif
                    </div>

                    <div class="mb-4 flex items-center gap-4">
                            <input type="file" id="profile-photo" name="profile_picture" accept="image/*" class="block w-full text-sm text-slate-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-violet-50 file:text-violet-700
                                hover:file:bg-violet-100">
                    </div>
                        
                    <!-- Full Name -->
                    <div class="mb-4">
                        <x-input-label for="full_name" :value="__('Full Name')" />
                        <x-text-input id="full_name" name="full_name" type="text" class="mt-1 block w-full" 
                            :value="old('full_name', $profile->full_name ?? '')" required autofocus />
                        <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                    </div>


                    <!-- LinkedIn URL -->
                    <div class="mb-4">
                        <x-input-label for="linkedin_url" :value="__('LinkedIn Profile')" />
                        <x-text-input id="linkedin_url" name="linkedin_url" type="url" class="mt-1 block w-full" 
                            :value="old('linkedin_url', $profile->linkedin_url ?? '')" />
                    </div>

                    <!-- Instagram URL -->
                    <div class="mb-4">
                        <x-input-label for="instagram_url" :value="__('Instagram Profile')" />
                        <x-text-input id="instagram_url" name="instagram_url" type="url" class="mt-1 block w-full" 
                            :value="old('instagram_url', $profile->instagram_url ?? '')" />
                    </div>

                    <!-- Professional Overview -->
                    <div class="mb-4">
                        <x-input-label for="overview" :value="__('Professional Overview')" />
                        <textarea id="overview" name="overview" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('overview', $profile->overview ?? '') }}</textarea>
                        <x-input-error :messages="$errors->get('overview')" class="mt-2" />
                    </div>

                    <!-- Areas of Interest -->
                    <div class="mb-4">
                        <x-input-label for="interest_areas" :value="__('Areas of Interest')" />

                        <div class="areas-input-container">
                            <div class="flex gap-2 mb-2">
                                <x-text-input 
                                    id="areas-input" 
                                    type="text" 
                                    class="mt-1 block w-full" 
                                    placeholder="Type Interest Area and select from suggestions" 
                                />
                            </div>

                            <!-- Suggestions will appear here -->
                            <div id="areas-suggestions" class="hidden mt-1 w-full border rounded-md bg-white shadow-lg max-h-40 overflow-y-auto z-10 absolute bg-white"></div>

                            <!-- Already selected areas -->
                            <div id="selected-areas" class="mt-2 flex flex-wrap gap-2">
                                @if(isset($interestAreas) && count($interestAreas) > 0)
                                    @foreach($interestAreas as $area)
                                        <div class="areas-tag bg-blue-100 text-blue-800 px-3 py-1 rounded-full flex items-center gap-2">
                                            <span>{{ $area->courses_name }}</span> 
                                            <!-- <input type="hidden" name="interest_areas[]" value="{{ $area->id }}" id="interest_{{ $area->id_courses }}">
                                                 <input type="hidden" name="interest_areas[{{ $loop->index }}][id]" value="{{ $area->id }}">
                                            -->
                                            <input type="hidden" name="interest_areas[{{ $loop->index }}][id]" value="{{ $area->id_courses }}">
                                            <button type="button" class="remove-area text-blue-600 hover:text-blue-800">&times;</button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Education Section -->
                    <div id="education-section" class="mt-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">{{ __('Education') }}</h3>

                        <!-- Existing Education Entries -->
                        @foreach($educationData as $index => $education)
                            <div class="education-entry mb-4 p-4 border border-gray-300 dark:border-gray-700 rounded-md">
                                <div class="mb-4">
                                    <x-input-label for="course_{{ $index }}" :value="__('Course')" />
                                    <select name="course[]" id="course_{{ $index }}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="">-- Select a Course --</option>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->id }}" {{ $course->id == $education->id_courses ? 'selected' : '' }}>
                                            {{ $course->courses_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="mb-4">
                                    <x-input-label for="institution_{{ $index }}" :value="__('Institution')" />
                                    <x-text-input id="institution_{{ $index }}" name="institution[]" type="text" class="mt-1 block w-full" :value="$education->institution_name ?? ''" required />
                                </div>
                                        
                                <div class="mb-4">
                                    <x-input-label for="certification_{{ $index }}" :value="__('Certification')" />
                                    <x-text-input id="certification_{{ $index }}" name="certification[]" type="text" class="mt-1 block w-full" :value="$education->certification ?? ''" />
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <x-input-label for="start_date_{{ $index }}" :value="__('Start Date')" />
                                        <x-text-input id="start_date_{{ $index }}" name="start_date[]" type="date" class="mt-1 block w-full" :value="$education->dt_start ?? ''" required />
                                    </div>
                                    <div>
                                        <x-input-label for="end_date_{{ $index }}" :value="__('End Date')" />
                                        <x-text-input id="end_date_{{ $index }}" name="end_date[]" type="date" class="mt-1 block w-full" :value="$education->dt_end ?? ''" />
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <x-input-label for="comments_{{ $index }}" :value="__('Additional Comments')" />
                                    <textarea id="comments_{{ $index }}" name="comments[]" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ $education->comments ?? '' }}</textarea>
                                </div>
                                <button type="button" class="remove-entry inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">Remove</button>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" id="add-education" class="mb-4 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Add Education
                    </button>

                    <div class="mb-4">
                        <x-input-label for="is_active" :value="__('Profile Status')" />
                        <div class="flex items-center">
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="is_active" value="1" {{ old('is_active', $profile->is_active ?? 1) == 1 ? 'checked' : '' }} />
                                <span>{{ __('Active') }}</span>
                            </label>
                            <label class="flex items-center space-x-2 ml-4">
                                <input type="radio" name="is_active" value="0" {{ old('is_active', $profile->is_active ?? 1) == 0 ? 'checked' : '' }} />
                                <span>{{ __('Inactive') }}</span>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button>
                            {{ __('Save Profile') }}
                        </x-primary-button>
                    </div>

                    @php
                        //dd('Picture :', $profile->profile_picture); // Immediate debugging
                    @endphp

                </form>
            </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Scripts -->
<script>

    document.addEventListener('DOMContentLoaded', function () {
    const areasInput = document.getElementById('areas-input');
    const areasSuggestions = document.getElementById('areas-suggestions');
    const selectedAreas = document.getElementById('selected-areas');
    let currentAreas = new Set();

       // Initialize with already loaded areas
       document.querySelectorAll('#selected-areas input[name="interest_areas[]"]').forEach(input => {
        currentAreas.add(input.value);
    });

    areasInput.addEventListener('input', debounce(function () {
        const searchTerm = this.value.trim();
        if (searchTerm.length < 2) {
            areasSuggestions.classList.add('hidden');
            return;
        }

        fetch(`/areas/search?term=${encodeURIComponent(searchTerm)}`)
            .then(response => response.json())
            .then(data => {
                areasSuggestions.innerHTML = '';
                if (data.length === 0) {
                    const div = document.createElement('div');
                    div.className = 'p-2 text-gray-500';
                    div.textContent = 'No results found';
                    areasSuggestions.appendChild(div);
                } else {
                    data.forEach(area => {
                        if (!currentAreas.has(area.id.toString())) {
                            const div = document.createElement('div');
                            div.className = 'p-2 hover:bg-gray-100 cursor-pointer';
                            div.textContent = area.courses_name;
                            div.onclick = () => addCourse(area);
                            areasSuggestions.appendChild(div);
                        }
                    });
                }
                areasSuggestions.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error searching areas:', error);
            });
    }, 300));

    function addCourse(area) {
        if (!currentAreas.has(area.id.toString())) {
            const areaTag = document.createElement('div');
            areaTag.className = 'areas-tag bg-blue-100 text-blue-800 px-3 py-1 rounded-full flex items-center gap-2';
            areaTag.innerHTML = `
                <span>${area.courses_name}</span>
                <input type="hidden" name="interest_areas[]" value="${area.id}" id="interest_${area.id}">
                <button type="button" class="remove-area text-blue-600 hover:text-blue-800">&times;</button>
            `;

            areaTag.querySelector('.remove-area').onclick = function () {
                currentAreas.delete(area.id.toString());
                areaTag.remove();
            };

            selectedAreas.appendChild(areaTag);
            currentAreas.add(area.id.toString());
            areasInput.value = '';
            areasSuggestions.classList.add('hidden');
        }
    }
      // Setup remove functionality for existing areas
      document.querySelectorAll('.remove-area').forEach(button => {
        button.addEventListener('click', function() {
            const areaTag = this.closest('.areas-tag');
            const input = areaTag.querySelector('input[name="interest_areas[]"]');
            if (input) {
                currentAreas.delete(input.value);
            }
            areaTag.remove();
        });
    });

    // Função debounce para limitar requisições
    function debounce(func, wait) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    // Fechar sugestões ao clicar fora
    document.addEventListener('click', function (e) {
        if (!areasInput.contains(e.target) && !areasSuggestions.contains(e.target)) {
            areasSuggestions.classList.add('hidden');
        }
    });
    });

    // Profile Photo Preview
    document.getElementById("profile-photo").addEventListener("change", function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById("profile-picture-preview").src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
    
    document.addEventListener('DOMContentLoaded', () => {
        const addEducationBtn = document.getElementById('add-education');
        const educationSection = document.getElementById('education-section');

        if (addEducationBtn && educationSection) {
            addEducationBtn.addEventListener('click', () => {
                const index = document.querySelectorAll('.education-entry').length; // Count existing entries
                const newEntry = document.createElement('div');
                newEntry.classList.add('education-entry', 'mb-4', 'p-4', 'border', 'border-gray-300', 'dark:border-gray-700', 'rounded-md');
                newEntry.innerHTML = `
                    <div class="mb-4">
                            <x-input-label for="course_${index}" :value="__('Course')" />
                            <select name="course[]" id="course_${index}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">-- Select a Course --</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->courses_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="institution_${index}" :value="__('Institution')" />
                            <x-text-input id="institution_${index}" name="institution[]" type="text" class="mt-1 block w-full" required />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="certification_${index}" :value="__('Certification')" />
                            <x-text-input id="certification_${index}" name="certification[]" type="text" class="mt-1 block w-full" />
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="start_date_${index}" :value="__('Start Date')" />
                                <x-text-input id="start_date_${index}" name="start_date[]" type="date" class="mt-1 block w-full" required />
                            </div>
                            <div>
                                <x-input-label for="end_date_${index}" :value="__('End Date')" />
                                <x-text-input id="end_date_${index}" name="end_date[]" type="date" class="mt-1 block w-full" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="comments_${index}" :value="__('Additional Comments')" />
                            <textarea id="comments_${index}" name="comments[]" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"></textarea>
                        </div>

                      <button type="button" class="remove-entry inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">Remove</button>
                `;

                educationSection.appendChild(newEntry);
                // Insert the new entry before the "Add Education" button
                // addEducationBtn.parentNode.insertBefore(newEntry, addEducationBtn);
            });

            // Event delegation for remove button
            educationSection.addEventListener('click', (event) => {
            if (event.target.classList.contains('remove-entry')) {
                event.preventDefault(); // Prevent default button behavior
                event.target.closest('.education-entry').remove();
                reindexEducationEntries(); // Call the re-indexing function
            }
        });

        } else {
            console.error('Add education button or section not found');
        }
    });

    function reindexEducationEntries() {
            const entries = document.querySelectorAll('.education-entry');
            entries.forEach((entry, index) => {
                entry.querySelectorAll('input, select, textarea').forEach(element => {
                    const name = element.getAttribute('name');
                    if (name) {
                        const newName = name.replace(/\[\d+\]/, `[${index}]`);
                        element.setAttribute('name', newName);
                        // Update the id as well, if needed
                        const id = element.getAttribute('id');
                        if (id) {
                            const newId = id.replace(/_\d+/, `_${index}`);
                            element.setAttribute('id', newId);
                        }
                    }
                });
            });
        }


</script>