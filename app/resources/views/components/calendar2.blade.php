<script>
    function datepicker() {
        return {
            showCalendar: false,
            showTime: false,
            showMonthDropdown: false,
            showYearDropdown: false,
            date: null,
            month: null,
            year: null,
            hour: null,
            minute: null,
            second: null,
            daysInMonth: [],
            emptyDays: [],
            daysOfWeek: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            formattedDate: '',
            dateFormat: 'YYYY-MM-DD', // Formato de data padrão
            yearRangeStart: null,
            yearRangeEnd: null,
            selectedDayIndex: null,

            init() {
                this.date = new Date();
                this.month = this.date.getMonth();
                this.year = this.date.getFullYear();
                this.yearRangeStart = this.year - 6;
                this.yearRangeEnd = this.year + 5;
                this.hour = this.date.getHours();
                this.minute = this.date.getMinutes();
                this.second = this.date.getSeconds();
                this.populateCalendar();
                this.updateFormattedDate();
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
                this.showMonthDropdown = false;
                this.showYearDropdown = false;
            },

            toggleMonthDropdown() {
                this.showMonthDropdown = !this.showMonthDropdown;
                this.showYearDropdown = false;
            },

            toggleYearDropdown() {
                this.showYearDropdown = !this.showYearDropdown;
                this.showMonthDropdown = false;
            },

            selectMonth(month) {
                this.month = month;
                this.populateCalendar();
                this.showMonthDropdown = false;
            },

            selectYear(year) {
                this.year = year;
                this.populateCalendar();
                this.showYearDropdown = false;
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

            prevYearRange() {
                this.yearRangeStart -= 12;
                this.yearRangeEnd -= 12;
            },

            nextYearRange() {
                this.yearRangeStart += 12;
                this.yearRangeEnd += 12;
            },

            selectDate(day) {
                if (this.showTime) {
                    this.date = new Date(this.year, this.month, day, this.hour, this.minute, this.second);
                    this.dateFormat = 'YYYY-MM-DD HH:mm:ss';
                } else {
                    this.date = new Date(this.year, this.month, day);
                    this.dateFormat = 'YYYY-MM-DD';
                }
                this.updateFormattedDate();
                this.closeCalendar();
            },

            updateFormattedDate() {
                this.formattedDate = this.formatDate(this.date, this.dateFormat);
            },

            setTime(unit, value) {
                this[unit] = value;
                if (this.date) {
                    this.date.setHours(this.hour);
                    this.date.setMinutes(this.minute);
                    this.date.setSeconds(this.second);
                    this.updateFormattedDate();
                }
            },

            isSelectedDate(day) {
                return this.date && this.date.getDate() === day && this.date.getMonth() === this.month && this.date.getFullYear() === this.year;
            },

            formatDate(date, format) {
                const map = {
                    YYYY: date.getFullYear(),
                    MM: ('0' + (date.getMonth() + 1)).slice(-2),
                    DD: ('0' + date.getDate()).slice(-2),
                    HH: ('0' + date.getHours()).slice(-2),
                    mm: ('0' + date.getMinutes()).slice(-2),
                    ss: ('0' + date.getSeconds()).slice(-2)
                };

                return format.replace(/YYYY|MM|DD|HH|mm|ss/gi, matched => map[matched]);
            },

            handleInput(event) {
                const value = event.target.value;
                if (this.isValidDate(value, this.dateFormat)) {
                    const date = this.parseDate(value, this.dateFormat);
                    if (date) {
                        this.date = date;
                        this.month = this.date.getMonth();
                        this.year = this.date.getFullYear();
                        this.hour = this.date.getHours();
                        this.minute = this.date.getMinutes();
                        this.second = this.date.getSeconds();
                        this.updateFormattedDate();
                        this.populateCalendar();
                    }
                } else {
                    this.formattedDate = value;
                }
            },

            isValidDate(dateString, format) {
                const date = this.parseDate(dateString, format);
                return date instanceof Date && !isNaN(date);
            },

            handleKeydown(event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    this.toggleCalendar();
                } else if (this.showCalendar) {
                    if (event.key === 'ArrowDown') {
                        event.preventDefault();
                        this.navigateCalendar(7);
                    } else if (event.key === 'ArrowUp') {
                        event.preventDefault();
                        this.navigateCalendar(-7);
                    } else if (event.key === 'ArrowRight') {
                        event.preventDefault();
                        this.navigateCalendar(1);
                    } else if (event.key === 'ArrowLeft') {
                        event.preventDefault();
                        this.navigateCalendar(-1);
                    }
                }
            },

            navigateCalendar(delta) {
                if (this.selectedDayIndex === null) {
                    this.selectedDayIndex = this.daysInMonth.indexOf(this.date.getDate());
                }

                let newIndex = this.selectedDayIndex + delta;

                if (newIndex < 0) {
                    this.prevMonth();
                    newIndex = this.daysInMonth.length + newIndex;
                } else if (newIndex >= this.daysInMonth.length) {
                    newIndex = newIndex - this.daysInMonth.length;
                    this.nextMonth();
                }

                this.selectedDayIndex = newIndex;
                const newDay = this.daysInMonth[this.selectedDayIndex];
                this.selectDate(newDay);
            },

            parseDate(dateString, format) {
                const regex = new RegExp(format.replace(/YYYY|MM|DD|HH|mm|ss/gi, matched => {
                    switch (matched) {
                        case 'YYYY':
                            return '(\\d{4})';
                        case 'MM':
                            return '(\\d{2})';
                        case 'DD':
                            return '(\\d{2})';
                        case 'HH':
                            return '(\\d{2})';
                        case 'mm':
                            return '(\\d{2})';
                        case 'ss':
                            return '(\\d{2})';
                    }
                }));

                const matches = regex.exec(dateString);
                if (!matches) return null;

                const map = {};
                format.replace(/YYYY|MM|DD|HH|mm|ss/gi, (matched, index) => {
                    map[matched] = matches[index / 4 + 1];
                });

                return new Date(
                    map['YYYY'],
                    map['MM'] - 1,
                    map['DD'],
                    map['HH'] || 0,
                    map['mm'] || 0,
                    map['ss'] || 0
                );
            }
        }
    }
</script>

<div x-data="datepicker()" x-init="init()" @click.away="closeCalendar()" class="relative">
    <input type="text" x-ref="input" x-model="formattedDate" @click="toggleCalendar()" @keydown="handleKeydown" @input="handleInput" class="border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
    <div x-show="showCalendar" class="absolute bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 border border-zinc-300 dark:border-zinc-700 rounded p-2 mt-1">
        <div class="flex justify-between mb-2">
            <button @click.prevent="prevMonth()">&#9664;</button>
            <span @click="toggleMonthDropdown()" class="cursor-pointer" x-text="monthNames[month]"></span>
            <span @click="toggleYearDropdown()" class="cursor-pointer" x-text="year"></span>
            <button @click.prevent="nextMonth()">&#9654;</button>
        </div>
        <div x-show="showMonthDropdown" class="absolute bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 border border-zinc-300 dark:border-zinc-700 rounded p-2 mt-1 grid grid-cols-3 gap-1">
            <template x-for="(monthName, index) in monthNames" :key="index">
                <div @click="selectMonth(index)" class="cursor-pointer text-center p-1" :class="{'bg-blue-500 text-zinc-100 dark:text-zinc-900': month === index, 'hover:bg-blue-900 hover:text-zinc-100 dark:hover:text-zinc-100': month !== index}">
                    <span x-text="monthName"></span>
                </div>
            </template>
        </div>
        <div x-show="showYearDropdown" class="absolute bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 border border-zinc-300 dark:border-zinc-700 rounded p-2 mt-1">
            <div class="flex justify-between mb-2">
                <button @click.prevent="prevYearRange()">&#9664;</button>
                <button @click.prevent="nextYearRange()">&#9654;</button>
            </div>
            <div class="grid grid-cols-3 gap-1">
                <template x-for="yr in Array.from({ length: 12 }, (v, k) => yearRangeStart + k)" :key="yr">
                    <div @click="selectYear(yr)" class="cursor-pointer text-center p-1" :class="{'bg-blue-500 text-zinc-100 dark:text-zinc-900': year === yr, 'hover:bg-blue-900 hover:text-zinc-100 dark:hover:text-zinc-100': year !== yr}">
                        <span x-text="yr"></span>
                    </div>
                </template>
            </div>
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
        <div class="flex justify-between mt-2" x-show="showTime">
            <div class="flex items-center">
                <input type="number" min="0" max="23" x-model="hour" @change="setTime('hour', $event.target.value)" class="border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <label for="minute" class="mx-2">:</label>
                <input type="number" min="0" max="59" x-model="minute" @change="setTime('minute', $event.target.value)" class="border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <label for="second" class="mx-2">:</label>
                <input type="number" min="0" max="59" x-model="second" @change="setTime('second', $event.target.value)" class="border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
            </div>
        </div>
    </div>
</div>
