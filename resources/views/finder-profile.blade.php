<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('FInder Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <form action="{{ isset($profile) && is_object($profile) ? route('finder-profile.update', $profile->id) : route('finder-profile.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if (isset($profile))
                        @method('PUT') <!-- Use PUT method for updates -->
                    @endif
                    
                    @php
                        $profilePicture = null;
                        if (isset($profile) && $profile->profile_picture) {
                            $fullPath = 'storage/' . $profile->profile_picture;
                            if (file_exists($fullPath)) {
                                $profilePicture = $profile->profile_picture;
                            }
                        }
                    @endphp
                    
                    @php
                        $picturePath = 'storage/' . $profilePicture;
                        $fullPath = public_path($picturePath);
                    @endphp


                    <!-- Profile Picture Display-->
                    <div class="mb-4">
                        @if ($profilePicture)
                            <div class="relative w-20 h-20  mb-4">
                                <img src="{{ asset('storage/' . $profilePicture) }}" 
                                    alt="Profile Picture" 
                                    class="rounded-full w-20 h-20 object-cover"
                                    id="profile-picture-preview"
                                    onerror="this.src='profile-image.png'" /> 
                            </div>      
                        @else
                            <div class="relative w-20 h-20 mx-auto mb-4">
                                <img src="{{ asset('storage/profile-image.png') }}" 
                                    alt="Default Profile Picture" 
                                    class="rounded-full w-20 h-20 object-cover"
                                    id="profile-picture-preview" />                            
                            </div>
                        @endif
                    </div>

                    <div class="mb-4">
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

                    <!-- Add this section after the Professional Overview section and before the Education section 
                    <div class="mb-4">
                        <x-input-label for="interest_areas" :value="__('Areas of Interest')" />
                        <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ($courses as $course)
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                        id="interest_{{ $course->id }}" 
                                        name="interest_areas[]" 
                                        value="{{ $course->id }}"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                        {{ in_array($course->id, $interestAreas ?? []) ? 'checked' : '' }}>
                                    <label for="interest_{{ $course->id }}" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $course->courses_name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('interest_areas')" class="mt-2" />
                    </div> -->

                    <!-- Areas of Interest Section -->
                    <div class="mb-4">
                        <x-input-label for="interest_areas" :value="__('Areas of Interest')" />
                           <!-- 
                        @php
                            @livewire('course-selector', ['selectedCourseIds' => $interestAreas ?? []])
                        @endphp
                        <x-input-error :messages="$errors->get('interest_areas')" class="mt-2" />
                    </div>
                            -->
                    
                            <!-- Education Section -->
                            <div id="education-section" class="mt-6">
                                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">{{ __('Education') }}</h3>
                            
                            @php
                                // dd('Reached show method edu:', $educationData); // Immediate debugging
                            @endphp

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
</x-app-layout>


<script>

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
                    event.target.closest('.education-entry').remove();
                }
            });
        } else {
            console.error('Add education button or section not found');
        }
    });
</script>