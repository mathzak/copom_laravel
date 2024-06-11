@props(['disabled' => false, 'mask' => ''])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-zinc-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm']) !!} data-mask="{{ $mask }}" oninput="applyMask(this)">

<script>
    function applyMask(input) {
        const mask = input.getAttribute('data-mask');
        if (!mask) return;

        const value = input.value.replace(/\D/g, '');
        let newValue = '';
        let maskIndex = 0;

        for (let i = 0; i < value.length; i++) {
            if (maskIndex >= mask.length) break;

            if (mask[maskIndex] === '9') {
                newValue += value[i];
                maskIndex++;
            } else {
                newValue += mask[maskIndex];
                maskIndex++;
                i--;
            }
        }

        input.value = newValue;
    }
</script>
