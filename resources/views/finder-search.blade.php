<x-app-layout>
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
                                <option value="{{ $course->id }}">{{ $course->courses_name }}</option>
                            @endforeach
                        </select>
                        <div class="w-1/2 p-2 border border-gray-300 rounded-md shadow-sm ml-4 h-40 overflow-y-auto bg-gray-50" id="selectedCourses"></div>
                    </div>
                </div>
            
            <div class="mb-4">
                <label for="skillSearch" class="block text-sm font-medium text-gray-700 mb-2">Select Skills</label>
                <input type="text" id="skillSearch" placeholder="Type to search..." class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mb-2">
                <div class="flex">
                <select id="skillList" multiple class="w-1/2 p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 h-40 overflow-y-auto">
                        @foreach($skills as $skill)
                            <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                        @endforeach
                     </select>
                    <div class="w-1/2 p-2 border border-gray-300 rounded-md shadow-sm ml-4 h-40 overflow-y-auto bg-gray-50" id="selectedSkills"></div>
                </div>
                   
            </div>
           
        </form>
        
        <div class="flex justify-center mt-4">
                <button type="submit" class="mb-4 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Search Advisors
                </button>
        </div>

    </div>
</div>
</x-app-layout>

<script>
    function filterOptions(inputId, listId) {
        document.getElementById(inputId).addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let options = document.getElementById(listId).options;
            for (let option of options) {
                option.style.display = option.text.toLowerCase().includes(filter) ? '' : 'none';
            }
        });
    }
    
    function moveItem(listId, targetId) {
    let list = document.getElementById(listId);
    let target = document.getElementById(targetId);

    list.addEventListener("click", function(event) {
        if (event.target.tagName === "OPTION") {
            let selectedValue = event.target.value;

            // Check if the item already exists in the target list
            let existingItems = target.querySelectorAll("div");
            for (let item of existingItems) {
                if (item.dataset.value === selectedValue) {
                    return; // Do nothing if already added
                }
            }

            // Create and add item if it's not a duplicate
            let div = document.createElement("div");
            div.textContent = event.target.text;
            div.classList.add("p-2", "bg-indigo-100", "rounded", "mb-1", "cursor-pointer");
            div.dataset.value = selectedValue;

            // Allow removal on click
            div.addEventListener("click", function() {
                this.remove();
            });

            target.appendChild(div);
        }
    });
}

// Apply function to course and skill lists
moveItem("courseList", "selectedCourses");
moveItem("skillList", "selectedSkills");
    
filterOptions('courseSearch', 'courseList');
filterOptions('skillSearch', 'skillList');

</script>

