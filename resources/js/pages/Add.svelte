<script>
    import Text from "../components/Text.svelte";
    import imageCompression from 'browser-image-compression';
    import { _text } from "../main";

    let states = {};
    let years = [];

    fetch('/api/offers/create')
        .then(response => response.json())
        .then(data => {
            states = data.states;
                years = data.years;
            });

    let offer = { state: "0" };
    let year = 0;

    let result = null;

    export let selected = 0;

    let loading = 0;

    async function send() {
        loading = 1;
        const image = document.querySelector('input[type=file]').files[0];
        const options = {
            maxSizeMB: 0.1,
            maxWidthOrHeight: 1920,
            useWebWorker: true,
        }

        try {
            const compressed = await imageCompression(image, options);
            offer.image = await imageCompression.getDataUrlFromFile(compressed);
        } catch (error) {
            console.log(error);
            return;
        }

        fetch('/api/offers/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(offer)
        })
        .then(res => res.json())
        .then(data => {
            result = data;
            if (result && result.status == '200') {
                offer = { state: 0 };
                selected = 4;
            }
        });
        loading = 0;
    }
</script>

<div class="text-2xl font-bold">
    <Text text="add-title" />
</div>
{#if result}
    <div class="alert">
        {result.message}
    </div>
{/if}
<div class="grid grid-cols-1 md:grid-cols-2 gap-2">
    <div class="flex flex-col md:col-span-2">
        <label for="subject" class="text-xs text-secondary"><Text text="year" /></label>
            <select id="year" name="year" class="input" bind:value={year} required>
                {#each years as year, index}
                    <option value="{index}">{year.name}</option>
                {/each}
            </select>
    </div>
    <div class="text-xs text-secondary"><Text text="book" /></div>
    <div class="flex overflow-x-scroll md:col-span-2 gap-3 pb-4">
        {#if years[year]}
            {#each years[year].books as book}
                <div class="shrink-0 w-1/2 md:w-1/4 font-bold card flex flex-col justify-between gap-1">
                    <!--<div><img src="/img/cover/{book.id}.png" alt="Photo of {book.name}" class="object-cover w-full h-full rounded-md"></div>!-->
                    <div class="text-xl">{book.name}</div>
                    <div class="gap-2 flex flex-col">
                        <div>
                            <div class="text-xs text-secondary">{book.author}</div>
                            <div class="text-xs text-secondary">{book.publisher} {book.release_year}</div>
                        </div>
                        <div class="flex gap-1 items-center">
                            <button class="{offer.book == book.id ? 'button-secondary' : 'button'} " on:click={() => offer.book = book.id}><Text text="{offer.book === book.id ? 'selected' : 'select'}" /></button>
                            {#if book.average_price > 0} 
                                <div title="average-price">{book.average_price} <Text text="currency" /></div>
                            {/if}
                            {#if book.average_price > 0 && book.average_max_price > 0}
                                /
                            {/if}
                            {#if book.average_max_price > 0} 
                                <div title="average-max-price" >{book.average_max_price} <Text text="currency" /></div>
                            {/if}
                        </div>
                    </div> 
                </div>
            {/each}
        {/if}
    </div>
    <div class="flex flex-col">
        <label for="subject" class="text-xs text-secondary"><Text text="price" /></label>
        <input type="number" class="input" bind:value={offer.price} required min='0' max='999'>
    </div>
    <div class="flex flex-col">
        <label for="subject" class="text-xs text-secondary"><Text text="book-state" /></label>
        <select id="subject" name="subject" class="input" bind:value={offer.state} required>
            {#each Object.entries(states) as [id, state]}
                <option value="{id}"><Text text="state-{state}" /></option>
            {/each}
        </select>
    </div>

<div class="flex flex-col">
    <div class="text-xs text-secondary"><Text text="photo" /></div>
    <input type="file" class="input" required></div>
</div>
{#if loading}
    <button class="button mt-2">
        <div class="animate-spin">
            <i class="bi bi-arrow-clockwise"></i>
        </div>
    </button>
{:else}
    <button class="button mt-2" on:click={send}><Text text="add-button" /></button>
{/if}
