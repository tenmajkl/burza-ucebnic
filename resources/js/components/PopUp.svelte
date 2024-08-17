<script>
    import { blur } from 'svelte/transition';
	import { onMount } from 'svelte';

    export let state;

    let char_state = 0;

    let state_table = [
        {':': [1, null]},
        {'q': [2, null], 'x': [2, null]},
        {'Enter': [3, () => state = false]},
        null,
    ]

    function close(event) {
        if (event.key == "Escape") {
            state = false;
            return;
        }

        if (!state_table[char_state][event.key]) {
            char_state = 0;
            return;
        }

        if (state_table[char_state][event.key][1]) {
            state_table[char_state][event.key][1]();
        }

        char_state = state_table[char_state][event.key][0];
        
        if (state_table[char_state] == null) {
            char_state = 0;
            return;
        }
    }

    onMount(() => {
        window.addEventListener("keydown", close);

        return () => {
            window.removeEventListener("keydown", close);
        }
    })
</script>
<div class="fixed top-0 left-0 w-screen h-screen bg-black/50 flex items-center justify-center" transition:blur={{ amount: 1 }} on:click={(e) => {if (e.srcElement.id === 'dim-background') {state = false}}} id="dim-background">
    <div class="card w-9/12 sm:w-1/3">
        <slot></slot>
    </div>
</div>
