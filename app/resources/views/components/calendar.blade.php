<div x-data="datePicker()" x-init="initFlatpickr" class="relative">
    <input type="text" x-ref="input" {{ $attributes->merge(['class' => 'form-input border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm']) }}>
</div>

<script>
    function datePicker() {
        return {
            initFlatpickr() {
                flatpickr(this.$refs.input, {
                    dateFormat: "Y-m-d",
                    // Adicione outras opções do Flatpickr aqui
                });
            }
        }
    }
</script>
