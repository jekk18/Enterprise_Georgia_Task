<x-layout> 
    <div class="max-w-4xl mx-auto px-4 py-12">
        {{-- უკან დაბრუნება --}}
        <div class="mb-6">
            <a href="{{ route('profile', ['locale' => app()->getLocale()]) }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                უკან პროფილზე
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100 overflow-hidden">
            {{-- Header & Tabs --}}
            <div class="bg-gray-50/50 border-b border-gray-100">
                <div class="p-8 pb-4">
                    <h2 class="text-2xl font-black text-gray-800 tracking-tight">✍️ ახალი სტატიის განთავსება</h2> 
                </div>
                
                {{-- Tab Switcher --}}
                <div class="flex px-8 gap-4">
                    <button type="button" onclick="switchTab('ka')" id="tab-btn-ka" 
                        class="pb-4 px-2 border-b-2 font-bold text-sm transition-all outline-none border-blue-600 text-blue-600">
                        🇬🇪 ქართული (KA)
                    </button>
                    <button type="button" onclick="switchTab('en')" id="tab-btn-en" 
                        class="pb-4 px-2 border-b-2 font-bold text-sm transition-all outline-none border-transparent text-gray-400 hover:text-gray-600">
                        🇺🇸 English (EN)
                    </button>
                </div>
            </div>

            <form action="{{ route('posts.store', app()->getLocale()) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                
                {{-- KA Section --}}
                <div id="section-ka" class="space-y-6 animate-fadeIn">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">სტატიის სათაური (KA)</label>
                        <input type="text" name="title_ka" value="{{ old('title_ka') }}" placeholder="მაგ: ტექნოლოგიური სიახლეები..." 
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition outline-none text-gray-700">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">მთავარი ფოტო (KA)</label>
                        <input type="file" name="image_ka" accept="image/*" 
                            class="w-full px-3 py-2.5 rounded-xl border border-gray-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition cursor-pointer text-sm text-gray-500">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">სტატიის შინაარსი (KA)</label>
                        <textarea name="description_ka" rows="8" placeholder="დაწერეთ თქვენი სტატიის ტექსტი ქართულად..." 
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition outline-none text-gray-700 resize-none">{{ old('description_ka') }}</textarea>
                    </div>
                </div>

                {{-- EN Section --}}
                <div id="section-en" class="space-y-6 animate-fadeIn" style="display: none;">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Article Title (EN)</label>
                        <input type="text" name="title_en" value="{{ old('title_en') }}" placeholder="e.g. Technology News 2026..." 
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition outline-none text-gray-700">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Main Image (EN)</label>
                        <input type="file" name="image_en" accept="image/*" 
                            class="w-full px-3 py-2.5 rounded-xl border border-gray-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition cursor-pointer text-sm text-gray-500">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">Article Content (EN)</label>
                        <textarea name="description_en" rows="8" placeholder="Write your article content in English here..." 
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition outline-none text-gray-700 resize-none">{{ old('description_en') }}</textarea>
                    </div>
                </div>

                {{-- საერთო ველები --}}
                <div class="pt-6 border-t border-gray-100 space-y-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-700 ml-1">კატეგორია (საერთო)</label>
                        <div class="relative">
                            <select name="category" required 
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition outline-none appearance-none bg-white text-gray-700">
                                <option value="">აირჩიეთ კატეგორია</option>
                                <option value="პოლიტიკა">პოლიტიკა</option>
                                <option value="ტექნოლოგიები">ტექნოლოგიები</option>
                                <option value="სპორტი">სპორტი</option>
                                <option value="გართობა">გართობა</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <button type="submit" 
                        class="w-full md:w-auto px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-lg shadow-blue-200 transition-all transform active:scale-95 flex items-center justify-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        გაგზავნა მოდერაციაზე
                    </button>
                </div>
            </form>
        </div>
    </div> 
</x-layout>


<script>
function switchTab(lang) {
    const kaSection = document.getElementById('section-ka');
    const enSection = document.getElementById('section-en');
    const kaBtn = document.getElementById('tab-btn-ka');
    const enBtn = document.getElementById('tab-btn-en');

    if (lang === 'ka') {
        kaSection.style.display = 'block';
        enSection.style.display = 'none';
        
        // Button Styles
        kaBtn.classList.add('border-blue-600', 'text-blue-600');
        kaBtn.classList.remove('border-transparent', 'text-gray-400');
        
        enBtn.classList.remove('border-blue-600', 'text-blue-600');
        enBtn.classList.add('border-transparent', 'text-gray-400');
    } else {
        kaSection.style.display = 'none';
        enSection.style.display = 'block';
        
        // Button Styles
        enBtn.classList.add('border-blue-600', 'text-blue-600');
        enBtn.classList.remove('border-transparent', 'text-gray-400');
        
        kaBtn.classList.remove('border-blue-600', 'text-blue-600');
        kaBtn.classList.add('border-transparent', 'text-gray-400');
    }
}
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out forwards;
    }
</style>