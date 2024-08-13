<script>
    import { blur } from 'svelte/transition';
	import { onMount } from 'svelte';

    export let state;

    function close(event) {
        if (event.key == "Escape") {
            state = false;
        }
    }

    onMount(() => {
        window.addEventListener("keydown", close);

        return () => {
            window.removeEventListener("keydown", close);
        }
    })
</script>
<div class="fixed top-0 left-0 w-screen h-screen bg-black/50 flex items-center justify-center" transition:blur={{ amount: 1 }}>
    <div class="card w-9/12 sm:w-1/3">
        <slot></slot>
    </div>
</div>
