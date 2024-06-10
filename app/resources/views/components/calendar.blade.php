<script>
    function datepicker() {
        return {
            showCalendar: false,
            date: null,
            month: null,
            year: null,
            daysInMonth: [],
            emptyDays: [],
            daysOfWeek: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            formattedDate: '',

            init() {
                this.date = new Date();
                this.month = this.date.getMonth();
                this.year = this.date.getFullYear();
                this.populateCalendar();
            },

            populateCalendar() {
                const firstDayOfMonth = new Date(this.year, this.month, 1);
                const lastDayOfMonth = new Date(this.year, this.month + 1, 0);

                const firstDayWeekday = firstDayOfMonth.getDay();
                const lastDate = lastDayOfMonth.getDate();

                this.emptyDays = Array.from({
                    length: firstDayWeekday
                }, (v, k) => k);
                this.daysInMonth = Array.from({
                    length: lastDate
                }, (v, k) => k + 1);
            },

            toggleCalendar() {
                this.showCalendar = !this.showCalendar;
            },

            closeCalendar() {
                this.showCalendar = false;
            },

            prevMonth() {
                if (this.month === 0) {
                    this.month = 11;
                    this.year--;
                } else {
                    this.month--;
                }
                this.populateCalendar();
            },

            nextMonth() {
                if (this.month === 11) {
                    this.month = 0;
                    this.year++;
                } else {
                    this.month++;
                }
                this.populateCalendar();
            },

            selectDate(day) {
                this.date = new Date(this.year, this.month, day);
                this.formattedDate = this.date.toLocaleDateString();
                this.closeCalendar();
            },

            isSelectedDate(day) {
                return this.date && this.date.getDate() === day && this.date.getMonth() === this.month && this.date.getFullYear() === this.year;
            }
        }
    }
</script>

<div x-data="datepicker()" x-init="init()" @click.away="closeCalendar()" class="relative">
    <input type="text" x-ref="input" x-model="formattedDate" @click="toggleCalendar()" class="border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" readonly>
    <div x-show="showCalendar" class="absolute bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 border rounded p-2 mt-1">
        <div class="flex justify-between mb-2">
            <button @click.prevent="prevMonth()">&#9664;</button>
            <span x-text="monthNames[month] + ' ' + year"></span>
            <button @click.prevent="nextMonth()">&#9654;</button>
        </div>
        <div class="grid grid-cols-7 gap-1">
            <template x-for="day in daysOfWeek" :key="day">
                <div class="text-center font-semibold" x-text="day"></div>
            </template>
            <template x-for="day in emptyDays" :key="day">
                <div></div>
            </template>
            <template x-for="day in daysInMonth" :key="day">
                <div @click="selectDate(day)" class="text-center cursor-pointer" :class="{'bg-blue-500 text-zinc-100 dark:text-zinc-900': isSelectedDate(day), 'hover:bg-blue-900 hover:text-zinc-100 dark:hover:text-zinc-100': !isSelectedDate(day)}">
                    <span x-text="day"></span>
                </div>
            </template>
        </div>
    </div>
</div>
