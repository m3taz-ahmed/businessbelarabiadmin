<div 
    x-data="{
        statePath: '{{ $getStatePath() }}',
        sections: @js($getState() ?? []),
        sectionTypes: @js($getSectionTypes()),
        collapsed: [],
        showSelector: false,

        init() {
            if (!Array.isArray(this.sections)) {
                this.sections = [];
            }
            
            this.sections.forEach(s => {
                if (!s._uid) s._uid = this.generateUid();
            });

            this.collapsed = new Array(this.sections.length).fill(false);
        },

        generateUid() {
            return Date.now().toString(36) + Math.random().toString(36).substr(2);
        },

        isCollapsed(index) {
            return this.collapsed[index] === true;
        },

        toggleCollapse(index) {
            this.collapsed[index] = !this.collapsed[index];
        },

        addSection(type) {
            const section = {
                type: type,
                data: this.getDefaultData(type),
                _uid: this.generateUid()
            };
            
            this.sections.push(section);
            this.collapsed.push(false); 
            this.updateState();
        },

        removeSection(index) {
            if(confirm('Are you sure you want to delete this section?')) {
                this.sections.splice(index, 1);
                this.collapsed.splice(index, 1);
                this.updateState();
            }
        },

        moveSectionUp(index) {
            if (index > 0) {
                const tempSection = this.sections[index];
                this.sections[index] = this.sections[index - 1];
                this.sections[index - 1] = tempSection;
                
                const tempCollapsed = this.collapsed[index];
                this.collapsed[index] = this.collapsed[index - 1];
                this.collapsed[index - 1] = tempCollapsed;
                
                this.updateState();
            }
        },

        moveSectionDown(index) {
            if (index < this.sections.length - 1) {
                const tempSection = this.sections[index];
                this.sections[index] = this.sections[index + 1];
                this.sections[index + 1] = tempSection;
                
                const tempCollapsed = this.collapsed[index];
                this.collapsed[index] = this.collapsed[index + 1];
                this.collapsed[index + 1] = tempCollapsed;
                
                this.updateState();
            }
        },

        addImage(sectionIndex) {
            if (!Array.isArray(this.sections[sectionIndex].data)) {
                this.sections[sectionIndex].data = [];
            }
            this.sections[sectionIndex].data.push({image: '', title: ''});
            this.updateState();
        },

        removeImage(sectionIndex, imageIndex) {
            this.sections[sectionIndex].data.splice(imageIndex, 1);
            this.updateState();
        },

        addListItem(sectionIndex) {
            if (!Array.isArray(this.sections[sectionIndex].data.list_items)) {
                this.sections[sectionIndex].data.list_items = [];
            }
            this.sections[sectionIndex].data.list_items.push('');
            this.updateState();
        },

        removeListItem(sectionIndex, itemIndex) {
            this.sections[sectionIndex].data.list_items.splice(itemIndex, 1);
            this.updateState();
        },

        addChoice(sectionIndex) {
            if (!Array.isArray(this.sections[sectionIndex].data.multiple_choice)) {
                this.sections[sectionIndex].data.multiple_choice = [];
            }
            this.sections[sectionIndex].data.multiple_choice.push('');
            this.updateState();
        },

        removeChoice(sectionIndex, choiceIndex) {
            this.sections[sectionIndex].data.multiple_choice.splice(choiceIndex, 1);
            this.updateState();
        },

        getDefaultData(type) {
            switch(type) {
                case 'paragraph_text':
                    return { paragraph_text: '', paragraph_title: '' };
                case 'heading':
                    return { title: '', title_level: 'medium' };
                case 'images':
                    return [{ image: '', title: '' }];
                case 'list':
                    return { list_type: 'dotted', list_items: [''], list_title: '' };
                case 'q_multiple_choice':
                    return { answer: '', question: '', multiple_choice: [''] };
                case 'q_true_false':
                    return { answer_true_false: 'true', question_true_false: '' };
                case 'quote':
                    return { quote: '', author_name: '', author_sub_title: '', author_image: '' };
                case 'video':
                    return { poster: '', duration: '', video_file: '', video_title: '' };
                default:
                    return {};
            }
        },

        getSectionTypeName(type) {
            return this.sectionTypes[type] || type;
        },

        updateState() {
            this.$wire.set(this.statePath, this.sections);
        }
    }"
    class="flex flex-col gap-6"
    wire:ignore
>
    <!-- Sections List -->
    <div class="flex flex-col gap-4">
        <template x-for="(section, index) in sections" :key="section._uid">
            <div 
                class="group bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm transition-all duration-300 hover:shadow-md ring-1 ring-transparent"
                :class="{ 'ring-primary-500/20': !isCollapsed(index) }"
            >
                <!-- Section Header -->
                <div 
                    class="flex items-center justify-between px-4 py-3 bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800 select-none cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800"
                    :class="{ 'rounded-xl border-b-0': isCollapsed(index), 'rounded-t-xl': !isCollapsed(index) }"
                    @click="toggleCollapse(index)"
                >
                    <div class="flex items-center gap-3">
                        <!-- Drag Handle -->
                        <div class="flex items-center justify-center p-1 text-gray-400 cursor-grab hover:text-gray-600 dark:hover:text-gray-200 active:cursor-grabbing">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </div>
                        
                        <!-- Section Title -->
                        <div class="flex flex-col">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-gray-900 dark:text-gray-100" x-text="getSectionTypeName(section.type)"></span>
                                <span class="px-2 py-0.5 text-[10px] font-medium tracking-wide text-gray-500 bg-gray-100 dark:bg-gray-700 dark:text-gray-400 rounded-full uppercase" x-text="`#${index + 1}`"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Toolbar -->
                    <div class="flex items-center gap-1" @click.stop>
                        <button type="button" @click="moveSectionUp(index)" :disabled="index === 0" class="p-1.5 text-gray-400 rounded-lg hover:bg-white hover:text-gray-700 hover:shadow-sm dark:hover:bg-gray-700 dark:hover:text-gray-200 disabled:opacity-30 disabled:cursor-not-allowed transition-all" title="Move Up"><svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" /></svg></button>
                        <button type="button" @click="moveSectionDown(index)" :disabled="index === sections.length - 1" class="p-1.5 text-gray-400 rounded-lg hover:bg-white hover:text-gray-700 hover:shadow-sm dark:hover:bg-gray-700 dark:hover:text-gray-200 disabled:opacity-30 disabled:cursor-not-allowed transition-all" title="Move Down"><svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></button>
                        <div class="w-px h-4 mx-1 bg-gray-200 dark:bg-gray-700"></div>
                        <button type="button" @click="removeSection(index)" class="p-1.5 text-danger-500 rounded-lg hover:bg-danger-50 hover:text-danger-600 dark:hover:bg-danger-900/30 dark:hover:text-danger-400 transition-all" title="Remove Section"><svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg></button>
                        <div class="ml-1 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': !isCollapsed(index) }"><svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg></div>
                    </div>
                </div>
                
                <!-- Section Body -->
                <div x-show="!isCollapsed(index)" class="p-6 space-y-4">
                    <!-- Paragraph Text -->
                    <template x-if="section.type === 'paragraph_text'">
                        <div class="space-y-4">
                            <div>
                                <label class="block mb-1.5 text-sm font-medium text-gray-950 dark:text-white">Paragraph Title</label>
                                <input type="text" x-model="section.data.paragraph_title" placeholder="Optional title for this paragraph" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white sm:text-sm">
                            </div>
                            <div>
                                <label class="block mb-1.5 text-sm font-medium text-gray-950 dark:text-white">Content</label>
                                <textarea x-model="section.data.paragraph_text" rows="5" placeholder="Write your content here..." class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white sm:text-sm"></textarea>
                            </div>
                        </div>
                    </template>
                    
                    <!-- Heading -->
                    <template x-if="section.type === 'heading'">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="md:col-span-3">
                                <label class="block mb-1.5 text-sm font-medium text-gray-950 dark:text-white">Heading Title</label>
                                <input type="text" x-model="section.data.title" placeholder="Enter heading" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white sm:text-sm">
                            </div>
                            <div>
                                <label class="block mb-1.5 text-sm font-medium text-gray-950 dark:text-white">Size</label>
                                <select x-model="section.data.title_level" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white sm:text-sm">
                                    <option value="small">Small (H4)</option>
                                    <option value="medium">Medium (H3)</option>
                                    <option value="large">Large (H2)</option>
                                </select>
                            </div>
                        </div>
                    </template>
                    
                    <!-- Images -->
                    <template x-if="section.type === 'images'">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-medium text-gray-950 dark:text-white">Image Gallery</label>
                                <button type="button" @click="addImage(index)" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 dark:text-primary-400 dark:bg-primary-900/30 dark:hover:bg-primary-900/50 transition-colors">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                                    Add Image
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-1 gap-4">
                                <template x-for="(image, imgIndex) in section.data" :key="imgIndex">
                                    <div class="relative flex flex-col md:flex-row gap-4 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-100 dark:border-gray-700/50">
                                        <div class="flex-1 space-y-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Image URL</label>
                                                <input type="text" x-model="image.image" placeholder="https://..." class="block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Caption / Title</label>
                                                <input type="text" x-model="image.title" placeholder="Optional caption" class="block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                            </div>
                                        </div>
                                        <div class="flex md:flex-col justify-end">
                                             <button type="button" @click="removeImage(index, imgIndex)" class="p-2 text-gray-400 hover:text-danger-500 transition-colors" title="Remove Image">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                            </button>
                                        </div>
                                        <template x-if="image.image">
                                            <div class="hidden md:block w-24 h-24 shrink-0 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-600">
                                                <img :src="image.image" class="w-full h-full object-cover" onerror="this.style.display='none'">
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="(!section.data || section.data.length === 0)">
                                    <div class="text-center py-6 text-sm text-gray-400 bg-gray-50 dark:bg-gray-800/30 rounded-lg border border-dashed border-gray-300 dark:border-gray-700">No images added yet.</div>
                                </template>
                            </div>
                        </div>
                    </template>
                    
                    <!-- Other sections (similarly styled inputs) -->
                    <template x-if="section.type === 'list'">
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block mb-1.5 text-sm font-medium text-gray-950 dark:text-white">List Title</label>
                                    <input type="text" x-model="section.data.list_title" placeholder="Optional list title" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white sm:text-sm">
                                </div>
                                <div>
                                    <label class="block mb-1.5 text-sm font-medium text-gray-950 dark:text-white">List Style</label>
                                    <select x-model="section.data.list_type" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white sm:text-sm">
                                        <option value="dotted">Bullet Points (•)</option>
                                        <option value="numbered">Numbered (1, 2, 3)</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="text-sm font-medium text-gray-950 dark:text-white">Items</label>
                                    <button type="button" @click="addListItem(index)" class="text-xs font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400">+ Add Item</button>
                                </div>
                                <div class="space-y-2">
                                    <template x-for="(item, itemIndex) in section.data.list_items" :key="itemIndex">
                                        <div class="flex items-center gap-2">
                                            <div class="p-2 text-gray-400 cursor-grab active:cursor-grabbing"><svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5" /></svg></div>
                                            <input type="text" x-model="section.data.list_items[itemIndex]" placeholder="List item content" class="block flex-1 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                                            <button type="button" @click="removeListItem(index, itemIndex)" class="p-2 text-gray-400 hover:text-danger-500 transition-colors"><svg class="w-4 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" /></svg></button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                     <template x-if="section.type === 'q_multiple_choice'">
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-1.5 text-sm font-medium text-gray-950 dark:text-white">Question</label>
                                    <input type="text" x-model="section.data.question" placeholder="e.g. What is the capital of France?" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white sm:text-sm">
                                </div>
                                <div>
                                    <label class="block mb-1.5 text-sm font-medium text-gray-950 dark:text-white">Correct Answer Label</label>
                                    <input type="text" x-model="section.data.answer" placeholder="e.g. Paris" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white sm:text-sm">
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="text-sm font-medium text-gray-950 dark:text-white">Options</label>
                                    <button type="button" @click="addChoice(index)" class="text-xs font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400">+ Add Option</button>
                                </div>
                                <div class="space-y-2">
                                    <template x-for="(choice, choiceIndex) in section.data.multiple_choice" :key="choiceIndex">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-mono text-gray-400 w-5 text-center" x-text="choiceIndex + 1 + '.'"></span>
                                            <input type="text" x-model="section.data.multiple_choice[choiceIndex]" placeholder="Option text" class="block flex-1 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                                            <button type="button" @click="removeChoice(index, choiceIndex)" class="p-2 text-gray-400 hover:text-danger-500 transition-colors"><svg class="w-4 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" /></svg></button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-if="section.type === 'q_true_false'">
                         <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <label class="block mb-1.5 text-sm font-medium text-gray-950 dark:text-white">Question</label>
                                <input type="text" x-model="section.data.question_true_false" placeholder="True/False Question" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white sm:text-sm">
                            </div>
                            <div>
                                <label class="block mb-1.5 text-sm font-medium text-gray-950 dark:text-white">Correct Answer</label>
                                <select x-model="section.data.answer_true_false" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white sm:text-sm">
                                    <option value="true">True</option>
                                    <option value="false">False</option>
                                </select>
                            </div>
                        </div>
                    </template>
                    
                    <template x-if="section.type === 'quote'">
                        <div class="space-y-4">
                            <div>
                                <label class="block mb-1.5 text-sm font-medium text-gray-950 dark:text-white">Quote</label>
                                <textarea x-model="section.data.quote" rows="3" placeholder="Enter the quote..." class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white sm:text-sm"></textarea>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block mb-1.5 text-sm font-medium text-gray-950 dark:text-white">Author Name</label>
                                    <input type="text" x-model="section.data.author_name" placeholder="John Doe" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white sm:text-sm">
                                </div>
                                <div>
                                    <label class="block mb-1.5 text-sm font-medium text-gray-950 dark:text-white">Title/Role</label>
                                    <input type="text" x-model="section.data.author_sub_title" placeholder="CEO, Company Inc." class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white sm:text-sm">
                                </div>
                                <div>
                                    <label class="block mb-1.5 text-sm font-medium text-gray-950 dark:text-white">Author Image</label>
                                    <input type="text" x-model="section.data.author_image" placeholder="https://..." class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white sm:text-sm">
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-if="section.type === 'video'">
                        <div class="space-y-4">
                            <div>
                                <label class="block mb-1.5 text-sm font-medium text-gray-950 dark:text-white">Video Title</label>
                                <input type="text" x-model="section.data.video_title" placeholder="Video Title" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white sm:text-sm">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-1.5 text-sm font-medium text-gray-950 dark:text-white">Video URL (MP4/WebM)</label>
                                    <input type="text" x-model="section.data.video_file" placeholder="https://example.com/video.mp4" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white sm:text-sm">
                                </div>
                                <div>
                                    <label class="block mb-1.5 text-sm font-medium text-gray-950 dark:text-white">Poster Image URL</label>
                                    <input type="text" x-model="section.data.poster" placeholder="https://example.com/poster.jpg" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white sm:text-sm">
                                </div>
                            </div>
                            <div>
                                <label class="block mb-1.5 text-sm font-medium text-gray-950 dark:text-white">Duration (Seconds)</label>
                                <input type="number" x-model="section.data.duration" placeholder="e.g. 120" class="block w-32 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white sm:text-sm">
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>
        
        <!-- Empty State -->
        <div x-show="sections.length === 0" class="flex flex-col items-center justify-center p-10 bg-white dark:bg-gray-900 border-2 border-primary-100 dark:border-gray-800 border-dashed rounded-xl transition-all hover:bg-gray-50 dark:hover:bg-gray-800/50">
            <div class="w-16 h-16 rounded-full bg-primary-50 dark:bg-gray-800 flex items-center justify-center mb-4 ring-8 ring-primary-50/50 dark:ring-gray-800/50">
                 <svg class="w-8 h-8 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Start Building Content</h3>
            <p class="text-sm text-gray-500 mt-2 text-center max-w-sm">Add your first section to begin creating your article structure.</p>
        </div>
    </div>
    
    <!-- Add Section Toolbar -->
    <div class="relative" @click.outside="showSelector = false">
        <button 
            type="button" 
            @click="showSelector = !showSelector"
            class="flex items-center justify-center w-full gap-2 py-4 bg-white border border-gray-300 border-dashed rounded-xl shadow-sm text-gray-500 hover:text-primary-600 hover:border-primary-500 hover:bg-primary-50/30 transition-all group dark:bg-gray-900 dark:border-gray-700 dark:text-gray-400 dark:hover:text-primary-400 dark:hover:border-primary-500"
        >
            <div class="p-1 rounded-full bg-gray-100 group-hover:bg-primary-100 dark:bg-gray-800 dark:group-hover:bg-primary-900/50 transition-colors">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
            </div>
            <span class="font-medium text-sm">Add New Section</span>
        </button>
        
        <!-- Selection Grid -->
        <div 
            x-show="showSelector" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            class="absolute z-50 left-0 right-0 bottom-full mb-3 p-3 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 max-h-80 overflow-y-auto"
        >
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                <template x-for="(label, type) in sectionTypes" :key="type">
                    <button 
                        type="button" 
                        @click="addSection(type); showSelector = false"
                        class="flex flex-col items-center justify-center p-4 gap-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 border border-transparent hover:border-gray-100 dark:hover:border-gray-600 transition-all text-center group"
                    >
                        <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-primary-50 text-primary-600 dark:bg-gray-700 dark:text-primary-400 group-hover:bg-primary-100 dark:group-hover:bg-gray-600 transition-colors">
                            <svg x-show="type === 'paragraph_text'" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" /></svg>
                            <svg x-show="type === 'heading'" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h12" /></svg>
                            <svg x-show="type === 'images'" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                            <svg x-show="type === 'list'" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                            <svg x-show="['q_multiple_choice', 'q_true_false'].includes(type)" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" /></svg>
                            <svg x-show="type === 'quote'" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 4.5l7.5 7.5-7.5 7.5m-6-15l7.5 7.5-7.5 7.5" /></svg>
                            <svg x-show="type === 'video'" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" /></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white" x-text="label"></span>
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>